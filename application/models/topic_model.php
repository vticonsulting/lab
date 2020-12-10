<?php

class Topic_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_topics()
    {
        $this->db->order_by('name');
        $query = $this->db->get('topics');

        return $query->result_array();
    }

    public function create_topic()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'user_id' => $this->session->userdata('user_id'),
        );

        return $this->db->insert('topics', $data);
    }

    public function get_topic($id)
    {
        $query = $this->db->get_where('topics', array('id' => $id));

        return $query->row();
    }

    public function delete_topic($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('topics');

        return true;
    }
}
