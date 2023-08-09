#!C:\Python310\python.exe

import face_recognition
import cv2
import json

def visualize_detected_faces(image_path, face_locations):
    image = cv2.imread(image_path)
    for face_location in face_locations:
        top, right, bottom, left = face_location
        cv2.rectangle(image, (left, top), (right, bottom), (0, 255, 0), 2)
    cv2.imshow('Detected Faces', image)
    cv2.waitKey(0)
    cv2.destroyAllWindows()

def calculate_face_match_percentage(image_path1, image_path2):
    try:
        # Load images using face_recognition
        image1 = face_recognition.load_image_file(image_path1)
        image2 = face_recognition.load_image_file(image_path2)

        # Detect faces in the images
        face_locations1 = face_recognition.face_locations(image1)
        face_locations2 = face_recognition.face_locations(image2)

        # Check if one face is detected in each image
        if len(face_locations1) != 1 or len(face_locations2) != 1:
            print("Error: One face should be detected in each image.")
            # Display the detected faces for visualization
            visualize_detected_faces(image_path1, face_locations1)
            visualize_detected_faces(image_path2, face_locations2)
            return -1

        # Get the face encodings for the detected faces
        face_encoding1 = face_recognition.face_encodings(image1, known_face_locations=face_locations1)[0]
        face_encoding2 = face_recognition.face_encodings(image2, known_face_locations=face_locations2)[0]

        # Compare the face encodings
        face_match_percentage = face_recognition.face_distance([face_encoding1], face_encoding2)[0]
        face_match_percentage = (1 - face_match_percentage) * 100

        return face_match_percentage

    except Exception as e:
        print(e)
        return -1

if __name__ == "__main__":
    import sys
    import numpy as np

    if len(sys.argv) != 3:
        print("Usage: python face_matching.py <image_path1> <image_path2>")
        sys.exit(1)

    image_path1 = sys.argv[1]
    image_path2 = sys.argv[2]

    face_match_percentage = calculate_face_match_percentage(image_path1, image_path2)
    if face_match_percentage != -1:
        print("Face match percentage: {:.2f}%".format(face_match_percentage))
