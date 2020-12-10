<?php if (! defined('BASEPATH')) {
  exit('No direct script access allowed');
}

include_once(APPPATH . 'controllers/base/Base_dashboard.php');

class Participant_Dashboard extends Base_dashboard
{


  public function __construct() {
    parent::__construct();
    $this->faq_url  = 'home/help';
    $this->help_url = 'home/help/1';
    $this->load->model('prize_model');
    $this->load->model('program_pledge_settings_model');
    $this->load->model('program_sponsor_model');
    $this->load->model('microsite_model');
    $this->contentGroup     = 'contentGroup1';
    $this->contentGroupName = 'participantdash';
  }


  public function prepare_dashboard($ref_slug = false) {
      $data['ref_slug']        = $ref_slug;
      $data['student']         = true;
      $data['sponsor']         = false;
      $data['help_action_url'] = '/home/help_desk';

      $data['teacher'] = $this->user_group_model
        ->has_user_group($this->student_id, User_group_model::TEACHERS);

    //    if participant is registered????
    if (!$this->session->userdata('participant_registered')) {
      $this->session->set_flashdata(
        'error',
        '<strong>Please complete Registration to particpate in the program.</strong>'
      );
      return redirect('/register/participant');
    } else {
      if (!$this->user_model->student_waivered($this->student_id)) {
        $this->session->set_flashdata(
          'error',
          '<strong>Please complete Participation Terms to particpate in the program.</strong>'
        );
        $parents = $this->user_model->student_parents($this->student_id);
        if (isset($parents[0]->fr_code)) {
          setcookie('parent_fr_code', $parents[0]->fr_code, 0, '/');
        }

        return redirect('/register/participant-terms');
      }
    }

    //    default func
      $data['user_group'] = User_group_model::STUDENTS;
      $data['main_nav']   = [
            'dashboard'   => 'Dashboard',
            'get_pledges' => 'Get Pledges',
            'stats'      => 'Stats',
            'my_pledges'  => 'My Pledges',
            'rewards'     => 'Rewards',
            'help'        => 'Help',
            'alerts'     => 'Alerts'
      ];

      $data['user_id']                = $this->student_id;
      $data['profile']                = $this->user_model->get_user_profile($this->student_id);
      $data['parent']                 = $this->user_model->get_user_profile_lite($data['profile']['parent_id']);
      $parent_id                      = !empty($data['profile']['parent_id']) ? $data['profile']['parent_id'] : null;
      $data['profile_name']           = $data['parent']['first_name'].' '.$data['parent']['last_name'];
      $data['parentFrCode']           = $this->user_model->get_fr_code($parent_id);
      $data['group_id']               = $data['profile']['group_id'];
      $data['program_id']             = $data['profile']['program_id'];
      $data['online_payment_enabled'] = $this->program_model->online_payment_enabled($data['program_id']);
      $data['school']                 = $this->school_model->school_for_program($data['program_id']);
      $data['event_name']             = $data['profile']['event_name'];
      $data['main_heading']           = $data['profile']['event_name'];
      $data['sponsor_pledge_url']     = '/student/get_pledges/' . ($data['mobile'] ? '#share' : '2');
      $data['sponsor_types']          = $this->user_model->sponsor_types(true);
      $data['better_funds_paragraph'] = $this->microsite_model->get_better_funds_raised_for_text($data['profile']['program_id']);
      $data['unit_id']                = $this->program_model->get_unit_id_from_program_id($data['program_id']);
      $pledge_type_exclusions         = ['ppj'];
      $data['pledge_types']           = $this->pledge_model->pledge_types(true, $pledge_type_exclusions, $data['unit_id']);
      $this->session->set_userdata([ 'program_id' => $data['program_id'] ]);

      $this->load->library('jwplayer');
      $data['profile']['personal_video_url'] = $this->jwplayer->convert_url_to_embed($data['profile']['personal_video_url']);

      // 2nd parameter needs to be the program_id (not null) so that end date can populate in order to avoid erroring on alerts tab
      $data['prizes']         = $this->prize_model->get_prizes_available_by_student([intval($this->student_id)], $data['program_id'], [$data['group_id']]);
      $data['prize_quantity'] = $this->pledge_model->get_all_pledge_counts_for_participant($this->student_id);

      $data['program_pledge_settings'] = $this->program_pledge_settings_model->get_pledge_settings($data['program_id']);
      if ($data['teacher'] || $data['program_pledge_settings']->flat_donate_only) {
        array_push($pledge_type_exclusions, 'ppl');
      }

      $data['sidebar_widgets'] = [
              'profile'        => 'mini_profile',
              'pledge_form'    => 'student_pledge_form',
      ];

      // needed for pledge progress (goal) widget and my pledges widget
      $this->load->helper('widget_pledges');
      setup_widget_pledges($data, $this->student_id, $data['program_id']);

      // needed for pledge widget & other places
      $program                         = $this->program_model->get($data['program_id']);
      $data['due_date']                = date('m/d/Y', strtotime($program->due_date));
      $data['program_online_pymts']    = $program->online_payment_enabled;
      $data['sponsor_convenience_fee'] = $program->sponsor_convenience_fee;
      $data['payable_to']              = $program->payee;
      $data['school_processing_fee']   = $program->school_processing_fee;
      $data['optional_sponsor_fee']    = $program->optional_sponsor_fee;
      $data['programs']                = [$program];
      $data['programs'][$program->id]  = $program;
      $data['collection_type']         = $program->collection_type;
      $data['program']                 = $program;

      $this->load->library('booster_api');
      $data['unit_data'] = $this->booster_api->get_unit_data($program->unit_id)->data;

      $data['footer_js'] = [
        'facebook_button.js',
        'dashboard/school_goals.js',
        'owl.carousel.2.0.0-beta.2.4.3.min.js',
        'video_player.js',
        'menu.js'
      ];

      $data['extra_css'][] = 'owl.carousel.2.0.0-beta.2.4.3.css';
      $data['extra_css'][] = 'owl.carousel.theme.2.0.0-beta.2.4.3.min.css';

      $data['urls'] = $this->user_model->get_special_urls($this->student_id);

      $data['share_buttons_fixed'] = true;

      $this->load->helper('microsite');
      $data = prepare_dash_microsite($data);

      $data['currUserId'] = $this->ion_auth->get_current_user_id();
      $this->_prepare_participants_list($data);

      $this->load->library('Share_Hero_Video_Email', [ 'id' => $this->student_id ]);
      $data['facebook'] = $this->share_hero_video_email->get_facebook_href($data['microsite']->school_image_name);
      $data             = array_merge($data, $this->_assign_event_labels_based_on_hero_video());

      //determine current number of alerts
      $data['alerts'] = $this->_get_alerts($data);

      $data['footer_init']       = ['tk_common.set_multipliers'];
      $data['footer_init_param'] = [$data['pledge_types']['multipliers']];
      $data['base_url']          = base_url();

      $participants                 = $this->user_model->get_participants_for_parent($this->session->userdata('user_id'));
      $data['participants']         = $this->user_model->attach_num_alerts_to($participants);
      $data['show_family_pledging'] = $data['program_pledge_settings']->family_pledging_enabled == '1' && $data['profile']['family_pledging_enabled'] == '1' ? true : false;
      $data['program_participants'] = $this->program_model->filter_by_program_id($data['participants'], $program->id, $data['show_family_pledging'], $this->student_id);
      $data['sms_href']             = $this->share_hero_video_email->get_sms_href($data['profile'], $participants, $data['program_pledge_settings']->family_pledging_enabled);
      $data['mail']                 = $this->share_hero_video_email->get_share_email_href(
        $data['unit_data'],
        $data['profile'],
        $participants,
        $data['program_pledge_settings']->family_pledging_enabled
      );

      $data['mobile'] = $this->agent->is_mobile();

      $data['view'] = 'dashboard/main';
      $data['microsite']->color_theme ?: 'default_theme';

      // Facebook share info
      $this->load->helper('sponsor_participant_helper');
      $data['fb_share_data'] = setFacebookShareData($data);

      $this->load->helper('school_goal');
      $data = prepare_dash_school_goal($data);

    if (!empty($data['microsite']->pics) ||
          !empty($data['school_goal']['total'])) {
      $data['sidebar_widgets']['below_pledge_form'] = 'school_goals';
    }

      $data['period'] = $this->pledge_model->validate_pledge_within_periods($data['program_id']);

      $data['logged_in_user'] = $this->user_model
        ->get_user_profile_lite($this->ion_auth->logged_in());

      $this->_add_content_group_info($data);

      return $data;
  }


