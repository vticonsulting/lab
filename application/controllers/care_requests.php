<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Care_requests extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'Care Requests';

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('care_requests/index', $data);
        $this->load->view('layouts/footer');
    }
}
