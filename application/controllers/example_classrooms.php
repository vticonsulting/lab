<?php if (! defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Classrooms extends MY_Controller
{


  public function __construct() {
    parent::__construct();

    $this->load->model('classroom_model');
    $this->load->helper('admin');
  }


  public function admin_show() {
    $data['active']         = 'programs';
    $data['active_sub_tab'] = 'classes';
    $this->load->model('user_group_model');
    $data['user_group_names']            = $this->user_group_model->get_names_dropdown(false);
    $data['data_table']['id']            = 'classes-table';
    $data['data_table']['sort']          = "[0, 'asc']";
    $data['data_table']['url']           = "classes/ajax_get_classrooms";
    $data['data_table']['column_filter'] = json_encode(
      ['sPlaceHolder' => "head:after",
        'aoColumns' => [['type' => "text", 'iFilterLength' => 3], //first name
            ['type' => "text", 'iFilterLength' => 3], //last name
            null, //view action
        ]]
    );

    $data['view'] = 'admin/classrooms/index';
    $this->load->view('admin/template', $data);

  }


  /*********************************************************************************************************************

    Show one program overview

  *********************************************************************************************************************/


  public function show_single_overview($classroom_id) {
      $this->load->model('user_group_model');
      $this->load->model('program_model');

      $data['program'] = $this->program_model->get_program_for_class($classroom_id);
      $program_id      = $data['program']->id;

      $this->program_model->attach_program_nav_bar_data_to($data);

      $data['active']                      = 'programs';
      $data['active_sub_tab']              = 'classes';
      $data['user_group_names']            = $this->user_group_model->get_names_dropdown(false);
      $data['data_table']['id']            = 'users-table';
      $data['data_table']['sort']          = "[0, 'asc']";
      $data['data_table']['url']           = "ajax_get_participants/{$classroom_id}";
      $data['data_table']['column_filter'] = json_encode(
        ['sPlaceHolder' => 'head:after',
          'aoColumns' => [
              null, //view action
              ['type' => 'text', 'iFilterLength' => 3], //first name
              ['type' => 'text', 'iFilterLength' => 3], //last name
              null, //grade
              null, //the class
              null, //access code
              ['type' => 'select', 'values' => array_values($data['user_group_names'])], // user groups drop dropdown
              null, //program group
              null, //laps
          ]]
      );
      $data['class_id']                    = $classroom_id;
      $data['footer_js']                   = ['jquery.jeditable.mini.js', 'jquery.validate.min.js', 'admin/edit_laps.js'];

      $data['view'] = 'admin/classrooms/users';
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


  /**
   * Add laps for a student, includes laps batch update.
   * Only numbers b/t 0 and 35, and blank values, and nothing else.
   * If non-numeric, set a NULL, which means "no laps entered yet".
   */
  public function add_laps($classroom_id) {
    $this->load->model('classroom_model');
    $this->load->model('program_model');
    $this->load->library('form_validation');
    $this->load->library('booster_api');
    $this->load->model('program_pledge_settings_model');
    $this->load->library('session');

    $datatables                      = false;
    $data['participants']            = $this->classroom_model->ajax_get_participants($classroom_id, $datatables);
    $data['class_id']                = $classroom_id;
    $unit_id                         = $this->program_model->get_unit_id_from_program_id($data['participants'][0]->program_id);
    $data['unit_data']               = $this->booster_api->get_unit_data($unit_id)->data;
    $program_id                      = $this->classroom_model->get_program_id($classroom_id);
    $data['program_pledge_settings'] = $this->program_pledge_settings_model->get_by('program_id', $program_id);

    $this->form_validation->set_rules('laps[]', 'Laps', 'callback_laps_check');
    $this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');

    if ($this->form_validation->run() == true && $data['program_pledge_settings']->flat_donate_only !== '1') {
      $this->load->model('user_model');
      $update_data = [];

      //get current users and their laps
      $orig_user_laps = $this->user_model->get_many(array_keys($this->input->post('laps')));
      //get list of users to update
      $users_to_update = $this->input->post('laps');
      //loop through and set laps that have changed
      foreach ($orig_user_laps as $laps) {
        if ($laps->laps != $users_to_update[$laps->id]) {
          if (!(is_numeric($users_to_update[$laps->id]) && $users_to_update[$laps->id] >= 0)) {
            $users_to_update[$laps->id] = null;
          }

          $original_laps_count = ($laps->original_laps_count) ? $laps->original_laps_count : $users_to_update[$laps->id];
          $current_date_time   = date('Y-m-d H:i:s');

          $update_data[] = [
                  'id'                       => $laps->id,
                  'laps'                     => $users_to_update[$laps->id],
                  'ts_laps_entered'          => $current_date_time,
                  'laps_modified_ts'         => $current_date_time,
                  'original_laps_count'      => $original_laps_count,
                  'laps_modified_by_user_id' => $this->session->userdata('user_id'),
          ];
        }
      }

      $program_id = null;

      if (count($update_data)) {
        $update_result = $this->db->update_batch('users', $update_data, 'id');
      }

      //build response message.
      if ($update_result) {
        if (!empty($data['participants'])) {
          $program_id = $data['participants'][0]->program_id;
          $grade      = $data['participants'][0]->grade_name;
          $class      = $data['participants'][0]->class;

          $data['success'] = '<strong>Success! ' . ucwords($data['unit_data']->name_plural) . ' updated for ' .
                $class . ' - ' . $grade . ' grade class.<a href="/admin/programs/classes/' . $program_id . '"> View All Classes.</a></strong>';

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

    //find teachers
    $teacherIds = [];
    $teacherArr = [];
    $program_id = $this->classroom_model->get_program_id($classroom_id);
    $teachers   = $this->program_model->get_teachers_in_program($program_id);

    if (!empty($teachers)) {
      foreach ($teachers as $teacher) {
        $teacherIds[] = $teacher->user_id;
      }
    }

    foreach ($data['participants'] as $key => $participant) {
        //get the ids for all the teachers;
      if (in_array($participant->id, $teacherIds)) {
        $participant->isTeacher = true;
        $teacherArr[]           = $participant;
        unset($data['participants'][$key]);
      }

        //assign 0 laps if zero laps were entered
      if (! empty($users_to_update) && in_array($participant->id, array_keys($users_to_update))) {
        $participant->laps = $users_to_update[$participant->id];
      }
    }

    $data['participants']   = array_merge($teacherArr, $data['participants']);
    $data['program']        = $this->program_model->basic_program($program_id);
    $data['active']         = 'programs';
    $data['active_sub_tab'] = 'classes';
    $data['footer_js']      = 'admin/autofill_laps.js';
    $data['view']           = 'admin/classrooms/add_laps';

    $this->load->view('admin/programs/template', $data);
  }


  public function ajax_get_participants($classroom_id = null) {

    $data['json'] = $this->classroom_model->ajax_get_participants($classroom_id);
    $this->load->view('admin/ajax', $data);

  }


  public function ajax_get_classrooms() {

    $data['json'] = $this->classroom_model->ajax_get_classrooms();
    $this->load->view('admin/ajax', $data);

  }


   // Delete program


  public function admin_delete($classroom_id) {
    $this->load->model('manual_payment_model');
    $students = $this->classroom_model->get_students($classroom_id);
    if (!empty($students)) {
      foreach ($students as $key => $student) {
        if (!empty($student->deleted)) {
          unset($students[$key]);
        }
      }
    }

    $data['studentsExist'] = !empty($students) ? true : false;

    $payments              = $this->manual_payment_model->get_payments_by_classroom_id($classroom_id);
    $data['paymentsExist'] = !empty($payments) ? true : false;

    if (empty($data['studentsExist']) && empty($data['paymentsExist'])) {
      $this->classroom_model->delete($classroom_id);
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success']));
    } else {
      $this->output->set_content_type('application/json')->set_output(json_encode(['data' => $data, 'status' => 'failed']));
    }
  }


  /*********************************************************************************************************************

  Edit a classroom

  *********************************************************************************************************************/


  public function admin_edit($classroom_id) {

    $this->load->model('program_pledge_settings_model');

    $program_id = $this->classroom_model->get_program_id($classroom_id);

    $data['program_pledge_settings'] = $this->program_pledge_settings_model->get_by('program_id', $program_id);
    $data['grades']                  = $this->classroom_model->get_grades_dropdown(true);
    $data['classroom']               = $this->classroom_model->get_full_classroom($classroom_id);
    $data['classroom']               = $data['classroom'][0];
    $data['active']                  = 'programs';
    $data['active_sub_tab']          = 'classes';
    $data['view']                    = 'admin/classrooms/edit';
    $data['footer_js']               = 'admin/classrooms/edit.js';
    $this->load->view('admin/template', $data);

  }


  public function admin_update($classroom_id) {

    $this->load->library('form_validation');

    $rules = [
        [
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'required|trim|max_length[100]'
        ],
        [
            'field' => 'number_of_participants',
            'label' => '# of Team Members',
            'rules' => 'integer'
        ],
        [
            'field' => 'pledge_meter',
            'label' => 'Pledge O\'Meter',
            'rules' => 'numeric'
        ],

        [
            'field' => 'grade_id',
            'label' => 'Grade',
            'rules' => 'required|max_length[2]'
        ]
    ];

    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === false) {//failed validation or the pic upload
      $this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');

      $data['grades']         = $this->classroom_model->get_grades_dropdown(true);
      $data['classroom']      = $this->classroom_model->get_full_classroom($classroom_id);
      $data['classroom']      = $data['classroom'][0];
      $data['active']         = 'programs';
      $data['active_sub_tab'] = 'classes';
      $data['view']           = 'admin/classrooms/edit';
      $this->load->view('admin/template', $data);
    } else {
      $class_info = [
        'name' => $this->input->post('name'),
        'grade_id' => $this->input->post('grade_id'),
        'number_of_participants' => $this->input->post('number_of_participants'),
        'pledge_meter' => $this->input->post('pledge_meter')
      ];

      $this->classroom_model->update($classroom_id, $class_info);

      $this->session->set_flashdata('success', '<div class="alert">Classroom updated. View <a href="' . site_url('admin/classes/' . $classroom_id) . '">classroom</a></div>');

      redirect('admin/classes/edit/' . $classroom_id);
    }
  }


  public function add_participant($classroom_id, $program_id = null) {
    $this->load->library('form_validation');
    $this->load->model('program_model');

    $rules = [
      [
          'field' => 'first_name',
          'label' => 'First Name',
          'rules' => 'required|trim|max_length[100]'
      ],
      [
          'field' => 'last_name',
          'label' => 'Last Name',
          'rules' => 'required|trim|max_length[100]'
      ],
      [
          'field' => 'classroom_id',
          'label' => 'Classroom',
          'rules' => 'required|trim|numeric'
      ]
    ];

    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === false) {//failed validation or the pic upload
      $this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');

      // for handling add participant from participants page, where
      // a classroom is not passed (not known yet)
      if ($classroom_id) {
        $data['program'] = $this->program_model->get_program_for_class($classroom_id);
      } elseif ($program_id) {
        $data['program'] = $this->program_model->get($program_id);
      }

      $data['program_id'] = $data['program']->id;
      $this->program_model->attach_program_nav_bar_data_to($data);
      $data['classrooms']     = $this->program_model->get_classrooms_dropdown_in_program($data['program']->id);
      $data['classroom_id']   = $classroom_id;
      $data['classroom']      = $this->classroom_model->get_full_classroom($classroom_id);
      $data['classroom']      = $data['classroom'][0];
      $data['active']         = 'programs';
      $data['active_sub_tab'] = 'classes';
      $data['view']           = 'admin/classrooms/add_participant';
      $this->load->view('admin/programs/template', $data);
    } else {
      $firstname                    = $this->input->post('first_name');
      $lastname                     = $this->input->post('last_name');
      $classroom_id                 = $this->input->post('classroom_id');
      $full_name                    = $firstname . ' ' . $lastname;
      $data['classroom']            = $this->classroom_model->get_full_classroom($classroom_id);
      $data['program']              = $this->program_model->get_program_for_class($classroom_id);
      $data['classroom']            = $data['classroom'][0];
      $program_id                   = $data['program']->id;
      $program_collection_refer_key = $this->program_model->get_collection_refer_key($data['program']->id);
      $data['active']               = 'programs';
      $data['active_sub_tab']       = 'classes';
      $this->load->model('user_model');

      if ($_POST['type'] == 'Student') {
        $student_temp     = new StdClass();
        $student_temp->id = $this->user_model->create_student($lastname, $firstname, $classroom_id);
        $this->load->model('prize_bound_model');
        $this->prize_bound_model->allocate_prizes_retroactive_group($data['classroom']->group, [$student_temp]);
        $this->add_participant_success($classroom_id, $student_temp->id, $full_name, $program_collection_refer_key);
        redirect('admin/classes/add_participant/' . $classroom_id);
      }

      if ($_POST['type'] == 'Teacher') {
        $teacher_id = $this->user_model->create_teacher($lastname, $firstname, $classroom_id);
        if ($teacher_id) {
          $teacher_temp     = new StdClass();
          $teacher_temp->id = $teacher_id;
          $this->load->model('prize_bound_model');
          $this->prize_bound_model->allocate_prizes_retroactive_group($data['classroom']->group, [$teacher_temp]);
          $this->add_participant_success($classroom_id, $teacher_id, $full_name, $program_collection_refer_key);
        } else {
          $this->session->set_flashdata(
            'error',
            'Sorry, this teacher cannot be added since you have already ' .
            'reached your limit of 3 teachers in a class.'
          );
        }

        redirect('admin/classes/add_participant/' . $classroom_id);
      }
    }

  }


  /**
   * Creates a success message used after creating a teacher or student from locker
   * @param type $classroom_id id of classroom
   * @param type $user_id id of user
   * @param type $full_name user's full name first and last with space
   * @param type $program_collection_refer_key collection refer key for a program
   */
  private function add_participant_success($classroom_id, $user_id, $full_name, $program_collection_refer_key) {
    unset($_POST);
    $this->session->set_flashdata(
      'success', '<strong>Great Success!</strong> ' .
         $full_name . ' was added. View <a href="' .
          site_url('admin/classes/' . $classroom_id) . '">Classroom</a>.' .
          '  View <a href="' .
          site_url('admin/users/profile/' . $user_id) .
          '">Participant</a>.  Add <a href="' .
          site_url('admin/pledges/new/' . $user_id) .
          '">Pledge</a>. Add <a href="' .
          site_url('programs/collect_payment/' . $program_collection_refer_key . '/' . $classroom_id . '/' . $user_id) .
      '">Payment</a> on collection site.'
    );
  }


  /**
   * Retrieves JSON for Total Pledges / Payments Stacked Bar Graph
   *
   * @param program_id - ID of program
   */
  public function ajax_total_pledges_payments_graph($classroom_id) {
    $this->load->model('program_model');
    $program_id       = $this->classroom_model->get_program_id($classroom_id);
    $pledges_payments = $this->program_model
      ->get_program_pledge_and_payment_totals($program_id, $classroom_id);

    $this->output->set_content_type('application/json')
      ->set_output($pledges_payments);
  }


  /**
   * Returns ajax response with grouped classes by grade
   * @param $program_id - id of program
   */
  public function ajax_get_grouped_classes_by_grade($program_id) {
    $grade_groups = $this->classroom_model->group_classes_by_grade($program_id);

    $this->output->set_content_type('application/json')
      ->set_output(json_encode($grade_groups));
  }


}