  private function _prepare_participants_list(&$data) {
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


  public function student_delete_pledge( $pledge_id ) {
    $this->output->enable_profiler(false);
    $status = false;

    $this->load->model('pledge_model');
    $this->load->model('prize_bound_student_model');
    $this->prize_bound_student_model->prepare_prize_pledge_delete($pledge_id);

    $pledge_delete_result = $this->pledge_model->delete_pledge($pledge_id);

    if (!empty($pledge_delete_result)) {
      $this->prize_bound_student_model->pledge_update_deleted();
      $status = true;
    }

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([ 'message' => 'confirmed', 'status' => $status ]));

  }


  /**
   * this is called when an ee sponsor is added
   *
   * @return void
   */
  public function ajax_add_ee_sponsor() {
    $parent_id = $this->ion_auth->logged_in();
    if (! $parent_id) {
      header('HTTP/1.1 500 Internal Server Booboo');
      header('Content-Type: application/json; charset=UTF-8');
      $error                = new stdClass();
      $error->error         = 'Your session has expired';
      $error->session_error = true;
      die(json_encode($error));
    }

    $this->load->model('potential_sponsor_model');
    $this->load->model('program_model');

    $first_name = trim($this->input->post('first_name'));
    $last_name  = trim($this->input->post('last_name')); //non-required-field
    $email      = trim($this->input->post('email'));

    $success_message = [];
    $error_message   = [];
    $this->load->helper('potential_sponsor_email_helper');
    $this->load->helper('potential_sponsor_email_enrollment_helper');

    if ($email) {
      $this->load->library('form_validation');
      $valid_email = $this->form_validation->valid_email($email);
      if (! $valid_email) {
        $error_message[] = '<div class="alert alert-error"><strong>Error: Invalid Email</strong></div>';
      }
    } else {
      $valid_email = false;
    }

    // Add Potential Sponsor from Form
    if ($first_name && $email && $valid_email) {
      $profile             = $this->user_model->get_user_profile($this->student_id);
      $participants        = $this->user_model->get_participants_for_parent($this->session->userdata('user_id'));
      $ee_sponsors_user_id = $this->user_model->get_ee_sponsors_user_id($profile, $participants);
      $pledges             = $this->pledge_model->get_sponsor_pledges_for_payment($parent_id, false, false);
      $this->load->helper('sponsor_participant_helper');
      $program_id      = $profile['program_id'];
      $sorted_sponsors = get_sorted_sponsors($program_id, $pledges, $ee_sponsors_user_id);

      $has_unsubscribe = $this->potential_sponsor_model->has_email_unsubscribe($ee_sponsors_user_id, $this->ion_auth->logged_in(), $email);
      if ($has_unsubscribe == true) {
        $error_message[] = '<div class="alert alert-error"><strong>This email address has been previously ' .
            'unsubscribed from our emails.</strong></div>';
      } elseif (array_key_exists($email, $sorted_sponsors['current_sponsors'])) {
        $error_message[] = '<div class="alert alert-error"><strong>Error Adding Current Sponsor Email: '
            . $email . '</strong></div>';
      } else {
        //save potential sponsor
        $this->potential_sponsor_model->save_potential_sponsor(
          $ee_sponsors_user_id,
          $email,
          $first_name,
          $last_name,
          $this->ion_auth->logged_in()
        );
        $unit_id = $this->program_model->get($program_id)->unit_id;

        //email potential sponsor
        $this->load->model('program_pledge_settings_model');
        $program_pledge_settings   = $this->program_pledge_settings_model->get_pledge_settings($program_id);
        $participants              = $this->user_model->get_participants_for_parent($this->session->userdata('user_id'));
        $participant_names         = format_students_name($profile, $participants, $program_pledge_settings->family_pledging_enabled);
        $are_is                    = are_is_modifier($profile, $participants, $program_pledge_settings->family_pledging_enabled);
        $potential_sponsor_emailer = new potential_sponsor_email_enrollment_helper(
          $ee_sponsors_user_id,
          $this->ion_auth->logged_in(),
          $program_id,
          $unit_id,
          $are_is,
          $participant_names
        );
        $potential_sponsor_emailer->send();

        $participant_ids    = array_map(
          function($participant){
            return $participant->user_id;
          },
          $participants
        );
        $potential_sponsors = $this->potential_sponsor_model->get_potential_sponsors($ee_sponsors_user_id, $this->ion_auth->logged_in());
        $this->load->helper('user_activities_helper');
        checkEasyEmailerActivity($participant_ids, $potential_sponsors);

        $success_message[] = '<strong>Subscribed and sent email to: '
            . $email . '</strong>';
      }
    } else {
      if (count($error_message) == 0) {
        $error_message[] = '<div class="alert alert-error"><strong>Error: missing fields</strong></div>';
      }
    }

    $data = [];
    if ($success_message) {
      $data['success_message']            = $success_message;
      $data['new_ee_sponsor']             = new stdClass();
      $data['new_ee_sponsor']->first_name = $first_name;
      $data['new_ee_sponsor']->last_name  = $last_name;
      $data['new_ee_sponsor']->email      = $email;
    }

    if ($error_message) {
      $data['error_message'] = $error_message;
    }

    echo json_encode($data);
    die();

  }


