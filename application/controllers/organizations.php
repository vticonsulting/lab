<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Organizations extends CI_Controller
{
	public function index($offset = 0) {
		// Pagination Config
		$config['base_url'] = base_url() . 'organizations/index/';
		$config['total_rows'] = $this->db->count_all('organizations');
		$config['per_page'] = 3;
		$config['uri_segment'] = 3;
		$config['attributes'] = array('class' => 'pagination-link');

		// Init Pagination
		$this->pagination->initialize($config);

		$data['title'] = 'Latest organizations';

		$data['organizations'] = $this->organization_model->get_organizations(false, $config['per_page'], $offset);

		$this->load->view('layouts/header');
		$this->load->view('layouts/nav');
		$this->load->view('organizations/index', $data);
		$this->load->view('layouts/footer');
	}

	public function view($slug = null) {
		$data['organization'] = $this->organization_model->get_organizations($slug);

		if (empty($data['organization'])) {
			show_404();
		}
		$data['title'] = $data['organization']['title'];

		$this->load->view('layouts/header');
		$this->load->view('layouts/nav');
		$this->load->view('organizations/view', $data);
		$this->load->view('layouts/footer');
	}

	public function create() {
		// Check login
		if (! $this->session->userdata('logged_in')) {
			redirect('users/login');
		}

		$data['title'] = 'Create organization';

		$data['categories'] = $this->organization_model->get_categories();

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('body', 'Body', 'required');

		if ($this->form_validation->run() === false) {
			$this->load->view('layouts/header');
			$this->load->view('layouts/nav');
			$this->load->view('organizations/create', $data);
			$this->load->view('layouts/footer');
		} else {
			// Upload Image
			$config['upload_path'] = './assets/images/organizations';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = '2048';
			$config['max_width'] = '2000';
			$config['max_height'] = '2000';

			$this->load->library('upload', $config);

			if (! $this->upload->do_upload()) {
				$errors = array('error' => $this->upload->display_errors());
				$organization_image = 'noimage.jpg';
			} else {
				$data = array('upload_data' => $this->upload->data());
				$organization_image = $_FILES['userfile']['name'];
			}

			$this->organization_model->create_organization($organization_image);

			// Set message
			$this->session->set_flashdata('organization_created', 'Your organization has been created');

			redirect('organizations');
		}
	}

	public function delete($id) {
		// Check login
		if (! $this->session->userdata('logged_in')) {
			redirect('users/login');
		}

		$this->organization_model->delete_organization($id);

		// Set message
		$this->session->set_flashdata('organization_deleted', 'Your organization has been deleted');

		redirect('organizations');
	}

	public function edit($slug) {
		// Check login
		if (! $this->session->userdata('logged_in')) {
			redirect('users/login');
		}

		$data['organization'] = $this->organization_model->get_organizations($slug);

		// Check user
		if ($this->session->userdata('user_id') != $this->organization_model->get_organizations($slug)['user_id']) {
			redirect('organizations');
		}

		$data['categories'] = $this->organization_model->get_categories();

		if (empty($data['organization'])) {
			show_404();
		}

		$data['title'] = 'Edit organization';

		$this->load->view('layouts/header');
		$this->load->view('layouts/nav');
		$this->load->view('organizations/edit', $data);
		$this->load->view('layouts/footer');
	}

	public function update() {
		// Check login
		if (! $this->session->userdata('logged_in')) {
			redirect('users/login');
		}

		$this->organization_model->update_organization();

		// Set message
		$this->session->set_flashdata('organization_updated', 'Your organization has been updated');

		redirect('organizations');
	}
}
