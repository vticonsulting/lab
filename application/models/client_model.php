<?php
/**
 * @author Victor Tolbert <victor@tolbert.design>
 */

class Client_model extends CI_Model
{
    public function get_clients($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('clients.id', 'DESC');
            $this->db->join('categories', 'categories.id = clients.category_id');

            $query = $this->db->get('clients');

            return $query->result_array();
        }

        $query = $this->db->get_where('clients', array('slug' => $slug));

        return $query->row_array();
    }

    /**
     * @param mixed $anything Anything that we can convert to a \DateTime object
     *
     * @throws \InvalidArgumentException
     *
     * @return \DateTime
     */
    public function dateTimeFromAnything($anything)
    {
        $type = gettype($anything);

        switch ($type) {
            // Some code that tries to return a \DateTime object
        }

        throw new \InvalidArgumentException(
            "Failed Converting param of type '{$type}' to DateTime object"
        );
    }
}
