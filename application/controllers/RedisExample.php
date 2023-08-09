<?php

class RedisExample extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Load the database library
    }

    public function index() {
        $this->load->driver('cache');
 
        $cached_data = $this->cache->get('my_cached_data');
        $data = $cached_data;
            var_dump($data);
            exit();
//        // Try to get the cached result
//        if ($cached_data = $this->cache->get('my_cached_data')) {
//            $data = $cached_data;
//            var_dump($data, "Hello");
//            exit();
//        } else {
//            // Data not found in cache, fetch from the database and cache it
//            $this->load->model('MyModel');
//            $data = $this->MyModel->get_data();
//            var_dump($data, "HI");
//            exit();
//            $this->cache->save('my_cached_data', $data, 3600); // Cache for 1 hour (3600 seconds)
//        }

        $this->load->view('my_view', ['data' => $data]);
    }

}
