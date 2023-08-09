import os

# Set the working directory to the same directory as the PHP script
os.chdir(os.path.dirname(os.path.abspath(__file__)))

import face_recognition as fr
import sys

def calculate_face_match_percentage(image_path1, image_path2):
    # Load images
    try:
        image1 = fr.load_image_file(image_path1)
        image2 = fr.load_image_file(image_path2)
    except Exception as e:
        print(e)
        return -1

    # Encode faces in the images
    face_encoding1 = fr.face_encodings(image1)[0]
    face_encoding2 = fr.face_encodings(image2)[0]

    # Compare faces
    face_match_percentage = fr.face_distance([face_encoding1], face_encoding2)[0]
    face_match_percentage = (1 - face_match_percentage) * 100  # Convert to percentage

    return face_match_percentage

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python face_matching.py <image_path1> <image_path2>")
        sys.exit(1)

    image_path1 = sys.argv[1]
    image_path2 = sys.argv[2]

    face_match_percentage = calculate_face_match_percentage(image_path1, image_path2)
    print("Face match percentage: {}%".format(face_match_percentage))
