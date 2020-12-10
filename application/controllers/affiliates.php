<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Affiliates extends CI_Controller
{
    public function index($offset = 0)
    {
        // Pagination Config
        $config['base_url'] = base_url() . 'affiliates/index/';
        $config['total_rows'] = $this->db->count_all('affiliates');
        $config['per_page'] = 3;
        $config['uri_segment'] = 3;
        $config['attributes'] = array('class' => 'pagination-link');

        // Init Pagination
        $this->pagination->initialize($config);

        $data['title'] = 'Latest Posts';

        $data['affiliates'] = $this->affiliate_model->get_affiliates(false, $config['per_page'], $offset);

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('affiliates/index', $data);
        $this->load->view('layouts/footer');
    }

    public function show($id)
    {
        $data['affiliate'] = $this->affiliate_model->get_affiliates($slug);

        if (empty($data['affiliate'])) {
            show_404();
        }
        $data['title'] = $data['affiliate']['title'];

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('affiliates/view', $data);
        $this->load->view('layouts/footer');
    }

    public function create()
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['title'] = 'Create Post';

        $data['categories'] = $this->affiliate_model->get_categories();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('body', 'Body', 'required');

        if ($this->form_validation->run() === false) {
            $this->load->view('layouts/header');
            $this->load->view('layouts/nav');
            $this->load->view('affiliates/create', $data);
            $this->load->view('layouts/footer');
        } else {
            // Upload Image
            $config['upload_path'] = './assets/images/affiliates';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '2048';
            $config['max_width'] = '2000';
            $config['max_height'] = '2000';

            $this->load->library('upload', $config);

            if (! $this->upload->do_upload()) {
                $errors = array('error' => $this->upload->display_errors());
                $affiliate_image = 'noimage.jpg';
            } else {
                $data = array('upload_data' => $this->upload->data());
                $affiliate_image = $_FILES['userfile']['name'];
            }

            $this->affiliate_model->create_affiliate($affiliate_image);

            // Set message
            $this->session->set_flashdata('affiliate_created', 'Your affiliate has been created');

            redirect('affiliates');
        }
    }

    public function destroy($id)
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $this->affiliate_model->delete_affiliate($id);

        // Set message
        $this->session->set_flashdata('affiliate_deleted', 'Your affiliate has been deleted');

        redirect('affiliates');
    }

    public function edit($slug)
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['affiliate'] = $this->affiliate_model->get_affiliates($slug);

        // Check user
        if ($this->session->userdata('user_id') != $this->affiliate_model->get_affiliates($slug)['user_id']) {
            redirect('affiliates');
        }

        $data['categories'] = $this->affiliate_model->get_categories();

        if (empty($data['affiliate'])) {
            show_404();
        }

        $data['title'] = 'Edit Post';

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('affiliates/edit', $data);
        $this->load->view('layouts/footer');
    }

    public function update()
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $this->affiliate_model->update_affiliate();

        // Set message
        $this->session->set_flashdata('affiliate_updated', 'Your affiliate has been updated');

        redirect('affiliates');
    }
}
