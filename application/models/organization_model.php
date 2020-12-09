<?php

class Organization_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_organizations($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('organizations.id', 'DESC');
            $this->db->join('categories', 'categories.id = organizations.category_id');

            $query = $this->db->get('organizations');

            return $query->result_array();
        }

        $query = $this->db->get_where('organizations', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_organization($organization_image)
    {
        $slug = url_title($this->input->organization('title'));

        $data = array(
            'title' => $this->input->organization('title'),
            'slug' => $slug,
            'body' => $this->input->organization('body'),
            'category_id' => $this->input->organization('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'organization_image' => $organization_image,
        );

        return $this->db->insert('organizations', $data);
    }

    public function delete_organization($id)
    {
        $image_file_name = $this->db->select('organization_image')->get_where('organizations', array('id' => $id))->row()->organization_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\organizations\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('organizations');

        return true;
    }

    public function update_organization()
    {
        $slug = url_title($this->input->organization('title'), '-', true);

        $data = array(
            'title' => $this->input->organization('title'),
            'slug' => $slug,
            'body' => $this->input->organization('body'),
            'category_id' => $this->input->organization('category_id'),
        );

        $this->db->where('id', $this->input->organization('id'));

        return $this->db->update('organizations', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_organizations_by_category($category_id)
    {
        $this->db->order_by('organizations.id', 'DESC');
        $this->db->join('categories', 'categories.id = organizations.category_id');

        $query = $this->db->get_where('organizations', array('category_id' => $category_id));

        return $query->result_array();
    }
}
