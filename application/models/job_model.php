<?php

class Job_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_jobs($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('jobs.id', 'DESC');
            $this->db->join('categories', 'categories.id = jobs.category_id');

            $query = $this->db->get('jobs');

            return $query->result_array();
        }

        $query = $this->db->get_where('jobs', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_job($job_image)
    {
        $slug = url_title($this->input->job('title'));

        $data = array(
            'title' => $this->input->job('title'),
            'slug' => $slug,
            'body' => $this->input->job('body'),
            'category_id' => $this->input->job('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'job_image' => $job_image,
        );

        return $this->db->insert('jobs', $data);
    }

    public function delete_job($id)
    {
        $image_file_name = $this->db->select('job_image')->get_where('jobs', array('id' => $id))->row()->job_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\jobs\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('jobs');

        return true;
    }

    public function update_job()
    {
        $slug = url_title($this->input->job('title'), '-', true);

        $data = array(
            'title' => $this->input->job('title'),
            'slug' => $slug,
            'body' => $this->input->job('body'),
            'category_id' => $this->input->job('category_id'),
        );

        $this->db->where('id', $this->input->job('id'));

        return $this->db->update('jobs', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_jobs_by_category($category_id)
    {
        $this->db->order_by('jobs.id', 'DESC');
        $this->db->join('categories', 'categories.id = jobs.category_id');

        $query = $this->db->get_where('jobs', array('category_id' => $category_id));

        return $query->result_array();
    }
}
