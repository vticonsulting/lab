<?php

class Snippet_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_snippets($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('snippets.id', 'DESC');
            $this->db->join('categories', 'categories.id = snippets.category_id');

            $query = $this->db->get('snippets');

            return $query->result_array();
        }

        $query = $this->db->get_where('snippets', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_snippet($snippet_image)
    {
        $slug = url_title($this->input->snippet('title'));

        $data = array(
            'title' => $this->input->snippet('title'),
            'slug' => $slug,
            'body' => $this->input->snippet('body'),
            'category_id' => $this->input->snippet('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'snippet_image' => $snippet_image,
        );

        return $this->db->insert('snippets', $data);
    }

    public function delete_snippet($id)
    {
        $image_file_name = $this->db->select('snippet_image')->get_where('snippets', array('id' => $id))->row()->snippet_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\snippets\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('snippets');

        return true;
    }

    public function update_snippet()
    {
        $slug = url_title($this->input->snippet('title'), '-', true);

        $data = array(
            'title' => $this->input->snippet('title'),
            'slug' => $slug,
            'body' => $this->input->snippet('body'),
            'category_id' => $this->input->snippet('category_id'),
        );

        $this->db->where('id', $this->input->snippet('id'));

        return $this->db->update('snippets', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_snippets_by_category($category_id)
    {
        $this->db->order_by('snippets.id', 'DESC');
        $this->db->join('categories', 'categories.id = snippets.category_id');

        $query = $this->db->get_where('snippets', array('category_id' => $category_id));

        return $query->result_array();
    }
}
