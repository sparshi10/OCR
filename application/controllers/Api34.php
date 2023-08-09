<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('image_match_model');
    }

    // ... (Other controller methods)

    public function image_match() {

        error_log(print_r($_POST, true));
        // Ensure the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(array('error' => 'Method Not Allowed'));
            return;
        }

        // Check if both images and $id are provided in the request
//        if (!isset($_POST['image1']) || !isset($_POST['image2']) || !isset($_POST['id'])) {
//            http_response_code(400); // Bad Request
//            echo json_encode(array('error' => 'Please provide both images and ID.'));
//            return;
//        }

        $image1 = $_POST['image1'];
        $image2 = $_POST['image2'];
        $id = $_POST['id'];

        // Call the model to perform image matching
        $face_match_percentage = $this->image_match_model->match_images($image1, $image2, $id);

        // Return the match percentage as a JSON response
        echo json_encode(array('face_match_opinion' => $face_match_percentage));
    }

    // ... (Other controller methods)
}
