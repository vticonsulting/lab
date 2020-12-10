<?php

class Contact_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_contacts($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('contacts.id', 'DESC');
            $this->db->join('categories', 'categories.id = contacts.category_id');

            $query = $this->db->get('contacts');

            return $query->result_array();
        }

        $query = $this->db->get_where('contacts', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_contact($contact_image)
    {
        $slug = url_title($this->input->contact('title'));

        $data = array(
            'title' => $this->input->contact('title'),
            'slug' => $slug,
            'body' => $this->input->contact('body'),
            'category_id' => $this->input->contact('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'contact_image' => $contact_image,
        );

        return $this->db->insert('contacts', $data);
    }

    public function delete_contact($id)
    {
        $image_file_name = $this->db->select('contact_image')->get_where('contacts', array('id' => $id))->row()->contact_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\contacts\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('contacts');

        return true;
    }

    public function update_contact()
    {
        $slug = url_title($this->input->contact('title'), '-', true);

        $data = array(
            'title' => $this->input->contact('title'),
            'slug' => $slug,
            'body' => $this->input->contact('body'),
            'category_id' => $this->input->contact('category_id'),
        );

        $this->db->where('id', $this->input->contact('id'));

        return $this->db->update('contacts', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_contacts_by_category($category_id)
    {
        $this->db->order_by('contacts.id', 'DESC');
        $this->db->join('categories', 'categories.id = contacts.category_id');

        $query = $this->db->get_where('contacts', array('category_id' => $category_id));

        return $query->result_array();
    }
}
