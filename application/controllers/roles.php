<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Roles extends CI_Controller
{
    public function show()
    {
        $data['title'] = 'Roles';
        
        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('roles', $data);
        $this->load->view('layouts/footer');
    }
}
