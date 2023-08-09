<!DOCTYPE html>
<html>
<head>
    <title>Face Match Result</title>
</head>
<body>
    <h2>Face Match Result</h2>
    <img src="<?php echo base_url($image1_path); ?>" alt="Image 1">
    <img src="<?php echo base_url($image2_path); ?>" alt="Image 2">
    <p>Face Match Percentage: <?php echo $face_match_percentage; ?>%</p>
</body>
</html>
