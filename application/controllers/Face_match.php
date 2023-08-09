<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

defined('BASEPATH') OR exit('No direct script access allowed');

class Face_match extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
    }

    public function index() {
        $this->load->view('face_match/upload_image');
    }

    public function upload_images() {

        // Set the working directory to the script's directory
        chdir(dirname(__FILE__));

        // Define the Python executable path (Python 3.9)
        $python_executable = 'python'; // Use the correct Python 3.9 executable path
//        $python_executable = 'python'; // Use the correct Python 3.9 executable path

        $config['upload_path'] = FCPATH . 'uploads/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 2048; // Maximum file size in kilobytes (2 MB)

        $this->load->library('upload', $config);

        //if (!$this->upload->do_upload('image1') || !$this->upload->do_upload('image2')) {
      //      $error = array('error' => $this->upload->display_errors());
      //      $this->load->view('face_match/upload_image', $error);
     //   } else {
            // Images uploaded successfully
            //$data = array('upload_data' => $this->upload->data());

            // Get the full paths of the uploaded images
//            $image1_path = $data['upload_data']['full_path'];
//            $image2_path = $data['upload_data']['full_path'];

            $image1_path = APPPATH.'../uploads/image11.jpeg';
            $image2_path = APPPATH.'../uploads/image31.jpeg';

            // Call the Python script for face matching
            $script_path = APPPATH.'../uploads/face_matching.py';

            $command = "$python_executable $script_path $image1_path $image2_path";
            var_dump($command);
            exit();
            //$output = exec($command);
           $output = shell_exec($command);
           var_dump($output);
           exit();
            try {
                $face_match_percentage = json_decode($output, true)['face_match_percentage'];
            } catch (Exception $e) {
                $face_match_percentage = -1;
            }

            // Now you can load the view to display the images and the match percentage.
            $this->load->view('face_match/image_match_result', array('image1_path' => $image1_path, 'image2_path' => $image2_path, 'face_match_percentage' => $face_match_percentage));
        //}
    }

}
