<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Image_match_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function match_images($image1, $image2, $id) {
        // Generate unique file name
        $image1_filename = $this->generateUniqueFilename($id, '_image1', '.txt');
        $image2_filename = $this->generateUniqueFilename($id, '_image2', '.txt');

        // Save the base64-encoded images to a temporary location
        $uploads_directory = APPPATH . '../uploads/temp/';
        file_put_contents($uploads_directory . $image1_filename, $image1);
        file_put_contents($uploads_directory . $image2_filename, $image2);

        // Convert base64 to binary (JPEG or PNG) and save the images
        $image1_path = $this->convertAndSaveImage($image1, $uploads_directory, $id . '_image1');
        $image2_path = $this->convertAndSaveImage($image2, $uploads_directory, $id . '_image2');

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

        // Delete the temporary images
        unlink($image1_path);
        unlink($image2_path);

//        return $face_match_percentage;
         echo json_encode(array('face_match_opinion' => $face_match_percentage));
    }

    private function generateUniqueFilename($id, $prefix, $extension) {
        $timestamp = microtime(true);
        $random = mt_rand();
        $unique_string = uniqid($prefix . '_', true);

        $filename = $id . '__' . $unique_string . $timestamp . $random . $extension;

        return $filename;
    }

    private function convertAndSaveImage($base64_data, $directory, $prefix) {
        $extension = '.png'; // You can change this to '.png' if needed
        $filename = $this->generateUniqueFilename($prefix, $extension);
        $image_path = $directory . $filename;

        $binary_data = base64_decode($base64_data);
        file_put_contents($image_path, $binary_data);

        return $image_path;
    }

}
