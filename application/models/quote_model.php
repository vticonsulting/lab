<?php

class Quote_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_quotes($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('quotes.id', 'DESC');
            $this->db->join('categories', 'categories.id = quotes.category_id');

            $query = $this->db->get('quotes');

            return $query->result_array();
        }

        $query = $this->db->get_where('quotes', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_quote($quote_image)
    {
        $slug = url_title($this->input->quote('title'));

        $data = array(
            'title' => $this->input->quote('title'),
            'slug' => $slug,
            'body' => $this->input->quote('body'),
            'category_id' => $this->input->quote('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'quote_image' => $quote_image,
        );

        return $this->db->insert('quotes', $data);
    }

    public function delete_quote($id)
    {
        $image_file_name = $this->db->select('quote_image')->get_where('quotes', array('id' => $id))->row()->quote_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\quotes\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('quotes');

        return true;
    }

    public function update_quote()
    {
        $slug = url_title($this->input->quote('title'), '-', true);

        $data = array(
            'title' => $this->input->quote('title'),
            'slug' => $slug,
            'body' => $this->input->quote('body'),
            'category_id' => $this->input->quote('category_id'),
        );

        $this->db->where('id', $this->input->quote('id'));

        return $this->db->update('quotes', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_quotes_by_category($category_id)
    {
        $this->db->order_by('quotes.id', 'DESC');
        $this->db->join('categories', 'categories.id = quotes.category_id');

        $query = $this->db->get_where('quotes', array('category_id' => $category_id));

        return $query->result_array();
    }
}
