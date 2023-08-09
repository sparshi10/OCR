<!DOCTYPE html>
<html>
<head>
    <title>Face Match</title>
</head>
<body>
    <h2>Upload two images to match faces</h2>
    <form method="post" action="face_match/upload_images" enctype="multipart/form-data">
        <input type="file" name="image1" required>
        <br>
        <input type="file" name="image2" required>
        <br>
        <button type="submit">Match Images</button>
    </form>
</body>
</html>