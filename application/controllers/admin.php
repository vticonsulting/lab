<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin extends MY_Controller
{
    protected $allowed_groups = [];


    public function __construct()
    {
        parent::__construct();
        if (! $this->ion_auth->logged_in()) {
            login_redirect(true, 'auth/login_email');
        }

        if (!$this->ion_auth->is_admin()) {
            show_error('You are not authorized to access this page. Return to <a href="' . base_url() . '">login page</a>.', 401);
        }

        //Pagination used in pretty all the main admin sections
        $this->load->library('pagination');
        $this->config->load('pagination'); //So we can keep a uniform look across sections
        $this->load->helper('admin');
    }


    public function index()
    {
        $data['view'] = 'admin/home';
        $this->load->view('admin/template', $data);
    }


    /**
     * display student star admin portal
     */
    public function student_star()
    {
        $data['view'] = 'admin/student_star/video_status_list';
        $this->load->library('Student_Star');
        $this->load->library('student_star_api');
        $data['student_star_videos']              = $this->student_star->get_video_statuses();
        $data['student_star_api_status_endpoint'] = $this->student_star_api->get_endpoint('api/render_jobs_by_hour/');
        $data['student_star_status_averages']     = $this->student_star->get_video_average_statuses();
        $data['status_types']                     = $this->student_star->get_status_types();
        $this->load->view('admin/template', $data);
    }


    /**
     * Display student star servers
     */
    public function student_star_servers()
    {
        $this->load->library('Student_Star');

        $data['student_star_agents'] = $this->student_star->get_blender_agents();
        $data['view']                = 'admin/student_star/agent_server_list';
        $this->load->view('admin/template', $data);
    }


    public function system_alert($data = [])
    {
        $this->load->model('system_alerts_model');

        if ($this->input->method() === 'post') {
            $data = $this->edit_system_alert();
            if ($data['success_message']) {
                $data['alerts_and_locations'][] = $data['system_alert_with_locations'];
            }
        }

        if (empty($data['system_alert_with_locations'])) {
            $data['system_alert_with_locations'] = $this->system_alerts_model->get_alerts('main_system_alert');
        }

        $data['active']      = 'system_alert';
        $data['footer_js']   = [ 'spectrum.js', 'admin/system_alerts.js'];
        $data['view']        = 'admin/system_alerts';
        $data['extra_css'][] = 'spectrum.css';
        $this->load->view('admin/template', $data);
    }


    public function edit_system_alert()
    {
        $this->load->model('system_alerts_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('alert_message', 'System Alert Message', 'required');

        if ($this->form_validation->run()) {
            $data['system_alert_with_locations'] = $this->input->post();
            if ($data['system_alert_with_locations']['system_alert_locations']) {
                $result = $this->system_alerts_model->upsert_system_alert($data['system_alert_with_locations']);
                if ($result) {
                    $data['success_message']             = 'This alert has now been posted for all our users to see.';
                    $data['system_alert_with_locations'] = $result;
                } else {
                    $data['error_message'] = 'There was an error attempting to save the alert';
                }
            } else {
                $data['error_message'] = 'Please select at least one location to display the system alert on.';
            }
        } else {
            $data['error_message'] = 'Please enter a system alert to display.';
        }

        return $data;
    }


    public function delete_system_alert($system_alert_name)
    {
        $this->load->model('system_alerts_model');
        $result = $this->system_alerts_model->delete_system_alert($system_alert_name);

        if ($result === true) {
            $this->session->set_flashdata('success_message', 'System alert message deleted.');
        } elseif ($result === null) {
            $this->session->set_flashdata('error_message', 'There was no system alert to delete.');
        } else {
            $this->session->set_flashdata('error_message', 'The system alert message was not deleted.');
        }

        return redirect('admin/system-alert');
    }


    public function get_alert_colors()
    {
        $this->load->model('system_alerts_model');
        $colors = $this->system_alerts_model->get_alert_colors();
        header('Content-Type: application/json');
        return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($colors));
    }
}
