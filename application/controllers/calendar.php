<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Calendar extends CI_Controller
{
    public function show()
    {
        $data['title'] = 'Calendar';
        
        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('calendar', $data);
        $this->load->view('layouts/footer');
    }
}
