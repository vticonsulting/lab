<?php if (! defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Programs extends MY_Controller
{


  public function __construct() {
    parent::__construct();
    $this->load->model('program_model');
    $this->load->library('form_validation');
    $this->load->library('pagination');
    $this->config->load('pagination'); //So we can keep a uniform look across sections
    $this->load->helper('admin');
  }


  /**
   * Common setup for datatable.
   * @return array
   */
  protected function _program_users_datatable($program_id, $type = null) {
    $this->load->model('classroom_model');

    $program = $this->program_model->get($program_id);
    return [
      'active'            => 'programs',
      'program'           => !empty($program) ? $program : null,
      'footer_js'         => 'admin/program_users.js',
      'footer_init'       => 'program_datatable.admin_program_users_init',
      'footer_init_param' => [
        'program_id'    => $program_id,
        'data_type'          => $type,
        'base_url'          => '/admin/programs/',
        'column_filter' => [
          'sPlaceHolder' => 'head:after',
          'aoColumns'    => [
            null, // actions
            ['type' => 'text', 'iFilterLength' => 3], //first name
            ['type' => 'text', 'iFilterLength' => 3], //last name
            ['type' => 'select',
              'values' => array_values($this->classroom_model->get_filtered_grades_dropdown($program_id))],
            ['type' => 'select',
              'values' => array_values($this->classroom_model->get_filtered_classes_dropdown($program_id))],
            null, //access code
            null, //program group
          ]
        ]
      ]
    ];
  }


  public function show_dashboard($program_id) {
    $this->load->model('pledge_model');
    $this->load->model('grade_model');
    $this->load->model('classroom_model');
    $this->load->model('pledge_model');
    $this->load->model('user_tasks_model');
    $this->load->model('user_group_model');
    $this->load->model('user_task_list_model');
    $this->load->model('classroom_shirts_model');
    $this->load->model('corporate_matching_model');

    $this->load->config('user_tasks');

    $data['active']         = 'programs';
    $data['active_sub_tab'] = 'dashboard';
    $data['mobile']         = $this->is_mobile();

    $this->load->model('pledge_model');
    $this->load->model('program_model');
    $this->load->model('unit_model');

    $data['pledge_map_stats'] = $this->pledge_model->get_state_country_counts($program_id);
    $data['program']          = $this->program_model->basic_program($program_id);
    $data['unit']             = $this->unit_model->get($data['program']->unit_id);

    if ($this->ion_auth->in_group([$data['program']->team_id]) === false
    && !$this->ion_auth->is_org_admin()) {
      $this->session->set_flashdata(
        'admin_message',
        'Sorry, you are not authorized to access that page.'
      );
      redirect('/admin/programs/');
      return;
    }

    $data['user_tasks']               = $this->user_tasks_model->get_program_tasks($program_id);
    $data['labels_array_from_config'] = $this->config->item('labels');
    $data['program']->school_stats    = $this->program_model->get_program_school_stats($program_id);
    $data['pledge_stats']             = $this->pledge_model->pledge_stats_for_program($program_id);
    $data['meals_donated']            = $this->pledge_model->calc_donated_meals($program_id);
    $data['team_member']              = $this->ion_auth->in_group([User_group_model::ADMIN, User_group_model::SYSADMIN]);
    $data['days_to_fun_run']          = $this->program_model->funrun_days_left($program_id, true);
    $data['missing_laps']             = count($this->program_model->get_participants_no_units($program_id));
    $data['pledge_periods']           = $this->pledge_model->get_formatted_pledge_periods_by_program($program_id);
    $data['user_task_lists']          = $this->user_task_list_model->get_all();
    $data['shirt_submission_stats']   = $this->classroom_shirts_model->get_shirt_submission_stats($program_id);

    // Top Ten Participants - Pledge Period Dropdown
    $data['top_ten_stats']['pledge_periods']      = $this->pledge_model
      ->get_formatted_pledge_periods_by_program($program_id);
    $pledge_start_obj                             = $this->pledge_model
      ->get_pledge_dates_by_program($program_id);
    $data['top_ten_stats']['grades']              = $this->grade_model->get_program_grades($program_id);
    $data['top_ten_stats']['classes']             = $this->classroom_model->group_classes_by_grade($program_id);
    $data['top_ten_stats']['pledge_period_start'] = $pledge_start_obj->pledging_start;
    $data['top_ten_stats']['program_id']          = $program_id;
    $data['top_ten_stats']['participants']        = [];

    $this->program_model->attach_program_nav_bar_data_to($data);

    $data['admin_view'] = true;
    $data['ck_editor']  = true;

    $client_percent    = (empty($data['program']->client_percent)) ? 0 : $data['program']->client_percent;
    $paid_statuses     = [Pledge_Model::CONFIRMED_STATUS, Pledge_Model::PAID_STATUS, Pledge_model::PAID_PENDING_STATUS];
    $confirmed_pledges = $this->pledge_model->get_program_pledge_totals_by_status($data['program']->id, $paid_statuses);
    unset($confirmed_pledges['pledge_o_meter']);
    unset($confirmed_pledges['ppl_total']);

    $data['total_payments'] = array_sum($confirmed_pledges) * ($client_percent / 100) * .95;

    $data['corp_match_eligible_summary'] = $this->corporate_matching_model->get_eligible_summary_by_program($data['program']->id);
    $data['corp_match_received_summary'] = $this->corporate_matching_model->get_received_summary_by_program($data['program']->id);
    $data['can_view_corporate_matching'] = $this->corporate_matching_model->can_view_corporate_matching_on_dashboard($data['program']);

    $data['footer_js'] = ['state_map.js', 'state_map_admin.js',
      'highcharts/highcharts.js','highcharts/highcharts-more.js',
      'highcharts/modules/exporting.js',
      'highcharts/modules/no-data-to-display.js','pledged_participants.js',
      'daily_pledge_graph.js','pledged_participants.js','sponsor_pie_chart.js',
      'participants_registered_gauge.js','total_pledges_payments_graph.js',
      'top_ten_participants.js'];

    $data['view'] = 'dashboard/partials/tab_content/dashboard';
    $this->load->view('admin/programs/template', $data);
  }


  /**
   * Student participants table page.
   *
   * @param int $program_id
   */
  public function show_students($program_id, $pledge_status = null) {
    $data = $this->_program_users_datatable($program_id, 'students');
    if ($pledge_status) {
      $data['status_id'] = $pledge_status;
    } elseif ($this->session->userdata('student_pending_only')) {
      // allow for redirect to pending only from pledges->confirm_all()
      $this->load->model('pledge_model');
      $data['status_id'] = $this->pledge_model->status_id(Pledge_model::PENDING_STATUS);
    }

    $data['program_id'] = $program_id;

    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['active_sub_tab'] = 'students';
    $data['view']           = 'admin/programs/students';
    $this->load->view('admin/programs/template', $data);
  }


  public function show_teachers($program_id) {
    $data                      = $this->_program_users_datatable($program_id);
    $data['footer_init_param'] = [
      'program_id'    => $program_id,
      'column_filter' => [
        'sPlaceHolder' => 'head:after',
        'aoColumns'    => [
          null, // actions
          ['type' => 'text', 'iFilterLength' => 3], // last name
          ['type' => 'text'], //first name is 1 char so no filter length..
        ]
      ]
    ];

    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['active_sub_tab'] = 'teachers';
    $data['view']           = 'admin/programs/teachers';
    $this->load->view('admin/programs/template', $data);
  }


  public function show_sponsors($program_id) {
    $data = $this->_program_users_datatable($program_id);
    // Dropdowns not required in this table header
    $data['footer_init_param'] = [
      'program_id'    => $program_id,
      'ao_columns'    => 'sponsors',
      'column_filter' => [
        'sPlaceHolder' => 'head:after',
        'aoColumns'    => [
          null, // actions
          ['type' => 'text', 'iFilterLength' => 3], //first name
          ['type' => 'text', 'iFilterLength' => 3], //last name
        ]
      ]
    ];
    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['active_sub_tab'] = 'sponsors';
    $data['view']           = 'admin/programs/sponsors';
    $this->load->view('admin/programs/template', $data);
  }


  public function show_parents($program_id) {
    $data = $this->_program_users_datatable($program_id);
    // Dropdowns not required in this table header
    $data['footer_init_param'] = [
      'program_id'    => $program_id,
      'ao_columns'    => 'parents',
      'column_filter' => [
        'sPlaceHolder' => 'head:after',
        'aoColumns'    => [
          null, // actions
          ['type' => 'text', 'iFilterLength' => 3], //first name
          ['type' => 'text', 'iFilterLength' => 3], //last name
        ]
      ]
    ];
    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['active_sub_tab'] = 'parents';
    $data['view']           = 'admin/programs/parents';
    $this->load->view('admin/programs/template', $data);
  }


  public function show_payments($program_id) {
    $data = $this->_program_users_datatable($program_id);
    // Dropdowns not required in this table header
    $data['footer_init_param'] = [
      'program_id'    => $program_id,
      'column_filter' => [
        'sPlaceHolder' => 'head:after',
        'aoColumns'    => [
          null, // actions
          ['type' => 'date'], //entry date
          ['type' => 'text', 'iFilterLength' => 3], //first name
          ['type' => 'text', 'iFilterLength' => 3], //last name
        ]
      ]
    ];
    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['active_sub_tab'] = 'payments';
    $data['view']           = 'admin/programs/payments';
    $this->load->view('admin/programs/template', $data);
  }


  private function guard_corporate_match($program_id) {
    if (show_corporate_matching($program_id)) {
      return true;
    }

    redirect('/admin');
  }


  public function corporate_match($program_id) {
    $this->load->model('access_token_model');
    $this->load->library('session');
    $this->guard_corporate_match($program_id);
    $user_id = $this->session->userdata('user_id');
    $token   = $this->access_token_model->create_titan_access_token($user_id, '/program/' . $program_id . '/corporate-matching');
    $data    = $this->_program_users_datatable($program_id);

    $data['cmg_table_url']  = get_titan_admin_url('/tk-login/' . $user_id . '/' . $token);
    $data['active_sub_tab'] = 'corporate_match';
    $data['view']           = 'admin/programs/corporate_match';
    $data['program_id']     = $program_id;
    $this->load->view('admin/programs/template', $data);
  }


  public function show_help_requests($program_id) {
    $data['active']         = 'programs';
    $data['active_sub_tab'] = 'help_requests';

    $this->load->model('zendesk_model');
    $data['tickets'] = $this->zendesk_model->get_tickets($program_id);

    if ($data['tickets'] === false) {
      $data['message'] = 'There was a problem contacting the help center.  Please try again.';
    } else {
      usort($data['tickets'], ['Programs', "_help_requests_sort"]);
    }

    $data['program'] = $this->program_model->get($program_id);
    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['view'] = 'admin/programs/help_requests';
    $this->load->view('admin/programs/template', $data);
  }


  private function _help_requests_sort($a, $b) {
    return strtotime($b["created_date"]) - strtotime($a["created_date"]);
  }


  /**
   * Send help request email to requester
   */
  public function send_email($ticket_id, $program_id) {
    $data['active']         = 'programs';
    $data['active_sub_tab'] = 'help_requests';

    $this->load->model('zendesk_model');
    $ticket         = $this->zendesk_model->get_ticket($ticket_id);
    $data['ticket'] = $ticket;

    if ($ticket === false) {
      $data['error_message'] = 'There was a problem contacting the help center.  Please try again.';
    }

    $data['program'] = (object)["id" => $program_id];

    if (!$this->ion_auth->is_admin()) {
      //Log unauthorized attempt to access page
      show_error(
        'You are not authorized to access this page. Return to <a href="'
        . base_url() . '">login page</a>.', 401
      );
    }

    $admin_user = $this->ion_auth->user()->row();

    if ($_POST) {
      $this->form_validation->set_rules('from', 'From', 'trim|required|valid_emails');
      $this->form_validation->set_rules('to', 'To', 'trim|required|valid_emails');
      $this->form_validation->set_rules('subject', 'Subject', 'required');
      $this->form_validation->set_rules('body', 'Body', 'required');
      $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

      if ($this->form_validation->run()) {
        $this->load->library('email');

        $config             = [];
        $config['mailtype'] = 'html';
        $email              = $this->email->initialize($config);

        $email_body = nl2br($this->input->post('body'));

        $email_result        = false;
        $email_address_valid = true;
        if (!empty($data['ticket']['requester_email']) &&
          filter_var($data['ticket']['requester_email'], FILTER_VALIDATE_EMAIL)) {
            $email->from(
              $this->input->post('from'),
              'Boosterthon Support - ' . $admin_user->first_name.' '.$admin_user->last_name
            )
              ->to($data['ticket']['requester_email'])
              ->subject($this->input->post('subject'))
              ->message($email_body);

            $this->load->model('rabbitmq_model');
            $email_result = $this->rabbitmq_model->queue_email($email);
        } else {
          $email_address_valid = false;
          $this->session->set_flashdata(
            'error', '<strong>Uh oh!</strong> ' .
              $ticket['requester'] . "'s  email address of " .
              $data['ticket']['requester_email'] . ' is invalid.  Message ' .
            'cannot be sent until this is corrected.'
          );
          log_message('error', 'Help Request Send Email INVALID EMAIL ADDRESS');
        }

        if (!$email_result) {
          if ($email_address_valid) {
            $this->session->set_flashdata(
              'error', '<strong>Uh oh!</strong> ' .
              'There was a problem sending this message.  Please try again.'
            );
          }

          log_message(
            'error', 'Error: Help Request Send Email: ' .
            $this->email->print_debugger()
          );
        } else {
          $this->session->set_flashdata(
            'success', '<strong>Success!</strong>' .
            ' Help request email to ' . $ticket['requester'] . ' (on behalf' .
            ' of Participant: '.$ticket['student_name'].') sent successfully!'
          );
        }

        redirect('/admin/programs/help_requests/' . $program_id);
      }
    }

    $default_body               = "Re: Help Request from " . $ticket['requester'] .
      ' (on behalf of Participant: ' . $ticket['student_name'] . ') ' . "\n\n" .
      "Dear " . $ticket['requester'] . ",\n\n\n\n" .
      "Have a great day!\n" . 'Boosterthon Support - '. $admin_user->first_name .
      ' '.$admin_user->last_name . "\n\n_______________________\n" .
      'Requester: ' . $ticket['requester'] . "\n" .
      'Requester Type: ' . $ticket['requester_type'] . "\n" .
      'Participant: ' . $ticket['student_name'] . "\n" .
      'Comment: ' . $ticket['comment'];
    $data['default_email_type'] = 'HRE';
    $data['default_to']         = ($this->input->post('to') === null) ? $ticket['requester_email'] : $this->input->post('to') === null;
    $data['default_from']       = ($this->input->post('from') === null) ? $admin_user->email : $this->input->post('from') === null;
    $data['default_subject']    = ($this->input->post('subject') === null) ? 'Help Request Response' : $this->input->post('subject') === null;
    $data['default_body']       = ($this->input->post('body') === null) ? $default_body : $this->input->post('body') === null;
    $data['email_types']        = ['HRE' => 'Help Request Email'];
    $data['view']               = 'admin/programs/help_request_send_email';
    $this->load->view('admin/template', $data);
  }


  /**
   * Updates hwlp requests ticket information
   *
   * @param int $program_id
   * @param int $ticket_id
   * @param string $update_field_type use one of the zendesk_model constants
   * @param mixed $update_value
   * @return mixed  false if error, or array with updated ticket field informationt
   */
  public function update_help_request($program_id, $ticket_id, $update_field_type, $update_value) {
    $this->load->model('zendesk_model');
    $ticket = $this->zendesk_model->update_ticket(
      $ticket_id,
      $update_field_type,
      $update_value,
      $program_id
    );
    if ($ticket === false) {
      $this->session->set_flashdata(
        'error', 'There was a problem ' .
        'contacting the help center.  Please try again.'
      );
    } else {
      if ($update_field_type === Zendesk_model::TKT_FIELD_TYPE_STATUS) {
        $field_type_msg = 'status';
      } elseif ($update_field_type === Zendesk_model::TKT_FIELD_TYPE_GROUP_ID) {
        $field_type_msg = 'assignee';
        if ($update_value == Zendesk_model::GROUP_FIELD) {
          $ticket = $this->zendesk_model->update_ticket(
            $ticket_id,
            Zendesk_model::TKT_FIELD_TYPE_ASSIGNEE_ID,
            Zendesk_model::ASSIGNEE_SUPPORT,
            $program_id
          );
        } elseif ($update_value == Zendesk_model::GROUP_HO) {
          $ticket = $this->zendesk_model->update_ticket(
            $ticket_id,
            Zendesk_model::TKT_FIELD_TYPE_ASSIGNEE_ID,
            0,
            $program_id
          );
        }

        if ($ticket === false) {
          $this->session->set_flashdata(
            'error', 'There was a problem ' .
            'contacting the help center.  Please try again.'
          );
        }
      }

      if ($ticket !== false) {
        $this->session->set_flashdata(
          'success', '<strong>Success!</strong> ' .
          'Help Request ' . $field_type_msg . ' successfuly udated!'
        );
      }
    }

    redirect('/admin/programs/help_requests/' . $program_id);
  }


  public function edit_merchant($program_id) {

    $this->form_validation->set_error_delimiters('', '');
    $this->form_validation->set_rules('sponsor_convenience_fee', 'Sponsor Convenience Fee', 'callback_fee_validation');
    $this->form_validation->set_rules('school_processing_fee', 'School Processing Fee', 'callback_school_processing_fee_validation');
    $this->form_validation->set_rules('promote_pay_online', 'Promote Pay Online Now', 'callback_online_payment_check');
    $this->form_validation->set_rules('online_payment_required', 'Online Payments Required', 'callback_online_payment_required_check');

    if ($this->form_validation->run()) {
      $merchant                           = $this->input->post();
      $merchant['hold_online_payments']   = $this->input->post('hold_online_payments');
      $merchant['online_payment_enabled'] = ($merchant['hold_online_payments']) ? null : $this->input->post('online_payment_enabled');

      if ($merchant['hold_online_payments'] && $this->input->post('online_payment_enabled')) {
        $this->session->set_flashdata('online_payments_error', 'Online payments were NOT enabled because the \'Hold Online Payments\' option is enabled.');
      }

      $merchant['online_payment_required']          = $this->input->post('online_payment_required');
      $merchant['promote_pay_online']               = $merchant['online_payment_enabled'] ? $this->input->post('promote_pay_online') : false;
      $merchant['filter_merchant_report_by_school'] = $this->input->post('filter_merchant_report_by_school');
      $merchant['sponsor_convenience_fee']          = $this->input->post('sponsor_convenience_fee');
      $merchant['optional_sponsor_fee']             = $this->input->post('optional_sponsor_fee');
      unset($merchant['submit']);

      $this->program_model->update($program_id, $merchant);
      $this->session->set_flashdata('merchant_message', 'Merchant Updated');

      redirect('admin/programs/edit/' . $program_id . '#merchant');
    } else {
      $this->session->set_flashdata('merchant_error_message', validation_errors());
      redirect('/admin/programs/edit/' . $program_id . '#merchant');
    }

    $data['program']        = $this->program_model->get($program_id);
    $data['active_sub_tab'] = 'merchant';
    $data['view']           = 'admin/programs/merchant';
    $this->load->view('admin/programs/template_edit_program', $data);
  }


  public function merchant_transaction_log($program_id) {
    $this->load->model('cc_transaction_model');

    $data['data_table']['id']   = 'merchant-transactions';
    $data['data_table']['url']  = "/programs/ajax_get_transactions/{$program_id}";
    $data['data_table']['sort'] = "[1, 'asc']";

    $data['program']        = $this->program_model->get($program_id);
    $data['active_sub_tab'] = 'merchant';
    $data['view']           = 'admin/programs/merchant_transaction_log';
    $this->load->view('admin/programs/template_nonloggedin', $data);
  }


  public function ajax_get_transactions($program_id) {
    $this->load->model('cc_transaction_model');
    $data['json'] = $this->cc_transaction_model->ajax_get_transactions($program_id);
    $this->load->view('admin/ajax', $data);
  }


  public function ajax_get_transaction_actions() {
    $transaction_id = $this->input->post('transaction_id');

    $this->load->model('cc_transaction_model');
    $transaction_action = $this->cc_transaction_model
      ->get_transaction_actions_markup($transaction_id);

    $this->output->set_content_type('application/json')
      ->set_output(json_encode($transaction_action));
  }


  /**
   * Datatable ajax function to return a program's student participants
   */
  public function ajax_get_students($program_id) {
    $status_id = $this->input->post('status_id');
    if (empty($status_id)) {
      $status_id = null;
    }

    $data['json'] = $this->program_model->ajax_get_participants($program_id, $status_id);
    $this->load->view('admin/ajax', $data);
  }


  /**
   *
   * @param type $program_id
   */
  public function ajax_get_students_collect($program_id) {
    $data['json'] = $this->program_model->ajax_get_students_collect($program_id);
    $this->load->view('admin/ajax', $data);
  }


  public function ajax_get_teachers($program_id) {
    $data['json'] = $this->program_model->ajax_get_teachers($program_id);
    $this->load->view('admin/ajax', $data);
  }


  public function ajax_get_sponsors($program_id) {
    $data['json'] = $this->program_model->ajax_get_sponsors($program_id);
    $this->load->view('admin/ajax', $data);
  }


  public function ajax_get_parents($program_id) {
    $data['json'] = $this->program_model->ajax_get_parents($program_id);
    $this->load->view('admin/ajax', $data);
  }


  public function ajax_get_payments($program_id) {
    $result       = $this->program_model->ajax_get_payments($program_id);
    $data['json'] = $result;
    $this->load->view('admin/ajax', $data);
  }


  /**
   * loads up an ajax table for program's pledges
   * @param type $program_id
   * @param type $offset
   */
  public function show_single_pledges($program_id) {
    $data['active']         = 'programs';
    $data['active_sub_tab'] = 'pledges';

    $this->load->model('pledge_model');

    //CREATE ARRAY OF STATUSES YOU DON'T WANT TO SHOW
    $status_not_in_array = [Pledge_model::ENTERED_STATUS,
        Pledge_model::CANCELLED_STATUS,
        Pledge_model::ABANDONED_STATUS];
    //GET STATUSES FOR DROPDOWN
    $data['pledge_statuses'] = $this->pledge_model->get_statuses_dropdown(false, $status_not_in_array);

    $data['program']                     = $this->program_model->get($program_id);
    $data['data_table']['id']            = 'pledges-table';
    $data['data_table']['sort']          = "[1, 'asc']";
    $data['data_table']['url']           = "ajax_get_pledges/{$program_id}/1";
    $data['data_table']['column_filter'] = json_encode(
      ['sPlaceHolder' => "head:after",
      'aoColumns' => [null, // select
      null, // actions
      ['type' => "text" , 'iFilterLength' => 3], //participant
      null, //classroom name
      null, //pledge amount
      null, //pledge type
      null, //estimated
      null, //entered date
      ['type' => "select", 'values' => array_values($data['pledge_statuses'])], // pledge statuses
      null,
      null,null,null]]
    );
    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['view'] = 'admin/programs/pledges';
    $this->load->view('admin/programs/template', $data);
  }


  public function ajax_get_pledges($program_id, $filtered = 0) {
    if (strtolower($this->input->post('sSearch_7')) == 'deleted') {
      $data['json'] = $this->program_model->ajax_get_pledges_deleted($program_id);
    } else {
      if ($filtered == 1) {
        $this->load->model('pledge_model');
        $status_not_in_array = [Pledge_model::ENTERED_STATUS,
          Pledge_model::CANCELLED_STATUS,
          Pledge_model::ABANDONED_STATUS];
      } else {
        $status_not_in_array = null;
      }

      $data['json'] = $this->program_model->ajax_get_pledges($program_id, $status_not_in_array);
    }

    $this->load->view('admin/ajax', $data);
  }


  public function show_single_classes($program_id) {
    $this->load->library('booster_api');
    $data['active']             = 'programs';
    $data['active_sub_tab']     = 'classes';
    $data['program']            = $this->program_model->get($program_id);
    $data['unit_data']          = $this->booster_api->get_unit_data($data['program']->unit_id)->data;
    $data['data_table']['id']   = 'classes-table';
    $data['data_table']['sort'] = "[0, 'asc']";
    $data['data_table']['url']  = "ajax_get_classes/{$program_id}";
    $this->program_model->attach_program_nav_bar_data_to($data);

    $unit_id           = $this->program_model->get_unit_id_from_program_id($program_id);
    $data['unit_data'] = $this->booster_api->get_unit_data($unit_id)->data;
    $data['view']      = 'admin/programs/classes';

    $this->load->view('admin/programs/template', $data);
  }


  public function shirts($collection_ref_key) {
    $this->load->model('classroom_shirts_model');

    $data['program']        = $this->program_model->get_by(['collection_refer_key' => $collection_ref_key]);
    $data['can_edit_sizes'] = strtotime($data['program']->pep_rally) > strtotime("+ 35 Days");

    if ($this->input->post()) {
      //The faculty shirt form POSTs program_id. Class shirt forms POST classroom_id.
      if ($this->input->post('program_id')) {
        $result = $this->store_faculty_shirt_sizes($this->input->post());
        if ($result) {
            $data['success_message'] = 'Success! Faculty T-Shirt Size has been submitted!';
        }
      } else {
        $result = $this->store_classroom_shirt_sizes($this->input->post('classroom_sizes'));
        if ($result) {
            $data['success_message'] = 'Success! T-Shirt Sizes have been updated!';
        }
      }
    }

    $data['classroom_sizes'] = $this->classroom_shirts_model->get_by_program($data['program']->id);
    $this->load->view('admin/classrooms/shirts', $data);
  }


  public function store_classroom_shirt_sizes($classroom_sizes) {

    $this->load->model('classroom_shirts_model');
    if (count($classroom_sizes)) {
        $this->classroom_shirts_model->upsert_many($classroom_sizes);
        return true;
    }

    return false;
  }


  public function store_faculty_shirt_sizes($form_data) {
    $this->load->model('classroom_shirts_model');

    $rules = [
      [
        'field' => 'first_name',
        'label' => 'First Name',
        'rules' => 'required|alpha',
      ],
      [
        'field' => 'last_name',
        'label' => 'Last Name',
        'rules' => 'required|alpha',
      ],
      [
        'field' => 'shirt_size',
        'label' => 'Shirt Size',
        'rules' => 'required',
      ],
      [
        'field' => 'program_id',
        'label' => 'Program Id',
        'rules' => 'required',
      ],
    ];
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() == true) {
      $this->classroom_shirts_model->insert_faculty_shirt_size($form_data);
      return true;
    }

    return false;
  }


  /**
   * CSV report for shipping addresses
   *
   * @param int $program_id
   */
  public function shipping_addresses_csv_report($program_id) {
    $this->load->model('prize_bound_student_model');
    $class_prizes = $this->prize_bound_student_model->get_class_shipping_address_csv($program_id);
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=shipping-address-report.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    ob_end_clean();
    $out = fopen('php://output', 'w');

    fputcsv(
      $out,
      [
        'ProgramId',
        'ProgramName',
        'AccountId',
        'Team',
        'ParentId',
        'ParentFirst',
        'ParentLast',
        'Email',
        'ParticipantId',
        'StudentFirst',
        'AddressLine1',
        'AddressLine2',
        'City',
        'State',
        'Zip',
        'InventoryCode'
      ]
    );

    foreach ($class_prizes as $class_prize) {
      fputcsv(
        $out,
        $class_prize
      );
    }

      fclose($out);
  }


  /**
   * CSV report of T-Shirt Sizes
   *
   * @param int $program_id
   */
  public function shirt_size_csv_report($program_id) {
    $this->load->model('classroom_shirts_model');
    $this->load->model('program_model');

    $program       = $this->program_model->get_by(['id' => $program_id]);
    $report_pieces = $this->classroom_shirts_model->get_tshirt_report_data($program);

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=tshirt-size-report.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    ob_end_clean();
    $out = fopen('php://output', 'w');

    //classroom tshirt size rows
    foreach ($report_pieces as $report_piece) {
      fputcsv(
        $out,
        $report_piece
      );
    }

    fclose($out);
  }


  /**
   * Collection dashboard (non-logged-in). 49662037
   *
   * @param string $key  Program collection page referral key
   */
  public function collect($key) {
    $this->load->model('school_model');
    $this->load->model('manual_payment_model');

    $program = $this->program_model->get_by('collection_refer_key', $key);
    if (!$program) {
        return show_404();
    }

    $data = $this->_program_users_datatable($program->id, 'students_collect');

    unset($data['footer_init_param']['column_filter']['aoColumns'][5]);
    unset($data['footer_init_param']['column_filter']['aoColumns'][6]);

    $data['active']         = 'programs';
    $data['active_sub_tab'] = 'collections';
    $data['program']        = $this->program_model->get($program->id);
    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['school'] = $this->school_model->school_for_program($program->id);

    $collections               = $this->program_model->get_collect_pledge_totals_per_class($program->id);
    $data['collections']       = $collections['data'];
    $data['pledged_total']     = $collections['pledged_total'];
    $data['collected_total']   = $collections['collected_total'];
    $data['scheduled_total']   = $collections['scheduled_total'];
    $data['outstanding_total'] = $collections['outstanding_total'];
    $data['cc_total']          = $collections['cc_total'];
    $data['cash_total']        = $collections['cash_total'];
    $data['check_total']       = $collections['check_total'];
    $data['cmg_total']         = $collections['cmg_total'];
    $data['today_total']       = $collections['today_total'];
    $data['key']               = $key;
    $data['pledges_payments']  = $this->program_model->get_program_pledge_and_payment_totals_from_collections($collections);

    $last_collection = $this->manual_payment_model->get_latest_collection_by_program($program->id);
    if ($last_collection) {
      $day                     = date('m/d/Y', strtotime($last_collection->created_at));
      $time                    = date('g:i A', strtotime($last_collection->created_at));
      $timezone                = $this->school_model->get_school_short_time_zone_by_program_id($program->id);
      $data['last_collection'] = "Last collection entered on $day at $time ($timezone)";
    }

    $data['view']      = 'admin/programs/collect';
    $data['footer_js'] = ['highcharts/highcharts.js',
      'highcharts/highcharts-more.js',
      'highcharts/modules/exporting.js',
      'highcharts/modules/no-data-to-display.js',
      'total_pledges_payments_graph.js',
      'admin/program_users.js',
      'admin/collect.js'];
    $this->load->view('admin/programs/template_nonloggedin', $data);
  }


  /**
   * Collection dashboard (non-logged-in). 49662037
   *
   * @param string $key  Program collection page referral key
   * @param int $classroom_id
   */
  public function collect_students($key, $classroom_id) {
    $this->load->model('school_model');
    $this->load->model('classroom_model');
    $this->load->model('user_group_model');

    $program = $this->program_model->get_by('collection_refer_key', $key);
    if (!$program) {
        return show_404();
    }

    $data['active']          = 'programs';
    $data['class_list_link'] = site_url('/programs/collect/' . $key);
    $data['program']         = $this->program_model->get($program->id);
    $this->program_model->attach_program_nav_bar_data_to($data);
    $classrooms     = $this->classroom_model->get_full_classroom($classroom_id);
    $data['class']  = $classrooms[0];
    $data['school'] = $this->school_model->school_for_program($program->id);

    $collections                = $this->program_model->
      get_collect_pledge_totals_for_class($program->id, $classroom_id, $key);
    $data['organization_admin'] = $this->ion_auth
      ->in_group([User_group_model::ORG_ADMIN, User_group_model::ADMIN]);
    $data['collections']        = $collections['data'];
    $data['pledged_total']      = $collections['pledged_total'];
    $data['collected_total']    = $collections['collected_total'];
    $data['scheduled_total']    = $collections['scheduled_total'];
    $data['outstanding_total']  = $collections['outstanding_total'];
    $data['cc_total']           = $collections['cc_total'];
    $data['cash_total']         = $collections['cash_total'];
    $data['check_total']        = $collections['check_total'];
    $data['cmg_total']          = $collections['cmg_total'];
    $data['today_total']        = $collections['today_total'];

    $data['pledges_payments'] = $this->program_model->
            get_program_pledge_and_payment_totals_from_collections($collections);

    $data['key']       = $key;
    $data['view']      = 'admin/programs/collect_students';
    $data['footer_js'] = [
      'highcharts/highcharts.js',
      'highcharts/highcharts-more.js',
      'highcharts/modules/exporting.js',
      'highcharts/modules/no-data-to-display.js',
      'total_pledges_payments_graph.js'];
    $this->load->view('admin/programs/template_nonloggedin', $data);
  }


  public function ajax_get_classrooms($program_id) {
    $data['json'] = $this->program_model->ajax_get_classrooms($program_id);
    $this->load->view('admin/ajax', $data);
  }


  /**
   * Get data for Program Collection page.
   * @param string $key  Collection refer key
   */
  public function ajax_get_class_collect($key) {
    $data['json'] = $this->program_model->ajax_get_class_collect($key);
    $this->load->view('admin/ajax', $data);
  }


  /**
   * Get data for Program Collection students page.
   * @param string $key  Collection refer key
   * @param int $classroom_id
   */
  public function ajax_get_class_collect_students($key, $classroom_id) {
    $data['json'] = $this->program_model->ajax_get_class_collect_students($key, $classroom_id);
    $this->load->view('admin/ajax', $data);
  }


  public function show_assigned_prizes($program) {
    $this->load->model('group_model');
    $groups = $this->group_model->for_dropdown($program);
    if (count($groups) >= 1) {
      $status = 'giveaway';
      $this->assigned_group_prizes($groups, $program, $status);
    } else {
      $this->session->set_flashdata('message', 'The program you selected to manage prizes for has no groups.');
      redirect(site_url('/admin/prizes/manage/program/' . $program));
    }
  }


  public function assigned_group_prizes($groups, $program_id, $status) {
    $groups          = is_array($groups) ? $groups : [$groups];
    $data['program'] = $this->program_model->full_program($program_id, true);

    // Determine if viewing bound for group or program
    if (empty($program_id)) {
      $single_group     = array_keys($groups);
      $data['group_id'] = $single_group[0];
      $data['header']   = 'Prizes Bound to Group: ' . $groups[0];
    } else {
      $data['program_id'] = $program_id;
      $data['header']     = 'Prizes Bound to Program: ' . $data['program']->name;
    }

    $data['groups']          = $groups;
    $data['status_selected'] = $status;
    // Update status is giveaway on delivery page, and vice versa
    $data['update_status'] = $status == "giveaway" ? "delivered" : "giveaway";

    $this->load->model('prize_bound_student_model');
    $prize_statuses = $this->prize_bound_student_model->get_prize_statuses();

    $data['active']         = 'programs';
    $data['active_sub_tab'] = 'prizes';

    $data['data_table_prizes']['id']            = 'assigned-prizes';
    $data['data_table_prizes']['url']           = "ajax_get_prizes/{$program_id}";
    $data['data_table_prizes']['column_filter'] = json_encode(
      [
      'sPlaceHolder' => "head:after",
      'aoColumns' => [
        null, //update
        null, //display amount
        ['type' => "select", 'values' => array_values($prize_statuses)], //prize statuses
        ['type' => 'date-range'],
        null, //prize name
        null, //student name
        null, //class name
      ]
      ]
    );
    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['view'] = 'admin/prizes/assigned';
    $this->load->view('admin/programs/template', $data);
  }


  public function ajax_get_prizes($groups) {
    $this->output->enable_profiler(false);
    $this->load->model('prize_bound_student_model');

    $this->output->set_content_type('application/json')
      ->set_output($this->prize_bound_student_model->get_prizes_datatable($groups));
  }


  public function assigned_delivered_prizes($program_id) {
    $data['program']                 = $this->program_model->full_program($program_id, true);
    $data['prize_report_permission'] = $this->program_model->has_prize_report_permission($program_id);

    $data['active']         = 'programs';
    $data['active_sub_tab'] = 'prizes';

    $data['data_table']['id']            = 'delivered-prizes';
    $data['data_table']['sort']          = "[0, 'desc']";
    $data['data_table']['url']           = "/programs/ajax_get_prize_deliveries/{$program_id}";
    $data['data_table']['column_filter'] = json_encode(
      [
      'sPlaceHolder' => "head:after",
      'aoColumns' => [
        null,
        null,
        null
      ]
      ]
    );

    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['footer_js'] = ['admin/prizes_delivered.js'];

    $data['view'] = 'admin/prizes/delivered';
    $this->load->view('admin/programs/template', $data);
  }


  public function ajax_get_prize_deliveries($program) {
    $this->output->enable_profiler(false);

    $this->load->model('prize_bound_student_model');

    $this->output->set_content_type('application/json')
      ->set_output(
        $this->prize_bound_student_model
        ->get_prize_deliveries_datatable($program)
      );
  }


  //View all programs (admin)


  public function admin_show() {
    $this->load->model('user_model');

    if ($this->ion_auth->in_group(User_group_model::ORG_ADMIN)) {
      redirect('admin/schools');
    }

    $this->session->unset_userdata(['program']);

    $data['semesters']    = $this->program_model->semester_dropdown(true);
    $data['teams']        = $this->user_group_model->field_teams_dropdown(true);
    $data['team_members'] = $this->user_model->get_field_people(true);

    $data['data_table']['id']   = 'programs-table';
    $data['data_table']['sort'] = "[5, 'desc'], [3, 'asc']";
    $data['data_table']['url']  = "/programs/ajax_get_programs";

    $semester_filter = ['type' => "select", 'values' => array_values($data['semesters'])];
    if (defined('PROGRAM_SEMESTER')) {
      $semester_filter['selected'] = PROGRAM_SEMESTER;
    }

    $data['data_table']['column_filter'] = json_encode(
      ['sPlaceHolder' => "head:after",
      'aoColumns' => [
        null, //actions
        null, //help
        null, //to do
        ['type' => "text" , 'iFilterLength' => 3], // school name
        null, // cc status
        $semester_filter,// semesters
        ['type' => "select", 'values' => array_values($data['teams'])], // teams
        ['type' => "select", 'values' => array_values($data['team_members'])], // field people
        null, //pep rally
        null, //fun run
      ]
      ]
    );

    $data['active'] = 'programs';
    $data['view']   = 'admin/programs/index';

    $this->load->view('admin/template', $data);
  }


  public function ajax_get_programs () {
    $this->load->model('user_group_model');
    $current_teams = $this->user_group_model->get_current_user_teams();
    $data['json']  = $this->program_model->ajax_get_programs($current_teams);
    $this->load->view('admin/ajax', $data);
  }


  public function upload_classes($program_id) {
    $this->load->model('group_model');
    $data['groups_dropdown'] = $this->group_model->for_dropdown($program_id, true);
    if (count($data['groups_dropdown']) <= 1) {
        $data['error_message'] = 'This Program does not have any groups.';
    }

    $data['program']    = $this->program_model->full_program($program_id, false);
    $data['program_id'] = $program_id;
    $data['view']       = 'admin/programs/upload_classes';
    $data['footer_js']  = ['spectrum.js','admin/edit_program.js', 'admin/microsite.js', 'dropzone.js', 'admin/upload_classes.js'];

    $this->program_model->attach_program_nav_bar_data_to($data);
    $this->load->view('admin/programs/template', $data);

  }


  public function upload_class_file() {
    $this->load->config('aws', true);
    $this->load->library('upload');
    $this->load->library('CSV');
    $s3_class_list_loc = $this->config->item('s3_class_lists', 'aws');
    $this->upload->set_allowed_types('csv');

    $programId = !empty($_POST['program_id']) ? $_POST['program_id'] : null;
    if ($this->upload->do_upload_s3('userfile', $s3_class_list_loc)) {
        $fileInfo                      = $this->upload->data();
        $class_endpoint                = s3_url($s3_class_list_loc . $this->upload->data('file_name'));
        $importData                    = $this->csv->process_csv($class_endpoint);
        $data['fileInfo']['full_path'] = $class_endpoint;
        $data['importData']            = $importData;
        $data['status']                = 'success';
    } else {
       $data['status'] = 'failed';
    }

    echo json_encode($data);
  }


  public function preview_class_import($program_id) {
    $this->load->config('aws', true);
    $this->load->library('CSV');
    $s3_class_list_loc = $this->config->item('s3_class_lists', 'aws');

    $program_id  = !empty($_POST['program_id']) ? $_POST['program_id'] : null;
    $groupId     = !empty($_POST['group_id']) ? $_POST['group_id'] : null;
    $filePath    = !empty($_POST['full_path']) ? $_POST['full_path'] : null;
    $pledgeMeter = !empty($_POST['pledge_meter']) ? $_POST['pledge_meter'] : null;
    //support for legacy browsers
    if (empty($filePath)) {
      $this->load->library('upload');
      $this->upload->set_allowed_types('csv');
      if ($this->upload->do_upload_s3('file', $s3_class_list_loc)) {
        $filePath = s3_url($s3_class_list_loc . $this->upload->data('file_name'));
      }
    }

    $data = [
      'program_id' => $program_id,
      'group_id' => $groupId,
      'pledge_meter' => $pledgeMeter,
      'full_path' => $filePath
    ];

    $importData = !empty($filePath) ? $this->csv->process_csv($filePath) : [];
    foreach ($importData as $key => $classData) {
      if ((empty($classData[0]) || trim($classData[0]) == '')
      && (empty($classData[1]) || trim($classData[1]) == '')
      && (empty($classData[2]) || trim($classData[2]) == '')) {
        unset($importData[$key]);
      }
    }

    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['importData'] = $importData;
    $data['program']    = $this->program_model->full_program($program_id, false);
    $data['view']       = 'admin/programs/preview_class_import';
    $data['footer_js']  = ['spectrum.js','admin/microsite.js', 'admin/preview_class_import.js'];
    $this->load->view('admin/programs/template', $data);
  }


  public function process_class_file() {
    $this->load->library('CSV');
    $this->load->model('classroom_model');
    $programId   = !empty($_POST['program_id']) ? $_POST['program_id'] : null;
    $groupId     = !empty($_POST['group_id']) ? $_POST['group_id'] : null;
    $filePath    = !empty($_POST['full_path']) ? $_POST['full_path'] : null;
    $pledgeMeter = !empty($_POST['pledge_meter']) ? $_POST['pledge_meter'] : null;

    $columns   = [];
    $columns[] = !empty($_POST['column1']) ? $_POST['column1'] : null;
    $columns[] = !empty($_POST['column2']) ? $_POST['column2'] : null;
    $columns[] = !empty($_POST['column3']) ? $_POST['column3'] : null;
    if (array_search('name-last-first', array_values($columns)) !== false) {
      $nameKey = 'name-last-first';
    } elseif (array_search('name-full', array_values($columns)) !== false) {
      $nameKey = 'name-full';
    } else {
      $nameKey = 'name';
    }

    $nameIndx  = array_search($nameKey, array_values($columns));
    $gradeIndx = array_search('grade', array_values($columns));
    $partIndx  = array_search('number_of_participants', array_values($columns));

    if ($nameIndx === false || $gradeIndx === false) {
      $requiredFields = [];
      if (!$nameIndx) {
        array_push($requiredFields, 'Class Name');
      }

      if (!$gradeIndx) {
        array_push($requiredFields, 'Grade');
      }

        $errors = ['requiredFields' => $requiredFields];
        header('Content-Type: application/json');
        echo json_encode(['status' => 'failed', 'errors' => $errors]);
        exit;
    }

    $importData = !empty($filePath) ? $this->csv->process_csv($filePath) : [];
    array_shift($importData);

    foreach ($importData as $key => $classData) {
      if ((empty($classData[0]) || trim($classData[0]) == '')
      && (empty($classData[1]) || trim($classData[1]) == '')
      && (empty($classData[2]) || trim($classData[2]) == '')) {
        unset($importData[$key]);
      }
    }

    //parse classname
    foreach ($importData as $key => $item) {
      if ($nameKey == 'name-last-first') {
        $name      = !empty($item[$nameIndx]) ? $item[$nameIndx] : null;
        $names     = explode(',', $name);
        $className = !empty($names[0]) ? $names[0] : $item[$nameIndx];
      } elseif ($nameKey == 'name-full') {
         $name      = !empty($item[$nameIndx]) ? $item[$nameIndx] : null;
         $names     = explode(' ', $name);
         $className = !empty($names[1]) ? $names[1] : $item[$nameIndx];
      }

       $importData[$key][$nameIndx] = $className;
    }

    $existingClasses = $this->classroom_model->get_filtered_classes_dropdown($programId);
    $existingClasses = !empty($existingClasses) ? $existingClasses : [];
    $existingClasses = array_values($existingClasses);
    foreach ($existingClasses as &$className) {
      $className = strtolower($className);
    }

    //check if class exists
    $classAlreadyExists = [];
    $duplicateClasses   = [];
    $classes            = [];
    foreach ($importData as $item) {
      //match items based on headers
      $name                 = !empty($item[$nameIndx]) ? $item[$nameIndx] : null;
      $numberOfParticipants = !empty($item[$partIndx]) ? $item[$partIndx] : null;
      if (in_array(strtolower($name), $existingClasses)) {
        $classAlreadyExists[] = $name;
      } elseif (in_array(strtolower($name), $classes)) {
        $duplicateClasses[] = $name;
      }

      $classes[] = $name;
    }

    //validation
    if (!empty($classAlreadyExists) || !empty($duplicateClasses)) {
      $errors = ['classesAlreadyExist' => $classAlreadyExists, 'duplicateClasses' => $duplicateClasses];
      header('Content-Type: application/json');
      echo json_encode(['status' => 'failed', 'errors' => $errors]);
      exit;
    }

    //get class aliases;
    $classAliases = $this->classroom_model->get_grade_aliases();

    //get correct grade id based on alias or grade name passed
    $gradeId = function($grade) use ($classAliases) {
      if (is_numeric($grade) && $grade >= -2 && $grade <= 12) {
        return $grade;
      }

        $grade = strtolower($grade);
      foreach ($classAliases as $classAlias) {
        $classAliasName = strtolower($classAlias->name);
        if (!empty($classAlias) && $classAliasName == $grade) {
          return $classAlias->id;
        }
      }

      //return grade "Other" by default
      return -2;
    };

      //add classes
    foreach ($importData as $item) {
      //match items based on headers
      $name                 = !empty($item[$nameIndx]) ? $item[$nameIndx] : null;
      $gradeName            = !empty($item[$gradeIndx]) ? $item[$gradeIndx] : 0;
      $grade                = $gradeId($gradeName);
      $numberOfParticipants = !empty($item[$partIndx]) ? $item[$partIndx] : 0;
      $this->classroom_model->insert_class($groupId, $grade, $name, $numberOfParticipants, null, $pledgeMeter);
    }

      header('Content-Type: application/json');
      echo json_encode(['status' => 'success']);
  }


  /**
   * Edit program. Includes import student and teacher users, via csv file
   *
   * @param [type] $program_id
   * @param [type] $data
   * @return void
   */
  public function admin_edit($program_id, $data = null) {
    $this->load->model('pledge_model');
    $this->load->model('prize_bound_model');
    $this->load->model('user_group_model');
    $this->load->model('group_model');
    $this->load->model('school_merchant_model');
    $this->load->model('prize_list_model');
    $this->load->model('school_model');
    $this->load->model('user_activities_model');
    $this->load->model('custom_program_alerts_model');
    $this->load->model('microsite_model');
    $this->load->model('sponsor_logos_model');
    $this->load->config('aws', true);
    $this->load->config('upload', true);
    $this->lang->load('programs');
    $this->load->helper('htmlpurifier');

    if ($this->ion_auth->in_group(User_group_model::ORG_ADMIN)) {
      $this->session->set_flashdata(
        'admin_message',
        'Sorry, you are not authorized to access that page.'
      );
      redirect('/admin/programs/dashboard/' . $program_id);
    }

    $this->session->unset_userdata(['program']);
    $data['prizes_list_dropdown'] = $this->prize_list_model->for_dropdown();
    $data['program']              = $this->program_model->full_program($program_id, false);

    if ($this->ion_auth->in_group([$data['program']->team_id]) === false) {
      $this->session->set_flashdata(
        'admin_message',
        'Sorry, you are not authorized to access that page.'
      );
      redirect('/admin/programs/');
    }

    $data['overview_text_override'] = clean_override_text_for_view(
      $this->program_model->get_override_text_for_view(
        $data['program'],
        $this->lang->line('override_text_default')
      )
    );

    $data['thank_you_text_override'] = clean_override_text_for_view(
      $this->program_model->get_thank_you_override_text_for_view(
        $data['program']->microsite,
        $data['program']->event_name,
        $this->lang->line('thank_you_text_override_default')
      )
    );

    $data['can_edit_fun_run'] = $this->program_model->can_edit_fun_run();

    $data['custom_program_alert'] = $this->custom_program_alerts_model->get_by(['program_id' => $program_id]);

    $data['program_logo_image'] = (object)['name' => 'program_logo_image', 'entity_id' => $data['program']->microsite->id];
    if ($data['program']->microsite->school_image_name) {
      $program_logo_path                      = $this->config->item('s3_program_logos', 'aws');
      $data['program_logo_image']->s3_path    = $program_logo_path.$data['program']->microsite->school_image_name;
      $data['program_logo_image']->image_name = $data['program']->microsite->school_image_name;
    }

    $data['color_theme'] = $this->microsite_model->get_color_theme_hex_code($data['program']->microsite->color_theme_id);

    // Funds Raised Images
    $funds_raised_img_path      = $this->config->item('s3_microsites', 'aws');
    $data['funds_raised_img_1'] = (object)['name' => 'funds_raised_img_1',
      'entity_id' => $data['program']->microsite->id, 'data' => (object)['pic_num' => 1,
      'current_pic_id' => $data['program']->microsite->pic_1->id, 'always_new' => 1]];
    if ($data['program']->microsite->pic_1->image) {
      $data['funds_raised_img_1']->s3_path    = $funds_raised_img_path.$data['program']->microsite->pic_1->image;
      $data['funds_raised_img_1']->image_name = $data['program']->microsite->pic_1->image;
    }

    $data['funds_raised_img_2'] = (object)['name' => 'funds_raised_img_2',
      'entity_id' => $data['program']->microsite->id, 'data' => (object)['pic_num' => 2,
      'current_pic_id' => $data['program']->microsite->pic_2->id, 'always_new' => 1]];
    if ($data['program']->microsite->pic_2->image) {
      $data['funds_raised_img_2']->s3_path    = $funds_raised_img_path.$data['program']->microsite->pic_2->image;
      $data['funds_raised_img_2']->image_name = $data['program']->microsite->pic_2->image;
    }

    $data['funds_raised_img_3'] = (object)['name' => 'funds_raised_img_3',
      'entity_id' => $data['program']->microsite->id, 'data' => (object)['pic_num' => 3,
      'current_pic_id' => $data['program']->microsite->pic_3->id, 'always_new' => 1]];
    if ($data['program']->microsite->pic_3->image) {
      $data['funds_raised_img_3']->s3_path    = $funds_raised_img_path.$data['program']->microsite->pic_3->image;
      $data['funds_raised_img_3']->image_name = $data['program']->microsite->pic_3->image;
    }

    $data['allowedSponsorLogosCount'] = $this->config->item('sponsor_logos_allowed_count', 'upload');
    $data['sponsor_logos']            = $this->sponsor_logos_model->getProgramSponsorLogos($program_id);
    $data['sponsor_logos_active']     = $data['program']->display_sponsor_logos_on_home_dashboard || $data['program']->display_sponsor_logos_on_pledge_page;

    // Default Event Name to School + ' Fun Run'
    if (empty($data['program']->event_name)) {
      $school                      = $this->school_model->get($data['program']->school_id);
      $data['program']->event_name = $school->name . ' Fun Run';
    }

    $data['groups_dropdown']  = $this->group_model->for_dropdown($program_id, true);
    $data['populated_groups'] = $this->group_model->populated_groups_for_dropdown($program_id);
    $data['team_members']     = (empty($data['program']->team_id)) ? ['No team assigned to program'] : $this->user_group_model->get_team_members($data['program']->team_id, true);
    $data['group_levels']     = $this->group_model->get_levels();

    // If this program doesn't have any groups, gracefully fail
    if (count($data['groups_dropdown']) > 1) {
      $groups                  = $this->group_model->for_dropdown($program_id, true);
      $data['groups_dropdown'] = $groups;
      $group_ids               = array_keys($groups);
      unset($group_ids[0]);
      $group_ids = array_values($group_ids);

      $periods_dropdown         = $this->pledge_model->get_pledge_periods_parsed_range($group_ids, true);
      $periods_dropdown[0]      = 'Apply Pledge Period to Prize';
      $data['periods_dropdown'] = $periods_dropdown;
      $data['bound_prizes']     = $this->prize_bound_model->get_prizes_by_group($group_ids);
    } else {
      $data['error_message'] = 'This Program does not have any groups.';
    }

    // AWS environment
    $this->load->config('aws', true);
    $data['env'] = $this->config->item('bucket', 'aws');

    $data['teams']            = $this->user_group_model->field_teams_dropdown();
    $data['collection_types'] = ['basic' => 'Basic','donor_base' => 'Donor Base'];

    $data['merchant'] = $this->school_merchant_model->program_school_has_merchant($program_id);

    $this->load->model('prize_model');
    $data['prizes_dropdown'] = $this->prize_model->get_all();

    // Pledge Settings
    $this->load->model('program_pledge_settings_model');
    $data['program_pledge_settings'] = $this->program_pledge_settings_model->get_pledge_settings($program_id);

    //florida prepaid
    $this->load->model('program_sponsor_model');
    $data['florida_prepaid'] = $this->program_sponsor_model->is_sponsored($program_id, Program_sponsor_model::FLORIDA_PREPAID);
    $data['good_grains']     = $this->program_sponsor_model->is_sponsored($program_id, Program_sponsor_model::GOOD_GRAINS);

    // User Activity Options
    $data['user_activities'] = $this->user_activities_model->getActivites('category');

    // Retrieve Unit Types
    $data['program_unit_types'] = $this->program_model->get_program_units_for_ui_select();

    $data['view']        = 'admin/edit_program';
    $data['footer_js']   = ['jquery.guillotine.min.js', 'piexif.js', 'image_editor.js', 'spectrum.js',
    'admin/edit_program.js', 'admin/microsite.js', 'admin/manage_prizes.js', 'dropzone.js'];
    $data['ck_editor']   = true;
    $data['extra_css'][] = 'spectrum.css';

    $this->load->view('admin/programs/template_edit_program', $data);
  }


  /**
   * Show classroom summary for students in this program,
   * link to printable labels. (42797177)
   * @deprecated
   */
  public function admin_labels($program_id) {
    $data['program']         = $this->program_model->full_program($program_id, false);
    $data['classrooms']      = $this->program_model->get_classrooms_in_program($program_id);
    $data['labels_per_page'] = 30;
    $data['program_id']      = $program_id;

    $data['view'] = 'admin/label_preview';
    $this->load->view('admin/template', $data);
  }


  /**
   * Produce a printable labels view for students in this program.
   * (42797177)
   */
  public function admin_labels_print($program_id) {
    $data['students'] = $this->program_model->get_students_in_program_for_labels($program_id);
    $data['program']  = $this->program_model->full_program($program_id, false);
    $this->load->view('admin/labels', $data);
  }


  /**
   * Produce a printable labels PDF for students in this program.
   * (47834349)
   */
  public function admin_labels_pdf($program_id) {
    $this->load->library('wkhtmltopdf');

    $data['program'] = $this->program_model->full_program($program_id, false);

    $wkhtmltopdf = new WkHtmlToPdf();
    $options     = ['page-size' => 'Letter'];
    if ($_GET['top_margin'] !== '') {
      $options['margin-top'] = (int)$_GET['top_margin'];
    }

    $wkhtmltopdf->setOptions($options);

    /* Separate classes into their own pages */
    $data['students'] = $this->program_model->get_students_in_program_for_labels($program_id);
    foreach ($data['students'] as $student) {
      $class_separate[$student->classroom_id][] = $student;
    }

    foreach ($class_separate as $class_chunk) {
      $data['students'] = $class_chunk;
      $wkhtmltopdf->addPage($this->load->view('admin/labels', $data, true));
    }

    if (!$wkhtmltopdf->send("Program Labels - {$data['program']->name} - " . date('mdY_HisA') . '.pdf')) {
        log_message('error', "PDF generation failed: {$wkhtmltopdf->getError()}");

        $this->load->view(
          'admin/template', [
            'view'    => 'admin/label_preview',
            'message' => "PDF generation failed: {$wkhtmltopdf->getError()}"
          ]
        );
    }
  }


  /**
   * CSV report of payment assignments to students under the program.
   * 49089715
   *
   * @param int $program_id
   */
  public function admin_participant_collection_summary($program_id) {

    $outstandings = $this->program_model->get_collections_summary($program_id);
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=participant-collection-summary.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    $out = fopen('php://output', 'w');
    fputcsv(
      $out, ['Participant Name', 'Class', 'Grade', 'Pledged',
      'Collected', 'Payment Scheduled','Outstanding', 'Parent', 'Last', 'Email', 'Phone']
    );

    foreach ($outstandings as $row) {
      //filter out participants who do not have pledges entered
      if (!($row->pledged > 0)) {
        continue;
      }

      fputcsv(
        $out, [
        $row->participant_name,
        $row->class,
        $row->grade_name,
        '$' . number_format($row->pledged, 2),
        '$' . number_format($row->collected, 2),
        '$' . number_format($row->scheduled, 2),
        '$' . number_format($row->outstanding, 2),
        $row->parent_first_name,
        $row->parent_last_name,
        $row->parent_email,
        '="' . $row->parent_phone . '"'
        ]
      );
    }

    fclose($out);
  }


  /**
   * CSV report of payment assignments to students under the program.
   * 49089715
   *
   * @param int $program_id
   */
  public function collection_details_report($program_id) {
    $this->load->model('payment_model');

    $assignments = $this->payment_model->get_student_collection_assignments($program_id);

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=collection-details.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    $out = fopen('php://output', 'w');
    fputcsv(
      $out,
      [
        'Participant First Name',
        'Participant Last Name',
        'Class',
        'Grade',
        'Collected Amount',
        'Type',
        'Check #',
        'Note',
        'Receipt',
        'Date',
        'Payer First Name',
        'Payer Last Name',
        'Payer Email',
        'Phone',
        'Address',
        'City',
        'State',
        'Zip',
        'Time Entered',
        'Entered By Name'
      ]
    );
    foreach ($assignments as $row) {
      fputcsv(
        $out,
        [
          $row->student_first_name,
          $row->student_last_name,
          $row->class_name,
          $row->grade,
          $row->split_amount,
          $row->type,
          $row->check_number,
          $row->note,
          ($row->receipt) ? 'Yes' : '',
          $row->created_date,
          $row->payer_first_name,
          $row->payer_last_name,
          $row->payer_email,
          '="' . $row->phone . '"',
          $row->address,
          $row->city,
          $row->state,
          $row->zip,
          $row->entered_date,
          $row->entered_by_name,
        ]
      );
    }

    fclose($out);
  }


  public function braintree_merchant_transactions($program_id) {
    $this->load->library('braintree_payments');
    $this->load->model('s3_report_model');
    $program         = $this->program_model->get($program_id);
    $data['program'] = $program;
    $this->form_validation->set_rules('ts_start', 'ts_start', 'required');
    $this->form_validation->set_rules('ts_end', 'ts_end', 'required');
    $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

    if ($this->form_validation->run() != false) {
      $ts_start            = strtotime($this->input->post('ts_start'));
      $ts_end              = strtotime($this->input->post('ts_end'));
      $school_id_reporting = $program->filter_merchant_report_by_school;
      $date_start          = date('Y-m-d', $ts_start);
      $date_end            = date('Y-m-d', $ts_end);

      $this->load->model('rabbitmq_model');
      $this->rabbitmq_model->queue_model_method('program_model', 'generate_merchant_transaction_report', $program_id, $school_id_reporting, $date_start, $date_end);
      $data['success'] = 'Report is being created, reload the page to see the new download link.  Processing the report sometimes takes a few minutes, depending on time window requested and # of transactions. You don’t need to create another new report.';
    }

    $data['program']             = $this->program_model->get($program_id);
    $data['reports']             = $this->s3_report_model->get_merchant_transaction_reports($program_id);
    $data['default_start_value'] = $data['program']->pledging_start;
    $data['default_end_value']   = date_add(new DateTime($data['default_start_value']), new DateInterval('P6M'))->format('Y-m-d h:i:s');
    $data['title']               = 'Merchant Transactions';
    $data['form_url']            = '/admin/programs/report/merchant-transactions/' . $program_id;
    $data['view']                = 'admin/programs/programs_summary_report_form';
    $this->load->view('admin/template', $data);
  }


  public function braintree_merchant_deposits($program_id) {
    $this->load->model('s3_report_model');
    $this->form_validation->set_rules('ts_start', 'ts_start', 'required');
    $this->form_validation->set_rules('ts_end', 'ts_end', 'required');
    $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

    if ($this->form_validation->run() != false) {
      $ts_start   = strtotime($this->input->post('ts_start'));
      $ts_end     = strtotime($this->input->post('ts_end'));
      $date_start = date('Y-m-d', $ts_start);
      $date_end   = date('Y-m-d', $ts_end);

      $this->load->model('rabbitmq_model');
      $this->rabbitmq_model->queue_model_method('program_model', 'generate_merchant_deposit_report', $program_id, $date_start, $date_end);
      $this->session->set_flashdata('enqueue_success', 'Report is being created, reload the page to see the new download link.  Processing the report sometimes takes a few minutes, depending on time window requested and # of deposits. You don’t need to create another new report.');
      redirect(site_url("admin/programs/report/merchant-deposits/$program_id"));
    }

    $data['program']             = $this->program_model->get($program_id);
    $data['reports']             = $this->s3_report_model->get_merchant_deposit_reports($program_id);
    $data['default_start_value'] = $data['program']->pledging_start;
    $data['default_end_value']   = date_add(new DateTime($data['default_start_value']), new DateInterval('P6M'))->format('Y-m-d h:i:s');
    $data['title']               = 'Merchant Report';
    $data['form_url']            = '/admin/programs/report/merchant-deposits/' . $program_id;
    $data['view']                = 'admin/programs/programs_summary_report_form';
    $this->load->view('admin/template', $data);
  }


  public function giving_market_certificates($program_id) {
    $this->load->model('s3_report_model');
    $this->form_validation->set_rules('ts_start', 'ts_start', 'required');
    $this->form_validation->set_rules('ts_end', 'ts_end', 'required');
    $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

    if ($this->form_validation->run() != false) {
      $ts_start   = strtotime($this->input->post('ts_start'));
      $ts_end     = strtotime($this->input->post('ts_end'));
      $date_start = date('Y-m-d H:i:s', $ts_start);
      $date_end   = date('Y-m-d H:i:s', $ts_end);

      $this->load->model('rabbitmq_model');

      $this->rabbitmq_model->queue_model_method('program_model', 'generate_giving_market_certificate_report', $program_id, $date_start, $date_end);
      $this->session->set_flashdata('enqueue_success', 'Certificate report is being created, reload the page to see the new download link.  Processing the report sometimes takes a few minutes, depending on time window requested and # of deposits. You don’t need to create another new report.');
      redirect(site_url("admin/programs/report/giving-market-certificates/$program_id"));
    }

    $data['program']             = $this->program_model->get($program_id);
    $data['reports']             = $this->s3_report_model->get_giving_market_certificate_reports($program_id);
    $data['default_start_value'] = $data['program']->pledging_start;
    $data['default_end_value']   = date_add(new DateTime($data['default_start_value']), new DateInterval('P6M'))->format('Y-m-d h:i:s');
    $data['title']               = 'Giving Market Certificate Report';
    $data['form_url']            = '/admin/programs/report/giving-market-certificates/' . $program_id;
    $data['view']                = 'admin/programs/giving_market_report_form';
    $this->load->view('admin/template', $data);
  }


  public function collection_reminder($program_id, $test = false) {
    //determine if you are testing the PDF's look prior to sending
    if ($_GET['test'] == true) {
      $test = true;
    }

    $this->load->model('meta_model');
    $this->load->model('microsite_model');
    $this->load->library('booster_api');

    // Retrieve Unit Data for Program
    $data['unit_data']        = $this->booster_api->get_unit_data($this->program_model->get_unit_id_from_program_id($program_id))->data;
    $data['funds_raised_for'] = $this->microsite_model->get_better_funds_raised_for_text($program_id);

    $this->form_validation->set_rules(
      'outstanding_amount', 'Outstanding Amount',
      'required|numeric|greater_than[0]'
    );
    $this->form_validation->set_rules('body', 'Letter Body', 'required');
    $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

    $coll_reminder_pgm_info = $this->program_model->get_collection_reminder_program_info($program_id);

    if ($this->form_validation->run() != false || $test === true) {
      $coll_reminder = $this->program_model->get_program_collection_reminder($program_id, $coll_reminder_pgm_info, $this->input->post('outstanding_amount'));
      if (!empty($coll_reminder)) {
        $data['coll_reminder'] = $coll_reminder;
        $data['letter_body']   = $this->input->post('body');
        $data['event_name']    = $coll_reminder_pgm_info->event_name;

        if (isset($_POST['send_email'])) {
          // prepares email to be sent in rabbitMQ
          $this->load->model('email_notification_model');
          $this->load->model('user_model');
          $data['email_notification_update_id'] = $this->email_notification_model->
            add_program_collection_reminder(
              $this->ion_auth->logged_in(),
              $this->user_model->get_email($this->ion_auth->logged_in()),
              $program_id,
              $this->input->post('parent_collection_letter')
            );

          $this->queue_collection_reminder($data);
          $data['success'] = 'The Collection Reminder Emails have been sent!';
        } elseif ($test != true) {
          //generate pdf
          $report_data = $this->load->view('admin/programs/collection_reminder_letter', $data, true);
          $this->load->library('wkhtmltopdf');
          $this->wkhtmltopdf->addPage($report_data);
          $pdf_file = 'collection_reminder_' . date('mdY_HisA') . '.pdf';

          if (!$this->wkhtmltopdf->send($pdf_file)) {
              log_message('error', "PDF generation failed: {$this->wkhtmltopdf->getError()}");
          }
        }
      } else {
        $data['outstanding_amount_high'] = true;
      }
    }

    if ($this->input->post('parent_collection_letter') == 'follow_up_day') {
      redirect("/admin/programs/communication/parent-collection-letter/$program_id");
    }

    $this->load->model('email_notification_model');
    $data['email_notification_history'] = $this->email_notification_model->get_program_communication_history($program_id);

    $this->load->model('program_model');

    $data['program_data']        = $coll_reminder_pgm_info;
    $data['program_id']          = $program_id;
    $data['program']             = $this->program_model->get($program_id);
    $data['def_body']            = ($this->input->post('body') === null) ? $this->load->view('admin/programs/collection_reminder_field_value', $data, true) : $this->input->post('body');
    $data['def_outstanding_amt'] = ( $this->input->post('outstanding_amount') === null ) ? $this->meta_model->get('default_outstanding_amt') : $this->input->post('outstanding_amount');
    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['footer_js'] = ['admin/collection_reminder_form.js'];
    $data['view']      = 'admin/programs/collection_reminder_form';
    $test ? $this->load->view('admin/programs/collection_reminder_letter', $data) : $this->load->view('admin/programs/template', $data);
  }


  public function sponsor_follow_up($program_id) {
    $this->form_validation->set_rules('body', 'Letter Body', 'required|text_placeholder_removed[(insert Facebook photo album link or youtube link)]|text_placeholder_removed[(enter estimated school profit)]');
    $this->form_validation->set_rules('subject', 'Subject', 'required');
    $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

    $this->load->model('school_model');
    $data['school'] = $this->school_model->school_for_program($program_id);
    $this->load->model('microsite_model');
    $data['microsite']  = $this->microsite_model->get_by('program_id', $program_id);
    $program_obj        = $this->program_model->get($program_id);
    $data['event_name'] = $program_obj->event_name;
    if ($this->form_validation->run() != false) {
      if (isset($_SESSION['error'])) {
        unset($_SESSION['error']);
      }

      $data['subject'] = $this->input->post('subject');
      $data['body']    = $this->input->post('body');
      $data['body']    = nl2br($data['body']);

      $this->load->model('email_notification_model');
      $this->load->model('user_model');
      $email_notification_update_id = $this->email_notification_model->add_program_sponsor_followup(
        $this->ion_auth->logged_in(),
        $this->user_model->get_email($this->ion_auth->logged_in()),
        $program_id
      );

      // have to queue after to track recipients because of the job
      // being queued and handled async by rabbit
      $data['email_notification_update_id'] = $email_notification_update_id;
      $this->queue_sponsor_follow_up($program_id, $data);

      $data['success'] = 'The Sponsor Follow Up Emails have been sent!';
    }

    $data['payee']      = $program_obj->payee;
    $data['program_id'] = $program_id;

    $this->load->model('program_model');
    $data['program'] = $this->program_model->get($program_id);

    $this->program_model->attach_program_nav_bar_data_to($data);
    $this->config->load('urls');
    $view_body       = $this->load->view('admin/programs/sponsor_follow_up_field_values', $data, true);
    $data['body']    = ($this->input->post('body') === null) ? $view_body : $this->input->post('body');
    $data['subject'] = ($this->input->post('subject') === null) ? 'Thank you for your generosity!' : $this->input->post('subject');
    $this->load->model('email_notification_model');
    $data['email_notification_history'] = $this->email_notification_model->get_program_communication_history($program_id);

    $data['footer_js'] = ['admin/sponsor_follow_up_form.js'];
    $data['view']      = 'admin/programs/sponsor_follow_up_form';
    $this->load->view('admin/programs/template', $data);
  }


  public function unpaid_sponsor_follow_up($program_id, $alternate = false) {
    $data['footer_js']  = ['admin/sponsor_follow_up_form.js'];
    $data['view']       = 'admin/programs/unpaid_sponsor_follow_up';
    $data['program_id'] = $program_id;
    $data['program']    = $this->program_model->get($program_id);

    $data['alternate'] = $alternate;
    if ($alternate) {
      $data['alternate_header'] = '#2';
      $data['unpaid_subject']   = '(Event Name) pledge payment due';
    } else {
      $data['alternate_header'] = '#1';
      $data['unpaid_subject']   = '(Event Name) pledge payment reminder';
    }

    $this->load->model('email_notification_model');
    $data['email_notification_history'] = $this->email_notification_model->get_program_communication_history($program_id);

    $this->load->view('admin/programs/template', $data);
  }


  public function send_unpaid_sponsor_follow_up($program_id, $alternate = false) {
    $this->load->model('email_notification_model');
    $this->load->model('user_model');
    $this->load->model('program_todos_model');
    $send_type                    = $alternate ? Email_notification_model::TYPE_PROGRAM_UNPAID_SPONSOR_FOLLOWUP_2 : Email_notification_model::TYPE_PROGRAM_UNPAID_SPONSOR_FOLLOWUP_1;
    $sending_user                 = $this->ion_auth->user()->row();
    $email_notification_update_id = $this->email_notification_model->save_email_notification(
      $sending_user->id,
      $sending_user->email,
      $send_type,
      $program_id
    );

    $this->queue_unpaid_sponsor_follow_up($program_id, $alternate, $email_notification_update_id);

    $this->session->set_flashdata(
      'success',
      "<div class='alert alert-success'>
      The Sponsor Thank You Email - After Collection Emails have been sent.</div>"
    );
    $this->program_todos_model->update_program_todos_single($program_id);
    redirect("/admin/programs/communication/unpaid-sponsor-follow-up/$program_id/$alternate");
  }


  public function parent_collection_letter($program_id) {
    $this->load->model('email_notification_model');
    $this->load->model('meta_model');

    $data['view']                       = 'admin/programs/parent_collection_letter';
    $data['footer_js']                  = ['admin/parent_collection_email_form.js'];
    $data['default_outstanding_amt']    = ( $this->input->post('outstanding_amount') === null ) ? $this->meta_model->get('default_outstanding_amt') : $this->input->post('outstanding_amount');
    $data['program_id']                 = $program_id;
    $data['program']                    = $this->program_model->get($program_id);
    $data['program_data']               = $this->program_model->get_collection_reminder_program_info($program_id);
    $data['default_body']               = ($this->input->post('body') === null) ? $this->load->view('admin/programs/parent_collection_letter_field_value', $data, true) : $this->input->post('body');
    $data['email_notification_history'] = $this->email_notification_model->get_program_communication_history($program_id);

    $this->load->view('admin/programs/template', $data);
  }


  private function queue_sponsor_follow_up($program_id, $data) {
    $this->load->model('rabbitmq_model');
    return $this->rabbitmq_model
      ->queue_model_method(
        'program_model',
        'sponsor_follow_up',
        $program_id, $data
      );
  }


  private function queue_unpaid_sponsor_follow_up($program_id, $alternate, $email_notification_update_id) {
    $this->load->model('rabbitmq_model');

    return $this->rabbitmq_model->queue_model_method(
      'program_model',
      'unpaid_sponsor_follow_up',
      $program_id,
      $alternate,
      $email_notification_update_id
    );
  }


  private function queue_collection_reminder($coll_data) {
    $this->load->model('rabbitmq_model');
    return $this->rabbitmq_model
      ->queue_model_method('program_model', 'collection_reminder', $coll_data);
  }


  private function queue_program_summary($semester) {
    $this->load->model('rabbitmq_model');
    $email_recip = $this->ion_auth->user()->row()->email;
    return $this->rabbitmq_model
      ->queue_model_method('program_model', 'send_program_summary_by_semester', $semester, $email_recip);
  }


  public function payment_request_log($program_id) {
    $data['data_table']['id']   = 'payment-request-log';
    $data['data_table']['url']  = "/programs/ajax_get_payment_requests/{$program_id}";
    $data['data_table']['sort'] = "[3, 'desc']";

    $data['program'] = $this->program_model->get($program_id);

    $data['view'] = 'admin/programs/payment_request_log';
    $this->load->view('admin/template', $data);
  }


  public function ajax_get_payment_requests($program_id) {
    $this->load->model('payment_notify_model');
    $data['json'] = $this->payment_notify_model->ajax_get_notifications($program_id);
    $this->load->view('admin/ajax', $data);
  }


  public function sys_admin_programs_summary_report() {
    $this->form_validation->set_rules('semester', 'Semester', 'required');
    $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

    $semesters         = $this->program_model->semester_dropdown();
    $semesters['all']  = 'All Semesters';
    $data['semesters'] = $semesters;
    $data['title']     = 'Programs Summary Report';
    $data['form_url']  = '/admin/programs/report/programs-summary-csv';
    $data['view']      = 'admin/programs/programs_summary_report_form';

    if ($this->form_validation->run() != false) {
      $data['success'] = 'Program Summary Report sent to your email.';
    }

    $this->load->view('admin/template', $data);
  }


  public function sys_admin_participant_report() {
    $this->form_validation->set_rules('semester', 'Semester', 'required');
    $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

    if ($this->form_validation->run() != false) {
      $this->load->model('pledge_model');
      $semester = $this->input->post('semester');
      $programs = $this->program_model->get_programs_by_semester($semester);

      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=participant-report-$semester.csv");
      header("Pragma: no-cache");
      header("Expires: 0");
      ob_end_clean();
      $out = fopen('php://output', 'w');

      $headings = ['Participant ID','Program', 'SF_ID','Parent ID','Fun Run','Name', 'Grade', 'Participant Name','Log In', 'M/F',
                'Flat', 'PPL', '#', 'Total', 'Laps', 'Amount', 'Collected', 'Outstanding', 'Photo', 'EE'];
      fputcsv($out, $headings);

      foreach ($programs as $program) {
        $participants = $this->pledge_model->get_class_pledges($program->id, null, true);

        foreach ($participants as $part) {
          $temp_total_amt = bcsub($part->total_amount, $part->collected_amount, 2);

          $row_for_csv = [
            $part->student_id,
            $part->program_name,
            $part->salesforce_id,
            $part->parent_id,
            $part->fun_run,
            ($part->user_group_id == User_group_model::TEACHERS ? '*' : '') .$part->first_name.' '.$part->last_name,
            $part->grade_name,
            $part->participant_name,
            $part->login_status,
            (strtolower($part->gender) == "male" || strtolower($part->gender) == "female") ? strtoupper($part->gender[0]) : '',
            ($part->flat_amount) ? '$'.number_format($part->flat_amount, 2) : '',
            ($part->ppl_amount) ? '$'.number_format($part->ppl_amount, 2) : '',
            $part->total_pledges,
            ($part->total_ppl) ? '$'.number_format($part->total_ppl, 2) : '',
            $part->laps,
            ((int)$part->total_amount) ? '$'.$part->total_amount : '',
            ($part->collected_amount) ? '$'.number_format($part->collected_amount, 2) : '',
            ($temp_total_amt && $temp_total_amt > 0) ? '$'.bcsub($part->total_amount, $part->collected_amount, 2) : '',
            $part->image_name ? 'Y' : '',
            $part->easy_emailer_enrollee_count
          ];
          fputcsv($out, $row_for_csv);
        } //end foreach participants in program
      } //end foreach programs

      fclose($out);
    } else {
      $semesters         = $this->program_model->semester_dropdown();
      $semesters['all']  = 'All Semesters';
      $data['semesters'] = $semesters;
      $data['view']      = 'admin/programs/participant_report_form';
      $this->load->view('admin/template', $data);
    }
  }


    /**
     * Create a report of registration codes
     * @param string $program_id string as int
     * @param string $type acceptable vals: PDF|HTML
     */
  public function codes($program_id, $type) {
    $classes         = $this->program_model->get_classrooms_without_counts_in_program($program_id);
    $data['classes'] = $classes;
    $data['view']    = 'admin/programs/reports/codes';
    if ($type === 'PDF') {
      $report_data = $this->load->view('admin/template_report', $data, true);

      $this->load->library('wkhtmltopdf');
      $this->wkhtmltopdf->addPage($report_data);
      if (!$this->wkhtmltopdf->send()) {
          log_message('error', "PDF generation failed: {$this->wkhtmltopdf->getError()}");
      }
    } elseif ($type === 'HTML') {
      $data['program']    = $this->program_model->get($program_id);
      $data['codes_html'] = true;
      $this->load->view('admin/template', $data);
    }
  }


    /**
     * Creates PDF report of laps run per student per class in a given program
     * @param type $program_id
     */
  public function lap_counter_pdf($program_id) {
    $this->load->model('unit_model');

    $data['report'] = $this->program_model
      ->get_program_lap_counter_report($program_id);
    $program        = $this->program_model->get($program_id);
    $data['unit']   = $this->unit_model->get($program->unit_id);
    $data['view']   = 'admin/programs/reports/lap_counter';
    $report_data    = $this->load->view('admin/template_report', $data, true);

    $this->load->library('wkhtmltopdf');
    $this->wkhtmltopdf->addPage($report_data);

    if (!$this->wkhtmltopdf->send()) {
      log_message('error', "PDF generation failed: {$this->wkhtmltopdf->getError()}");
    }
  }


  public function admin_code_labels($program_id) {
    $data['program']         = $this->program_model->full_program($program_id, false);
    $data['classrooms']      = $this->program_model->get_classrooms_with_school_abbreviation($program_id);
    $data['labels_per_page'] = 30;
    $data['program_id']      = $program_id;

    $data['view'] = 'admin/code_label_preview';
    $this->load->view('admin/template', $data);
  }


  public function admin_code_labels_print($program_id) {
    $data['classrooms'] = $this->program_model->get_classrooms_with_school_abbreviation($program_id);
    $data['program']    = $this->program_model->full_program($program_id, false);
    $this->load->view('admin/code_labels', $data);
  }


  public function admin_code_labels_pdf($program_id) {
    $this->load->library('wkhtmltopdf');

    $data['program'] = $this->program_model->full_program($program_id, false);

    $wkhtmltopdf = new WkHtmlToPdf();
    $options     = ['page-size' => 'Letter', 'disable-smart-shrinking'];
    if ($_GET['top_margin'] !== '') {
        $options['margin-top'] = (int)$_GET['top_margin'];
    }

    $wkhtmltopdf->setOptions($options);

    /* Separate classes into their own pages */
    $data['classrooms'] = $this->program_model->get_classrooms_with_school_abbreviation($program_id);

    // Add Student Labels - 30 per page
    foreach ($data['classrooms'] as $classroom) {
      $wkhtmltopdf->addPage(
        $this->load->view(
          'admin/code_labels_pdf',
          [
            'labels_per_page' => 30,
            'labelType'     => 'member',
            'program'       => $data['program'],
            'classrooms'    => [$classroom]
          ], true
        )
      );
    }

    // Add Teacher Labels
    $teacher_pages = ceil(count($data['classrooms']) / 30);
    for ($page = 1;$page <= $teacher_pages;$page++) {
      $wkhtmltopdf->addPage(
        $this->load->view(
          'admin/code_labels_pdf',
          [
            'labels_per_page' => 1,
            'labelType' => 'leader',
            'program' => $data['program'],
            'classrooms' => array_slice($data['classrooms'], 30 * ($page - 1), 30)
          ], true
        )
      );
    }

    if (!$wkhtmltopdf->send("Program Labels - {$data['program']->name} - " . date('mdY_HisA') . '.pdf')) {
        log_message('error', "PDF generation failed: {$wkhtmltopdf->getError()}");

        $this->load->view(
          'admin/template', [
            'view'    => 'admin/label_preview',
            'message' => "PDF generation failed: {$wkhtmltopdf->getError()}"
          ]
        );
    }
  }


    // Delete program


  public function delete($program_id) {
    if (! $this->ion_auth->is_admin()) {
        show_error('You are not authorized to access this page. Return to <a href="' . base_url() . '">login page</a>.', 401);
    }

    $this->program_model->delete($program_id);

    if ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')
                   ->set_output(json_encode(['message' => 'Program deleted', 'status' => 'success']));
    }
  }


    /**
     * JSON endpoint for quickselect search.
     *
     * @param int $program_id
     */
  public function student_search($program_id) {
    if ($this->input->is_ajax_request()) {
      $quickselect = $this->_get_student_autocomplete_json($program_id, $_GET['term']);
      $this->output->set_content_type('application/json')
        ->set_output(json_encode($quickselect));
    }
  }


    /**
     * JSON endpoint for quickselect search.
     * This version is for non-logged-in page (Collection dash)
     * so requires a key in URL.
     *
     * @param int $program_id
     */
  public function collect_student_search($key) {
    if ($this->input->is_ajax_request()) {
      $program = $this->program_model->get_by('collection_refer_key', $key);
      if (!$program) {
        return show_404();
      }

      $quickselect = $this->_get_student_autocomplete_json($program->id, $_GET['term']);
      $this->output->set_content_type('application/json')
        ->set_output(json_encode($quickselect));
    }
  }


  private function _get_student_autocomplete_json($program_id, $term) {
    $results = $this->program_model->student_search($program_id, $term);
    // Rearrange for quickselect
    $quickselect = [];
    foreach ($results as $row) {
      $student_name = ucfirst(strtolower($row->first_name)) . ' ' .
        ucfirst(strtolower($row->last_name)) . '  ' . $row->grade_id .
        '-' . ucfirst(strtolower($row->class_name));

      $quickselect[] = [$student_name,
        $row->id];
    }

    return $quickselect;
  }


  // update program
  public function update($program_id = null) {

    if (! $this->ion_auth->is_admin()) {
      show_error('You are not authorized to access this page. Return to <a href="' . base_url() . '">login page</a>.', 401);
    }

    $this->form_validation->set_rules('payee', 'Payable To', 'trim|max_length[255]');
    $this->form_validation->set_rules('pep_rally', 'Pep Rally', 'trim');
    $this->form_validation->set_rules('fun_run', 'Fun Run', 'trim');
    $this->form_validation->set_rules(
      'total_raised_goal', 'Total Raised Goal',
      'trim|numeric_wcomma[0,1000000]'
    );

    $this->form_validation->set_rules(
      'client_percent', 'Predicted Client %',
      'trim|integer|less_than[100]|greater_than[0]'
    );

    if ($this->input->post('total_raised_goal')) {
      $this->form_validation->set_rules(
        'client_percent', 'Predicted Client %',
        'required|trim|integer|less_than[100]|greater_than[0]'
      );
    }

    $this->form_validation->set_rules('teacher_party', 'Teacher Party', 'trim');
    $this->form_validation->set_rules('teacher_party_location', 'Teacher Party Location', 'trim|max[300]');
    $this->form_validation->set_rules('preprogram_fr', 'Preprogram F&R', 'trim');
    $this->form_validation->set_rules('parent_membership', 'Parent Membership', 'trim');
    $this->form_validation->set_rules('collection_type', 'Collection Type', 'required');
    $this->form_validation->set_rules('event_name', 'Event Name', 'required|callback_validate_event_name');
    $this->form_validation->set_rules('unit_id', 'Unit Type', 'required');
    $this->form_validation->set_rules(
      'show_corporate_matching_widget',
      'Show Corporate Matching Widget',
      'greater_than_equal_to[0]|less_than_equal_to[1]'
    );
    $this->form_validation->set_rules(
      'hide_teacher_action_steps',
      'Hide Teacher Action Steps',
      'greater_than_equal_to[0]|less_than_equal_to[1]'
    );
    $this->form_validation->set_rules(
      'hide_teacher_incentives',
      'Hide Teacher Incentives',
      'greater_than_equal_to[0]|less_than_equal_to[1]'
    );

    if ($this->ion_auth->in_group([User_group_model::SYSADMIN])) {
      $this->form_validation->set_rules(
        'unit_range_low', 'Range Low',
        'trim|integer|greater_than[0]'
      );

      $this->form_validation->set_rules(
        'range_high', 'Range High',
        'trim|integer|greater_than[0]'
      );

      $this->form_validation->set_rules(
        'max_charge', 'Max Charge',
        'trim|integer|greater_than[0]'
      );

      $this->form_validation->set_rules(
        'no_units_entered_default', 'No Units Entered Charge',
        'trim|integer|greater_than_equal_to[0]'
      );

      $this->form_validation->set_rules(
        'unit_estimated_average', 'Estimated Average',
        'trim|integer|greater_than[0]'
      );

      $this->form_validation->set_rules(
        'unit_flat_conversion', 'Flat Conversion',
        'trim|integer|greater_than[0]'
      );
      $this->form_validation->set_rules(
        'require_address',
        'Require Address to Register',
        'greater_than_equal_to[0]|less_than_equal_to[1]'
      );
      $this->form_validation->set_rules(
        'enable_direct_give',
        'Enable Direct Give',
        'greater_than_equal_to[0]|less_than_equal_to[1]'
      );

      $this->form_validation->set_rules(
        'enable_on_behalf_of_payments',
        'Enable On Behalf of Payments',
        'greater_than_equal_to[0]|less_than_equal_to[1]'
      );

      $extraValidation = '';
      if ($this->input->post('enable_direct_give') == 1 && $this->input->post('custom_url') !== null) {
        $extraValidation = '|required|callback_check_custom_url_unique[' . (int)$program_id . ']';
      }

      $this->form_validation->set_rules(
        'custom_url',
        'Direct Give Custom URL',
        'trim|alpha_dash|max_length[40]' . $extraValidation
      );
    }

    if ($this->form_validation->run() === false) {
      $data['program_error_message'] = validation_errors();
      $this->admin_edit($program_id, $data);
    } else {
      $total_raised_goal = str_replace(',', '', $this->input->post('total_raised_goal'));
      $client_percent    = $this->input->post('client_percent');
      if ($total_raised_goal && $client_percent) {
        $client_goal = $total_raised_goal * ($client_percent * .01);
      }

      $program_info = [
        'payee' => $this->input->post('payee'),
        'due_date' => empty($this->input->post('due_date')) ? null : date('Y-m-d H:i:s', strtotime($this->input->post('due_date'))),
        'collection_type' => htmlspecialchars($this->input->post('collection_type')),
        'total_raised_goal' => $total_raised_goal ?: null,
        'client_percent' => $client_percent ?: null,
        'client_goal' => $client_goal,
        'archived' => $this->input->post('archived') ?: null,
        'event_name' => htmlspecialchars($this->input->post('event_name')),
        'restrict_access_prize_reports' => $this->input->post('restrict_access_prize_reports'),
        'parent_email_prompts' => $this->input->post('parent_email_prompts'),
        'unit_id' => $this->input->post('unit_id'),
        'show_corporate_matching_widget' => $this->input->post('show_corporate_matching_widget'),
        'ssv_disabled' => (int)$this->input->post('ssv_disabled'),
        'hide_teacher_action_steps' => $this->input->post('hide_teacher_action_steps'),
        'hide_teacher_incentives' => $this->input->post('hide_teacher_incentives'),
        'require_address' => (int)$this->input->post('require_address'),
      ];

      if ($this->input->post('enable_direct_give') == 1) {
        $program_info['custom_url']                   = $this->input->post('custom_url');
        $program_info['enable_on_behalf_of_payments'] = $this->input->post('enable_on_behalf_of_payments');
      } else {
        $program_info['custom_url']                   = null;
        $program_info['enable_on_behalf_of_payments'] = null;
      }

      if ($this->program_model->can_edit_fun_run()) {
        $program_info['fun_run'] = $this->input->post('fun_run');
      }

      if ($this->ion_auth->in_group([User_group_model::SYSADMIN])) {
        $program_info['unit_range_low']           = $this->input->post('unit_range_low');
        $program_info['unit_range_high']          = $this->input->post('unit_range_high');
        $program_info['unit_max_charge']          = $this->input->post('unit_max_charge');
        $program_info['no_units_entered_default'] = $this->input->post('no_units_entered_default');
        $program_info['unit_estimated_average']   = $this->input->post('unit_estimated_average');
        $program_info['unit_flat_conversion']     = $this->input->post('unit_flat_conversion');
      }

      $successful_update = $this->program_model->update($program_id, $program_info);

      if ($successful_update == false) {
        error_log('Fun Run had a problem updating this program.  Please try again later.');
      }

      $data['program_message'] = 'Program updated successfully.';
      //handle program sponsors
      $this->load->model('program_sponsor_model');
      $this->program_sponsor_model->admin_set_sponsors_from_post($program_id);

      //update program todos
      $this->load->model('program_todos_model');
      $this->program_todos_model->update_program_todos_single($program_id);

      $this->admin_edit($program_id, $data);
    }
  }


  public function check_custom_url_unique($url, $program_id = null) {
    $result = $this->program_model->check_unique_custom_url($url, $program_id);
    if ($result !== 0) {
        $this->form_validation->set_message('check_custom_url_unique', 'Custom URL must be unique.');
        return false;
    }

    return true;
  }


  /**
   * Update start of delivery period
   *
   * @param int $program_id
   * @return void
   */
  public function update_pledging_start($program_id) {

    $new_start_ts = new DateTime($this->input->post('ts'));
    $result       = $this->program_model->update_pledging_start($program_id, $new_start_ts);
    $this->output->set_content_type('application/json')
    ->set_output(json_encode($result));
  }


  /**
   * @param int $period_id
   * @return void
   */
  public function update_period($period_id) {
    $result = $this->program_model->update_period($period_id, $this->input->post('ts'));

    $this->output->set_content_type('application/json')
    ->set_output(json_encode($result));
  }


  public function delete_period($pledge_period_id) {
    $this->load->model('prize_bound_model');
    $existing_prizes_in_period = $this->prize_bound_model->select_prizes_for_pledge_period($pledge_period_id);

    if ($existing_prizes_in_period) {
      $message     = 'There\'s a prize associated with this pledge period. Please edit or delete the prize first before deleting this pledge period.';
      $json_output = [
        'message' => $message,
        'status'  => false
      ];
    } else {
      $program_id = $this->program_model->get_program_id_by_pledge_period_id($pledge_period_id);
      $log_data   = [
      "action"     => "delete_pledge_period",
      "program_id" => $program_id,
      "delete_on"  => date('m/d/Y h:i:s A', now()),
      ];
      $this->user_audit->record_user_action($log_data);

      $delete_period = $this->program_model->delete_period($pledge_period_id);

      if ($delete_period) {
        $message     = 'Pledge period deleted.';
        $json_output = ['message' => 'Period Deleted', 'status' => true];
      } elseif ($delete_period === false) {
        $json_output = ['message' => 'Period Not Deleted', 'status' => false];
      }
    }

    $this->session->set_flashdata('pledge_period_message', $message);
    $this->output->set_content_type('application/json')
    ->set_output(json_encode($json_output));
  }


  // Update delivery date for a period

  public function new_period() {
    if ($this->program_model->validate_has_pledging_start($this->input->post('program_id'))) {
      $new_period = $this->program_model->new_period($this->input->post('program_id'), $this->input->post('delivery_date'));

      $this->output->set_content_type('application/json')
        ->set_output(json_encode($new_period));
    } else {
      //no pledging start message or something like that?
      $this->output->set_content_type('application/json')
          ->set_output(json_encode(['status' => false, 'message' => "Update pledging start time before adding or editing pledge periods."]));
    }
  }


  public function pledge_settings($program_id) {
    $this->load->model('program_pledge_settings_model');
    if ($this->form_validation->run()) {
      $recommended_pledge_amounts = $this->_get_recommended_pledge_amounts_posted();
      $settings                   = [
        'program_id'                        => $program_id,
        'flag_high_donation'                => $this->input->post('flag_high_donation'),
        'flag_payment_scheduled_high_value' => $this->input->post('flag_payment_scheduled_high_value'),
        'flag_high_cumulative_per_period'   => $this->input->post('flag_high_cumulative_per_period'),
        'flag_high_quantity_per_period'     => $this->input->post('flag_high_quantity_per_period'),
        'weekend_challenge_amount'          => $this->input->post('weekend_challenge_amount'),
        'flat_donate_only'                  => $this->input->post('flat_donate_only'),
        'ppu_donations_only'                => $this->input->post('ppu_donations_only'),
        'minimize_flat_donation'  => $this->input->post('minimize_flat_donation'),
        'recommended_pledge_amounts'        => serialize($recommended_pledge_amounts),
        'family_pledging_enabled'           => $this->input->post('family_pledging_enabled'),
      ];
    }

    if (!$this->ion_auth->is_admin()) {
      show_error('You are not authorized to access this page. Return to <a href="' . base_url() . '">login page</a>.', 401);
    }

    $this->form_validation->set_rules(
      'flag_high_donation', 'High Donation',
      'required|numeric|is_natural_no_zero|max_length[3]'
    );
    $this->form_validation->set_rules(
      'flag_payment_scheduled_high_value', 'Payment Scheduled High Value',
      'required|numeric|is_natural_no_zero|max_length[6]'
    );
    $this->form_validation->set_rules(
      'flag_high_cumulative_per_period', 'Cumulative Pledge Amount',
      'required|numeric|is_natural_no_zero|max_length[4]'
    );
    $this->form_validation->set_rules(
      'flag_high_quantity_per_period', 'Pledges In Period',
      'required|numeric|is_natural_no_zero|max_length[2]'
    );
    $this->form_validation->set_rules('weekend_challenge_amount', 'Weekend Challenge Amount', 'required|numeric|greater_than[0]');
    $this->form_validation->set_rules('flat_donate_only', 'Flat Donation Only', 'is_natural|less_than[2]');
    $this->form_validation->set_rules('perlap_a[]', 'Per Lap A', 'numeric|greater_than[0]');
    $this->form_validation->set_rules('perlap_b[]', 'Per Lap B', 'numeric|greater_than[0]');
    $this->form_validation->set_rules('flat_a[]', 'Flat A', 'numeric|greater_than[0]');
    $this->form_validation->set_rules('flat_b[]', 'Flat B', 'numeric|greater_than[0]');
    $this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');

    if ($this->form_validation->run()) {
      $recommended_pledge_amounts = $this->_get_recommended_pledge_amounts_posted();
      $settings                   = [
        'program_id'                        => $program_id,
        'flag_high_donation'                => $this->input->post('flag_high_donation'),
        'flag_payment_scheduled_high_value' => $this->input->post('flag_payment_scheduled_high_value'),
        'flag_high_cumulative_per_period'   => $this->input->post('flag_high_cumulative_per_period'),
        'flag_high_quantity_per_period'     => $this->input->post('flag_high_quantity_per_period'),
        'weekend_challenge_amount'          => $this->input->post('weekend_challenge_amount'),
        'flat_donate_only'                  => $this->input->post('flat_donate_only'),
        'ppu_donations_only'                => $this->input->post('ppu_donations_only'),
        'minimize_flat_donation'            => $this->input->post('minimize_flat_donation'),
        'recommended_pledge_amounts'        => serialize($recommended_pledge_amounts),
        'family_pledging_enabled'           => $this->input->post('family_pledging_enabled'),
      ];

      $program_settings = $this->program_pledge_settings_model->get_by('program_id', $program_id); // do not change to get_pledge_settings($program_id) this will break the insert / update

      if ($program_settings) {
        $this->program_pledge_settings_model->update_by('program_id', $program_id, $settings);
      } else {
        $this->program_pledge_settings_model->insert($settings);
      }

      $this->session->set_flashdata('pledge_settings_message', 'Pledge Flagging Settings updated successfully.');
      redirect('admin/programs/edit/' . $program_id . '#pledge-settings');
    } else {
      $this->session->set_flashdata('pledge_settings_error_message', validation_errors());
      redirect('/admin/programs/edit/' . $program_id . '#pledge-settings');
    }

    $data['program']                 = $this->program_model->full_program($program_id, false);
    $data['program_pledge_settings'] = $this->program_pledge_settings_model->get_pledge_settings($program_id);
    $data['active']                  = 'pledge_settings';
    $data['view']                    = 'admin/programs/edit_pledge_settings';

    $this->load->view('admin/programs/template_edit_program', $data);
  }


  private function _get_recommended_pledge_amounts_posted() {
    $recommended_pledge_amounts = [
      'perlap_a' => $this->input->post('perlap_a'),
      'perlap_b' => $this->input->post('perlap_b'),
      'flat_a'   => $this->input->post('flat_a'),
      'flat_b'   => $this->input->post('flat_b'),
    ];
    if ($this->input->post('ppl_default_a')) {
      $recommended_pledge_amounts['ppl_default_a'] = $this->input->post('ppl_default_a');
    }

    if ($this->input->post('flat_default_a')) {
      $recommended_pledge_amounts['flat_default_a'] = $this->input->post('flat_default_a');
    }

    if ($this->input->post('ppl_default_b')) {
      $recommended_pledge_amounts['ppl_default_b'] = $this->input->post('ppl_default_b');
    }

    if ($this->input->post('flat_default_b')) {
      $recommended_pledge_amounts['flat_default_b'] = $this->input->post('flat_default_b');
    }

    return $recommended_pledge_amounts;
  }


  public function add_class($program_id) {
    $this->load->model('classroom_model');
    $data['program_id']         = $program_id;
    $data['grades']             = $this->classroom_model->get_grades_dropdown($program_id, true);
    $data['classes']            = $this->classroom_model->get_groups_by_program_id($program_id);
    $data['active']             = 'programs';
    $data['active_sub_tab']     = 'classes';
    $data['program']            = $this->program_model->get($program_id);
    $data['data_table']['id']   = 'classes-table';
    $data['data_table']['sort'] = "[0, 'asc']";
    $data['data_table']['url']  = "ajax_get_classes/{$program_id}";
    $this->program_model->attach_program_nav_bar_data_to($data);
    $this->form_validation->set_rules('class_name', 'Class Name', 'required');
    $this->form_validation->set_rules('pledge_meter', 'Pledge O\'Meter Goal', 'required|numeric');
    $this->form_validation->set_rules('number_of_participants', '# of Team Members', 'integer');
    if ($this->form_validation->run() == false) {
      $data['view'] = 'admin/programs/add_class';
      $this->load->view('admin/programs/template', $data);
    } else {
      $this->classroom_model->insert_class(
        $this->input->post('groups'),
        $this->input->post('grades'),
        $this->input->post('class_name'),
        $this->input->post('number_of_participants'),
        null,
        $this->input->post('pledge_meter')
      );

      $data['success'] = 'Classroom created.';
      $data['view']    = 'admin/programs/add_class';
      $this->load->view('admin/programs/template', $data);
    }
  }


  /**
   * Retrieves JSON for Program dashboard daily pledge graph
   *
   * @param program_id - ID of program
   */
  public function ajax_daily_pledge_graph($program_id) {
    $this->load->model('pledge_model');
    $daily_pledges = $this->pledge_model
      ->get_pledges_by_day($program_id);

    $this->output->set_content_type('application/json')
      ->set_output($daily_pledges);
  }


  /**
   * Retrieves JSON for Program dashboard Pledged Participants gauge
   *
   * @param program_id - ID of program
   */
  public function ajax_pledged_participants_gauge($program_id) {
    $this->load->model('pledge_model');
    $program_pledge_stats = $this->pledge_model
      ->get_program_pledge_participation($program_id);

    $this->output->set_content_type('application/json')
      ->set_output($program_pledge_stats);
  }


  /**
   * Retrieves JSON for Program dashboard Online Registered Participants gauge
   *
   * @param program_id - ID of program
   */
  public function ajax_participants_registered_gauge($program_id) {
    $this->load->model('pledge_model');
    $program_pledge_stats = $this->pledge_model
      ->get_program_pledge_participation($program_id);

    $this->output->set_content_type('application/json')
      ->set_output($program_pledge_stats);
  }


  /**
   * Retrieves JSON for Program dashboard sponsor pie chart
   *
   * @param program_id - ID of program
   */
  public function ajax_sponsor_pie_chart($program_id) {
    $this->load->model('pledge_model');

    $sponsor_pledges = $this->pledge_model
      ->get_pledges_grouped_by_sponsor($program_id);

    $formatted_pledges = $this->pledge_model
      ->format_grouped_sponsor_pledges($sponsor_pledges);

    $this->output->set_content_type('application/json')
      ->set_output($formatted_pledges);
  }


  /**
   * Retrieves JSON for Total Pledges / Payments Stacked Bar Graph
   *
   * @param program_id - ID of program
   */
  public function ajax_total_pledges_payments_graph($program_id) {
    $pledges_payments = $this->program_model
      ->get_program_pledge_and_payment_totals($program_id);

    $this->output->set_content_type('application/json')
      ->set_output($pledges_payments);
  }


  public function ajax_total_pledges_payments_by_class($program_id, $period_ordinal = null) {
    $pledges_payments = $this->program_model
      ->get_program_pledge_and_payment_by_class($program_id, $period_ordinal);

    $this->output->set_content_type('application/json')
      ->set_output($pledges_payments);
  }


  /**
   * Returns JSON array of pledge totals for a given period
   * @param $program_id - ID of program
   */
  public function ajax_get_pledge_period_totals($program_id, $ordinal) {
    $pledge_periods = $this->program_model->get_pledge_totals_by_period($program_id, $ordinal);

    $this->output->set_content_type('application/json')
        ->set_output($pledge_periods);
  }


  public function fee_validation() {
    // must be casted to float because they will contain '0.00'
    // which is evaluated as not empty
    $fees = [
      'school' => floatval($this->input->post('school_processing_fee')),
      'sponsor' => floatval($this->input->post('sponsor_convenience_fee')),
      'optional' => floatval($this->input->post('optional_sponsor_fee'))
    ];

    $online_only_enabled = boolval($this->input->post('online_payment_required'));

    $fee_count = count(
      array_filter(
        $fees, function($val) {
          return !empty($val);
        }
      )
    );

    if ($online_only_enabled && ($fees['sponsor'] > 0)) {
      $this->form_validation->set_message('fee_validation', 'With online payments required, you cannot force user to pay the fee. Please add School Processing fee and Online Processing Fee.');
      return false;
    }

    if ($fee_count > 1 && !empty($fees['sponsor'])) {
      $this->form_validation->set_message('fee_validation', 'Only school and online processing fees can be set simultaneously.');
      return false;
    }

    if ($fees['school'] > 4 || $fees['sponsor'] > 4 || $fees['optional'] > 4) {
      $this->form_validation->set_message('fee_validation', 'No fee can exceed $4.00.');
      return false;
    }

    return true;
  }


  /**
   * Determines if the User Agent is Mobile
   * @return boolean
   */
  public function is_mobile() {
    $this->load->library('user_agent');
    if ($this->agent->is_mobile()) {
      return true;
    }

    return false;
  }


  /**
   * Returns the HTML table content for the top 10 participants
   *
   * @param int $program_id
   * @param int $pledge_period_id
   */
  public function ajax_top_ten_participants($program_id,$pledge_period_id,$grade_id,$class_id) {
    if (empty($program_id)) {
      show_404();
    }

    $this->load->model('pledge_model');

    $data                      = [];
    $data['participants']      = $this->pledge_model
      ->get_top_ten_participants($program_id, $pledge_period_id, $grade_id, $class_id);
    $data['participant_count'] = count($data['participants']);

    $this->output->set_content_type('text/html')
      ->set_output($this->load->view('dashboard/partials/tab_content/top_ten_participants_table', $data, true));
  }


  public function complete_top_prize_todo_for_program($program_id, $todo) {
    $this->load->model('program_todos_model');
    $this->program_model->complete_top_prize_todos($program_id);
    $this->program_todos_model->remove_todos($program_id, [$todo]);
    $this->show_todos($program_id);
  }


  public function complete_teacher_prize_todo_for_program($program_id, $todo) {
    $this->load->model('program_todos_model');
    $this->program_model->complete_teacher_prize_todos($program_id);
    $this->program_todos_model->remove_todos($program_id, [$todo]);
    $this->show_todos($program_id);
  }


  /**
   * Renders the program todos page
   * @param type $program_id
   */
  public function show_todos($program_id) {
    $data                    = ['program_id' => $program_id];
    $data['active']          = 'programs';
    $data['active_sub_tab']  = 'todos';
    $data['mobile']          = $this->is_mobile();
    $data['program']         = $this->program_model->get($program_id);
    $data['days_to_fun_run'] = $this->program_model->funrun_days_left($program_id, true);

    $this->load->model('program_todos_model');
    $this->program_model->attach_program_nav_bar_data_to($data);
    $data['todos'] = $this->program_todos_model->get_todos($program_id);

    $data['view'] = 'admin/programs/todos';
    $this->load->view('admin/programs/template', $data);
  }


  /**
   * CSV report of students with no pledges per specified program
   *
   * @param int $program_id
   */
  public function admin_student_no_pledge_report($program_id) {

    $report_data = $this->program_model->get_student_no_pledge_report_data($program_id);

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=" . $program_id . "-registered-no-pledge.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    $out = fopen('php://output', 'w');

    fputcsv(
      $out, [
      'Participant Name',
      'Grade',
      'Class',
      'Parent First Name',
      'Parent Last Name',
      'Parent Email',
      'Parent Phone'
      ]
    );

    foreach ($report_data as $row) {
      fputcsv(
        $out, [
        $row->participant_name,
        $row->grade_name,
        $row->class,
        $row->parent_first_name,
        $row->parent_last_name,
        $row->parent_email,
        $row->parent_phone
        ]
      );
    }

    fclose($out);

  }


  public function merchant_deposit_summary() {
    if ($_POST) {
      $ts_start    = strtotime($this->input->post('ts_start'));
      $ts_end      = strtotime($this->input->post('ts_end'));
      $report_data = $this->program_model->get_merchant_deposit_summary_data($ts_start, $ts_end);
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=merchant-deposit-summary.csv");
      header("Pragma: no-cache");
      header("Expires: 0");
      $out = fopen('php://output', 'w');
      fputcsv(
        $out, [
          'School Name',
          'Programs Salesforce ID',
          'Braintree Deposit Amount (Not pledge amount)',
          'Date of Deposit'
          ]
      );
      //make sure there is data to report
      if ($report_data && $report_data['transactions']) {
        //report all the datas
        foreach ($report_data['transactions'] as $row) {
          fputcsv(
            $out, [$row['school_name'],
                     $row['program_salesforce_id'],
                     $row['settlement_amount'],
                     $row['disbursement_date']]
          );
        }
      }

      fclose($out);
    } else {
      $data['title']    = 'Merchant Transaction Summary';
      $data['form_url'] = '/programs/merchant_deposit_summary';
      $data['view']     = 'admin/programs/programs_summary_report_form';
      $this->load->view('admin/template', $data);
    }
  }


  /**
   * Validation to ensure the Online Processing Fee and the
   * School Processing Fee are the same amount
   *
   * @return bool
   */
  public function school_processing_fee_validation() {

    $optional_sponsor_fee  = floatval($this->input->post('optional_sponsor_fee'));
    $school_processing_fee = floatval($this->input->post('school_processing_fee'));

    if ($optional_sponsor_fee > 0) {
      if ($school_processing_fee != $optional_sponsor_fee) {
        $this->form_validation->set_message(
          'school_processing_fee_validation',
          'If an online processing fee is a added, the school processing fee must be same amount.'
        );
        return false;
      }
    }

    return true;

  }


  public function online_payment_required_check() {
    $online_payment_enabled  = $this->input->post('online_payment_enabled');
    $online_payment_required = $this->input->post('online_payment_required');

    if ($online_payment_enabled !== '1' && $online_payment_required === '1') {
      $this->form_validation->set_message(
        'online_payment_required_check',
        'Online Payments can not be required if online payments are turned off'
      );
      return false;
    }

    return true;
  }


  public function online_payment_check() {
    $online_payment_enabled  = $this->input->post('online_payment_enabled');
    $promote_online_payment  = $this->input->post('promote_pay_online');
    $online_payment_required = $this->input->post('online_payment_required');

    if ($promote_online_payment) {
      //required to have both enabled
      if ($online_payment_enabled) {
        //can't have both enabled at same time
        if ($online_payment_required) {
          $this->form_validation->set_message(
            'online_payment_check',
            '"Promote Pay Online" cannot be selected with "Online Payments Required".'
          );
          return false;
        } else {
          return true;
        }
      } else {
        $this->form_validation->set_message(
          'online_payment_check',
          'Promote Pay Online Now can only be enabled when Online Payments are enabled.'
        );
        return false;
      }
    }

    return true;
  }


  /**
   * Add laps for a student, includes laps batch update.
   * Only numbers b/t 0 and 35, and blank values, and nothing else.
   * If non-numeric, set a NULL, which means "no laps entered yet".
   */
  public function add_units($program_id) {
    $this->load->model('program_model');
    $this->load->library('booster_api');
    $this->load->library('form_validation');

    $data['participants']   = $this->program_model->get_participants_no_units($program_id);
    $unit_id                = $this->program_model->get_unit_id_from_program_id($program_id);
    $data['unit_data']      = $this->booster_api->get_unit_data($unit_id)->data;
    $data['program']        = $this->program_model->basic_program($program_id);
    $data['active']         = 'programs';
    $data['active_sub_tab'] = 'classes';
    $data['hide_autofill']  = true;
    $data['view']           = 'admin/classrooms/add_laps';

    $this->form_validation->set_rules('laps[]', 'Laps', 'callback_laps_check');
    $this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');

    if ($this->form_validation->run() == true) {
      $this->load->model('user_model');
      $update_data = [];

      //get current users and their laps
      $orig_user_laps = $this->user_model->get_many(array_keys($this->input->post('laps')));
      //get list of users to update
      $users_to_update = $this->input->post('laps');
      //loop through and remove user's laps that did not change
      foreach ($orig_user_laps as $laps) {
        if ($laps->laps == $users_to_update[$laps->id]) {
          unset($users_to_update[$laps->id]);
        }
      }

      //loop through and map users to update_data array of objects
      foreach ($users_to_update as $user_id => $laps) {
        if (!(is_numeric($laps) && $laps >= 0)) {
          $laps = null;
        }

        $update_data[] = [
          'id'              => $user_id,
          'laps'            => $laps,
          'ts_laps_entered' => date('Y-m-d h:m:s')  // will set to current time
        ];
      }

      if (count($update_data)) {
        $update_result = $this->db->update_batch('users', $update_data, 'id');
      }

      //build response message.
      if ($update_result) {
        if (!empty($data['participants'])) {
          $program_id = $data['participants'][0]->program_id;

          $data['success'] = '<strong>Success! ' . ucwords($data['unit_data']->name_plural) . ' updated.</strong>';
          //update program todos
          $this->load->model('program_todos_model');
          $this->program_todos_model->update_program_todos_single($program_id);
        }
      } elseif (count($update_data) == 0) {
        $data['warn'] = 'No ' . ucwords($data['unit_data']->name_plural) . ' changes were entered.';
      } else {
        $data['error'] = '' . ucwords($data['unit_data']->name_plural) . ' could not be updated at this time.  Please try again.';
      }
    }

    $data['title']           = 'All Participants with Missing ' . ucwords($data['unit_data']->name_plural);
    $data['subtitle']        = '*All Participants on this page have a pledge per '.$data['unit_data']->name.' and require a value to be entered for '.$data['unit_data']->name_plural.'.';
    $data['form_action_url'] = '/admin/program/add_missing_units/' . $program_id;
    $data['active']          = 'programs';
    $data['active_sub_tab']  = 'classes';
    $data['view']            = 'admin/classrooms/add_laps';

    $this->load->view('admin/programs/template', $data);
  }


  public function laps_check($str) {
    if ($str < 0) {
      $this->form_validation->set_message('laps_check', 'The field value can not be less than 0');
      return false;
    } elseif ($str > 35) {
      $this->form_validation->set_message('laps_check', 'The field value can not be greater than 35');
      return false;
    } elseif (empty($str)) {
      return true;
    } elseif (is_numeric($str) && $str >= 0 && $str <= 35) {
      return true;
    } else {
      $this->form_validation->set_message(
        'laps_check', 'The field value can only ' .
        'be a number between 0 and 35, or left blank'
      );
      return false;
    }
  }


  public function validate_event_name($event_name) {
    $this->form_validation->set_message('validate_event_name', 'The Event Name contains invalid characters.');
    if (preg_match('/[^\x20-\x7e]/', $event_name)) {
      return false;
    } else {
      return true;
    }
  }


  public function reports($program_id) {
    $this->load->model('unit_model');

    $data['active']                  = 'programs';
    $data['active_sub_tab']          = 'reports';
    $data['mobile']                  = $this->is_mobile();
    $data['program']                 = $this->program_model->basic_program($program_id);
    $data['unit']                    = $this->unit_model->get($data['program']->unit_id);
    $data['prize_report_permission'] = $this->program_model->has_prize_report_permission($program_id);

    $data['view'] = 'admin/programs/reports';

    $this->load->view('admin/programs/template', $data);
  }


}
