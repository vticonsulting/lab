<?php

class Course_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_courses($slug = false, $limit = false, $offset = false)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($slug === false) {
            $this->db->order_by('courses.id', 'DESC');
            $this->db->join('topics', 'topics.id = courses.topic_id');

            $query = $this->db->get('courses');

            return $query->result_array();
        }

        $query = $this->db->get_where('courses', array('slug' => $slug));

        return $query->row_array();
    }

    public function create_course($course_image)
    {
        $slug = url_title($this->input->course('title'));

        $data = array(
            'title' => $this->input->course('title'),
            'slug' => $slug,
            'body' => $this->input->course('body'),
            'topic_id' => $this->input->course('topic_id'),
            'user_id' => $this->session->userdata('user_id'),
            'course_image' => $course_image,
        );

        return $this->db->insert('courses', $data);
    }

    public function delete_course($id)
    {
        $image_file_name = $this->db->select('course_image')->get_where('courses', array('id' => $id))->row()->course_image;
        $cwd = getcwd(); // save the current working directory
        $image_file_path = $cwd . "\\assets\\images\\courses\\";
        chdir($image_file_path);
        unlink($image_file_name);
        chdir($cwd); // Restore the previous working directory
        $this->db->where('id', $id);
        $this->db->delete('courses');

        return true;
    }

    public function update_course()
    {
        $slug = url_title($this->input->course('title'), '-', true);

        $data = array(
            'title' => $this->input->course('title'),
            'slug' => $slug,
            'body' => $this->input->course('body'),
            'topic_id' => $this->input->course('topic_id'),
        );

        $this->db->where('id', $this->input->course('id'));

        return $this->db->update('courses', $data);
    }


    public function get_topics()
    {
        $this->db->order_by('name');

        $query = $this->db->get('topics');

        return $query->result_array();
    }

    public function get_courses_by_topic($topic_id)
    {
        $this->db->order_by('courses.id', 'DESC');
        $this->db->join('topics', 'topics.id = courses.topic_id');

        $query = $this->db->get_where('courses', array('topic_id' => $topic_id));

        return $query->result_array();
    }
}
