<?php if (! defined('BASEPATH')) {
  exit('No direct script access allowed');
}

/**
* Name:  API controller (currently only used for the iPad team dashboard)
*
* Author:  Nic Rosental
*            nic@epiclabs.com
*             @nicdev
*
* Created:  12.1.2012
*/

require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller
{


    /*********************************************************************************************************************

        Exchange login credentials for a code
        /api/auth

    *********************************************************************************************************************/


  public function token_post() {

    if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'))) {
      $this->load->model('user_model');

      $api_token = $this->user_model->auth_token($this->input->post('email'));

      if ($api_token) {
        $this->jsonize([ 'token' => $api_token ]);
        return;
      }
    }

    show_error('access_denied', 401);

  }


    /*********************************************************************************************************************

        Return classrooms in the format needed for the iPad app
        /api/classrooms

    *********************************************************************************************************************/


  public function classrooms_post() {

    $token = $this->input->post('token');

    $user = $this->auth($token);

    $classrooms = [];

    foreach ($user->program_groups as $pg) {
      $more_classrooms = $this->group_model->get_classrooms($pg);

      if (is_array($more_classrooms)) {
        $classrooms = array_merge($classrooms, $more_classrooms);
      }
    }

    $this->jsonize($classrooms);

  }


    /**
     * callback hancler for the completion of the hero video
     * validates post
     * saves url of hero video
     *
     * {"response": "completed",
     * "id_participant": "3453152",
     * "id_job": "15109",
     * "id_youtube": "N6qb2Rm-ZIc",
     * "url_youtube": "https://www.youtube.com/watch?v=N6qb2Rm-ZIc"}
     *
     * @param type $hash
     * @param type $job_id
     */
  public function hero_video_post() {
    $entityBody = file_get_contents('php://input');
    $postobj    = json_decode($entityBody);
    log_message('error', "Hero Video Receive: \n" . var_export($postobj, true));
    $this->load->model('hero_video_job_model');
    $job_id   = $postobj->id_job;
    $id_video = $postobj->id_video;
    if ($this->hero_video_job_model->validate_job_id($job_id)) {
      if ($this->hero_video_job_model->validate_video_url($id_video)) {
        $this->hero_video_job_model->set_video_id($postobj->id_participant, $id_video);
        $this->load->helper('hero_video_email_helper');
        $hero_video_email = new Hero_Video_Email($postobj->id_participant);
        $hero_video_email->send();
      } else {
        error_log('hero_video_post -> invalid video url' . $id_video);
      }
    } else {
      error_log('hero_video_post -> invalid job id' . $job_id);
    }

    echo 'success';
  }


    /*********************************************************************************************************************

        Return students in the format needed for the iPad app
        /api/students

    *********************************************************************************************************************/


  public function students_post() {

    $user = $this->auth();

    $this->load->model('group_model');

    $students = [];

    foreach ($user->program_groups as $pg) {
      $more_students = $this->group_model->get_participants($pg);

      if (is_array($more_students)) {
        $students = array_merge($students, $more_students);
      }
    }

    $this->jsonize($students);

  }


    /*********************************************************************************************************************

        Return programs in the format needed for the iPad app
        /api/programs

    *********************************************************************************************************************/


  public function programs_post() {

    $user = $this->auth();

    $this->load->model('program_model');

    foreach ($user->teams as $team) {
      $programs_arr[] = $this->program_model->get_programs_for_team($team, true);
    }

    //Compact the multiple arrays into a single one
    $programs = [];
    foreach ($programs_arr as $p) {
      $programs = array_merge($programs, $p);
    }

    $this->jsonize($programs);

  }


    /*********************************************************************************************************************

        Output JSON

    *********************************************************************************************************************/


  protected function jsonize( $contents ) {

    $this->output->set_content_type('application/json')
                 ->set_output(json_encode($contents));

  }


    /*********************************************************************************************************************

        Check API token
        Returns an API object with the user's id set, also the user's groups, or 401 if bad

    *********************************************************************************************************************/


  protected function auth( $token ) {

    $this->load->model('user_model');

    $user     = new stdClass;
    $user->id = $this->user_model->verify_token($token);

    if ($user->id) {
      $this->load->model('user_group_model');
      $this->load->model('group_model');

      $user->teams          = $this->user_group_model->get_teams_for_user($user->id);
      $user->program_groups = $this->group_model->current_groups($user->teams);

      return $user;
    }

    show_error('access_denied', 401);

  }


    /*********************************************************************************************************************

        Test PUT request
        curl --request PUT -k "https://tk.epiclabs.com/api/put_request"

    *********************************************************************************************************************/


  public function put_request_put() {

    $this->response('received a put request');

  }


    /*********************************************************************************************************************

        Test DELETE request
        curl --request DELETE -k "https://tk.epiclabs.com/api/del_request"

    *********************************************************************************************************************/


  public function del_request_delete() {

    $this->response('received a delete request');

  }


}
