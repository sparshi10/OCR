<?php

class MyModel extends CI_Model {
    public function get_data()
    {
        // Your database query logic here
        // For example:
        $query = $this->db->get('my_table');
        return $query->result_array();
    }
}