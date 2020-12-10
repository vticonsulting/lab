<?php

class Assignment_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_assignments($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('assignments.id', 'DESC');
            $this->db->join('categories', 'categories.id = assignments.category_id');

            $query = $this->db->get('assignments');

            return $query->result_array();
        }

        $query = $this->db->get_where('assignments', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_assignment($assignment_image)
    {
        $slug = url_title($this->input->assignment('title'));

        $data = array(
            'title' => $this->input->assignment('title'),
            'slug' => $slug,
            'body' => $this->input->assignment('body'),
            'category_id' => $this->input->assignment('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'assignment_image' => $assignment_image,
        );

        return $this->db->insert('assignments', $data);
    }

    public function delete_assignment($id)
    {
        $image_file_name = $this->db->select('assignment_image')->get_where('assignments', array('id' => $id))->row()->assignment_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\assignments\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('assignments');

        return true;
    }

    public function update_assignment()
    {
        $slug = url_title($this->input->assignment('title'), '-', true);

        $data = array(
            'title' => $this->input->assignment('title'),
            'slug' => $slug,
            'body' => $this->input->assignment('body'),
            'category_id' => $this->input->assignment('category_id'),
        );

        $this->db->where('id', $this->input->assignment('id'));

        return $this->db->update('assignments', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_assignments_by_category($category_id)
    {
        $this->db->order_by('assignments.id', 'DESC');
        $this->db->join('categories', 'categories.id = assignments.category_id');

        $query = $this->db->get_where('assignments', array('category_id' => $category_id));

        return $query->result_array();
    }
}
