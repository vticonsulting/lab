<?php

class Prospect_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_prospects($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('prospects.id', 'DESC');
            $this->db->join('categories', 'categories.id = prospects.category_id');

            $query = $this->db->get('prospects');

            return $query->result_array();
        }

        $query = $this->db->get_where('prospects', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_prospect($prospect_image)
    {
        $slug = url_title($this->input->prospect('title'));

        $data = array(
            'title' => $this->input->prospect('title'),
            'slug' => $slug,
            'body' => $this->input->prospect('body'),
            'category_id' => $this->input->prospect('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'prospect_image' => $prospect_image,
        );

        return $this->db->insert('prospects', $data);
    }

    public function delete_prospect($id)
    {
        $image_file_name = $this->db->select('prospect_image')->get_where('prospects', array('id' => $id))->row()->prospect_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\prospects\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('prospects');

        return true;
    }

    public function update_prospect()
    {
        $slug = url_title($this->input->prospect('title'), '-', true);

        $data = array(
            'title' => $this->input->prospect('title'),
            'slug' => $slug,
            'body' => $this->input->prospect('body'),
            'category_id' => $this->input->prospect('category_id'),
        );

        $this->db->where('id', $this->input->prospect('id'));

        return $this->db->update('prospects', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_prospects_by_category($category_id)
    {
        $this->db->order_by('prospects.id', 'DESC');
        $this->db->join('categories', 'categories.id = prospects.category_id');

        $query = $this->db->get_where('prospects', array('category_id' => $category_id));

        return $query->result_array();
    }
}
