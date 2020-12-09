<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Properties extends CI_Controller
{
    public function index()
    {
        $data['user_name'] = 'Bernard';
        $data['status_group'] = ['All', 'Available', 'Unavailable'];
        $data['properties'] = $this->property_model->all();
        $data['selected_filter'] = $this->session->selected_filter;

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('properties/index', $data);
        $this->load->view('layouts/footer');
    }

    public function set_filter()
    {
        $session_data['selected_filter'] = $this->input->get('filter');
        $this->session->set_userdata($session_data);
        redirect('properties/index');
    }

    public function show($id)
    {
        $data['id'] = $id;

        // $this->load->model('property');
        $data['name'] =$this->property_model->get();
        $version = $this->property_model->get_version();
        $data['version'] = $version->conn_id->server_info;

        $this->load->view('properties/show', $data);
    }

    public function edit($id)
    {
        $this->load->helper('form');

        if ($_POST) {
            $image = false;

            if ($_FILES) {
                $image = $this->do_upload();
            }

            $name = $this->input->post('name');
            $description = $this->input->post('description');
            $new_data['name'] = $name;
            $new_data['description'] = $description;

            if ($image) {
                $new_data['image'] = $image;
            }

            $this->property_model->update($id, $new_data);
            redirect('properties/index');
        }

        $data['property'] = $this->property_model->get($id);

        $this->load->view('layouts/header');
        $this->load->view('layouts/nav');
        $this->load->view('properties/edit', $data);
        $this->load->view('layouts/footer');
    }

    public function do_upload()
    {
        $config['upload_path'] = './assets/images/';
        $config['allowed_types'] = 'gif|jpg|png';

        $this->load->library('upload', $config);
        $this->upload->do_upload('image_file');

        $data = $this->upload->data();
        return $data['file_name'];
    }

    public function kml_export()
    {
        //$this->output->set_content_type('application/xml');
        $this->output->set_content_type('application/octet-stream');
        header('Content-Disposition: inline; filename="real_estate_kml_export.kml"');
        $this->load->view('properties/kml_export');
    }

    public function view_image()
    {
        $image = file_get_contents('assets/images/ThinkstockPhotos-145054512_small.jpg');
        $this->output->set_content_type('jpeg')->set_output($image);
    }

    public function db_test()
    {
        $this->property_model->connection_test();
    }
}
