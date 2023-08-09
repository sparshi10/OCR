#!C:\Python310\python.exe
import face_recognition
import numpy as np
import json
from PIL import Image
from io import BytesIO
import sys

def decode_base64_image(image_data):
    image_bytes = BytesIO(base64.b64decode(image_data))
    return Image.open(image_bytes)

def compare_faces(image_data_1, image_data_2, threshold=0.6):
    # Decode the base64 image data to Pillow images
    img1 = decode_base64_image(image_data_1)
    img2 = decode_base64_image(image_data_2)

    # Convert Pillow images to NumPy arrays
    img1_np = np.array(img1)
    img2_np = np.array(img2)

    # Encode all the faces detected in the images into 128-dimensional face embeddings
    face_encodings_1 = face_recognition.face_encodings(img1_np)
    face_encodings_2 = face_recognition.face_encodings(img2_np)

    if len(face_encodings_1) == 0 or len(face_encodings_2) == 0:
        result = {"face_match_percentage": "Rejected"}
    else:
        # Convert face encodings to NumPy arrays
        face_encodings_1 = np.array(face_encodings_1)
        face_encodings_2 = np.array(face_encodings_2)

        # Compare all face embeddings using the Euclidean distance
        face_distances = np.linalg.norm(face_encodings_1 - face_encodings_2, axis=1)

        # Check if any of the face pairs have a distance less than the threshold
        if any(face_distance < threshold for face_distance in face_distances):
            result = {"face_match_percentage": "Face Matched"}
        else:
            result = {"face_match_percentage": "Rejected"}

    return json.dumps(result)

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python match_face.py <base64_encoded_image1> <base64_encoded_image2>")
        sys.exit(1)

    image_data_1 = sys.argv[1]
    image_data_2 = sys.argv[2]

    result = compare_faces(image_data_1, image_data_2)
    print(result)
