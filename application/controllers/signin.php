<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Signin extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'Signin';

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === false) {
            $this->template->write('title', 'Signin to Roy Tutorials', true);
            $this->template->add_js('assets/js/niceforms.js');
            $this->template->add_css('assets/css/page.css');
            $this->template->write_view('content', 'signin', '', true);
            $this->template->render();
        } else {
            redirect();
        }
    }
}
