<?php

class Resource_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_resources($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('resources.id', 'DESC');
            $this->db->join('categories', 'categories.id = resources.category_id');

            $query = $this->db->get('resources');

            return $query->result_array();
        }

        $query = $this->db->get_where('resources', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_resource($resource_image)
    {
        $slug = url_title($this->input->resource('title'));

        $data = array(
            'title' => $this->input->resource('title'),
            'slug' => $slug,
            'body' => $this->input->resource('body'),
            'category_id' => $this->input->resource('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'resource_image' => $resource_image,
        );

        return $this->db->insert('resources', $data);
    }

    public function delete_resource($id)
    {
        $image_file_name = $this->db->select('resource_image')->get_where('resources', array('id' => $id))->row()->resource_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\resources\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('resources');

        return true;
    }

    public function update_resource()
    {
        $slug = url_title($this->input->resource('title'), '-', true);

        $data = array(
            'title' => $this->input->resource('title'),
            'slug' => $slug,
            'body' => $this->input->resource('body'),
            'category_id' => $this->input->resource('category_id'),
        );

        $this->db->where('id', $this->input->resource('id'));

        return $this->db->update('resources', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_resources_by_category($category_id)
    {
        $this->db->order_by('resources.id', 'DESC');
        $this->db->join('categories', 'categories.id = resources.category_id');

        $query = $this->db->get_where('resources', array('category_id' => $category_id));

        return $query->result_array();
    }
}
