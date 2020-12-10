<?php if (! defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Microsites extends MY_Controller
{
  public $picture_data = '';


  public function __construct() {
    parent::__construct();
    $this->load->config('aws', true);
    $this->picture_path              = $this->config->item('s3_microsites', 'aws');
    $this->program_logo_path         = $this->config->item('s3_program_logos', 'aws');
    $this->program_sponsor_logo_path = $this->config->item('s3_program_sponsor_logos', 'aws');
  }


  public function validate_alert_start_date($date) {
    $datetime = new DateTime();
    $year     = $datetime->setTimestamp(strtotime($date))->format('Y');

    if ($year > 1970 && $year < 2038) {
      return true;
    } else {
      $this->form_validation->set_message('validate_alert_start_date', 'Alert start date invalid');
      return false;
    }
  }


  public function validate_alert_end_date($date) {
    $datetime = new DateTime();
    $year     = $datetime->setTimestamp(strtotime($date))->format('Y');

    if ($year > 1970 && $year < 2038) {
      return true;
    } else {
      $this->form_validation->set_message('validate_alert_end_date', 'Alert end date invalid');
      return false;
    }
  }


  public function edit($program_id) {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('', '');

    $custom_alert_active = $this->input->post('custom_alert_text') === '' ? 0 : ($this->input->post('custom_alert_active') ? 1 : 0);
    $sponsor_logos       = (int)$this->input->post('sponsor_logos_active');

    if (!empty($_FILES['school_logo_image']['name'])) {
      $this->form_validation->set_rules('school_logo_image', 'School Logo', 'callback_validate_image_upload[school_logo_image]');
    }

    $this->form_validation->set_rules('slug', 'Slug', 'callback_slug_duplicate_check[' . $program_id . ']');
    $this->form_validation->set_rules('overview_text_override', 'Overview Text Override', 'trim');
    $this->form_validation->set_rules('thank_you_text_override', 'Thank You Text Override', 'trim');
    $this->form_validation->set_rules('funds_raised_for', 'Funds Raised For', 'trim');
    $this->form_validation->set_rules('custom_alert_text', 'Overview Text Override', 'trim');
    $this->form_validation->set_rules('sponsor_logos_active', 'Sponsor Logos', 'trim');
    $this->form_validation->set_rules('sponsor_logo_home_dashboard', 'Home Dashboard', 'trim');
    $this->form_validation->set_rules('sponsor_logo_pledge_page', 'Sponsor Pledge Page', 'trim');

    if ($custom_alert_active === 1) {
      $this->form_validation->set_rules('custom_alert_start', 'Alert Start Date', 'callback_validate_alert_start_date');
      $this->form_validation->set_rules('custom_alert_end', 'Alert End Date', 'callback_validate_alert_end_date');
    }

    if ($this->form_validation->run() === true) {
      $this->load->model('program_model');

      // Check if Sponsor Logo was assigned to a location
      if ($sponsor_logos === 1) {
        $sponsor_logo_home_dashboard = $this->input->post('sponsor_logo_home_dashboard');
        $sponsor_logo_pledge_page    = $this->input->post('sponsor_logo_pledge_page');

        if ($sponsor_logo_home_dashboard == 1 || $sponsor_logo_pledge_page == 1) {
          $this->program_model->update_sponsor_logos($program_id, $sponsor_logo_home_dashboard, $sponsor_logo_pledge_page);
        } else {
          // Sponsor Logo information is not correct
          $this->session->set_flashdata('error_message', 'Please select where your logos should appear.');
          redirect('/admin/programs/edit/' . $program_id . '#content');
        }
      } else {
        $this->program_model->update_sponsor_logos($program_id);
      }

      $this->load->model('microsite_model');
      $microsite         = $this->microsite_model->get_by('program_id', $program_id);
      $program_logo_name = $microsite->school_image_name;
      $this->load->model('microsite_pic_model');
      $this->load->model('microsite_video_model');

      $pic_number = $_POST['submitted_pic'];
      if (!empty($this->picture_data)) {
        // @codingStandardsIgnoreStart
        $image_name                                     = $this->picture_data['file_name'];
        $data['program']->microsite->$pic_number->image = $image_name;
        // @codingStandardsIgnoreEnd
      }

      if (isset($_POST['submit_photo'])) {
        $pic_id            = $this->input->post($pic_number);
        $pic_data['image'] = $image_name;
        if (empty($pic_id)) {
          $input_data[$pic_number] = $this->microsite_pic_model->insert($pic_data);
        } else {
          $this->microsite_pic_model->update($pic_id, ['image' => $image_name]);
        }
      }

      // Images not uploaded but selected or unchanged from previous
      $pics = [
        'pic_1' => empty($input_data['pic_1']) ? $this->input->post('pic_1') : $input_data['pic_1'],
        'pic_2' => empty($input_data['pic_2']) ? $this->input->post('pic_2') : $input_data['pic_2'],
        'pic_3' => empty($input_data['pic_3']) ? $this->input->post('pic_3') : $input_data['pic_3'],
      ];

      //update pic descriptions
      foreach ($pics as $k => $v) {
        if (!empty($v)) {
          $input_data[$k] = $v;
        }
      }

      // Color theme
      $input_data['color_theme_id'] = $this->microsite_model->get_color_theme_id_from_hex_code($this->input->post('color_theme'));

      //Handle videos
      $input_data['intro_vid_override']       = $this->microsite_video_model->insert($this->input->post('intro_vid_override'));
      $input_data['get_pledges_vid_override'] = $this->microsite_video_model->insert($this->input->post('get_pledges_vid_override'));
      $input_data['teacher_video_override']   = $this->microsite_video_model->insert($this->input->post('teacher_video_override'));
      $input_data['video_1']                  = $this->microsite_video_model->insert($this->input->post('video_1'));
      $input_data['video_2']                  = $this->microsite_video_model->insert($this->input->post('video_2'));
      $input_data['video_3']                  = $this->microsite_video_model->insert($this->input->post('video_3'));
      $input_data['video_4']                  = $this->microsite_video_model->insert($this->input->post('video_4'));
      $input_data['video_5']                  = $this->microsite_video_model->insert($this->input->post('video_5'));

      $input_data['video_1'] = $input_data['video_1'] === false ? '' : $input_data['video_1'];
      $input_data['video_2'] = $input_data['video_2'] === false ? '' : $input_data['video_2'];
      $input_data['video_3'] = $input_data['video_3'] === false ? '' : $input_data['video_3'];
      $input_data['video_4'] = $input_data['video_4'] === false ? '' : $input_data['video_4'];
      $input_data['video_5'] = $input_data['video_5'] === false ? '' : $input_data['video_5'];

      //Handle video descriptions
      $video_data['intro_vid_override_desc'] = $this->microsite_video_model->description($this->input->post('intro_vid_override_desc'), $input_data['intro_vid_override']);
      $video_data['video_1_desc']            = $this->microsite_video_model->description($this->input->post('video_1_desc'), $input_data['video_1'], false);
      $video_data['video_2_desc']            = $this->microsite_video_model->description($this->input->post('video_2_desc'), $input_data['video_2'], false);
      $video_data['video_3_desc']            = $this->microsite_video_model->description($this->input->post('video_3_desc'), $input_data['video_3'], false);
      $video_data['video_4_desc']            = $this->microsite_video_model->description($this->input->post('video_4_desc'), $input_data['video_4'], false);
      $video_data['video_5_desc']            = $this->microsite_video_model->description($this->input->post('video_5_desc'), $input_data['video_5'], false);

      $video_ids = [
        $input_data['intro_vid_override'],
        $input_data['video_1'],
        $input_data['video_2'],
        $input_data['video_3'],
        $input_data['video_4'],
        $input_data['video_5'],
      ];

      foreach ($video_ids as $key => $video_id) {
        if ($video_id === false || empty($video_id)) {
          unset($video_ids[$key]);
        }
      }

      if (!empty($video_ids)) {
          $program_ids = $this->microsite_video_model->get_program_ids_by_video_ids($video_ids);
      } else {
        $program_ids = [];
      }

      $program_ids[] = $program_id;
      $this->microsite_video_model->bust_cached_videos($program_ids);

      // _has_funds_for_text() will handle the true and false scenarios
      $input_data['funds_raised_for'] = $this->_has_funds_for_text($this->input->post('funds_raised_for'));

      $input_data['program_id'] = $program_id;
      $school_name              = $this->input->post('school_name');

      //Program Logo
      if (!empty($_FILES['program_logo_image']['name'])) {
        $this->_new_program_logo($microsite->id);
      } elseif ($program_logo_name && $this->input->post('modified') == 'true') {
        $this->load->library('image_editor');
        $this->load->library('aws_s3');
        $success         = false;
        $path            = $this->input->post('path');
        $current_img_url = s3_url($path);

        $config = [
          'rotation_angle' => $this->input->post('angle'),
          'scale' => $this->input->post('scale'),
          'x_axis' => $this->input->post('x'),
          'y_axis' => $this->input->post('y'),
          'height' => $this->input->post('h'),
          'width' => $this->input->post('w')
        ];

        $cropped_image_path = $this->image_editor->edit_image($current_img_url, $config);
        $upload             = $this->aws_s3->upload($path, $cropped_image_path);

        if ($upload) {
          unlink($cropped_image_path);
          $success = true;
        }
      }

      $slug = $this->input->post('slug');
      if (empty($slug)) {
        $input_data['slug'] = $this->microsite_model->make_slug($school_name);
      } else {
        $input_data['slug'] = $slug;
      }

      $input_data['overview_text_override']  = $this->input->post('overview_text_override');
      $input_data['thank_you_text_override'] = $this->input->post('thank_you_text_override');

      $input_data['hide_character_videos'] = $this->input->post('hide_character_videos');

      // if a microsite exists, this is an update
      $previous_microsite = $this->microsite_model->get_by('program_id', $program_id);
      if (!empty($previous_microsite)) {
        $this->microsite_model->update($previous_microsite->id, $input_data);
        $this->session->set_flashdata('message', 'Content Updated.');
      } else {
        $this->microsite_model->insert($input_data);
        $this->session->set_flashdata('message', 'Content Added.');
      }

      $start_datetime = new DateTime();
      $start_datetime->setTimestamp(strtotime($this->input->post('custom_alert_start')));
      $end_datetime = new DateTime();
      $end_datetime->setTimestamp(strtotime($this->input->post('custom_alert_end')));

      $custom_program_alert = [
          'active' => $custom_alert_active,
          'text' => $this->input->post('custom_alert_text'),
          'start' => $start_datetime->format('Y-m-d H:i:s'),
          'end' => $end_datetime->format('Y-m-d H:i:s'),
          'program_id' => $program_id
      ];
      $this->load->model('custom_program_alerts_model');
      $this->load->library('booster_api');
      $old_custom_program_alert = $this->custom_program_alerts_model->get_by(['program_id' => $custom_program_alert['program_id']]);

      $create_alert = false;
      $delete_alert = false;

      if ($old_custom_program_alert) {
        $this->custom_program_alerts_model->update($old_custom_program_alert->id, $custom_program_alert);

        if ($custom_alert_active === 1) {
          $create_alert = true;
        } elseif ($custom_alert_active === 0 && (int)$old_custom_program_alert->active === 1) {
          $delete_alert = true;
        }
      } else {
        $this->custom_program_alerts_model->insert($custom_program_alert);
        $create_alert = true;
      }

      if ($create_alert) {
        $this->booster_api->create_program_notification(
          $program_id,
          $this->input->post('custom_alert_text'),
          $start_datetime->format('c'),
          $end_datetime->format('c')
        );
      } elseif ($delete_alert) {
        $this->booster_api->delete_program_notification(
          $program_id
        );
      }

      //update program todos
      $this->load->model('program_todos_model');
      $this->program_todos_model->update_program_todos_single($program_id);

      redirect('/admin/programs/edit/' . $program_id . '#content');
    } else {
      $this->session->set_flashdata('error_message', validation_errors());
      redirect('/admin/programs/edit/' . $program_id . '#content');
    }
  }


  /**
   * Validates image upload
   * @return BOOLEAN $valid - whether image passes validation
   */
  public function validate_image_upload($img, $field) {
    $this->load->config('aws', true);
    $school_logo_path = $this->config->item('s3_program_logos', 'aws');

    $config['allowed_types'] = 'gif|jpg|jpeg|png';
    $config['max_size']      = '5120';
    $config['max_width']     = '5000';
    $config['max_height']    = '5000';

    $this->load->library('upload', $config);
    $valid = $this->upload->validate_image_upload($field, $school_logo_path, true);
    $valid = $valid && !empty($img);

    if (!$valid) {
      $this->form_validation->set_message(
        'validate_image_upload',
        'There was an error with your image upload.<BR/>' .
        $this->upload->display_errors()
      );
    }

    return $valid;
  }


  public function slug_duplicate_check($slug, $program) {
    $this->load->model('microsite_model');
    $slug_check = $this->microsite_model->get_by('slug', $slug);
    if (empty($slug_check) || $slug_check->program_id == $program) {
      return true;
    } else {
      $this->form_validation->set_message('slug_duplicate_check', 'That slug already exists.');
      return false;
    }
  }


  public function pic_search() {
    $desc = $this->input->post('search');
    $init = $this->input->post('init');
    $this->load->model('microsite_pic_model');

    if ($init) {
      $result = $this->microsite_pic_model->load_default();
    } else {
      $result = $this->microsite_pic_model->search_description($desc);
    }

    if ($result) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($result));
    } else {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(false));
    }
  }


  public function delete_program_logo_image($microsite_id) {
    $path = $this->input->post('path');
    $this->load->library('aws_s3');
    $success = $this->aws_s3->delete_image($path);

    $fb_path = $this->_construct_fb_image_name($path);

    if ($success == true && $this->aws_s3->validate_path($fb_path)) {
      $this->aws_s3->delete_image($fb_path);
    }

    $this->load->model('microsite_model');
    $this->microsite_model->update($microsite_id, [ 'school_image_name' => null ]);

    $this->output->set_content_type('application/json')
                     ->set_output(json_encode($success));
  }


  public function new_program_logo_image($microsite_id) {
    $result = ['error', ''];
    $this->load->library('aws_s3');

    if ($_FILES['program_logo_image']['error'] == 1) {
      return false;
    } elseif (!empty($_FILES['program_logo_image']['tmp_name'])) {
      $this->_new_program_logo($microsite_id);
    }

    $result['new_image_path'] = s3_url($this->program_logo_path.$this->upload->file_name);
    $result['image_path']     = $this->program_logo_path.$this->upload->file_name;
    $result['image']          = $this->upload->file_name;

    $this->output->set_content_type('application/json')
                    ->set_output(json_encode($result));
  }


  private function _new_program_logo($microsite_id) {
    $this->load->library('aws_s3');
    $this->load->library('upload');
    $img_config['allowed_types'] = 'gif|jpg|jpeg|png';
    $img_config['max_size']      = '5120';
    $img_config['max_width']     = '5000';
    $img_config['max_height']    = '5000';
    // MY_upload specific optional configs
    $img_config['hash_flag'] = true;

    $this->load->library('upload', $img_config);

    $valid_file = $this->upload->validate_image_upload('program_logo_image', $this->program_logo_path, true);
    if (!$valid_file) {
      return false;
    }

    $this->load->library('image_editor', $img_config);

    $config = [
      'rotation_angle' => $this->input->post('angle'),
      'scale' => $this->input->post('scale'),
      'x_axis' => $this->input->post('x'),
      'y_axis' => $this->input->post('y'),
      'height' => $this->input->post('h'),
      'width' => $this->input->post('w')
    ];

    $cropped_image_path = $this->image_editor->edit_image($_FILES['program_logo_image']['tmp_name'], $config);
    $upload             = $this->aws_s3->upload($this->program_logo_path.$this->upload->file_name, $cropped_image_path);

    if ($upload) {
      // alter scaling and position to match 200px square per facebooks api docs
      $fb_config = $this->_mutate_image_configs_for_fb_upload($config);

      // append '_fb' convention to fb post version of image
      $fb_cropped_image_path = $this->image_editor->edit_image($cropped_image_path, $fb_config);

      // upload fb share copy!
      $fb_upload = $this->aws_s3->upload($this->program_logo_path.$this->_construct_fb_image_name($this->upload->file_name), $fb_cropped_image_path);

      unlink($cropped_image_path);
    }

    // Update microsite model image name
    $this->load->model('microsite_model');
    $this->microsite_model->update($microsite_id, [ 'school_image_name' => $this->upload->file_name ]);

    // Delete old program logo from AWS
    $path = $this->input->post('path');
    $this->load->library('aws_s3');
    $delete = $this->aws_s3->delete_image($path);
  }


  private function _construct_fb_image_name($file_name) {
    // construct new file name based on convention
      $fb_image_name_exploded = explode('.', $file_name);
      $result                 = $fb_image_name_exploded[0] . '_fb.' . end($fb_image_name_exploded);
      return $result;
  }


  private function _mutate_image_configs_for_fb_upload($arr = false) {
    $max_desired_value = 200;

    if (isset($arr['scale'])) {
      $arr['scale']          = ( $max_desired_value / max($arr['width'], $arr['height']) );
      $arr['width']          = $arr['width'] * $arr['scale'];
      $arr['height']         = $arr['height'] * $arr['scale'];
      $arr['x_axis']         = 0;
      $arr['y_axis']         = 0;
      $arr['rotation_angle'] = 0;
    }

    return $arr;
  }


  private function _prepare_funds_for_text( $text ) {

    if (empty($text)) {
      return '';
    }

    if ($this->_is_last_character_alpha_numeric($text)) {
      $new_text = substr($text, 0, -1);
      return ( $this->_prepare_funds_for_text($new_text) );
    } else {
      return htmlspecialchars($text);
    }

  }


  private function _is_last_character_alpha_numeric($string) {

    return ! ctype_alnum(substr($string, -1));

  }


  public function new_funds_raised_image($microsite_id) {
    $result          = [];
    $result['error'] = '';
    $this->load->library('aws_s3');
    $img_data['pic_num'] = $this->input->post('pic_num');

    if ($_FILES['funds_raised_img_'.$img_data['pic_num']]['error'] == 1) {
      return false;
    } else {
      $this->_new_funds_raised_img($microsite_id, $img_data);

      //update program todos
      $this->load->model('program_todos_model');
      $this->load->model('microsite_model');
      $microsite_obj = $this->microsite_model->get($microsite_id);
      $this->program_todos_model->update_program_todos_single($microsite_obj->program_id);
    }

    $result['new_image_path'] = s3_url($this->picture_path.$this->upload->file_name);
    $result['image_path']     = $this->picture_path.$this->upload->file_name;
    $result['image']          = $this->upload->file_name;

    $this->output->set_content_type('application/json')
                    ->set_output(json_encode($result));
  }


  private function _new_funds_raised_img($microsite_id, $img_data) {
    $this->load->library('aws_s3');
    $this->load->library('upload');
    $img_config['allowed_types'] = 'gif|jpg|jpeg|png';
    $img_config['max_size']      = '5120';
    $img_config['max_width']     = '5000';
    $img_config['max_height']    = '5000';
    $img_config['hash_flag']     = true;
    $this->load->library('upload', $img_config);
    $this->load->library('image_editor', $img_config);
    $config = [
      'rotation_angle' => $this->input->post('angle'),
      'scale' => $this->input->post('scale'),
      'x_axis' => $this->input->post('x'),
      'y_axis' => $this->input->post('y'),
      'height' => $this->input->post('h'),
      'width' => $this->input->post('w')
    ];

    // New Image
    if (!empty($_FILES['funds_raised_img_'.$img_data['pic_num']]['tmp_name'])) {
      $valid_file = $this->upload->validate_image_upload('funds_raised_img_'.$img_data['pic_num'], $this->picture_path, true);
      if (!$valid_file) {
        return false;
      }

      $cropped_image_path = $this->image_editor->edit_image($_FILES['funds_raised_img_'.$img_data['pic_num']]['tmp_name'], $config);
      $upload             = $this->aws_s3->upload($this->picture_path.$this->upload->file_name, $cropped_image_path);
      // Edit Image
    } elseif ($this->input->post('always_new')) {
      $filename                = $this->upload->get_new_filename($this->input->post('image_name'));
      $cropped_image_path      = $this->image_editor->edit_image(s3_url($this->input->post('path')), $config);
      $upload                  = $this->aws_s3->upload($this->picture_path.$filename, $cropped_image_path);
      $this->upload->file_name = $filename;
    }

    if ($upload) {
      unlink($cropped_image_path);
      $success = true;
    }

    // Insert new image
    $this->load->model('microsite_pic_model');
    $new_img = $this->microsite_pic_model->insert([ 'image' => $this->upload->file_name ]);

    // Update microsite model image name
    $this->load->model('microsite_model');
    $this->microsite_model->update($microsite_id, [ 'pic_'.$img_data['pic_num'] => $new_img ]);
  }


  public function delete_funds_raised_image($microsite_id) {
    $result          = [];
    $result['error'] = '';
    $this->load->model('microsite_model');
    $this->load->model('microsite_pic_model');
    $img_data['pic_num'] = $this->input->post('pic_num');
    $success             = $this->microsite_model->update($microsite_id, [ 'pic_'.$img_data['pic_num'] => null ]);
    if (!$success) {
      $result['error']      = true;
      $result['error_desc'] = 'Could not update microsite';
      log_message('error', 'microsites>delete_funds_raised_image: Could not update microsite');
    }

    //update program todos
    $this->load->model('program_todos_model');
    $this->load->model('microsite_model');
    $microsite_obj = $this->microsite_model->get($microsite_id);
    $this->program_todos_model->update_program_todos_single($microsite_obj->program_id);

    $this->output->set_content_type('application/json')
                     ->set_output(json_encode($result));
  }


  private function _has_funds_for_text($better_funds_text) {

    if (empty($better_funds_text)) {
      return '';
    } else {
      return $this->_prepare_funds_for_text($better_funds_text);
    }

  }


  public function get_custom_colors() {
    $this->load->model('microsite_model');
    $colors = $this->microsite_model->get_custom_colors();
    return $colors;
  }


  public function new_sponsor_logo_image($imageNumber) {
    $result = ['error', ''];
    $this->load->library('aws_s3');
    $this->load->config('upload', true);
    $programId = intval($this->input->post('programid'));
    $this->load->model('sponsor_logos_model');
    $sponsorLogoCount         = $this->sponsor_logos_model->getProgramSponsorLogoCount($programId);
    $allowedSponsorLogosCount = $this->config->item('sponsor_logos_allowed_count', 'upload');
    if ($sponsorLogoCount >= $allowedSponsorLogosCount) {
      $result['error']      = true;
      $result['error_desc'] = 'You have reached the maximum of ' . $allowedSponsorLogosCount . ' sponsor logos allowed.';
    } elseif ($_FILES['sponsor_logo_img_' . $imageNumber]['error'] == 1 ||
      empty($_FILES['sponsor_logo_img_'. $imageNumber]['tmp_name'])) {
      $result['error']      = true;
      $result['error_desc'] = 'There was a problem with the file. Please try again.';
    } elseif (!$this->isValidAdmin($programId)) {
      $result['error']      = true;
      $result['error_desc'] = 'You are not authorized to make this change.';
    } else {
      $url                      = $this->input->post('url');
      $entityId                 = $this->input->post('entity_id');
      $sponsorLogos             = $this->_new_sponsor_logo_img($programId, $imageNumber, $url, $entityId);
      $result['new_image_path'] = s3_url($this->program_sponsor_logo_path.$this->upload->file_name);
      $result['image_path']     = $this->program_sponsor_logo_path.$this->upload->file_name;
      $result['image']          = $this->upload->file_name;
      $result['error']          = false;
      $result['html']           = $this->load->view(
        'admin/programs/edit/partials/sponsor_logos',
        [
          'allowedSponsorLogosCount' => $allowedSponsorLogosCount,
          'programId' => $programId,
          'sponsor_logos' => $sponsorLogos
        ],
        true
      );
    }

    $this->output->set_content_type('application/json')
                    ->set_output(json_encode($result));
  }


  private function _new_sponsor_logo_img($program_id, $img_data, $url, $existingEntityId = null) {
    $this->load->library('aws_s3');
    $this->load->library('upload');
    $img_config['allowed_types'] = 'gif|jpg|jpeg|png';
    $img_config['max_size']      = '5120';
    $img_config['max_width']     = '5000';
    $img_config['max_height']    = '5000';
    $img_config['hash_flag']     = true;
    $this->load->library('upload', $img_config);
    $this->load->library('image_editor', $img_config);
    $config = [
      'rotation_angle' => $this->input->post('angle'),
      'scale' => $this->input->post('scale'),
      'x_axis' => $this->input->post('x'),
      'y_axis' => $this->input->post('y'),
      'height' => $this->input->post('h'),
      'width' => $this->input->post('w')
    ];

    if (!empty($_FILES['sponsor_logo_img_'.$img_data]['tmp_name'])) {
      $valid_file = $this->upload->validate_image_upload('sponsor_logo_img_'.$img_data, $this->program_sponsor_logo_path, true);
      if (!$valid_file) {
        return false;
      }

      $cropped_image_path = $this->image_editor->edit_image($_FILES['sponsor_logo_img_'.$img_data]['tmp_name'], $config);
      $upload             = $this->aws_s3->upload($this->program_sponsor_logo_path.$this->upload->file_name, $cropped_image_path);
      // Edit Image
    } elseif ($this->input->post('always_new')) {
      $filename                = $this->upload->get_new_filename($this->input->post('image_name'));
      $cropped_image_path      = $this->image_editor->edit_image(s3_url($this->input->post('path')), $config);
      $upload                  = $this->aws_s3->upload($this->program_sponsor_logo_path.$filename, $cropped_image_path);
      $this->upload->file_name = $filename;
    }

    if ($upload) {
      unlink($cropped_image_path);
      $success = true;
    }

    $sponsorLogo = [
      'image_name' => $this->upload->file_name,
      'program_id' => $program_id,
      'url' => $url
    ];

    // Insert new image
    $this->load->model('sponsor_logos_model');
    if ($existingEntityId) {
      $this->sponsor_logos_model->update($existingEntityId, $sponsorLogo);
    } else {
      $this->sponsor_logos_model->createProgramSponsorLogo($sponsorLogo);
    }

    $this->db->trans_complete();
    $sponsor_logos = $this->sponsor_logos_model->getProgramSponsorLogos($program_id);
    return $sponsor_logos;
  }


  public function delete_sponsor_logo_image($imageNumber, $entityId) {
    $result                = [];
    $result['error']       = '';
    $result['image_count'] = $imageNumber;
    $this->load->model('sponsor_logos_model');
    $this->load->library('aws_s3');
    $this->load->config('upload', true);
    $sponsorLogo = $this->sponsor_logos_model->getSponsorLogo($entityId);
    $path        = $this->program_sponsor_logo_path . $sponsorLogo->image_name;
    if ($this->isValidAdmin($sponsorLogo->program_id)) {
      $delete = $this->aws_s3->delete_image($path);
      $this->sponsor_logos_model->deleteSponsorLogo($entityId);
      $this->db->trans_complete();
      $sponsorLogos             = $this->sponsor_logos_model->getProgramSponsorLogos($sponsorLogo->program_id);
      $allowedSponsorLogosCount = $this->config->item('sponsor_logos_allowed_count', 'upload');
      $result['html']           = $this->load->view(
        'admin/programs/edit/partials/sponsor_logos',
        [
          'allowedSponsorLogosCount' => $allowedSponsorLogosCount,
          'programId' => $sponsorLogo->program_id,
          'sponsor_logos' => $sponsorLogos
        ],
        true
      );
    } else {
      $result['error']      = true;
      $result['error_desc'] = 'You are not authorized to make this change.';
    }

    $this->output->set_content_type('application/json')
      ->set_output(json_encode($result));
  }


  private function isValidAdmin($programId) {
    $this->load->model('program_model');
    $this->load->model('organization_administrator_model');
    $this->load->model('user_model');

    $program              = $this->program_model->get($programId);
    $is_program_org_admin = $this->organization_administrator_model
      ->is_associated(
        $program->school_id,
        $this->ion_auth->logged_in()
      );
      return $is_program_org_admin || is_sys_admin() || $this->user_model->is_team_member($this->ion_auth->logged_in());
  }


}
