<?php

class Volunteer_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_volunteers($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('volunteers.id', 'DESC');
            $this->db->join('categories', 'categories.id = volunteers.category_id');

            $query = $this->db->get('volunteers');

            return $query->result_array();
        }

        $query = $this->db->get_where('volunteers', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_volunteer($volunteer_image)
    {
        $slug = url_title($this->input->volunteer('title'));

        $data = array(
            'title' => $this->input->volunteer('title'),
            'slug' => $slug,
            'body' => $this->input->volunteer('body'),
            'category_id' => $this->input->volunteer('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'volunteer_image' => $volunteer_image,
        );

        return $this->db->insert('volunteers', $data);
    }

    public function delete_volunteer($id)
    {
        $image_file_name = $this->db->select('volunteer_image')->get_where('volunteers', array('id' => $id))->row()->volunteer_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\volunteers\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('volunteers');

        return true;
    }

    public function update_volunteer()
    {
        $slug = url_title($this->input->volunteer('title'), '-', true);

        $data = array(
            'title' => $this->input->volunteer('title'),
            'slug' => $slug,
            'body' => $this->input->volunteer('body'),
            'category_id' => $this->input->volunteer('category_id'),
        );

        $this->db->where('id', $this->input->volunteer('id'));

        return $this->db->update('volunteers', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_volunteers_by_category($category_id)
    {
        $this->db->order_by('volunteers.id', 'DESC');
        $this->db->join('categories', 'categories.id = volunteers.category_id');

        $query = $this->db->get_where('volunteers', array('category_id' => $category_id));

        return $query->result_array();
    }
}
