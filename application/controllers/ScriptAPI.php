<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ScriptAPI extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
    }

    public function index() {
        $this->load->view('face_match/upload_image');
    }

    public function image_match() {
        // Ensure the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(array('error' => 'Method Not Allowed'));
            return;
        }

        $base64_image1 = $_POST['image1'];
        $base64_image2 = $_POST['image2'];

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

        // Load the view and pass necessary data
        $data = array(
            'image1_path' => $image1_path,
            'image2_path' => $image2_path,
        );

        $this->load->view('script/script_view.php', $data);
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
