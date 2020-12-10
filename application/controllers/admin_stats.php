<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin_Stats extends MY_Controller
{
    protected $allowed_groups = [];
    protected $_per_page;


    public function __construct()
    {
        parent::__construct();

        if (! $this->ion_auth->logged_in()) {
            // login_redirect(true, 'auth/login_email');
        }

        //Resstrict access to only system admins
        $this->load->model('User_group_model');
        if (! $this->ion_auth->in_group([ User_group_model::SYSADMIN ])) {
            show_error('You are not authorized to access this page. Return to <a href="' . base_url() . '">login page</a>.', 401);
        }

        // Pagination used in pretty all the main admin sections
        $this->load->library('pagination');
        $this->config->load('pagination');
        $this->load->helper('admin');
    }


    public function index()
    {
        $this->load->library('admin_stat');
        $this->load->model('unit_model');
        $data['active']                   = 'admin_stats';
        $data['is_dev_or_stage']          = getenv('APPLICATION_ENV') == 'development' || getenv('APPLICATION_ENV') == 'stage';
        $data['ajax_stats_list']          = $this->admin_stat->get_ajax_stats_list();
        $data['view']                     = 'admin/stats';
        $data['footer_js_cdn']            = ['https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.3/handlebars.min.js'];
        $data['footer_js']                = ['admin/admin_stats.js'];
        $data['program_unit_type_images'] = $this->unit_model->get_program_unit_images_for_ui_select();
        $this->load->view('admin/template', $data);
    }


    public function clean_user_email_addresses()
    {
        $this->load->model('user_model');
        $this->user_model->clean_user_email_addresses();
        $this->user_model->clean_user_email_addresses('braintree_customers');
        $this->user_model->clean_user_email_addresses('cc_transactions');
        $this->user_model->clean_user_email_addresses('cc_transaction_backup');
        $this->user_model->clean_user_email_addresses('braintree_merchants');
        // this table does not have an id field so can not use the algorithm of this function to replace emails
        $this->user_model->clean_user_email_addresses('pledge_sponsors');
        $this->user_model->clean_user_email_addresses('potential_sponsors');

        $this->index();
    }


    private function _validate_admin_stats_post_dates()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('ts_start', 'ts_start', 'required');
        $this->form_validation->set_rules('ts_end', 'ts_end', 'required');
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

        if ($this->form_validation->run() != false) {
            $validated = true;
        } else {
            $validated = false;
        }

        return $validated;
    }


    public function add_unit_type()
    {
        $this->load->library('session');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', 'title', 'required|max_length[25]');
        $this->form_validation->set_rules('name', 'name', 'required|max_length[25]');
        $this->form_validation->set_rules('name_plural', 'name_plural', 'required|max_length[25]');
        $this->form_validation->set_rules('multiplier_internal', 'multiplier_internal', 'required|integer|max_length[10]');
        $this->form_validation->set_rules('default_multiplier', 'default_multiplier', 'required|integer|max_length[10]');
        $this->form_validation->set_rules('default_lower_limit', 'default_lower_limit', 'required|integer|max_length[10]');
        $this->form_validation->set_rules('default_upper_limit', 'default_upper_limit', 'required|integer|max_length[10]');
        $this->form_validation->set_rules('unit_image_id', 'unit_image_id', 'required|integer');
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
        if ($this->form_validation->run() == false) {
            return $this->index();
        }

        $unit = [
      'title'               => $this->input->post('title'),
      'name'                => $this->input->post('name'),
      'name_plural'         => $this->input->post('name_plural'),
      'multiplier_internal' => $this->input->post('multiplier_internal'),
      'default_multiplier'  => $this->input->post('default_multiplier'),
      'default_lower_limit' => $this->input->post('default_lower_limit'),
      'default_upper_limit' => $this->input->post('default_upper_limit'),
      'unit_image_id'       => $this->input->post('unit_image_id')
    ];

        $this->load->model('unit_model');
        if ($this->unit_model->insert_unit($unit)) {
            $this->session->set_flashdata(['message' => 'New Unit Added Successfully!']);
        }

        return $this->index();
    }
}
