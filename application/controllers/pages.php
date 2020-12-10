<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends CI_Controller
{
    public function show($page = 'home')
    {
        if (! file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
            show_404();
        }

        // $raw = '22. 11. 1968';
        // $start = DateTime::createFromFormat('d. m. Y', $raw);
        // d($start->format('Y-m-d'));

        // $end = clone $start;
        // $end->add(new DateInterval('P1M6D'));
        // $diff = $end->diff($start);
        // d($diff->format('%m month, %d days (total: %a days)'));

        $data['title'] = ucfirst($page);
        $data['keywords'] = $this->config->item('site_keywords');

        // $this->load->view('layouts/hello_bar');
        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('pages/' . $page, $data);
        $this->load->view('layouts/footer');
    }
}
