
<!-- upload_image.php -->
<form action="<?php echo base_url('api/convert_to_base64'); ?>" method="post" enctype="multipart/form-data">
    <input type="file" name="image" id="image">
    <button type="submit">Convert to Base64</button>
</form>

