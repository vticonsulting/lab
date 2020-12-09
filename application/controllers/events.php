<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Events extends CI_Controller
{
    public function index($offset = 0)
    {
        $data['title'] = 'List Events';

        // Pagination Config
        $config['base_url'] = base_url() . 'events/index/';
        $config['total_rows'] = $this->db->count_all('events');
        $config['per_page'] = 3;
        $config['uri_segment'] = 3;
        $config['attributes'] = array('class' => 'pagination-link');
        
        // Init Pagination
        $this->pagination->initialize($config);

        $data['events'] = $this->event_model->get_events(false, $config['per_page'], $offset);

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('events/index', $data);
        $this->load->view('layouts/footer');
    }

    public function view($slug = null)
    {
        $data['event'] = $this->event_model->get_events($slug);

        if (empty($data['event'])) {
            show_404();
        }

        $data['title'] = $data['event']['title'];

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('events/view', $data);
        $this->load->view('layouts/footer');
    }

    public function create()
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['title'] = 'Create Post';

        $data['categories'] = $this->event_model->get_categories();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('body', 'Body', 'required');

        if ($this->form_validation->run() === false) {
            $this->load->view('layouts/header');
            $this->load->view('layouts/nav');
            $this->load->view('events/create', $data);
            $this->load->view('layouts/footer');
        } else {
            // Upload Image
            $config['upload_path'] = './assets/images/events';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '2048';
            $config['max_width'] = '2000';
            $config['max_height'] = '2000';

            $this->load->library('upload', $config);

            if (! $this->upload->do_upload()) {
                $errors = array('error' => $this->upload->display_errors());
                $event_image = 'noimage.jpg';
            } else {
                $data = array('upload_data' => $this->upload->data());
                $event_image = $_FILES['userfile']['name'];
            }

            $this->event_model->create_event($event_image);

            // Set message
            $this->session->set_flashdata('event_created', 'Your event has been created');

            redirect('events');
        }
    }

    public function delete($id)
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $this->event_model->delete_event($id);

        // Set message
        $this->session->set_flashdata('event_deleted', 'Your event has been deleted');

        redirect('events');
    }

    public function edit($slug)
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['event'] = $this->event_model->get_events($slug);

        // Check user
        if ($this->session->userdata('user_id') != $this->event_model->get_events($slug)['user_id']) {
            redirect('events');
        }

        $data['categories'] = $this->event_model->get_categories();

        if (empty($data['event'])) {
            show_404();
        }

        $data['title'] = 'Edit Post';

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('events/edit', $data);
        $this->load->view('layouts/footer');
    }

    public function update()
    {
        // Check login
        if (! $this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $this->event_model->update_event();

        // Set message
        $this->session->set_flashdata('event_updated', 'Your event has been updated');

        redirect('events');
    }
}
