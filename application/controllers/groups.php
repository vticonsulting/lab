<?php if (! defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Groups extends MY_Controller
{


  public function __construct() {

    parent::__construct();

    $this->load->model('group_model');
    $this->load->helper('admin');

    if (!$this->ion_auth->is_admin()) {
      show_error('You are not authorized to access this page. Return to <a href="' . base_url() . '">login page</a>.', 401);
    }

  }


    /*********************************************************************************************************************

        Delete program

    *********************************************************************************************************************/


  public function delete( $program_id ) {

    if (! $this->ion_auth->is_admin()) {
      show_error('You are not authorized to access this page. Return to <a href="' . base_url() . '">login page</a>.', 401);
    }

     $this->program_model->delete($program_id);

    if ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')
                  ->set_output(json_encode([ 'message' => 'Program deleted', 'status' => 'success' ]));
    }

  }


    /*********************************************************************************************************************

        Program search
        Allows searching through program data

    *********************************************************************************************************************/


  public function search() {

    $results = $this->user_model->search($this->input->post('term'));

    if ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json');

      if (! empty($results)) {
        $this->output->set_content_type('application/json')->set_output(json_encode($results));
      } else {
        $this->output->set_content_type('application/json')->set_output(json_encode('No matches'));
      }
    }

  }


  public function upload_class_file() {
    $this->load->config('aws', true);
    $this->load->library('upload');
    $this->load->library('CSV');
    $s3_class_list_loc = $this->config->item('s3_class_lists', 'aws');
    $this->upload->set_allowed_types('csv');

    $programId = !empty($_POST['program_id']) ? $_POST['program_id'] : null;
    $groupId   = !empty($_POST['group_id']) ? $_POST['group_id'] : null;
    if ($this->upload->do_upload_s3('userfile', $s3_class_list_loc)) {
        $fileInfo           = $this->upload->data();
        $class_endpoint     = s3_url($s3_class_list_loc . $this->upload->data('file_name'));
        $importData         = $this->csv->process_csv($class_endpoint);
        $data['fileInfo']   = $fileInfo;
        $data['importData'] = $importData;
        $data['status']     = 'success';
    } else {
       $data['status'] = 'failed';
    }

    echo json_encode($data);
  }


  public function process_class_file() {
    $this->load->library('CSV');
    $this->load->model('classroom_model');
    $programId  = !empty($_POST['program_id']) ? $_POST['program_id'] : null;
    $groupId    = !empty($_POST['group_id']) ? $_POST['group_id'] : null;
    $filePath   = !empty($_POST['full_path']) ? $_POST['full_path'] : null;
    $importData = $this->csv->process_csv($filePath);
    array_shift($importData);
    $existingClasses = $this->classroom_model->get_filtered_classes_dropdown($programId);
    $existingClasses = !empty($existingClasses) ? $existingClasses : [];
    $classNames      = array_values($existingClasses);
    foreach ($classNames as &$className) {
      $className = strtolower($className);
    }

    //add new classes
    foreach ($importData as $item) {
      //match items based on headers
      $grade = $item[1];
      $name  = $item[0];
      if (in_array(strtolower($name), $classNames)) {
        continue;
      }

      $this->classroom_model->insert_class($groupId, $grade, $name);
    }

    echo json_encode($data);
  }


    /*********************************************************************************************************************

        Populate group through csv student/teacher list
        Allows searching through program data

    *********************************************************************************************************************/


  public function populate($import_id=null) {

    $this->load->library('upload');
    $this->load->config('aws', true);
    $this->upload->set_allowed_types('csv');
    $this->load->library('form_validation');
    $s3_class_list_loc = $this->config->item('s3_class_lists', 'aws');

    $rules = [
      [
        'field' => 'group_id',
        'label' => 'Group',
        'rules' => 'required|is_natural_no_zero'
      ],
    ];

    $this->form_validation->set_rules($rules);

    if ($import_id) {
      // Returning to an existing configured import (e.g. to fix column formats)
      $import = $this->group_model->get_import($import_id);
      if ($import) {
        $data['import_data'] = json_decode($import->import_data, true);
      }
    } elseif ($this->form_validation->run() === false ||
      !$this->upload->do_upload_s3('userfile', $s3_class_list_loc)) {
      $upload_errors = empty($_FILES['userfile']['name']) ? 'You must select a file to upload' : $this->upload->display_errors('', '');
      $this->session->set_flashdata('upload_errors', $upload_errors);
      $this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');
      $this->session->set_flashdata('group_id_error', form_error('group_id'));
      $this->session->set_flashdata('group_id_val', $this->input->post('group_id'));

      redirect($this->input->post('redirect_back') . '#student-code');
    } else {
      //First up, process all data, then present it to the user (let them edit?), then import it.

      $this->load->library('CSV');
      $class_endpoint      = s3_url($s3_class_list_loc . $this->upload->data('file_name'));
      $data['import_data'] = $this->csv->process_csv($class_endpoint);

      log_message('debug', 'csv data: '.print_r($data['import_data'], true));

      $import_id = $this->group_model->store_import($data['import_data'], $class_endpoint, $this->input->post('group_id'));
    }

    $data['message'] = empty($data['import_data']) ? 'No data available. Please double check the file and
               <a href="' . $this->input->post('redirect_back') . '#student-code">try again.</a>' : 'Please define format for each column, then click Preview Import, or
               <a href="' . $this->input->post('redirect_back') . '#student-code"
                  class="btn btn-danger">Cancel</a>';

    $data['format_dropdown'] = $this->group_model->column_format_dropdown();
    $data['field_aliases']   = $this->group_model->get_field_aliases();
    $data['import_id']       = $import_id;
    $data['redirect_back']   = $this->input->post('redirect_back');

    $data['view'] = 'admin/edit_import_layout';
    $this->load->view('admin/template', $data);

  }


    /**
     * Import CSV according to selected column formats. Report any problems
     * found, and allow user to approve result.
     */
  public function preview_import( $import_id ) {

    $this->load->model('group_model');
    $this->load->model('classroom_model');

    // Collect user's format definitions for first eight columns
    if ($this->input->post('column_formats')) { // Hidden field, basically to support separate teacher policy form
      $formats = unserialize($this->input->post('column_formats'));
    } else {
      $formats = [];
      foreach (range(0, 7) as $i) {
        $formats[$i] = (int)$this->input->post('column_format_'.$i);
      }
    }

    // Look for policy parameters to resolve dupe teacher names
    $dupe_teacher_policy = [];
    if ($this->input->post()) {
      foreach ($this->input->post() as $k => $v) {
        if (preg_match('/^policy_(\w+)$/', $k, $matches)) {
          $dupe_teacher_policy[$matches[1]]['policy'] = $v;
        } elseif (preg_match('/^grade_(\w+)$/', $k, $matches)) {
          $dupe_teacher_policy[$matches[1]]['grade'] = $v;
        }
      }
    }

    $data = $this->group_model->preview_import($import_id, $formats, $dupe_teacher_policy);

    if (empty($data['imported_rows'])) {
      $data['message'] = 'Data could not be imported using these column formats.
               Please review errors below then
               <a href="/groups/populate/'.$import_id.'">fix column layout.</a>';
    } else {
      $data['message'] = 'Please review imported data below before clicking Complete Import.'
        . ($data['failed_rows'] ? ' Warning: '.count($data['failed_rows']).' rows had errors, shown below.' : '')
        . ' <a href="' . $this->input->post('redirect_back') . '#student-code" class="btn btn-danger">Cancel</a>';
    }

    $data['import_id']      = $import_id;
    $data['column_formats'] = $formats;
    $data['grades']         = ['' => ''] + $this->classroom_model->get_grades_dropdown(true);
    $data['redirect_back']  = $this->input->post('redirect_back');

    $data['view'] = 'admin/edit_import';
    $this->load->view('admin/template', $data);
  }


     /*********************************************************************************************************************

        Finish import

     *********************************************************************************************************************/


  public function finish_import( $import_id ) {
    $this->load->model('group_model');

    $column_formats      = unserialize($this->input->post('column_formats'));
    $dupe_teacher_policy = unserialize($this->input->post('dupe_teacher_policy'));

    //Returns the group ID it's loading students for
    $import   = $this->group_model->get_import($import_id);
    $group_id = $import->group_id;

    if ($this->group_model->finish_import($import_id, $column_formats, $dupe_teacher_policy)) {
      //Find the corresponding program and load up the edit screen
      $this->session->set_flashdata('message', "Students for group {$group->name} imported.");
    } else {
      // This shouldn't happen, since import was validated in the previous step.
      $this->session->set_flashdata('message', 'Error: No data could be imported.');
    }

    $group = $this->group_model->get($group_id);
    redirect("admin/programs/edit/{$group->program_id}");
  }


  /**
  * "Rollback" group import. 45843181
  */
  public function rollback() {
    $this->load->model('group_model');

    $group_id = $this->input->post('group_id');

    $group = $this->group_model->get($group_id);
    if ($group_id) {
      $this->group_model->delete_participants($this->input->post('group_id'));
      $this->session->set_flashdata('message', "Students, classrooms and teachers for group {$group->name} removed.");
    }

    redirect($group ? "admin/programs/edit/{$group->program_id}" : 'admin/programs');
  }


    /*********************************************************************************************************************

        Update a group

    *********************************************************************************************************************/


  public function update( $group_id ) {

    if (! $this->ion_auth->is_admin()) {//Not in allowed group
      show_error('You are not authorized to access this page. Return to <a href="' . base_url() . '">login page</a>.', 401);
    }

    $this->load->library('form_validation');

    $rules = [
      [
        'field' => 'name',
        'label' => 'Name',
        'rules' => 'trim|max_length[255]'
      ],
      [
        'field' => 'group_level',
        'label' => 'group_level',
        'rules' => 'is_natural'
      ]
    ];

    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === false) {//failed validation or the pic upload
      $this->session->set_flashdata('groups_error_message', validation_errors());
      redirect('/admin/programs/edit/' . $this->input->post('program_id') . '#groups');
    } else {
      $this->load->model('group_model');

      $group_info = ['actual_students_override' => $this->input->post('actual_students') > 0 ? 1 : 0];

      $this->group_model->update($group_id, $group_info);

      $this->session->set_flashdata('group_success', '<div class="alert">Group updated successfully. View <a href="' . site_url('admin/programs/' . $this->input->post('program_id')) . '">group&rsquo;s program</a></div>');

      redirect('admin/programs/edit/' . $this->input->post('program_id'));
    }

  }


    /*********************************************************************************************************************

        Callback function to ensure they passed an existing user for owner and point person

    *********************************************************************************************************************/


  public function exists( $value, $table_field ) {

    $table_field = explode('.', $table_field);
    $query       = $this->db->where($table_field[1], $value)->get($table_field[0]);

    return $query->num_rows() > 0 ? true : false;

  }


}
