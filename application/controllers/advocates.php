<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Advocates extends CI_Controller
{
    public function index($offset = 0)
    {
        // Pagination Config
        $config['base_url'] = base_url() . 'advocates/index/';
        $config['total_rows'] = $this->db->count_all('advocates');
        $config['per_page'] = 3;
        $config['uri_segment'] = 3;
        $config['attributes'] = array('class' => 'pagination-link');

        // Init Pagination
        $this->pagination->initialize($config);

        $data['title'] = 'Latest Posts';

        $data['advocates'] = $this->advocate_model->get_advocates(false, $config['per_page'], $offset);

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('advocates/index', $data);
        $this->load->view('layouts/footer');
    }

    public function show($id)
    {
        $data['advocate'] = $this->advocate_model->get_advocates($slug);

        if (empty($data['advocate'])) {
            show_404();
        }
        $data['title'] = $data['advocate']['title'];

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('advocates/view', $data);
        $this->load->view('layouts/footer');
    }

    public function create()
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['title'] = 'Create Post';

        $data['categories'] = $this->advocate_model->get_categories();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('body', 'Body', 'required');

        if ($this->form_validation->run() === false) {
            $this->load->view('layouts/header');
            $this->load->view('layouts/nav');
            $this->load->view('advocates/create', $data);
            $this->load->view('layouts/footer');
        } else {
            // Upload Image
            $config['upload_path'] = './assets/images/advocates';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '2048';
            $config['max_width'] = '2000';
            $config['max_height'] = '2000';

            $this->load->library('upload', $config);

            if (! $this->upload->do_upload()) {
                $errors = array('error' => $this->upload->display_errors());
                $advocate_image = 'noimage.jpg';
            } else {
                $data = array('upload_data' => $this->upload->data());
                $advocate_image = $_FILES['userfile']['name'];
            }

            $this->advocate_model->create_advocate($advocate_image);

            // Set message
            $this->session->set_flashdata('advocate_created', 'Your advocate has been created');

            redirect('advocates');
        }
    }

    public function destroy($id)
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $this->advocate_model->delete_advocate($id);

        // Set message
        $this->session->set_flashdata('advocate_deleted', 'Your advocate has been deleted');

        redirect('advocates');
    }

    public function edit($slug)
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['advocate'] = $this->advocate_model->get_advocates($slug);

        // Check user
        if ($this->session->userdata('user_id') != $this->advocate_model->get_advocates($slug)['user_id']) {
            redirect('advocates');
        }

        $data['categories'] = $this->advocate_model->get_categories();

        if (empty($data['advocate'])) {
            show_404();
        }

        $data['title'] = 'Edit Post';

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('advocates/edit', $data);
        $this->load->view('layouts/footer');
    }

    public function update()
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $this->advocate_model->update_advocate();

        // Set message
        $this->session->set_flashdata('advocate_updated', 'Your advocate has been updated');

        redirect('advocates');
    }
}
