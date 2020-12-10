<?php

class Notification_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_notifications($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('notifications.id', 'DESC');
            $this->db->join('categories', 'categories.id = notifications.category_id');

            $query = $this->db->get('notifications');

            return $query->result_array();
        }

        $query = $this->db->get_where('notifications', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_notification($notification_image)
    {
        $slug = url_title($this->input->notification('title'));

        $data = array(
            'title' => $this->input->notification('title'),
            'slug' => $slug,
            'body' => $this->input->notification('body'),
            'category_id' => $this->input->notification('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'notification_image' => $notification_image,
        );

        return $this->db->insert('notifications', $data);
    }

    public function delete_notification($id)
    {
        $image_file_name = $this->db->select('notification_image')->get_where('notifications', array('id' => $id))->row()->notification_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\notifications\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('notifications');

        return true;
    }

    public function update_notification()
    {
        $slug = url_title($this->input->notification('title'), '-', true);

        $data = array(
            'title' => $this->input->notification('title'),
            'slug' => $slug,
            'body' => $this->input->notification('body'),
            'category_id' => $this->input->notification('category_id'),
        );

        $this->db->where('id', $this->input->notification('id'));

        return $this->db->update('notifications', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_notifications_by_category($category_id)
    {
        $this->db->order_by('notifications.id', 'DESC');
        $this->db->join('categories', 'categories.id = notifications.category_id');

        $query = $this->db->get_where('notifications', array('category_id' => $category_id));

        return $query->result_array();
    }
}
