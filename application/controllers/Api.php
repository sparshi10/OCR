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
        //session_time_out
//        ini_set('max_execution_time', 120);
        error_log(print_r($_POST,true));
        
        // Ensure the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(array('error' => 'Method Not Allowed'));
            return;
        }
        // Check if both images are provided in the request
//        if (!isset($_POST['image1']) || !isset($_POST['image2'])) {
//            http_response_code(400); // Bad Request
//            echo json_encode(array('error' => 'Please provide both images.'));
//            return;
//        }
        $base64_image1 = $_POST['image1'];
        $base64_image2 = $_POST['image2'];
        
        // Set the uploads directory path
        $uploads_directory = APPPATH . '../uploads/';

        // Generate unique file names for the images
        $image1_filename = $this->generateUniqueFilename('image1', '.txt'); // Save as a .txt file to preserve base64 data
        $image2_filename = $this->generateUniqueFilename('image2', '.txt');

        // Save the base64-encoded images to the uploads directory as text files
        file_put_contents($uploads_directory . $image1_filename, $base64_image1);
        file_put_contents($uploads_directory . $image2_filename, $base64_image2);

        // Convert base64 to binary (JPEG or PNG) and save the images
        $image1_path = $this->convertAndSaveImage($base64_image1, $uploads_directory, 'image1');
        $image2_path = $this->convertAndSaveImage($base64_image2, $uploads_directory, 'image2');


        // Call the Python script for face matching
        $python_executable = 'python'; // Use the correct Python executable path
        $script_path = APPPATH . '../uploads/match_face.py';

        // Escaping shell arguments to prevent command injection
        $image1_path = escapeshellarg($image1_path);
        $image2_path = escapeshellarg($image2_path);

        $command = "$python_executable $script_path $image1_path $image2_path";

        // Use shell_exec with 2>&1 to capture both stdout and stderr
        $output = shell_exec($command . ' 2>&1');

        try {
            $face_match_result = json_decode($output, true);
            $face_match_percentage = $face_match_result['face_match_percentage'];
        } catch (Exception $e) {
            $face_match_percentage = -1;
        }

        // Return the match percentage as a JSON response
        echo json_encode(array('face_match_opinion' => $face_match_percentage));
    }

    private function generateUniqueFilename($prefix, $extension) {
        $timestamp = microtime(true); // Get the current timestamp with microseconds
        $random = mt_rand(); // Generate a random number
        $unique_string = uniqid($prefix . '_', true); // Generate a unique ID
        // Combine all the parts to create the unique filename
        $filename = $unique_string . $timestamp . $random . $extension;
        return $filename;
    }

    private function convertAndSaveImage($base64_data, $directory, $prefix) {
        // Generate unique filename with desired image extension (JPEG or PNG)
        $extension = '.png'; // You can change this to '.png' if needed
        $filename = $this->generateUniqueFilename($prefix, $extension);
        $image_path = $directory . $filename;

        // Convert base64 to binary and save the image
        $binary_data = base64_decode($base64_data);
        file_put_contents($image_path, $binary_data);

        return $image_path;
    }

}
