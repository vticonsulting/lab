<?php

class Advocate_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_advocates($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('advocates.id', 'DESC');
            $this->db->join('categories', 'categories.id = advocates.category_id');

            $query = $this->db->get('advocates');

            return $query->result_array();
        }

        $query = $this->db->get_where('advocates', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_advocate($advocate_image)
    {
        $slug = url_title($this->input->advocate('title'));

        $data = array(
            'title' => $this->input->advocate('title'),
            'slug' => $slug,
            'body' => $this->input->advocate('body'),
            'category_id' => $this->input->advocate('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'advocate_image' => $advocate_image,
        );

        return $this->db->insert('advocates', $data);
    }

    public function delete_advocate($id)
    {
        $image_file_name = $this->db->select('advocate_image')->get_where('advocates', array('id' => $id))->row()->advocate_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\advocates\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('advocates');

        return true;
    }

    public function update_advocate()
    {
        $slug = url_title($this->input->advocate('title'), '-', true);

        $data = array(
            'title' => $this->input->advocate('title'),
            'slug' => $slug,
            'body' => $this->input->advocate('body'),
            'category_id' => $this->input->advocate('category_id'),
        );

        $this->db->where('id', $this->input->advocate('id'));

        return $this->db->update('advocates', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_advocates_by_category($category_id)
    {
        $this->db->order_by('advocates.id', 'DESC');
        $this->db->join('categories', 'categories.id = advocates.category_id');

        $query = $this->db->get_where('advocates', array('category_id' => $category_id));

        return $query->result_array();
    }
}
