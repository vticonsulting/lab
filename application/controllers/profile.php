<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function show()
    {
        $data['title'] = 'My Profile';

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('profile', $data);
        $this->load->view('layouts/footer');
    }
}
