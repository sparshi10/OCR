<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Base_SixtyFour extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
    }

    public function index() {
        $this->load->view('base64');
    }

    public function convert_to_base64() {
        // Check if an image is uploaded
        if (!isset($_FILES['image']['tmp_name'])) {
            echo "Please select an image to upload.";
            return;
        }

        // Set the uploads directory path
        $uploads_directory = APPPATH . '../uploads/';

        // Get the temporary path of the uploaded image
        $image_temp_path = $_FILES['image']['tmp_name'];

        // Read the image file
        $image_data = file_get_contents($image_temp_path);

        // Convert the image to base64
        $base64_image = base64_encode($image_data);

        // Generate a unique filename for the base64 data
        $filename = $this->generateUniqueFilename('image', '.txt');
        $base64_file_path = $uploads_directory . $filename;

        // Save the base64 data to a file
        file_put_contents($base64_file_path, $base64_image);

        echo "Image converted to base64 and saved successfully.";
    }

    private function generateUniqueFilename($prefix, $extension) {
        $timestamp = microtime(true); // Get the current timestamp with microseconds
        $random = mt_rand(); // Generate a random number
        $unique_string = uniqid($prefix . '_', true); // Generate a unique ID

        // Combine all the parts to create the unique filename
        $filename = $unique_string . $timestamp . $random . $extension;
        return $filename;
    }
}
