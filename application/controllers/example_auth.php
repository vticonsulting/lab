<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{

  private $is_mobile;


  public function __construct() {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->library('session');
    $this->load->library('ion_auth');
    $this->load->helper('url');
    $this->load->library('user_agent');
    if ($this->agent->is_mobile()) {
      $this->is_mobile = true;
    }
  }


  public function index() {
    if (!$this->ion_auth->logged_in()) {
      //redirect them to the login page
      redirect('auth/login');
    } elseif (!$this->ion_auth->is_admin()) {
      //redirect them to the home page because they must be an administrator to view this
      redirect($this->config->item('base_url'));
    } else {
      //set the flash data error message if there is one
      $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

      //list the users
      $this->data['users'] = $this->ion_auth->users()->result();
      foreach ($this->data['users'] as $k => $user) {
        $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
      }

      $this->load->view('auth/index', $this->data);
    }
  }


  public function login_rate_maxed() {
    return $this->load->view('front/login_rate_maxed');
  }


  public function beta_landing() {
    if (getenv('TITAN_SIGNUP_KILL_SWITCH') === 'true') {
      //set cookie expire 10 years from now
      setcookie('use_new_signup_page', false, time() + (10 * 365 * 24 * 60 * 60), '/', '.boosterthon.com');
      header('Location: ' . site_url('/login'));
      exit();
    }

    //set cookie expire 10 years from now
    setcookie('use_new_signup_page', true, time() + (10 * 365 * 24 * 60 * 60), '/', '.boosterthon.com');
    header('Location: ' . site_url('/v3'));
    exit();
  }


  public function login_email($basic_login = false) {
    //check if school is attempting to login
    if (is_numeric($this->input->post('identity'))) {
      $this->login_collection();
    }

    $this->form_validation->set_rules('identity', 'Username', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');

    if ($this->input->post('login') || $basic_login) {
      $this->_login_common('login', null, $this->input->get('email'), null, null, null);
    } else {
      $this->_login_common('login_email', null, $this->input->get('email'), null, null, null);
    }
  }


  public function login( $fr_code=null,
                  $redirect_dashboard=null,
                  $pledge=false,
                  $register_part=false,
                  $post_login_url = false) {

    $this->form_validation->set_rules('fr_code', 'Access Code', 'required');
    $this->_login_common(
      'login', $fr_code, null, $redirect_dashboard,
      $pledge, $register_part, $post_login_url
    );

  }


  public function login_titan($user_id, $access_token) {

    $this->load->library('session');
    $this->load->model('booster_login_attempt_model');
    $metadata_array = [
      'user_id' => $user_id,
      'access_token' => $access_token,
    ];

    $this->booster_login_attempt_model->limit_access_rate($metadata_array);

    $this->load->model('access_token_model');

    $token = $this->access_token_model->get_token($user_id, $access_token);

    if ($token) {
        $login_id = $this->ion_auth->login_titan_token($token->access_token);
        $redirect = $token->redirect ?: '/';
    } else {
        $redirect = '/';
    }

    $this->access_token_model->delete_users_tokens($user_id);

    $this->session->set_userdata('logged_in_via_titan', true);
    return redirect($redirect);
  }


  public function to_titan_redirect() {
    $this->load->model('access_token_model');
    $this->load->library('session');

    $user_id = $this->session->userdata('user_id');
    $token   = $this->access_token_model->create_titan_access_token($user_id);

    // Log user out of TK, redirect to Titan
    $this->ion_auth->logout(get_titan_url('tk-pledge-complete/'.(int)$user_id.'/'.$token));
  }


 // @codingStandardsIgnoreLine
  protected function _login_common($view,
                                   $fr_code=null,
                                   $email=null,
                                   $redirect_dashboard=null,
                                   $pledge=false,
                                   $register_part=false,
                                   $post_login_url=false) {

    $this->load->model('user_model');
    $this->load->model('user_group_model');
    $this->load->model('booster_login_attempt_model');

    if ($this->form_validation->run() === true || !empty($fr_code)) {
      $email_to_log   = $email ? $email : $this->input->post('identity');
      $metadata_array = [
        'email' => $email_to_log,
        'fr_code' => $fr_code,
      ];

      $this->booster_login_attempt_model->limit_access_rate($metadata_array);
      $reg_code = false;
      if ($this->input->post('fr_code') || $fr_code) {
        $range = range(8, 9);
        if (in_array(strlen($this->input->post('fr_code')), $range) ||
          in_array(strlen(($fr_code)), $range)) {
          $reg_code = $this->input->post('fr_code') ?: $fr_code;
          $this->load->helper('registration_code');
          $code = classroom_reg_code_check($reg_code);
          if ($code) {
            $reg_code_info = $this->user_model->create_temp_user_by_reg_code($code);
            if ($reg_code_info) {
              $fr_code_to_use = $this->user_model->get_fr_code($reg_code_info->temp_user_id);
            } else {
              $this->invalid_registration_code();
            }
          } else {
            $this->invalid_registration_code();
          }
        } elseif ($this->input->post('fr_code')) {
          $fr_code_to_use = $this->input->post('fr_code');
        } else {
          $fr_code_to_use = $fr_code;
        }

        // only log in if this is not a participant view, fake the login id otherwise
        if ($redirect_dashboard != 'view-participant'
          and $redirect_dashboard != 'edit-participant'
          and $redirect_dashboard != 'edit-personalize') {
          $login_id = $this->ion_auth->login_fr_code($fr_code_to_use);
        } else {
          $login_id = true;
        }

        $identity = $fr_code_to_use;
      } else {
        $login_id = $this->ion_auth->login(
          $this->input->post('identity'),
          $this->input->post('password'),
          $this->input->post('remember')
        );
        $identity = $this->input->post('identity');
      }

      if ($login_id) {
        // if a redirect url exists, use it
        $post_login_url = $this->session->userdata('post_login_url') ? $this->session->userdata('post_login_url') : $post_login_url;
        if ($view == 'login' && (stripos($post_login_url, '/admin') !== false ||
            stripos($_POST['redirect'], '/admin') !== false)) {
          $_POST['redirect'] = '';
          $post_login_url    = '';
          $this->session->unset_userdata(['post_login_url']);
        }

        $default_redirect = empty($post_login_url) ? '/dashboard' : $post_login_url;

        $this->session->set_userdata('participant_registered', true);
        if ($view === 'login_email' && ($this->ion_auth->in_group(User_group_model::ADMIN) ||
            $this->ion_auth->in_group(User_group_model::ORG_ADMIN))) {
          $default_redirect = '/admin';
          if ($this->ion_auth->in_group(User_group_model::ORG_ADMIN)) {
            $default_redirect = '/admin/schools';
          }
        } else {
          if ($this->ion_auth->in_group(
            [User_group_model::STUDENTS,
            User_group_model::TEACHERS]
          )) {
            if (!$this->user_model->student_registered($login_id)) {
              $this->session->set_userdata('participant_registered', false);
              if ($reg_code) {
                $this->session->set_userdata('reg_code_info', $reg_code_info);
              }

              if (stristr($default_redirect, '/register/participant') !== false) {
                return redirect($default_redirect);
              } elseif (isset($reg_code_info->parent_reg)) {
                return redirect('/register/parent');
              } else {
                return redirect('/register/participant');
              }
            } elseif (!$this->user_model->student_waivered($login_id)) {
              $parents = $this->user_model->student_parents($login_id);
              if (isset($parents[0]->fr_code)) {
                setcookie('parent_fr_code', $parents[0]->fr_code, 0, '/');
              }

              return redirect('/register/participant-terms');
            } else {
              $this->session->set_userdata('participant_registered', true);
              $teacher = $this->ion_auth->in_group(User_group_model::TEACHERS);
              if ($fr_code && ! $redirect_dashboard) {
                $default_redirect = '/' . ($teacher ? 'teacher' : 'student') . '/my_pledges';
              } elseif ($redirect_dashboard && $pledge) {
                $default_redirect = '/' . ($teacher ? 'teacher' : 'student') . '/dashboard/pledge';
              } else {
                $default_redirect = '/' . ($teacher ? 'teacher' : 'student') . '/dashboard';
              }
            }
          } elseif ($this->ion_auth->in_group(
            [User_group_model::PARENTS,
                                                    User_group_model::SPONSORS,
            User_group_model::ORG_ADMIN]
          ) &&
                    $view === 'login') {
            if ($fr_code && ! $redirect_dashboard) {
              $default_redirect = $default_redirect ?: '/home/my_pledges';
              $this->session->unset_userdata(['view_fr_code']);
            } elseif ($redirect_dashboard == 'view-participant') {
              // set up new session and redirect to student dashboard so it's viewable
              $this->session->set_userdata('view_fr_code', $fr_code);
              if ($pledge) {
                $default_redirect = '/' . ($teacher ? 'teacher' : 'student') . '/dashboard/pledge';
              } elseif ($register_part) {
                $post_post_login_url = $this->session->userdata('post_post_login_url');
                $this->session->unset_userdata(['post_post_login_url']);
                $default_redirect = $post_post_login_url;
              } else {
                $default_redirect = '/' . ($teacher ? 'teacher' : 'student') . '/dashboard';
              }
            } elseif ($redirect_dashboard == 'edit-participant') {
              // set up new session and redirect to student dashboard so it's viewable
              $this->session->set_userdata('view_fr_code', $fr_code);
              $default_redirect = '/profile/update_student_profile/';
            } elseif ($redirect_dashboard == 'edit-personalize') {
              // set up new session and redirect to student dashboard so it's viewable
              $this->session->set_userdata('view_fr_code', $fr_code);
              $default_redirect = '/profile/update_student_profile/1';
            } elseif ($redirect_dashboard == 'register-participant') {
              // unset participant user session and redirect to parent context
              $this->session->unset_userdata(['view_fr_code']);
              $default_redirect = '/home/register-participant/';
            } else {
              $this->session->unset_userdata(['view_fr_code']);
              $default_redirect = '/home/dashboard';
            }
          }
        }

        $redir            = $this->input->post('redirect');
        $default_redirect = reattach_ga_campaign_query($default_redirect);
        //redirect them to dashboard, or the page they were trying to access
        return redirect($this->input->post('redirect') ?: $default_redirect);
      } else {
        //LOGGING FOR DROP OUT ON SUCCESSFUL REGISTER
        if ($redirect_dashboard == 1) {
          log_message("error", "ERROR WITH REGISTER LOGIN LOC_1:  " . print_r($this->ion_auth->errors(), true));
          log_message("error", "FR CODE:  " . $fr_code);
        }

        //if the login was un-successful
        //redirect them back to the login page
        $this->clear_cookie();
        $this->session->set_flashdata('message', $this->ion_auth->errors());
        $data['login_error'] = true;
        $this->load->view('front/' . $view, $data);
      }
    } else {
      //LOGGING FOR DROP OUT ON SUCCESSFUL REGISTER
      if ($redirect_dashboard == 1) {
        log_message("error", "ERROR WITH REGISTER LOGIN LOC_2:  " . print_r(validation_errors(), true));
        log_message("error", "FR CODE:  " . $fr_code);
      }

      //Clear cookie data once decided user is not logged in
      $this->clear_cookie();

      //validation failed, back to the login page
      $data['fr_code']     = $fr_code;
      $data['email']       = $email;
      $data['message']     = ( validation_errors() ) ? validation_errors() : $this->session->flashdata('message');
      $data['login_error'] = validation_errors() ? true : false;
      $data['redirect']    = $this->session->userdata('post_login_url');

      $data['show_cookie_policy'] = get_cookie('hide_cookie_policy') ? false : true;
      setcookie('hide_cookie_policy', 'hide', null, '/', '.boosterthon.com');
      $this->load->view('front/' . $view, $data);
    }

  }


  private function clear_cookie() {
    $persistent_cookies = [
      'XDEBUG_SESSION',
      'use_new_public_page',
      'use_new_signup_page',
      'csrfer_ckie',
    ];
    foreach ($_COOKIE as $cookie_name => $cookie_value) {
      if (!in_array($cookie_name, $persistent_cookies)) {
        delete_cookie($cookie_name);
      }
    }
  }


  /**
   * also post or get variable "redirect"
   * @param type $view_fr_code
   */
  public function participant_page( $view_fr_code) {
    //validate student's parent is currently logged in
    $redirect = '/logout';
    $this->load->model('user_model');
    if ($this->user_model->is_my_participant_by_fr($view_fr_code)) {
      $this->session->set_userdata('view_fr_code', $view_fr_code);
      $redirect = $this->input->get('redirect') ? $this->input->get('redirect') : $this->input->post('redirect');
    }

    return redirect($redirect);
  }


  private function invalid_registration_code() {
    $this->session->set_flashdata(
      'message',
      'Oops!  You entered an invalid registration code. Please try again.'
    );
    redirect('/auth/login');
  }


  private function login_email_for_register() {
    $this->load->library('form_validation');
    $this->load->library('ion_auth');

    $this->form_validation->set_rules('identity', 'Username', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');

    if ($this->form_validation->run()) {
      $login_id = $this->ion_auth->login(
        $this->input->post('identity'),
        $this->input->post('password'), false, false
      );
      $identity = $this->input->post('identity');
      if ($login_id) {
        //if the login is successful
        $this->session->set_userdata('login_register_fail', false);
        return redirect('/register/participant/' . $login_id);
      } else {
        $this->session->set_flashdata('message_login', $this->ion_auth->errors());
      }
    } else {
      $this->session->set_flashdata('message_login', validation_errors());
    }

    $data['email'] = $this->input->post("identity");
    $this->session->set_userdata('login_register_fail', true);
    return redirect('/register/participant/');
  }


  // login to collection site as a school


  public function login_collection() {
    $post = $this->input->post();
    // change auth identity type to username
    $is_logged_in = $this->ion_auth->ion_auth_model->login_collection($post['identity'], $post['password']);
    // if not valid kick-out
    if (!$is_logged_in) {
      $this->session->set_flashdata('message', $this->ion_auth->errors());
      redirect('/auth/login_email');
    }

    redirect(base_url('admin/schools/edit/'.$post['identity'].'#programs'));
  }


  /*********************************************************************************************************************

    Log user out

  *********************************************************************************************************************/


  public function logout($override_admin=null) {
    $this->data['title'] = "Logout";

    $this->load->library('booster_api');
    $this->load->model('user_group_model');

    if ($this->ion_auth->logged_in() && !$override_admin &&
      ($this->ion_auth->is_admin() || $this->ion_auth->in_group(User_group_model::ORG_ADMIN))) {
      $logout_location = 'auth/login_email';
    } else {
      $logout_location = 'auth';
    }

    try {
      //log the user out of Titan
      $this->booster_api->logout();
    } catch (\Exception $e) {
      log_message('error', $e->getMessage());
    }

    //log the user out
    $logout = $this->ion_auth->logout();

    //redirect them back to the page they came from
    redirect($logout_location);
  }


  public function forgot_password($login_locker=null) {
    $this->form_validation->set_rules(
      'email', 'Email Address',
      'trim|required|valid_email'
    );
    //enabling new design changes for quick ui fix.
    $data['enable_new_design_override'] = true;
    $data['hide_top_nav_bar']           = true;
    if ($this->form_validation->run() == false) {
      if ($this->input->post('json_output')) {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(false));
      } else {
        //setting up the new design change over
        $data['footer_js'] = ['jquery.backstretch.min.js','../bootstrap_3/js/bootstrap.min.js'];
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
          $this->session->set_flashdata('error', 'Please enter valid email address.');
        }

        $data['extra_css'][] = 'new_registration_override.css';
        $data['view']        = 'auth/forgot_password';
        $this->load->view('dashboard/template', $data);
      }
    } else {
      //run the forgotten password method to email an activation code to the user
      $forgotten = $this->ion_auth->
        forgotten_password($this->input->post('email'), $login_locker);
      if ($forgotten) {
        $this->session->set_flashdata('success', $this->ion_auth->messages());
        if ($this->input->post('json_output')) {
          $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode(true));
        } else {
          if ($login_locker) {
            redirect("/auth/login_email");
          }

          $data['footer_js']   = ['jquery.backstretch.min.js','../bootstrap_3/js/bootstrap.min.js'];
          $data['extra_css'][] = 'new_registration_override.css';
          $data['email']       = $this->input->post('email');
          $data['view']        = 'auth/successful_password_reset.php';
          $this->load->view('dashboard/template', $data);
        }
      } else {
        $this->session->set_flashdata('error', $this->ion_auth->errors());
        if ($this->input->post('json_output')) {
          $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode(false));
        } else {
          if ($login_locker) {
            redirect("forgot-password/1");
          }

          redirect("forgot-password");
        }
      }
    }
  }


  //reset password - final step for forgotten password


  public function reset_password($code = null, $login_locker=null) {
    if (!$code) {
      show_404();
    }

    $user = $this->ion_auth->forgotten_password_check($code);
    if ($user) {
      $data = [
        'main_class'      => 'reset-password',
        'main_nav'        => [],
        'sidebar_widgets' => []
      ];

      $data['active_main']         = 'Change password';
      $data['tabs']                = ['change_password'];
      $data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
      $data['max_password_length'] = $this->config->item('max_password_length', 'ion_auth');

      $this->form_validation->set_rules(
        'password', 'New Password',
        'callback_valid_password|trim|required|min_length[' . $data['min_password_length'] . ']|max_length[' . $data['max_password_length'] . ']'
      );
      $this->form_validation->set_rules(
        'password2', 'Confirm New Password',
        'trim|required|matches[password]'
      );

      if ($this->form_validation->run() == false) {
        $data['message']      = (validation_errors()) ? 'Please fix the form errors shown below.' : $this->session->flashdata('message');
        $data['code']         = $code;
        $data['user_id']      = $user->id;
        $data['login_locker'] = $login_locker;
        $data['view']         = 'dashboard/main';
        $data['extra_css'][]  = 'new_reset_password_override.css';
        $this->load->vars($data);
        $this->load->view('dashboard/template');
      } else {
        // do we have a valid request?
        if ($user->id != $this->input->post('user_id')) {
          //something fishy might be up
          $this->ion_auth->clear_forgotten_password_code($code);
          show_error('This form post did not pass our security checks.');
        } else {
          // finally change the password
          $identity = $user->{$this->config->item('identity', 'ion_auth')};
          $change   = $this->ion_auth->reset_password($identity, $this->input->post('password'));
          if ($change) {
            $this->session->set_flashdata('success', $this->ion_auth->messages());
            if ($login_locker) {
              redirect("/auth/login_email");
            }

            redirect("login");
          } else {
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect('auth/reset_password/' . $code);
          }
        }
      }
    } else {
      //if the code is invalid then send them back to the forgot password page
      $this->session->set_flashdata('message', $this->ion_auth->errors());
      redirect("forgot-password");
    }
  }


  /*********************************************************************************************************************

  Activate user

  *********************************************************************************************************************/


  public function activate($id, $code=false) {
    if ($code !== false) {
      $activation = $this->ion_auth->activate($id, $code);
    } elseif ($this->ion_auth->is_admin()) {
      $activation = $this->ion_auth->activate($id);
    }

    if ($activation) {
      //redirect them to the auth page
      $this->session->set_flashdata('message', $this->ion_auth->messages());
      redirect("auth");
    } else {
      //redirect them to the forgot password page
      $this->session->set_flashdata('message', $this->ion_auth->errors());
      redirect("forgot-password");
    }
  }


  //deactivate the user


  public function deactivate($id = null) {
    $id = $this->config->item('use_mongodb', 'ion_auth') ? (string)$id : (int)$id;

    $this->load->library('form_validation');
    $this->form_validation->set_rules('confirm', 'confirmation', 'required');
    $this->form_validation->set_rules('id', 'user ID', 'required|alpha_numeric');

    if ($this->form_validation->run() == false) {
      // insert csrf check
      $this->data['csrf'] = $this->_get_csrf_nonce();
      $this->data['user'] = $this->ion_auth->user($id)->row();

      $this->load->view('auth/deactivate_user', $this->data);
    } else {
      // do we really want to deactivate?
      if ($this->input->post('confirm') == 'yes') {
        // do we have a valid request?
        if ($this->_valid_csrf_nonce() === false || $id != $this->input->post('id')) {
          show_error('This form post did not pass our security checks.');
        }

        // do we have the right userlevel?
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
          $this->ion_auth->deactivate($id);
        }
      }

      //redirect them back to the auth page
      redirect('auth');
    }
  }


  /*********************************************************************************************************************

    Create new user

  *********************************************************************************************************************/


  public function create_user() {
    $this->data['title'] = "Create User";

    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
      redirect('auth');
    }

    //validate form input
    $this->form_validation->set_rules('first_name', 'First Name', 'required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required');
    $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
    $this->form_validation->set_rules('phone1', 'First Part of Phone', 'required|min_length[3]|max_length[3]');
    $this->form_validation->set_rules('phone2', 'Second Part of Phone', 'required|min_length[3]|max_length[3]');
    $this->form_validation->set_rules('phone3', 'Third Part of Phone', 'required|min_length[4]|max_length[4]');
    $this->form_validation->set_rules('company', 'Company Name', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
    $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

    if ($this->form_validation->run() == true) {
      $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
      $email    = $this->input->post('email');
      $password = $this->input->post('password');

      $additional_data = [
        'first_name' => $this->input->post('first_name'),
        'last_name'  => $this->input->post('last_name'),
        'company'    => $this->input->post('company'),
        'phone'      => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
      ];
    }

    if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data)) {
      //check to see if we are creating the user
      //redirect them back to the admin page
      $this->session->set_flashdata('message', "User Created");
      redirect("auth");
    } else {
      //display the create user form
      //set the flash data error message if there is one
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

      $this->data['first_name']       = [
        'name'  => 'first_name',
        'id'    => 'first_name',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('first_name'),
      ];
      $this->data['last_name']        = [
        'name'  => 'last_name',
        'id'    => 'last_name',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('last_name'),
      ];
      $this->data['email']            = [
        'name'  => 'email',
        'id'    => 'email',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('email'),
      ];
      $this->data['company']          = [
        'name'  => 'company',
        'id'    => 'company',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('company'),
      ];
      $this->data['phone1']           = [
        'name'  => 'phone1',
        'id'    => 'phone1',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('phone1'),
      ];
      $this->data['phone2']           = [
        'name'  => 'phone2',
        'id'    => 'phone2',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('phone2'),
      ];
      $this->data['phone3']           = [
        'name'  => 'phone3',
        'id'    => 'phone3',
        'type'  => 'text',
        'value' => $this->form_validation->set_value('phone3'),
      ];
      $this->data['password']         = [
        'name'  => 'password',
        'id'    => 'password',
        'type'  => 'password',
        'value' => $this->form_validation->set_value('password'),
      ];
      $this->data['password_confirm'] = [
        'name'  => 'password_confirm',
        'id'    => 'password_confirm',
        'type'  => 'password',
        'value' => $this->form_validation->set_value('password_confirm'),
      ];

      $this->load->view('auth/create_user', $this->data);
    }
  }


  public function edit_user($id) {
    $this->data['title'] = "Edit User";

    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
      redirect('auth');
    }

    $user = $this->ion_auth->user($id)->row();

    //process the phone number
    if (isset($user->phone) && !empty($user->phone)) {
      $user->phone = explode('-', $user->phone);
    }

    //validate form input
    $this->form_validation->set_rules('first_name', 'First Name', 'required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required');
    $this->form_validation->set_rules('phone1', 'First Part of Phone', 'required|min_length[3]|max_length[3]');
    $this->form_validation->set_rules('phone2', 'Second Part of Phone', 'required|min_length[3]|max_length[3]');
    $this->form_validation->set_rules('phone3', 'Third Part of Phone', 'required|min_length[4]|max_length[4]');
    $this->form_validation->set_rules('company', 'Company Name', 'required');

    if (isset($_POST) && !empty($_POST)) {
      // do we have a valid request?
      if ($this->_valid_csrf_nonce() === false || $id != $this->input->post('id')) {
        show_error('This form post did not pass our security checks.');
      }

      $data = [
        'first_name' => $this->input->post('first_name'),
        'last_name'  => $this->input->post('last_name'),
        'company'    => $this->input->post('company'),
        'phone'      => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
      ];

      //update the password if it was posted
      if ($this->input->post('password')) {
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        $data['password'] = $this->input->post('password');
      }

      if ($this->form_validation->run() === true) {
        $this->ion_auth->update($user->id, $data);

        //check to see if we are creating the user
        //redirect them back to the admin page
        $this->session->set_flashdata('message', "User Saved");
        redirect("auth");
      }
    }

    //display the edit user form
    $this->data['csrf'] = $this->_get_csrf_nonce();

    //set the flash data error message if there is one
    $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

    //pass the user to the view
    $this->data['user'] = $user;

    $this->data['first_name']       = [
      'name'  => 'first_name',
      'id'    => 'first_name',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('first_name', $user->first_name),
    ];
    $this->data['last_name']        = [
      'name'  => 'last_name',
      'id'    => 'last_name',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('last_name', $user->last_name),
    ];
    $this->data['company']          = [
      'name'  => 'company',
      'id'    => 'company',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('company', $user->company),
    ];
    $this->data['phone1']           = [
      'name'  => 'phone1',
      'id'    => 'phone1',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('phone1', $user->phone[0]),
    ];
    $this->data['phone2']           = [
      'name'  => 'phone2',
      'id'    => 'phone2',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('phone2', $user->phone[1]),
    ];
    $this->data['phone3']           = [
      'name'  => 'phone3',
      'id'    => 'phone3',
      'type'  => 'text',
      'value' => $this->form_validation->set_value('phone3', $user->phone[2]),
    ];
    $this->data['password']         = [
      'name' => 'password',
      'id'   => 'password',
      'type' => 'password'
    ];
    $this->data['password_confirm'] = [
      'name' => 'password_confirm',
      'id'   => 'password_confirm',
      'type' => 'password'
    ];

    $this->load->view('auth/edit_user', $this->data);
  }


  /**
   * Nonce CSRF functions. Not quite sure these are necessary as CSRF is implemented at the framework level. Possible
   *
   * @return void
   */
  private function _get_csrf_nonce() {
    $this->load->helper('string');
    $key   = random_string('alnum', 8);
    $value = random_string('alnum', 20);
    $this->session->set_flashdata('csrfkey', $key);
    $this->session->set_flashdata('csrfvalue', $value);

    return [$key => $value];
  }


  private function _valid_csrf_nonce() {
    if ($this->input->post($this->session->flashdata('csrfkey')) !== false &&
      $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
      return true;
    } else {
      return false;
    }
  }


  /**
   * authentication required to set up loader.io load testing
   */
  public function authenticate_loader_io($code) {
    echo "loaderio-$code";
    die;
  }


}
