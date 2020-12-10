<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Components extends CI_Controller
{
    public function index($offset = 0)
    {
        // Pagination Config
        $config['base_url'] = base_url() . 'components/index/';
        $config['total_rows'] = $this->db->count_all('components');
        $config['per_page'] = 3;
        $config['uri_segment'] = 3;
        $config['attributes'] = array('class' => 'pagination-link');

        // Init Pagination
        $this->pagination->initialize($config);

        $data['title'] = 'Latest Posts';

        $data['components'] = $this->component_model->get_components(false, $config['per_page'], $offset);

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('components/index', $data);
        $this->load->view('layouts/footer');
    }

    public function view($slug = null)
    {
        $data['component'] = $this->component_model->get_components($slug);

        if (empty($data['component'])) {
            show_404();
        }
        $data['title'] = $data['component']['title'];

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('components/view', $data);
        $this->load->view('layouts/footer');
    }

    public function create()
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['title'] = 'Create Post';

        $data['categories'] = $this->component_model->get_categories();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('body', 'Body', 'required');

        if ($this->form_validation->run() === false) {
            $this->load->view('layouts/header');
            $this->load->view('layouts/nav');
            $this->load->view('components/create', $data);
            $this->load->view('layouts/footer');
        } else {
            // Upload Image
            $config['upload_path'] = './assets/images/components';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '2048';
            $config['max_width'] = '2000';
            $config['max_height'] = '2000';

            $this->load->library('upload', $config);

            if (! $this->upload->do_upload()) {
                $errors = array('error' => $this->upload->display_errors());
                $component_image = 'noimage.jpg';
            } else {
                $data = array('upload_data' => $this->upload->data());
                $component_image = $_FILES['userfile']['name'];
            }

            $this->component_model->create_component($component_image);

            // Set message
            $this->session->set_flashdata('component_created', 'Your component has been created');

            redirect('components');
        }
    }

    public function delete($id)
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $this->component_model->delete_component($id);

        // Set message
        $this->session->set_flashdata('component_deleted', 'Your component has been deleted');

        redirect('components');
    }

    public function edit($slug)
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['component'] = $this->component_model->get_components($slug);

        // Check user
        if ($this->session->userdata('user_id') != $this->component_model->get_components($slug)['user_id']) {
            redirect('components');
        }

        $data['categories'] = $this->component_model->get_categories();

        if (empty($data['component'])) {
            show_404();
        }

        $data['title'] = 'Edit Post';

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('components/edit', $data);
        $this->load->view('layouts/footer');
    }

    public function update()
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $this->component_model->update_component();

        // Set message
        $this->session->set_flashdata('component_updated', 'Your component has been updated');

        redirect('components');
    }
}
