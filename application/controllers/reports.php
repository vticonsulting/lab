<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'Reports';

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('reports/index', $data);
        $this->load->view('layouts/footer');
    }
}
