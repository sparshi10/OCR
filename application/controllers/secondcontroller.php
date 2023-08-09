<?php

defined('BASEPATH') OR exit('No direct script access allowed');
putenv('TESSDATA_PREFIX=C:\ProgramData\chocolatey\lib\capture2text\tools\Capture2Text\Utils\tesseract\tessdata');
require 'vendor/autoload.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

class ImageController extends CI_Controller {

    public function processImage() {
        // Check if the request is a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if the 'submit' parameter is set
            if (isset($_POST['submit'])) {
                // Check if a file was uploaded
                if (isset($_FILES['file'])) {
                    $file_name = $_FILES['file']['name'];
                    $tmp_file = $_FILES['file']['tmp_name'];

                    if (!session_id()) {
                        session_start();
                        $unq = session_id();
                    }

                    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $allowed_extensions = array('jpeg', 'jpg', 'png');

                    // Check if the file extension is allowed
                    if (!in_array($file_extension, $allowed_extensions)) {
                        echo json_encode(array('error' => 'Invalid file format. Only JPEG, JPG, and PNG files are allowed.'));
                        exit();
                    }

                    // Generate a unique file name
                    $file_name = $unq . '_' . time() . '_' . str_replace(array('!', "@", '#', '$', '%', '^', '&', ' ', '*', '(', ')', ':', ';', ',', '?', '/' . '\\', '~', '`', '-'), '_', strtolower($file_name));

                    // Move the uploaded file to the 'uploads' directory
                    if (move_uploaded_file($tmp_file, 'uploads/' . $file_name)) {
                        try {
                            // Create a grayscale image
                            $image = null;
                            if ($file_extension == 'png') {
                                $image = imagecreatefrompng('uploads/' . $file_name);
                            } else {
                                $image = imagecreatefromjpeg('uploads/' . $file_name);
                            }
                            imagefilter($image, IMG_FILTER_GRAYSCALE);
                            $gray_file_name = $unq . '_' . time() . '_gray_' . str_replace(' ', '_', strtolower($file_name));
                            imagepng($image, 'uploads/' . $gray_file_name);
                            imagedestroy($image);

                            // Perform OCR using Tesseract OCR
                            $result = $this->performOCR('uploads/' . $gray_file_name);

                            // Send the extracted data back to the user as JSON
                            echo json_encode($result);
                        } catch (Exception $e) {
                            echo json_encode(array('error' => $e->getMessage()));
                        }
                    } else {
                        echo json_encode(array('error' => 'File failed to upload.'));
                    }
                } else {
                    echo json_encode(array('error' => 'No file uploaded.'));
                }
            } else {
                // Display the image upload form
                $this->load->view('image_upload');
            }
        } else {
            echo json_encode(array('error' => 'Invalid request method.'));
        }
    }

    private function performOCR($imagePath) {
        // Use the TesseractOCR library to perform OCR
        $tesseract = new TesseractOCR($imagePath);
        $tesseract->setLanguage('eng');

        // Retrieve the entire text from the image
        $result = $tesseract->run();

        // Return the extracted text
        return array(
            'text' => $result
        );
    }

}
