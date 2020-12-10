<?php if (! defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class User_Tasks extends MY_Controller
{

  // Set the default JSON response
  private $json_response = ['success' => false];


  /**
   * Class Construct
   */
  public function __construct() {

    parent::__construct();
    $this->load->model('user_tasks_model');
    $this->load->model('user_task_list_model');
    $this->load->model('user_task_template_model');

  }


  /**
   * Main Task Lists Administration Page
   *
   * @return response
   */
  public function admin_task_lists_show() {

    $data['task_lists'] = $this->user_task_list_model->get_all(1000);
    $data['view']       = 'admin/tasks/task_lists';

    $this->load->view('admin/template', $data);

  }


  /**
   * Create a New Task Template
   *
   * @return response
   */
  public function admin_task_list_update($task_list_id) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $task_data = [
        'name' => $this->input->post('name'),
      ];

      $update_result = $this->user_task_list_model->update($task_list_id, $task_data);

      if ($update_result) {
        $this->session->set_flashdata('message', 'The task List has been successfully updated!');
      } else {
        $this->session->set_flashdata('error', 'There was a promlem attempting to update this task list! Please try again..');
      }

      redirect('/admin/user_tasks/lists/' . $task_list_id . '/edit');
    } else {
      exit('Invalid Request Method');
    }

  }


  /**
   * Create a New Task Template
   *
   * @return response
   */
  public function admin_list_store() {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $list_data = [
        'name' => $this->input->post('name')
      ];

      $store_result = $this->user_task_list_model->store($list_data);

      if ($store_result) {
        $this->session->set_flashdata('message', 'The Task List Has Been Successfully Created!');
      } else {
        $this->session->set_flashdata('error', 'There was a promlem attempting to create this task list! Please try again..');
      }

      redirect('/admin/user_tasks/lists');
    } else {
      exit('Invalid Request Method');
    }

  }


  /**
   * Edit a specified Task List
   * @param in $task_list_id - The ID of the specified task list
   * @return response
   */
  public function admin_list_edit($task_list_id) {
    $this->load->config('user_tasks');
    $data['task_list']           = $this->user_task_list_model->get($task_list_id);
    $data['available_templates'] = $this->user_task_template_model->get_available_templates($task_list_id);
    $data['current_templates']   = $this->user_task_template_model->get_list_templates($task_list_id);
    $data['view']                = 'admin/tasks/edit_task_list';

    $this->load->view('admin/template', $data);

  }


  /**
   * Add a task template to a specified Task List
   * @param in $task_list_id - The ID of the specified task list
   * @return response
   */
  public function add_task_to_list($task_list_id) {

    $this->load->model('user_task_list_model');

    $store_result = $this->user_task_list_model->add_task_template_to_list($task_list_id);

    if ($store_result) {
      $this->session->set_flashdata('message', 'The task template has been successfully added to this list!');
    } else {
      $this->session->set_flashdata('error', 'There was a promlem attempting to add this task template! Please try again..');
    }

    redirect('/admin/user_tasks/lists/' . $task_list_id . '/edit');

  }


  /**
   * Dekete a Task List and Associated Tasks
   *
   * @return response
   */
  public function admin_list_delete($task_list_id) {

    $delete_result = $this->user_task_list_model->delete($task_list_id);

    if ($delete_result) {
      $this->session->set_flashdata('message', 'The task list has been successfully deleted!');
    } else {
      $this->session->set_flashdata('error', 'There was a promlem attempting to delete this task list! Please try again..');
    }

    redirect('/admin/user_tasks/lists');

  }


  /**
   * Remove a task template to a specified Task List
   * @param in $task_list_id - The ID of the specified task list
   * @return response
   */
  public function remove_task_from_list($task_list_id, $task_template_id) {

    $delete_result = $this->user_task_list_model->remove_task_template_from_list($task_list_id, $task_template_id);

    if ($delete_result) {
      $this->session->set_flashdata('message', 'The task template has been successfully removed to this list!');
    } else {
      $this->session->set_flashdata('error', 'There was a promlem attempting to remove this task template! Please try again..');
    }

    redirect('/admin/user_tasks/lists/' . $task_list_id . '/edit');

  }


  /**
   * Main Task Template Administration Page
   *
   * @return response
   */
  public function admin_templates_show() {

    $this->load->model('user_task_template_model');
    $this->load->config('user_tasks');

    $data['templates'] = $this->user_task_template_model->get_task_templates(1000);
    $data['ck_editor'] = true;
    $data['view']      = 'admin/tasks/index';

    $this->load->view('admin/template', $data);

  }


  /**
   * Create a New Task Template
   *
   * @return response
   */
  public function admin_template_store() {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $task_data = [
        'title' => $this->input->post('task_title'),
        'label' => $this->input->post('task_label')
      ];

      $store_result = $this->user_task_template_model->store($task_data);

      if ($store_result) {
        $this->session->set_flashdata('message', 'The Task Template Has Been Successfully Created!');
      } else {
        $this->session->set_flashdata('error', 'There was a promlem attempting to create this task template! Please try again..');
      }

      redirect('/admin/user_tasks/templates');
    } else {
      exit('Invalid Request Method');
    }

  }


  /**
   * Create a New Task Template
   *
   * @return response
   */
  public function admin_template_update($task_template_id) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $task_data = [
        'title' => $this->input->post('task_title'),
        'label' => $this->input->post('task_label')
      ];

      $update_result = $this->user_task_template_model->update($task_template_id, $task_data);

      if ($update_result) {
        $this->session->set_flashdata('message', 'The Task Template Has Been Successfully Updated!');
      } else {
        $this->session->set_flashdata('error', 'There was a promlem attempting to update this task template! Please try again..');
      }

      redirect('/admin/user_tasks/templates');
    } else {
      exit('Invalid Request Method');
    }

  }


  /**
   * Delete a Task Template
   *
   * @return response
   */
  public function admin_template_delete($task_template_id) {

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
      $delete_result = $this->user_task_template_model->delete($task_template_id);

      if ($delete_result) {
        $this->json_response['success'] = true;
      }

      $this->output
           ->set_content_type('application/json')
           ->set_output(json_encode($this->json_response));
    } else {
      exit('Invalid Request Method');
    }

  }


  /**
   * Create a program task
   * @param int $program_id - The ID of the program to add the task for
   *
   * return response
   */
  public function add_program_task($program_id) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $task_data = [
        'program_id'         => $program_id,
        'type'               => 'Program',
        'title'              => $this->input->post('title'),
        'due_date'           => $this->input->post('due_date'),
        'created_by_user_id' => $this->ion_auth->logged_in(),
        'created_at'         => date('Y-m-d h:m:s'),
        'updated_at'         => date('Y-m-d h:m:s')
      ];

      $result     = $this->user_tasks_model->store($task_data);
      $return_tab = $this->input->post('return_tab');
      $return_to  = ($return_tab) ? '?returnto=' . $return_tab : null;
      redirect('/admin/programs/dashboard/' . $program_id . $return_to . '#tasks');
    } else {
      exit('Invalid Request Method');
    }

  }


  /**
   * Update a specified program task
   * @param  int $program_id - The ID of the program to add the task for
   * @param  int $task_id - The ID of the task
   *
   * @return response
   */
  public function update_program_task($program_id, $task_id) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $task_data = [
        'title'      => $this->input->post('title'),
        'due_date'   => $this->input->post('due_date'),
        'updated_at' => date('Y-m-d h:m:s'),
       ];

       $result = $this->user_tasks_model->update($task_id, $task_data);

       $return_tab = $this->input->post('return_tab');
       $return_to  = ($return_tab) ? '?returnto=' . $return_tab : null;
       redirect('/admin/programs/dashboard/' . $program_id . $return_to . '#tasks');
    } else {
      exit('Invalid Request Method');
    }

  }


  public function reschedule_tasks() {
    $due_date   = $this->input->post('due_date');
    $task_ids   = $this->input->post('task_ids');
    $program_id = $this->input->post('program_id');

    foreach ($task_ids as $task_id) {
      $result = $this->user_tasks_model->update($task_id, ['due_date' => $due_date]);
    }

    $return_tab = $this->input->post('return_tab');
    $return_to  = ($return_tab) ? '?returnto=' . $return_tab : null;
    redirect('/admin/programs/dashboard/' . $program_id . $return_to . '#tasks');
  }


  /**
   * Mark a specified task as open
   * @param  int $task_id - The ID of the task
   *
   * @return void
   */
  public function mark_task_open($task_id) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $task_data = [
        'completed_on_date'    => null,
        'completed_by_user_id' => null
      ];

      $query_result = $this->user_tasks_model->update($task_id, $task_data);

      if ($query_result) {
        $this->json_response['success'] = true;
      }

      $this->output
           ->set_content_type('application/json')
           ->set_output(json_encode($this->json_response));
    } else {
      exit('Invalid Request Method');
    }

  }


   /**
   * Mark a specified task as complete
   * @param  int $task_id - The ID of the task
   *
   * @return void
   */
  public function mark_task_completed($task_id) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $task_data = [
        'completed_on_date'    => date('Y-m-d H:i:s'),
        'completed_by_user_id' => $this->ion_auth->logged_in()
      ];

      $query_result = $this->user_tasks_model->update($task_id, $task_data);

      if ($query_result) {
        $this->json_response['success'] = true;
      }

      $this->output
           ->set_content_type('application/json')
           ->set_output(json_encode($this->json_response));
    } else {
      exit('Invalid Request Method');
    }

  }


  /**
   * Mark a specified task as complete
   * @param  int $task_id - The ID of the task
   *
   * @return void
   */
  public function mark_task_deleted($task_id) {

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
      $query_result = $this->user_tasks_model->delete($task_id);

      if ($query_result) {
        $this->json_result['success'] = true;
      }

      $this->output
           ->set_content_type('application/json')
           ->set_output(json_encode($this->json_result));
    } else {
      exit('Invalid Request Method');
    }

  }


  /**
   * Mark tasks as deleted from posted task_ids
   *
   * @return void
   */
  public function mark_tasks_deleted() {
    $task_ids = $this->input->post('task_ids');
    if ($task_ids) {
      $query_result = $this->user_tasks_model->delete_many($task_ids);
    }

    $this->json_result['success'] = false;
    if ($query_result) {
      $this->json_result['success'] = true;
    }

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($this->json_result));
  }


  /**
   * Import all tasks associated with a specified task list to a specificied program
   * @param  int $program_id - The ID of the program to add the task for
   *
   * @return response
   */
  public function import_task_list_to_program($program_id) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $import_result = $this->user_tasks_model->inport_from_task_list($program_id, $this->input->post('task_list_id'));

      if ($import_result) {
        $this->session->set_flashdata('message', 'The task list has been successfully imported!');
      } else {
        $this->session->set_flashdata('error', 'There was a promlem attempting to import this task list! Please try again..');
      }

      redirect('/admin/programs/dashboard/' . $program_id . '#tasks');
    } else {
      exit('Invalid Request Method');
    }

  }


}
