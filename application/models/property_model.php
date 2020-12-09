<?php

class Property_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default', true);
    }

    public function get($id)
    {
        $where['id'] = $id;
        $result_set = $this->db->get_where('properties', $where);
        $result_arr = $result_set->result_array();

        return $result_arr[0];
    }

    public function update($id, $new_data)
    {
        $where['id'] = $id;
        $this->db->update('properties', $new_data, $where);
    }

    public function get_version()
    {
        $result_set = $this->db->query('SELECT VERSION()');

        return $result_set;
    }

    public function all()
    {
        $result_set = $this->db->get('properties');

        return $result_set->result_array();
    }

    public function connection_test()
    {
    }
}
