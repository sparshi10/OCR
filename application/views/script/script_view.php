<!-- application/views/face_match_view.php -->

<?php
$scriptContent = <<<EOT
#!/usr/bin/env python
import face_recognition
import face_recognition_models
import numpy as np
import json

def compare_faces(image_path_1, image_path_2, threshold=0.6):
    # Load face images from the two provided paths
    img1 = face_recognition.load_image_file(image_path_1)
    img2 = face_recognition.load_image_file(image_path_2)

    # Encode all the faces detected in the images into 128-dimensional face embeddings
    face_encodings_1 = face_recognition.face_encodings(img1)
    face_encodings_2 = face_recognition.face_encodings(img2)

    if len(face_encodings_1) == 0 or len(face_encodings_2) == 0:
        result = {"face_match_opinion": "Rejected"}
    else:
        # Convert face encodings to NumPy arrays
        face_encodings_1 = np.array(face_encodings_1)
        face_encodings_2 = np.array(face_encodings_2)

        # Compare all face embeddings using the Euclidean distance
        face_distances = np.linalg.norm(face_encodings_1 - face_encodings_2, axis=1)

        # Check if any of the face pairs have a distance less than the threshold
        if any(face_distance < threshold for face_distance in face_distances):
            result = {"face_match_result": "Face Matched"}
        else:
            result = {"face_match_result": "Rejected"}

    return json.dumps(result)

if __name__ == "__main__":
    import sys

    if len(sys.argv) != 3:
        print("Usage: python match_face.py <image_path1> <image_path2>")
        sys.exit(1)

    image_path_1 = sys.argv[1]
    image_path_2 = sys.argv[2]

    result = compare_faces(image_path_1, image_path_2)
    print(result)
EOT;

$tempScriptPath = APPPATH . '../application/cache/temp_script.py';
file_put_contents($tempScriptPath, $scriptContent);

// Construct the command to execute the temporary script
$command = "python $tempScriptPath $image1_path $image2_path";
$output = shell_exec($command . ' 2>&1');

// Clean up: Delete the temporary script file
unlink($tempScriptPath);

// Process the output and return the result to the controller
echo json_encode(array('face_match_opinion' => $output));
?>