  public function ajax_enroll_ee_sponsor() {
    $data['profile']     = $this->user_model->get_user_profile($this->student_id);
    $participants        = $this->user_model->get_participants_for_parent($this->session->userdata('user_id'));
    $ee_sponsors_user_id = $this->user_model->get_ee_sponsors_user_id($data['profile'], $participants);
    $data['program_id']  = $data['profile']['program_id'];
    $parent_id           = !empty($data['profile']['parent_id']) ? $data['profile']['parent_id'] : null;

    $pledges = $this->pledge_model->get_sponsor_pledges_for_payment($parent_id, false, false);
    $this->load->helper('sponsor_participant_helper');
    $program_id      = !empty($data['program_id']) ? $data['program_id'] : null;
    $sorted_sponsors = get_sorted_sponsors($program_id, $pledges, $ee_sponsors_user_id);

    $response          = new stdClass();
    $response->success = true;

    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email Address', 'required|trim|valid_email');
    if ($this->form_validation->run()) {
      //ENROLL SPONSOR
      $email             = $this->input->post('email');
      $potential_sponsor = $sorted_sponsors['previous_sponsors'][$email];
      $this->load->model('potential_sponsor_model');
      $this->potential_sponsor_model->save_potential_sponsor(
        $ee_sponsors_user_id, $potential_sponsor->email,
        $potential_sponsor->first_name, $potential_sponsor->last_name,
        $this->ion_auth->logged_in(), $potential_sponsor->id
      );
      //send email
      $this->load->helper('potential_sponsor_email_helper');
      $this->load->helper('potential_sponsor_email_enrollment_helper');
      $unit_id = $this->program_model->get($program_id)->unit_id;
      $this->load->model('program_pledge_settings_model');
      $program_pledge_settings   = $this->program_pledge_settings_model->get_pledge_settings($data['program_id']);
      $participants              = $this->user_model->get_participants_for_parent($this->session->userdata('user_id'));
      $participant_names         = format_students_name($data['profile'], $participants, $program_pledge_settings->family_pledging_enabled);
      $are_is                    = are_is_modifier($data['profile'], $participants, $program_pledge_settings->family_pledging_enabled);
      $potential_sponsor_emailer = new potential_sponsor_email_enrollment_helper(
        $ee_sponsors_user_id,
        $this->ion_auth->logged_in(),
        $program_id,
        $unit_id,
        $are_is,
        $participant_names
      );
      $potential_sponsor_emailer->send();
    } else {
      $response->success = false;
    }

    $participant_ids    = array_map(
      function($participant){
        return $participant->user_id;
      },
      $participants
    );
    $potential_sponsors = $this->potential_sponsor_model->get_potential_sponsors($ee_sponsors_user_id, $this->ion_auth->logged_in());
    $this->load->helper('user_activities_helper');
    checkEasyEmailerActivity($participant_ids, $potential_sponsors);

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response));
  }


  public function ajax_unenroll_ee_sponsor() {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email Address', 'required|trim|valid_email');
    if ($this->form_validation->run()) {
      $data['profile']     = $this->user_model->get_user_profile($this->student_id);
      $participants        = $this->user_model->get_participants_for_parent($this->session->userdata('user_id'));
      $ee_sponsors_user_id = $this->user_model->get_ee_sponsors_user_id($data['profile'], $participants);
      //UNENROLL SPONSOR
      $email = $this->input->post('email');
      $this->load->model('potential_sponsor_model');
      $this->potential_sponsor_model->remove_potential_sponsor($ee_sponsors_user_id, $this->ion_auth->logged_in(), $email);
    }
  }


  /**
   * Handles the Get Pledges tab on the students dashboard
   */
  public function student_get_pledges($share = "") {
    $this->load->config('form_list');
    $this->load->model('potential_sponsor_model');
    $this->load->model('participant_model');
    $this->load->model('prize_bound_student_model');
    $this->load->library('form_validation');
    $this->load->helper('user_activities_helper');

    $this->logout_if_is_not_in_group($this->ion_auth->get_participant_group_array(), $this->student_id);
    $data                               = $this->prepare_dashboard();
    $data['active_main']                = 'get_pledges';
    $data['active_nav']                 = 'Get Pledges'; // Replacing active_main when we remove desktop
    $data['num_of_texts_shared']        = $this->participant_model->get_num_of_texts_shared_by_user_id($this->student_id);
    $data['show_num_of_texts_dropdown'] = $this->prize_bound_student_model->show_num_of_texts_shared_dropdown($this->student_id);

    if (!empty($share)) {
      switch($share){
        case '1':
          $data['active_secondary'] = 'previous_sponsors';
          break;
        case '2':
          $data['active_secondary'] = 'share';
          break;
        default:
          break;
      }
    }

    $data['tabs']                     = [
      'how_to',
      ['Easy-Emailer' => 'previous_sponsors'],
      'share'
    ];
    $parent_id                        = !empty($data['profile']['parent_id']) ? $data['profile']['parent_id'] : null;
    $data['potential_sponsors_exist'] = $this->pledge_model
                                             ->potential_sponsors_exist($data['program_id'], $this->student_id, $parent_id);

    if ($data['microsite']->get_pledges_vid_override) {
      $data['pledge_video_initial'] = $data['microsite']->get_pledges_vid_override->hash;
    } else {
      $data['pledge_video_initial'] = $this->config->item('get_pledges_video');
    }

    $pledgesAmount = $data['widget_pledges']['total_avg'];
    if ($data['program_pledge_settings']->flat_donate_only) {
      $pledgesAmount = $data['widget_pledges']['total_flat_avg'];
    }

    $data['goal_remaining'] = $data['profile']['personal_pledge_goal'] - intval(substr($pledgesAmount, 1));
    if ($data['goal_remaining'] < 0) {
      $data['goal_remaining'] = 0;
    }

    $pledges = $this->pledge_model->get_sponsor_pledges_for_payment($parent_id, false, false);
    $this->load->helper('sponsor_participant_helper');
    $program_id = !empty($data['program_id']) ? $data['program_id'] : null;

    //set parent email
    $parent = $this->user_model->get($parent_id);
    if ($parent && property_exists($parent, 'email')) {
      $data['parent_email'] = $parent->email;
    } else {
      $data['parent_email'] = '';
    }

    $data['better_funds_paragraph'] = $this->microsite_model->get_better_funds_raised_for_text($program_id);

    $ee_sponsors_user_id  = $this->user_model->get_ee_sponsors_user_id($data['profile'], $data['participants']);
    $sorted_sponsors      = get_sorted_sponsors($program_id, $pledges, $ee_sponsors_user_id);
    $previous_sponsors    = $sorted_sponsors['previous_sponsors'];
    $current_sponsors     = $sorted_sponsors['current_sponsors'];
    $previous_sponsors    = $this->potential_sponsor_model->attach_user_email_opt_out($previous_sponsors);
    $potential_sponsors   = $this->potential_sponsor_model->get_potential_sponsors($ee_sponsors_user_id, $this->ion_auth->logged_in());
    $sponsors             = $previous_sponsors + $potential_sponsors;
    $data['all_sponsors'] = $this->potential_sponsor_model->sort_sponsors_by_ee_status($sponsors, $potential_sponsors, $current_sponsors);
    // Check and Record Easy Emailer Activity if user has met criteria
    $data['alerts']       = $this->_get_alerts($data);
    $data['participants'] = $this->user_model->attach_num_alerts_to($data['participants']);

    $data['potential_sponsors_exist'] = $this->pledge_model
                                            ->potential_sponsors_exist(
                                              $data['program_id'],
                                              $ee_sponsors_user_id,
                                              $parent_id
                                            );
    $data['student_id']               = $this->student_id;

    if (!is_array($data['footer_js'])) {
      $data['footer_js'] = [];
    }

    $data['footer_js'][] = 'jquery.validate.min.js';
    $data['footer_js'][] = 'previous_sponsors.js';

    $data['footer_js_cdn'] = ['https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.3/handlebars.min.js'];
    if ($data['mobile'] == true) {
      $data['mobile_title'] = 'Get Pledges';
      $data['view']         = 'dashboard/mobile/partials/get_pledges';
      $this->load->view('dashboard/mobile/template', $data);
    } else {
      $data['view'] = 'dashboard/main';
      $this->load->view('dashboard/template', $data);
    }
  }


  protected function logout_if_not_in_valid_group() {
    $this->logout_if_is_not_in_group($this->ion_auth->get_sponsor_parent_group_array());
    return true;
  }


  public function student_payment_confirmation() {
    $this->logout_if_is_not_in_group($this->ion_auth->get_participant_group_array(), $this->student_id);
    $data = $this->prepare_dashboard();
    $this->_payment_confirmation($data, 'student');
  }


  /**
   * When logged in as student, the payment screen is also usable -
   * to support the use case of parent and student going through the
   * process together.
   */
  public function student_payment($error = false) {
    $this->logout_if_is_not_in_group($this->ion_auth->get_participant_group_array(), $this->student_id);
    $data = $this->prepare_dashboard();

    $data['active_main']       = 'pledge_payment';
    $data['active_main_title'] = 'Payment';

    $this->load->model('pledge_model');
    if ($this->ion_auth->in_group(User_group_model::TEACHERS, $this->student_id)) {
      $data['pledges'] = $this->pledge_model->get_teacher_pledges_for_payment(intval($this->student_id));
    } else {
      $data['pledges'] = $this->pledge_model->get_student_pledges_for_payment(intval($this->student_id));
    }

    // If for any reason no payment ready pledges are found
    if (empty($data['pledges'])) {
      if (!empty($error)) {
        $this->session->set_flashdata(
          'message',
          'It appears this order has already been submitted, or the pledges have already been paid for.'
        );
      }

      redirect('student/my_pledges');
    }

    $data['payment_action'] = '/student/payment-details';

    $data['tabs'] = [
      'pledge_payment'
    ];

    array_push($data['footer_js'], 'jquery.validate.min.js');
    array_push($data['footer_js'], 'dashboard/payment.js');

    if ($data['mobile'] == true) {
      $data['no_pledge'] = true;
      $data['view']      = 'dashboard/mobile/partials/pledge_payment';
      $this->load->view('dashboard/mobile/template', $data);
    } else {
      $this->load->view('dashboard/template', $data);
    }
  }


  public function stats() {
    $this->load->model('pledge_model');
    $this->load->config('form_list');

    $this->logout_if_is_not_in_group($this->ion_auth->get_participant_group_array(), $this->student_id);

    $student_id               = intval($this->ion_auth->user()->row()->id);
    $data                     = $this->prepare_dashboard($student_id);
    $data['pledge_map_stats'] = $this->pledge_model->get_state_country_counts(
      $data['program_id'],
      $data['profile']['classroom_id'],
      null,
      $this->session->userdata('view_fr_code')
    );
    $parent_id                = !empty($data['profile']['parent_id']) ? $data['profile']['parent_id'] : null;

    $data['potential_sponsors_exist'] = $this->pledge_model
      ->potential_sponsors_exist($data['program_id'], $this->student_id, $parent_id);

    $data['active_main'] = 'STATS';
    $data['active_nav']  = 'Stats';//replacing active_main when we remove desktop

    //calclulate total pledges (flat if flat only) and pledge goal
    $classroom_id = !empty($data['profile']['classroom_id']) ? ($data['profile']['classroom_id']) : null;
    if (!empty($classroom_id)) {
      $response     = ($data['program_pledge_settings']->flat_donate_only) ? $this->pledge_model->get_flat_pledge_total_for_class($classroom_id) : $this->pledge_model->get_ppl_total_for_class($classroom_id);
      $pledge_total = !empty($response->pledge_total) ? $response->pledge_total : null;
      $this->load->model('classroom_model');
      $classroom = $this->classroom_model->get($classroom_id);
    }

    $pledge_meter                  = empty($classroom->pledge_meter) ? null : $classroom->pledge_meter;
    $data['class_pledge_o_meters'] = $this->program_model->get_class_pledge_o_meters($data['program_id']);

    if ($classroom->pledge_meter > 0) {
      $data['single_class_pledge_o_meter'] = $this->program_model->get_class_pledge_o_meters($data['profile']['classroom_id'], $pledge_meter);
    }

    $pledge_total = empty($pledge_total) ? 0 : round(str_replace(",", "", $pledge_total));
    $ppl          = !empty($ppl) ? ceil($ppl) : 0;

    if (!empty($pledge_meter) && $pledge_meter != 0) {
      $percent_of_goal = ($pledge_total / $pledge_meter) > 1 ? 100 : 100 * ($pledge_total / $pledge_meter);
    } else {
      $percent_of_goal = 50;
    }

    $data['pledge_total']    = $pledge_total;
    $data['pledge_meter']    = $pledge_meter;
    $data['percent_of_goal'] = $percent_of_goal;
    $data['minus_percent']   = 100 - $percent_of_goal;

    $data['countries'] = $this->config->item('country_list');
    $data['states']    = $this->config->item('state_list');

    $data['tabs'] = [
      ['Pledge-O-Meter' => 'pledge_meter'],
            'state_map'
    ];

    $data['footer_js'][] = 'state_map.js';
    $data['footer_js'][] = 'state_map_dash.js';
    $data['footer_js'][] = 'highcharts_5_0_14/highcharts.js';

    $this->load->helper('pledge_total');

    if ($data['mobile'] == true) {
      $data['mobile_title'] = 'Stats';
      $data['view']         = 'dashboard/mobile/partials/stats';
      $this->load->view('dashboard/mobile/template', $data);
    } else {
      $this->load->view('dashboard/template', $data);
    }
  }


  public function student_pledges($sponsors_tab_active=false) {
    $this->logout_if_is_not_in_group($this->ion_auth->get_participant_group_array(), $this->student_id);
    $this->load->config('form_list');
    $data                = $this->prepare_dashboard();
    $parent_id           = !empty($data['profile']['parent_id']) ? $data['profile']['parent_id'] : null;
    $data['active_main'] = 'my_pledges';
    $data['active_nav']  = 'My Pledges';//replacing active_main when we remove desktop
    // Must do another check if approved for braintree
    $this->load->model('school_merchant_model');
    $data['program_online_pymts'] = $data['program_online_pymts'] &&
      $this->school_merchant_model->
      online_payment_available($data['school']->id, $data['school']->merchant_type);

    //calclulate ppl and pledge goal
    $this->load->model('pledge_model');
    $classroom_id = !empty($data['profile']['classroom_id']) ? ($data['profile']['classroom_id']) : null;
    if (!empty($classroom_id)) {
      $response = $this->pledge_model->get_ppl_total_for_class($classroom_id);
      $ppl      = !empty($response->ppl) ? $response->ppl : null;
      $this->load->model('classroom_model');
      $classroom = $this->classroom_model->get($classroom_id);
    }

    $data['potential_sponsors_exist'] = $this->pledge_model
      ->potential_sponsors_exist($data['program_id'], $this->student_id, $parent_id);
    //all pledges associated with the student
    $data['pledges'] = $this->pledge_model->get_pledges_for_edit_by_student($this->student_id);

    // Get Comments from API
    $data['pledge_comments'] = $this->pledge_model->retrieve_pledge_comments_from_api([ $data['user_id'] ]);

    //all pledges assoicated with the parent
    $pledges             = $this->pledge_model->get_sponsor_pledges_for_payment($parent_id, false, false);
    $data['all_pledges'] = $pledges;

    $data['payable']   = $this->pledge_model->pledges_payable($data['pledges']);
    $data['countries'] = $this->config->item('country_list');
    $data['states']    = $this->config->item('state_list');

    $this->load->helper('sponsor_participant_helper');
    $program_id                = !empty($data['program_id']) ? $data['program_id'] : null;
    $sorted_sponsors           = get_sorted_sponsors($program_id, $pledges, $this->student_id);
    $data['previous_sponsors'] = $sorted_sponsors['previous_sponsors'];
    $data['current_sponsors']  = $sorted_sponsors['current_sponsors'];

    $this->load->model('user_model');
    $parent = $this->user_model->get($parent_id);
    if ($parent && property_exists($parent, 'email')) {
      $data['parent_email'] = $parent->email;
    } else {
      $data['parent_email'] = '';
    }

    $data['all_prev_sponsor_emails'] = [];
    foreach ($data['previous_sponsors'] as $key => $previous_sponsor) {
      array_push($data['all_prev_sponsor_emails'], $previous_sponsor->email);
    }

    $data['tabs'] = [
      'my_pledges',
      'my_sponsors'
    ];
    if ($sponsors_tab_active) {
      $data['active_tab_index'] = 1;
    }

    $this->load->helper('pledge_total');

    // Facebook share info
    $this->load->helper('sponsor_participant_helper');
    $data['fb_share_data'] = setFacebookShareData($data);

    if ($data['mobile'] == true) {
      $data['mobile_title'] = 'My Pledges';
      $data['view']         = 'dashboard/mobile/partials/my_pledges';
      $this->load->view('dashboard/mobile/template', $data);
    } else {
      $this->load->view('dashboard/template', $data);
    }
  }


  public function student_alerts() {

    $this->logout_if_is_not_in_group($this->ion_auth->get_participant_group_array(), $this->student_id);
    $data = $this->prepare_dashboard();

    $data['active_main'] = 'alerts';
    $data['active_nav']  = 'Alerts';//replacing active_main when we remove desktop

    //for now simce this is only alert check can use $data['num_alerts'] from prepare_student_dash()
    $data['new_prize_available'] = $data['alerts'];

    // Determine alerts page - student or teacher
    $this->load->helper('user_group');
    $data['user_group'] = get_user_group($this->student_id);

    $parent_id                        = !empty($data['profile']['parent_id']) ? $data['profile']['parent_id'] : null;
    $data['potential_sponsors_exist'] = $this->pledge_model
      ->potential_sponsors_exist($data['program_id'], $this->student_id, $parent_id);

    $data['tabs'] = [
      'alerts'
    ];

    if ($data['mobile'] == true) {
      $data['mobile_title'] = 'Alerts';
      $data['view']         = 'dashboard/partials/tab_content/alerts';
      $this->load->view('dashboard/mobile/template', $data);
    } else {
      $this->load->view('dashboard/template', $data);
    }
  }


  /**
     * Student Rewards Submission Method
     *
     * @param int - $current_reward ? (Note: not sure if this is an integer, but wanted the placeholder)
     * @param int - $current_id - int - The ID of ?
     *
     * @return response
  */
  public function student_rewards($current_reward = null, $current_id = null) {

    $this->logout_if_is_not_in_group($this->ion_auth->get_participant_group_array(), $this->student_id);
    $data = $this->prepare_dashboard();

    $this->load->helper('prize_reward_helper');
    $this->load->helper('pledge_period_helper');
    $pledge_periods = get_pledge_periods_for_prizes($data['prizes'], $data['program_id']);
    $this->load->model('pledge_model');
    $pledge_period_totals = $this->pledge_model->calculate_pledges_per_student_per_period($data['widget_pledges']['pledges'], $data['program_id'], $pledge_periods);

    $data['active_main'] = 'rewards';
    $data['active_nav']  = 'Rewards';//replacing active_main when we remove desktop

    // Determine rewards page - student or teacher
    $this->load->helper('user_group');
    $data['user_group'] = get_user_group($this->student_id);

    $data['tabs'] = ['my_rewards'];

    // initialize video with first prize
    $current_prize = empty($current_reward) ? 0 : intval($current_reward);

    if (isset($current_id)) {
      foreach ($data['prizes'] as $index => $prize) {
        if ($prize->id == $current_id) {
          $current_reward = $index;
          $current_prize  = $current_reward;
        }
      }
    }

    $parent_id                        = !empty($data['profile']['parent_id']) ? $data['profile']['parent_id'] : null;
    $data['potential_sponsors_exist'] = $this->pledge_model->
                                        potential_sponsors_exist($data['program_id'], $this->student_id, $parent_id);

    array_push(
      $data['footer_js'], 'owl.carousel.2.0.0-beta.2.4.3.min.js',
      'froogaloop.min.js',
      'dashboard/vimeo.js',
      'dashboard/my_rewards.js'
    );

    $data['extra_css'] = ['owl.carousel.2.0.0-beta.2.4.3.css','owl.carousel.theme.2.0.0-beta.2.4.3.min.css','minisite.css'];

    $data['prize_video_initial'] = $data['prizes'][$current_prize]->video;
    $data['current_reward']      = $current_reward;
    $data['pledge_periods']      = $pledge_period_totals['pledge_periods'];
    $data['total_ppl']           = $pledge_period_totals['total_ppl'];
    $data['total_flat']          = $pledge_period_totals['total_flat'];
    $data['total_pledges']       = ($data['program_pledge_settings']->flat_donate_only) ? $data['total_flat'] : $data['total_ppl'];

    if ($data['mobile'] == true) {
      $data['mobile_title'] = 'Prizes';
      $data['view']         = 'dashboard/mobile/partials/my_rewards';
      $this->load->view('dashboard/mobile/template', $data);
    } else {
      $this->load->view('dashboard/template', $data);
    }

  }


  /**
   *
   * Student-only dashboard
   *
  */
  public function student() {
    $this->logout_if_is_not_in_group($this->ion_auth->get_participant_group_array(), $this->student_id);
    $this->check_for_dashboard_cookie_and_set();

    $this->load->model('microsite_model');
    $this->load->model('video_player_model');
    $this->load->model('participant_model');
    $this->load->helper('sponsor_participant_helper');
    $this->load->model('school_merchant_model');
    $this->load->model('prize_bound_student_model');

    $data = $this->prepare_dashboard();

    $data['business_leaders'] = $this->pledge_model->get_business_leaderboard($data['program_id']);

    $this->load->library('braintree_payments');
    $school_merchant_data    = $this->school_merchant_model->get_merchant_data($data['school']->id);
    $data['braintree_token'] = $this->braintree_payments->get_client_token();
    $data['legal_name']      = $school_merchant_data ? $this->security->xss_clean($school_merchant_data->legal_name) : '';
    $data['tax_id']          = $school_merchant_data ? $this->security->xss_clean($school_merchant_data->tax_id) : '';

    $data['active_main'] = 'dashboard';
    $data['active_nav']  = 'Dashboard';//replacing active_main when we remove desktop
    $data['tabs']        = [];

    /* pledge modal errors when pledging on this version of safari
     * when pleding is changed to its own page please remove this logic 10-27-2015 */
    $this->load->library('user_agent');
    $bad_ios_user_agent            = 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_2 like Mac OS '
            . 'X) AppleWebKit/537.51.2 (KHTML, like Gecko) Version/7.0 '
            . 'Mobile/11D257 Safari/9537.53';
    $data['is_bad_safari_version'] = $this->agent->agent_string() === $bad_ios_user_agent;

    $data['current_reward'] = 0;

    $program_id                       = !empty($data['program_id']) ? $data['program_id'] : null;
    $parent_id                        = !empty($data['profile']['parent_id']) ? $data['profile']['parent_id'] : null;
    $data['potential_sponsors_exist'] = $this->pledge_model->potential_sponsors_exist($program_id, $this->student_id, $parent_id);
    $data['todays_vid_player']        = $this->video_player_model->get_character_video_player();
    $this->config->load('form_list');
    $data['countries']                  = $this->config->item('country_list');
    $data['states']                     = $this->config->item('state_list');
    $data['student_id']                 = $this->student_id;
    $data['num_of_texts_shared']        = $this->participant_model->get_num_of_texts_shared_by_user_id($this->student_id);
    $data['show_num_of_texts_dropdown'] = $this->prize_bound_student_model->show_num_of_texts_shared_dropdown($this->student_id);

    // think about breaking this out into a new function for reuse
    if ($this->ion_auth->in_group(User_group_model::TEACHERS, $this->student_id)) {
      $this->load->config('user_tasks');
      $this->load->model('user_tasks_model');
      $data['teacher_tasks'] = $this->user_tasks_model->get_user_tasks($this->student_id);
      // Get unit ID of the fun run to check in view for variable content
      $this->load->model('program_model');
      $data['funrun_unit_id'] = Program_model::UNIT_ID_FUNRUN;

      $user_group          = User_group_model::TEACHERS;
      $data['tabs'][]      = ["Teachers" => "welcome_teachers"];
      $data['welcome_vid'] = '88680051';

      $class_pledge_stats                  = $this->pledge_model->get_pledge_stats_by_classroom($data['profile']['classroom_id']);
      $data['class_pledge_total']          = $class_pledge_stats->total_pledged;
      $data['class_collection_percentage'] = $class_pledge_stats->collected_percentage;
      $data['class_pledge_data']           = $this->pledge_model->get_program_pledge_totals_by_status($data['program_id'], null, $data['profile']['classroom_id']);
    } else {
      $user_group          = User_group_model::STUDENTS;
      $data['welcome_vid'] = '88675181';
    }

    array_push($data['footer_js'], 'froogaloop.min.js');
    array_push($data['footer_js'], 'dashboard/vimeo.js');
    array_push($data['footer_js'], 'teacher_tab.js');

    $data['today_video_initial'] = '88678207';
    $pledge_totals               = $this->pledge_model->get_student_pledges_total_compiled($data['user_id']);
    if ($pledge_totals->pledge_count && $pledge_totals->laps !== null) {
      $data['tabs'][] = 'finish_line';
      $data['tabs'][] = ['overview' => 'overview_desktop'];
      //pledge totals is used to control the mobile tabs for finish line
      $data['pledge_totals'] = $pledge_totals;
    } else {
      $data['tabs'][] = ['overview' => 'overview_desktop'];
    }

    $data['vid_player']      = $this->video_player_model->get_participant_dash_player($program_id, $data['user_id'], (bool)$pledge_totals->pledge_count);
    $data['pledge_comments'] = $this->pledge_model->retrieve_pledge_comments_from_api([ $data['user_id'] ]);

    // Facebook share info
    $data['fb_share_data'] = setFacebookShareData($data);

    $data['hide_character_videos'] = $this->microsite_model->hide_character_videos($program_id);

    if ($data['hide_character_videos'] == 0) {
      $data['tabs'][] = ["Character Videos" => "character_videos"];
    }

    if ($data['mobile']) {
      $this->load->helper('prize_reward_helper');
      $this->load->helper('pledge_period_helper');
      $pledge_periods = get_pledge_periods_for_prizes($data['prizes'], $data['program_id']);
      $this->load->model('pledge_model');
      $pledge_period_totals   = $this->pledge_model->calculate_pledges_per_student_per_period($data['widget_pledges']['pledges'], $data['program_id'], $pledge_periods);
      $data['pledge_periods'] = $pledge_period_totals['pledge_periods'];
      $data['total_ppl']      = $pledge_period_totals['total_ppl'];
      $data['total_flat']     = $pledge_period_totals['total_flat'];
      $data['total_pledges']  = ($data['program_pledge_settings']->flat_donate_only) ? $data['total_flat'] : $data['total_ppl'];

      //set advertisment for mobile dashboard
      $data['ads']                             = $this->program_sponsor_model->get_my_ads($program_id, Program_sponsor_model::STUDENT_WELCOME);
      $data['read_more_welcome_wrapper_class'] = 'teacher_welcome_readmore_mobile';
      $data['view']                            = 'dashboard/mobile/partials/home';
      array_push($data['footer_js'], 'dashboard/my_rewards.js');
      $this->load->view('dashboard/mobile/template', $data);
    } else {
      //set advertisement for non mobile advertisement
      $data['ads']                             = $this->program_sponsor_model->get_my_ads($program_id, Program_sponsor_model::STUDENT_WELCOME);
      $data['read_more_welcome_wrapper_class'] = 'teacher_welcome_readmore';
      $this->load->view('dashboard/template', $data);
    }
  }


  public function mobile_menu() {
    $this->logout_if_is_not_in_group($this->ion_auth->get_participant_group_array(), $this->student_id);
    $data = $this->prepare_dashboard();

    $data['view'] = 'dashboard/mobile/partials/menu';
    $this->load->view('dashboard/mobile/template', $data);
  }


  private function _assign_event_labels_based_on_hero_video() {
    $has_hero_video = $this->user_model->has_hero_video($this->student_id);

    $data['sms_data_share']   = $has_hero_video ? 'Participant_SMS_Video' : 'Participant_SMS';
    $data['email_data_share'] = $has_hero_video ? 'Participant_Mail_Video' : 'Participant_Mail';

    return $data;
  }


  private function _get_alerts($data) {
    $count = 0;

    $weekend_prize = $this->prize_model->get_weekend_prize_dates(
      intval($this->student_id),
      $data['program_id'],
      $data['profile']['group_id'],
      $data['prizes']
    );
    if ($weekend_prize) {
      $count += count($weekend_prize);
    }

    $this->load->model('prize_bound_student_model');
    $facebook_prize = $this->prize_bound_student_model->should_show_facebook_prize_alert($data['user_id']);
    $facebook_prize ? $count++ : false;

    $text_share_prizes = $this->prize_bound_student_model->should_show_text_share_prize_alert($data['user_id']);
    $count            += count($text_share_prizes);

    $this->load->model('potential_sponsor_model');
    $potential_sponsor_enrolled_activity_prize = $this->potential_sponsor_model->should_show_easy_email_enrollment_alert($this->student_id, $data['profile']['parent_id']);
    $potential_sponsor_enrolled_activity_prize ? $count++ : false;

    $potential_sponsor_enrolled_activity_prize_1 = $this->potential_sponsor_model->should_show_easy_email_enrollment_alert_1($this->student_id, $data['profile']['parent_id']);
    $potential_sponsor_enrolled_activity_prize_1 ? $count++ : false;

    $potential_sponsor_enrolled_activity_prize_2 = $this->potential_sponsor_model->should_show_easy_email_enrollment_alert_2($this->student_id, $data['profile']['parent_id']);
    $potential_sponsor_enrolled_activity_prize_2 ? $count++ : false;

    $potential_sponsor_enrolled_activity_prize_3 = $this->potential_sponsor_model->should_show_easy_email_enrollment_alert_3($this->student_id, $data['profile']['parent_id']);
    $potential_sponsor_enrolled_activity_prize_3 ? $count++ : false;

    $potential_sponsor_enrolled_activity_prize_4 = $this->potential_sponsor_model->should_show_easy_email_enrollment_alert_4($this->student_id, $data['profile']['parent_id']);
    $potential_sponsor_enrolled_activity_prize_4 ? $count++ : false;

    $potential_sponsor_enrolled_activity_prize_5 = $this->potential_sponsor_model->should_show_easy_email_enrollment_alert_5($this->student_id, $data['profile']['parent_id']);
    $potential_sponsor_enrolled_activity_prize_5 ? $count++ : false;

    $potential_sponsor_enrolled_activity_prize_6 = $this->potential_sponsor_model->should_show_easy_email_enrollment_alert_6($this->student_id, $data['profile']['parent_id']);
    $potential_sponsor_enrolled_activity_prize_6 ? $count++ : false;

    $this->load->model('custom_program_alerts_model');
    $custom_program_alerts = $this->custom_program_alerts_model->get_active_alerts($data['program']->id);
    $count                += count($custom_program_alerts);

    $alerts = [
      'weekend_prize' => $weekend_prize,
      'facebook_share' => $facebook_prize,
      'text_share_prizes' => $text_share_prizes,
      'easy_email' => $potential_sponsor_enrolled_activity_prize,
      'easy_email_1' => $potential_sponsor_enrolled_activity_prize_1,
      'easy_email_2' => $potential_sponsor_enrolled_activity_prize_2,
      'easy_email_3' => $potential_sponsor_enrolled_activity_prize_3,
      'easy_email_4' => $potential_sponsor_enrolled_activity_prize_4,
      'easy_email_5' => $potential_sponsor_enrolled_activity_prize_5,
      'easy_email_6' => $potential_sponsor_enrolled_activity_prize_6,
      'custom_program_alerts' => $custom_program_alerts,
      'count' => $count
    ];
    return $alerts;
  }


  public function update_laps() {
    $this->output->enable_profiler(false);
    $user_id   = (int)$this->input->post('user_id');
    $parent_id = (int)$this->session->userdata('user_id');
    $laps      = (int)$this->input->post('laps');

    $new_lap_data = [
      'laps' => $laps,
      'laps_modified_by_user_id' => $parent_id,
      'laps_modified_ts' => null // will set to current time
    ];

    if ($laps <= 10) {
      $updated_result = false;
    } else {
      $this->load->model('user_model');
      $update_result = $this->user_model->update($user_id, $new_lap_data);
    }

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([ 'success' => $update_result ]));
  }


  public function update_texts() {
    $this->load->model('participant_model');
    $this->load->model('user_model');
    $this->load->model('program_model');
    $this->load->model('program_pledge_settings_model');
    $this->load->model('students_parent_model');
    $this->output->enable_profiler(false);
    $this->load->helper('user_activities_helper');

    $response               = true;
    $parent_user_id         = (int)$this->session->userdata('user_id');
    $texts_count            = (int)$this->input->post('texts_count');
    $participant_user_id    = (int)$this->input->post('student_id');
    $participants_parent_id = (int)$this->students_parent_model->get_parent_by_student($participant_user_id);

    // Ensure that the user is this participant's parent
    if ($parent_user_id === $participants_parent_id) {
      $program_id                      = $this->program_model->get_program_id_by_user_id($participant_user_id);
      $participant_profile             = $this->user_model->get_user_profile($participant_user_id);
      $program_family_pledging_enabled = $this->program_pledge_settings_model->get_pledge_settings($program_id)->family_pledging_enabled;
      $is_family_pledging_enabled      = is_family_pledging_enabled($participant_profile, $program_family_pledging_enabled);

      if ($is_family_pledging_enabled) {
        $participants = $this->user_model->get_family_pledging_participants_by_participant_id($participant_user_id);
      } else {
        $participants = [$this->user_model->get_participant_by('user_id', $participant_user_id)];
      }

      foreach ($participants as $participant) {
        $update_result        = $this->participant_model->update_by('user_id', $participant->user_id, ['num_of_texts_shared' => $texts_count]);
        $reward_update_result = check_and_update_texting_activity($participant->user_id, $texts_count);
        if ($update_result === false || $reward_update_result === false) {
            $response = false;
        }
      }
    } else {
      $response = false;
    }

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([ 'success' => $response ]));
  }


}
