<?php if (! defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class User_Groups extends MY_Controller
{

  protected $protected_groups = [ 'admin', 'members' ];


  public function __construct() {

    parent::__construct();

    $this->load->model('user_group_model');

    $this->load->library('pagination');
    $this->config->load('pagination'); //So we can keep a uniform look across sections
    $this->load->helper('admin');

  }


    /*********************************************************************************************************************

        User groups management screen

    *********************************************************************************************************************/


  public function admin_show() {

    $this->load->model('user_model');
    $this->load->model('user_group_model');

    $data['group_types']             = $this->user_group_model->types_for_dropdown();
    $data['user_groups_type_select'] = $this->user_group_model->types_for_dropdown(true);
    $data['user_groups']             = $this->user_group_model->get_page($this->config->item('per_page'), $this->uri->rsegment(3));

    $config['base_url']   = '/admin/user_groups';
    $config['total_rows'] = $this->user_group_model->count_rows();

    $this->pagination->initialize($config);

    $data['pagination'] = $this->pagination->create_links();

    $data['active'] = 'user_groups';
    $data['view']   = 'admin/user_groups';

    $this->load->view('admin/template', $data);

  }


    /*********************************************************************************************************************

        Edit user group form

    *********************************************************************************************************************/


  public function admin_edit( $user_group_id ) {

    $this->load->model('user_model');
    $this->load->model('user_group_model');

    $data['user_group']  = $this->user_group_model->get($user_group_id);
    $data['users']       = $this->user_group_model->get_users($user_group_id);
    $data['group_types'] = $this->user_group_model->types_for_dropdown();

    $data['active'] = 'user_groups';
    $data['view']   = 'admin/edit_user_group';

    $this->load->view('admin/template', $data);

  }


    /*********************************************************************************************************************

      User groups management screen, filtered by type

    *********************************************************************************************************************/


  public function admin_show_by_type( $group_type = 'internal' ) {

    $this->load->model('user_group_model');

    $data['group_types'] = $this->user_group_model->types_for_dropdown();

    if (strtolower($group_type) === 'all') {
      $data['user_groups'] = $this->user_group_model->order_by('name')->get_all();
    } else {
      $data['user_groups'] = $this->user_group_model->get_many_by('type', $group_type);
    }

    $data['user_groups_type_select'] = $this->user_group_model->types_for_dropdown(true);

    $data['user_type'] = ucwords($group_type);

    $data['active'] = 'user_groups';
    $data['view']   = 'admin/user_groups';

    $this->load->view('admin/template', $data);

  }


    /*********************************************************************************************************************

      Delete user group

    *********************************************************************************************************************/


  public function delete( $user_group_id ) {

    if (! $this->ion_auth->is_admin()) {
      show_error('You are not authorized to access this page. Return to <a href="' . base_url() . '">login page</a>.', 401);
    }

    if ($this->is_protected($user_group_id)) {
      $this->output->set_content_type('application/json')
                   ->set_output(json_encode([ 'message' => 'This is a protected group and cannot be deleted', 'status' => 'error' ]));
      return;
    }

    $this->user_group_model->delete($user_group_id);

    if ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')
                  ->set_output(json_encode([ 'message' => 'User group deleted', 'status' => 'success' ]));
    }

  }


    /*********************************************************************************************************************

        Display user group and related data

    *********************************************************************************************************************/


  public function view( $user_id = null ) {

    $user         = $this->ion_auth->user($user_id)->row(); //If left at NULL, it returns the currently logged on user
    $user->groups = $this->ion_auth->get_users_groups($user_id)->result();

    if ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode($user));
    } else {
      $data['user'] = $user;
      $data['view'] = 'admin/profile';

      $this->load->view('admin/template', $data);
    }

  }


    /*********************************************************************************************************************

        Quick create
        Route: /admin/user/quick_create

    *********************************************************************************************************************/


  public function quick_create() {

    if (! $this->ion_auth->is_admin()) {
      show_error('You are not authorized to access this page. Return to <a href="' . base_url() . '">login page</a>.', 401);
    }

    $this->load->library('form_validation');

    $rules = [
      [
        'field' => 'name',
        'label' => 'Name',
        'rules' => 'trim|required|is_unique[user_groups.name]|max_length[20]'
      ],

      [
        'field' => 'description',
        'label' => 'Description',
        'rules' => 'required|trim|max_length[100]'
      ],

      [
        'field' => 'type',
        'label' => 'Type',
        'rules' => '',
      ]
    ];

    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === false) {
      $this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');

      $errors = [
                      'name' => form_error('name'),
                      'description' => form_error('description'),
                      'type' => form_error('type') ];

      $this->output->set_content_type('application/json')->set_output(json_encode([  $errors ]));
    } else {
      $this->load->model('user_group_model');

      $user_group_info = [ 'name' => $this->input->post('name'),
                                'description' => $this->input->post('description'),
                                'type' => $this->input->post('type') ];

      $user_group_id = $this->user_group_model->insert($user_group_info);

      $this->output->set_content_type('application/json')->set_output(json_encode([ $user_group_id ]));
    }

  }


    /*********************************************************************************************************************

        Edit user group

    *********************************************************************************************************************/


  public function edit( $user_group_id ) {

    if (! $this->ion_auth->is_admin()) {
      if ($user_id !== $this->ion_auth->user()->row()->id) { //If attempting to edit anything other than their own profile
        show_error('You are not authorized to access this page. Return to <a href="' . base_url() . '">login page</a>.', 401);
      }
    }

    $this->load->library('form_validation');
    $this->load->library('upload');

    $rules = [
      [
        'field' => 'name',
        'label' => 'Name',
        'rules' => 'trim|required|max_length[20]'
      ],
      [
        'field' => 'description',
        'label' => 'Description',
        'rules' => 'trim|required|max_length[100]'
      ],
      [
        'field' => 'type',
        'label' => 'Type',
        'rules' => ''
      ],
    ];

    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === false) {
      $this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');

      $this->load->model('user_group_model');

      $data['user_group']  = $this->user_group_model->get($user_group_id);
      $data['users']       = $this->user_group_model->get_users($user_group_id);
      $data['group_types'] = $this->user_group_model->types_for_dropdown();

      $data['active'] = 'user_groups';
      $data['view']   = 'admin/edit_user_group';

      $this->load->view('admin/template', $data);
    } else {
      $user_group_info = [ 'name' => $this->input->post('name'),
                               'description' => $this->input->post('description'),
                               'type' => $this->input->post('type'),
                             ];

      //If this is a protected group, don't update the name
      if ($this->is_protected($user_group_id)) {
        unset($user_group_info['name']);
      }

      $this->user_group_model->update($user_group_id, $user_group_info);

      $this->session->set_flashdata('success', '<div class="alert">User group updated successfully.</div>');

      redirect('admin/user_groups/edit/' . $user_group_id);
    }

  }


    /*********************************************************************************************************************

        Retrieve team members

    *********************************************************************************************************************/


  public function team_members( $team_id ) {

    $members = $this->user_group_model->get_users($team_id, 1000, 0);

    $this->output->set_content_type('application/json')
                 ->set_output(json_encode($members));

  }


    /*********************************************************************************************************************

        Check whether it's a protected group

    *********************************************************************************************************************/


  protected function is_protected( $user_group_id ) {

    foreach ($this->protected_groups as $pg) {
      $protected_ids[] = $this->user_group_model->get_by('name', $pg)->id;
    }

    return ( in_array($user_group_id, $protected_ids) ) ? true : false;

  }


    /*********************************************************************************************************************

        Update User-User Groups relationships

    *********************************************************************************************************************/


  public function update_relationships() {

    $this->user_group_model->update_relationships();

  }


}
