<?php if (! defined('BASEPATH')) {
  exit('No direct script access allowed');
}

/**
 * Dashboard_ajax.php
 *
 * This class should handle the light ajax calls to
 * supplement the dashboard controller
 *
 */
class Dashboard_Ajax extends MY_Controller
{


  /**
   * Returns the ppl warning message for online payment selection
   *
   * @param string $pledge_ids
   * @return string
   */
  public function get_ppl_warning() {
    $this->load->model('pledge_model');
    $pledge_ids = $this->input->post('pledge_ids');
    if (empty($pledge_ids)) {
      return ["success" => true, "html" => ""];
    }

    $data      = [];
    $json_data = [
      "success" => true,
      "html" => $this->load
        ->view('dashboard/partials/tab_content/ppl_warning', $data, true)
    ];
    echo json_encode($json_data);
  }


  public function hide_comment() {
    $this->load->library('booster_api');

    $pledge_ids = $this->input->post();
    $update_to  = ['show_comment' => 0];

    $this->db->set('comment', '');
    $this->db->where('id', $pledge_ids['tk_pid']);
    $queryResult = $this->db->update('pledges', $update_to);

    if ($this->db->affected_rows() > 0) {
      $response = [
        'success' => ['status_code' => 200]
      ];
    } else {
      $response = [
        'success' => ['status_code' => 400]
      ];
    }

    header('Content-type: application/json');
    echo json_encode($response);
  }


}
