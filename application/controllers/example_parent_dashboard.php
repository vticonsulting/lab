<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include_once(APPPATH . 'controllers/base/Base_dashboard.php');

class Parent_Dashboard extends Base_dashboard
{
    public function __construct()
    {
        parent::__construct();
        $this->faq_url          = 'home/help';
        $this->help_url         = 'home/help/1';
        $this->contentGroup     = 'contentGroup2';
        $this->contentGroupName = 'homepage';
    }


    /**
     *
     * PAYMENT FUNCTION DOES HAPPEN
     *
     * Sponsor/parent payment dashboard. 48674015
     */
    public function payment()
    {
        $this->load->model('user_experiment_model');
        $this->logout_if_is_not_in_group($this->ion_auth->get_sponsor_parent_group_array());

        $this->load->helper('sponsor_participant_helper');

        $data = $this->prepare_dashboard();

        $data['active_main'] = 'home';
        $data['active_nav']  = 'Home';//replacing active_main when we remove desktop

        $data['tabs'] = ['participants'];

        array_push($data['footer_js'], 'froogaloop.min.js');
        array_push($data['footer_js'], 'dashboard/vimeo.js');

        $data['show_zendesk_widget']   = true;
        $data['today_video_initial']   = '88678207';
        $data['parent_dash']           = true;
        $data['is_data_layer_enabled'] = true;

        $this->user_experiment_model->record_dashboard_entry();

        if ($data['mobile'] == true) {
            $data['welcome_vid']  = $data['teacher'] ? '88680051' : '88675181';
            $data['mobile_title'] = 'Home';
            $data['view']         = 'dashboard/mobile/partials/home_sponsor';
            $this->load->view('dashboard/mobile/template', $data);
        } else {
            $this->load->view('dashboard/template', $data);
        }
    }


    public function prepare_dashboard($ref_slug = false)
    {
        $this->load->helper('microsite');
        $this->load->model('school_merchant_model');

        $user_id                        = $this->ion_auth->user()->row()->id;
        $data['ref_slug']               = $ref_slug;
        $data['student']                = false;
        $data['sponsor']                = $this->user_group_model->has_user_group($user_id, User_group_model::SPONSORS);
        $data['is_parent']              = $this->user_group_model->has_user_group($user_id, User_group_model::PARENTS);
        $data['sponsor_dash']           = true;
        $data['is_parent_sponsor_page'] = true;
        $data['help_action_url']        = '/home/help_desk';
        $data['user_group']             = User_group_model::SPONSORS;
        $data['programs']               = $this->program_model->get_programs_for_sponsor_or_parent($user_id);
        $data['user_id']                = $user_id;
        $data['profile']                = $this->user_model->get_user_profile($user_id);
        $data['profile_name']           = $data['profile']['first_name'].' '.$data['profile']['last_name'];
        $data['main_heading']           = '';
        $data['main_class']             = 'no-scroll';
        $data['sidebar_widgets']        = ['sponsor_pledge'];
        $data['sponsor_types']          = $this->user_model->sponsor_types(true);
        $data['color_theme']            = 'no_theme';
        $data['pledge_types']           = $this->pledge_model->pledge_types(['ppj']);

        // replace with pledges
        $data['pledges'] = $this->pledge_model->get_sponsor_pledges_for_payment($user_id);

        $data['payable']              = $this->pledge_model->pledges_payable($data['pledges']);
        $data['program_online_pymts'] = count($this->pledge_model->filter_pledges_payable_online($data['pledges'])) > 0 ? true : false;
        if ($this->agent->is_mobile()) {
            $data['mobile']    = true;
            $data['no_pledge'] = true;
        }

        $participants         = $this->user_model->get_participants_for_parent($this->session->userdata('user_id'));
        $data['participants'] = $this->user_model->attach_num_alerts_to($participants);
        $data['currUserId']   = $this->ion_auth->get_current_user_id();
        $this->_prepare_participants_list($data);

        $data['view']              = 'dashboard/main';
        $data                      = prepare_dash_microsite($data);
        $data['footer_init']       = ['tk_common.set_multipliers'];
        $data['footer_init_param'] = [$data['pledge_types']['multipliers']];
        $data['footer_js']         = ['menu.js'];
        $data['logged_in_user']    = $this->user_model
            ->get_user_profile_lite($this->ion_auth->logged_in());

        $this->_add_content_group_info($data);

        return $data;
    }


