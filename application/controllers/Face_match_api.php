<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Face_match_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('upload');
    }

    public function upload_images() {
        $config['upload_path'] = FCPATH . 'uploads/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 2048; // Maximum file size in kilobytes (2 MB)

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('image1') || !$this->upload->do_upload('image2')) {
            // Image upload failed, handle the error
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
            return;
        } else {
            // Images uploaded successfully
            $data = array('upload_data' => $this->upload->data());

            // Get the full paths of the uploaded images
            $image1_path = APPPATH . '../uploads/' . $_FILES['image1']['name'];
            $image2_path = APPPATH . '../uploads/' . $_FILES['image2']['name'];

            // Define the Python executable path (Python 3.9)
            $python_executable = 'C:\Python310\python.exe'; // Use the correct Python 3.9 executable path
//            $python_executable = 'C:\Users\User\AppData\Local\Microsoft\WindowsApps\python.exe'; // Use the correct Python 3.9 executable path

            // Call the Python script for face matching
            $script_path = APPPATH . '/..python_scripts/face_matching.py';

            $command = "$python_executable $script_path $image1_path $image2_path";
            $output = shell_exec($command);

            try {
                $face_match_percentage = json_decode($output, true)['face_match_percentage'];
            } catch (Exception $e) {
                $face_match_percentage = -1;
            }

            // Return the result as JSON
            $result = array(
                'image1_path' => $image1_path,
                'image2_path' => $image2_path,
                'face_match_percentage' => $face_match_percentage
            );

            echo json_encode($result);
        }
    }

}
