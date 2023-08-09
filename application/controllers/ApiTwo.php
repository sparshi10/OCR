<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
    }

    public function index() {
        $this->load->view('face_match/upload_image');
    }

    public function image_match() {
//         Ensure the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(array('error' => 'Method Not Allowed'));
            return;
        }

        // Check if both images are provided in the request
        if (!isset($_FILES['image1']) || !isset($_FILES['image2'])) {
            http_response_code(400); // Bad Request
            echo json_encode(array('error' => 'Please provide both images.'));
            return;
        }

        // Set the working directory to the script's directory
        chdir(dirname(__FILE__));

        // Call the Python script for face matching
        $image1_path = $_FILES['image1']['tmp_name'];
        $image2_path = $_FILES['image2']['tmp_name'];

        $python_executable = 'python'; // Use the correct Python executable path
        $script_path = APPPATH.'../uploads/match_face.py';

        $command = "$python_executable $script_path $image1_path $image2_path";
        
        $output = shell_exec($command);

        try {
            $face_match_result = json_decode($output, true);
            $face_match_percentage = $face_match_result['face_match_percentage'];
        } catch (Exception $e) {
            $face_match_percentage = -1;
        }

        // Return the match percentage as a JSON response
        echo json_encode(array('face_match_opinion' => $face_match_percentage));
    }
}