    private function _prepare_participants_list(&$data)
    {
        $user_ids                    = $this->user_model->get_sponsored_participants($data['currUserId']);
        $data['user_child_profiles'] = [];
        $data['user_part_profiles']  = [];
        if (!empty($user_ids)) {
            $user_profiles = $this->user_model->get_mini_user_profile($user_ids);
            foreach ($user_profiles as $profile) {
                if ($profile->parent_id == $data['currUserId']) {
                    // Only parent kids can have pledge button
                    $profile->period = $this->pledge_model->validate_pledge_within_periods($profile->program_id);
                    array_push($data['user_child_profiles'], $profile);
                } else {
                    array_push($data['user_part_profiles'], $profile);
                }
            }
        }
    }


    /**
     * Handles the Help tab on the sponsor/parent dashboard
     */
    public function sponsor_parent_faq()
    {
        return redirect(get_titan_url('support/login'));
    }


    protected function logout_if_not_in_valid_group()
    {
        $this->logout_if_is_not_in_group($this->ion_auth->get_sponsor_parent_group_array());
        return true;
    }


    protected function set_help_desk_validation($user_group)
    {
        $this->form_validation->set_rules('program', 'School Name', 'required');
        parent::set_help_desk_validation($user_group);
    }


    /**
     * The My Pledges view for sponsors/parents. 48674015
     */
    public function sponsor_pledges()
    {
        $this->logout_if_is_not_in_group($this->ion_auth->get_sponsor_parent_group_array());

        $this->load->config('form_list');
        $this->load->helper('pledge_total');

        $data                = $this->prepare_dashboard();
        $data['active_main'] = 'my_pledges';
        $data['active_nav']  = 'Pledges';//replacing active_main when we remove desktop
        $data['parent_dash'] = true;
        $data['countries']   = $this->config->item('country_list');
        $data['states']      = $this->config->item('state_list');
        $data['unit_data']   = $this->booster_api->get_unit_data($data['unit_id']);

        $data['tabs'] = [
      'my_pledges',
    ];

        if ($data['mobile'] == true) {
            $data['mobile_title']             = 'My Pledges';
            $data['sponsor_pledges_editable'] = true;
            $data['view']                     = 'dashboard/mobile/partials/my_pledges';
            $this->load->view('dashboard/mobile/template', $data);
        } else {
            $this->load->view('dashboard/template', $data);
        }
    }


    public function sponsor_mobile_menu()
    {
        $this->logout_if_is_not_in_group($this->ion_auth->get_sponsor_parent_group_array());

        $data = $this->prepare_dashboard();

        $data['view'] = 'dashboard/mobile/partials/menu_sponsor';
        $this->load->view('dashboard/mobile/template', $data);
    }


    public function edit_laps()
    {
        $this->load->helper('number_format');
        $this->logout_if_is_not_in_group($this->ion_auth->get_sponsor_parent_group_array());

        $this->load->helper('sponsor_participant_helper');
        $parent_id = $this->ion_auth->get_current_user_id();

        $data = $this->prepare_dashboard();

        $data['lap_participants'] = $this->user_model->get_parent_laps_for_edit($parent_id);

        $data['active_main']       = 'edit_laps';
        $data['active_main_title'] = 'Edit Laps';

        $data['tabs'] = ['edit_laps'];

        array_push($data['footer_js'], 'dashboard/vimeo.js');

        $data['today_video_initial'] = '88678207';
        $data['parent_dash']         = true;

        if ($data['mobile'] == true) {
            $this->load->view('dashboard/mobile/template', $data);
        } else {
            $this->load->view('dashboard/template', $data);
        }
    }


    public function delete_participant($participant_user_id)
    {
        $is_deleted = $this->user_model->delete_participant($participant_user_id);
        if ($is_deleted) {
            //redirect to parent dashboard and give success message
            redirect('/home/dashboard');
        } else {
            //redirect to participant info page and give failure message
            $this->session->set_flashdata('message', 'Error deleting participant, please contact customer support.');
            redirect('/participant/profile');
        }
    }
}
