<?php

class Affiliate_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_affiliates($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('affiliates.id', 'DESC');
            $this->db->join('categories', 'categories.id = affiliates.category_id');

            $query = $this->db->get('affiliates');

            return $query->result_array();
        }

        $query = $this->db->get_where('affiliates', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_affiliate($affiliate_image)
    {
        $slug = url_title($this->input->affiliate('title'));

        $data = array(
            'title' => $this->input->affiliate('title'),
            'slug' => $slug,
            'body' => $this->input->affiliate('body'),
            'category_id' => $this->input->affiliate('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'affiliate_image' => $affiliate_image,
        );

        return $this->db->insert('affiliates', $data);
    }

    public function delete_affiliate($id)
    {
        $image_file_name = $this->db->select('affiliate_image')->get_where('affiliates', array('id' => $id))->row()->affiliate_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\affiliates\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('affiliates');

        return true;
    }

    public function update_affiliate()
    {
        $slug = url_title($this->input->affiliate('title'), '-', true);

        $data = array(
            'title' => $this->input->affiliate('title'),
            'slug' => $slug,
            'body' => $this->input->affiliate('body'),
            'category_id' => $this->input->affiliate('category_id'),
        );

        $this->db->where('id', $this->input->affiliate('id'));

        return $this->db->update('affiliates', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_affiliates_by_category($category_id)
    {
        $this->db->order_by('affiliates.id', 'DESC');
        $this->db->join('categories', 'categories.id = affiliates.category_id');

        $query = $this->db->get_where('affiliates', array('category_id' => $category_id));

        return $query->result_array();
    }
}
