<?php

class Event_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_events($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('events.id', 'DESC');
            $this->db->join('categories', 'categories.id = events.category_id');

            $query = $this->db->get('events');

            return $query->result_array();
        }

        $query = $this->db->get_where('events', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_event($event_image)
    {
        $slug = url_title($this->input->event('title'));

        $data = array(
            'title' => $this->input->event('title'),
            'slug' => $slug,
            'body' => $this->input->event('body'),
            'category_id' => $this->input->event('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'event_image' => $event_image,
        );

        return $this->db->insert('events', $data);
    }

    public function delete_event($id)
    {
        $image_file_name = $this->db->select('event_image')->get_where('events', array('id' => $id))->row()->event_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\events\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('events');

        return true;
    }

    public function update_event()
    {
        $slug = url_title($this->input->event('title'), '-', true);

        $data = array(
            'title' => $this->input->event('title'),
            'slug' => $slug,
            'body' => $this->input->event('body'),
            'category_id' => $this->input->event('category_id'),
        );

        $this->db->where('id', $this->input->event('id'));

        return $this->db->update('events', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_events_by_category($category_id)
    {
        $this->db->order_by('events.id', 'DESC');
        $this->db->join('categories', 'categories.id = events.category_id');

        $query = $this->db->get_where('events', array('category_id' => $category_id));

        return $query->result_array();
    }
}
