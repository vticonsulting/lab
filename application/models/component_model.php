<?php

class Component_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_components($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('components.id', 'DESC');
            $this->db->join('categories', 'categories.id = components.category_id');

            $query = $this->db->get('components');

            return $query->result_array();
        }

        $query = $this->db->get_where('components', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_component($component_image)
    {
        $slug = url_title($this->input->component('title'));

        $data = array(
            'title' => $this->input->component('title'),
            'slug' => $slug,
            'body' => $this->input->component('body'),
            'category_id' => $this->input->component('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'component_image' => $component_image,
        );

        return $this->db->insert('components', $data);
    }

    public function delete_component($id)
    {
        $image_file_name = $this->db->select('component_image')->get_where('components', array('id' => $id))->row()->component_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\components\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('components');

        return true;
    }

    public function update_component()
    {
        $slug = url_title($this->input->component('title'), '-', true);

        $data = array(
            'title' => $this->input->component('title'),
            'slug' => $slug,
            'body' => $this->input->component('body'),
            'category_id' => $this->input->component('category_id'),
        );

        $this->db->where('id', $this->input->component('id'));

        return $this->db->update('components', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_components_by_category($category_id)
    {
        $this->db->order_by('components.id', 'DESC');
        $this->db->join('categories', 'categories.id = components.category_id');

        $query = $this->db->get_where('components', array('category_id' => $category_id));

        return $query->result_array();
    }
}
