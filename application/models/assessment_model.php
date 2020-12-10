<?php

class Assessment_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_assessments($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('assessments.id', 'DESC');
            $this->db->join('categories', 'categories.id = assessments.category_id');

            $query = $this->db->get('assessments');

            return $query->result_array();
        }

        $query = $this->db->get_where('assessments', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_assessment($assessment_image)
    {
        $slug = url_title($this->input->assessment('title'));

        $data = array(
            'title' => $this->input->assessment('title'),
            'slug' => $slug,
            'body' => $this->input->assessment('body'),
            'category_id' => $this->input->assessment('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'assessment_image' => $assessment_image,
        );

        return $this->db->insert('assessments', $data);
    }

    public function delete_assessment($id)
    {
        $image_file_name = $this->db->select('assessment_image')->get_where('assessments', array('id' => $id))->row()->assessment_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\assessments\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('assessments');

        return true;
    }

    public function update_assessment()
    {
        $slug = url_title($this->input->assessment('title'), '-', true);

        $data = array(
            'title' => $this->input->assessment('title'),
            'slug' => $slug,
            'body' => $this->input->assessment('body'),
            'category_id' => $this->input->assessment('category_id'),
        );

        $this->db->where('id', $this->input->assessment('id'));

        return $this->db->update('assessments', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_assessments_by_category($category_id)
    {
        $this->db->order_by('assessments.id', 'DESC');
        $this->db->join('categories', 'categories.id = assessments.category_id');

        $query = $this->db->get_where('assessments', array('category_id' => $category_id));

        return $query->result_array();
    }
}
