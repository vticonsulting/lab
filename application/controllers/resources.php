<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resources extends CI_Controller
{
    public function index($offset = 0)
    {
        $data['title'] = 'FAM Resources';

        // Pagination Config
        $config['base_url'] = base_url() . 'resources/index/';
        $config['total_rows'] = $this->db->count_all('resources');
        $config['per_page'] = 3;
        $config['uri_segment'] = 3;
        $config['attributes'] = array('class' => 'pagination-link');

        // Init Pagination
        $this->pagination->initialize($config);


        $data['resources'] = $this->resource_model->get_resources(false, $config['per_page'], $offset);

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('resources/index', $data);
        $this->load->view('layouts/footer');
    }

    public function view($slug = null)
    {
        $data['resource'] = $this->resource_model->get_resources($slug);

        if (empty($data['resource'])) {
            show_404();
        }
        $data['title'] = $data['resource']['title'];

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('resources/view', $data);
        $this->load->view('layouts/footer');
    }

    public function create()
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['title'] = 'Create resource';

        $data['categories'] = $this->resource->get_categories();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('body', 'Body', 'required');

        if ($this->form_validation->run() === false) {
            $this->load->view('layouts/header');
            $this->load->view('layouts/nav');
            $this->load->view('resources/create', $data);
            $this->load->view('layouts/footer');
        } else {
            // Upload Image
            $config['upload_path'] = './assets/images/resources';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '2048';
            $config['max_width'] = '2000';
            $config['max_height'] = '2000';

            $this->load->library('upload', $config);

            if (! $this->upload->do_upload()) {
                $errors = array('error' => $this->upload->display_errors());
                $resource_image = 'noimage.jpg';
            } else {
                $data = array('upload_data' => $this->upload->data());
                $resource_image = $_FILES['userfile']['name'];
            }

            $this->resource_model->create_resource($resource_image);

            // Set message
            $this->session->set_flashdata('resource_created', 'Your resource has been created');

            redirect('resources');
        }
    }

    public function delete($id)
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $this->resource_model->delete_resource($id);

        // Set message
        $this->session->set_flashdata('resource_deleted', 'Your resource has been deleted');

        redirect('resources');
    }

    public function edit($slug)
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['resource'] = $this->resource_model->get_resources($slug);

        // Check user
        if ($this->session->userdata('user_id') != $this->resource_model->get_resources($slug)['user_id']) {
            redirect('resources');
        }

        $data['categories'] = $this->resource_model->get_categories();

        if (empty($data['resource'])) {
            show_404();
        }

        $data['title'] = 'Edit resource';

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('resources/edit', $data);
        $this->load->view('layouts/footer');
    }

    public function update()
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $this->resource_model->update_resource();

        // Set message
        $this->session->set_flashdata('resource_updated', 'Your resource has been updated');

        redirect('resources');
    }
}
