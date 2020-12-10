<?php

class Vehicle_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_vehicles($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('vehicles.id', 'DESC');
            $this->db->join('categories', 'categories.id = vehicles.category_id');

            $query = $this->db->get('vehicles');

            return $query->result_array();
        }

        $query = $this->db->get_where('vehicles', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_vehicle($vehicle_image)
    {
        $slug = url_title($this->input->vehicle('title'));

        $data = array(
            'title' => $this->input->vehicle('title'),
            'slug' => $slug,
            'body' => $this->input->vehicle('body'),
            'category_id' => $this->input->vehicle('category_id'),
            'user_id' => $this->session->userdata('user_id'),
            'vehicle_image' => $vehicle_image,
        );

        return $this->db->insert('vehicles', $data);
    }

    public function delete_vehicle($id)
    {
        $image_file_name = $this->db->select('vehicle_image')->get_where('vehicles', array('id' => $id))->row()->vehicle_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\vehicles\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('vehicles');

        return true;
    }

    public function update_vehicle()
    {
        $slug = url_title($this->input->vehicle('title'), '-', true);

        $data = array(
            'title' => $this->input->vehicle('title'),
            'slug' => $slug,
            'body' => $this->input->vehicle('body'),
            'category_id' => $this->input->vehicle('category_id'),
        );

        $this->db->where('id', $this->input->vehicle('id'));

        return $this->db->update('vehicles', $data);
    }


    public function get_categories()
    {
        $this->db->order_by('name');

        $query = $this->db->get('categories');

        return $query->result_array();
    }

    public function get_vehicles_by_category($category_id)
    {
        $this->db->order_by('vehicles.id', 'DESC');
        $this->db->join('categories', 'categories.id = vehicles.category_id');

        $query = $this->db->get_where('vehicles', array('category_id' => $category_id));

        return $query->result_array();
    }
}
