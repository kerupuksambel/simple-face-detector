<?php
    // Check the existence of the file
    if(!$_FILES || !key_exists('gambar', $_FILES)){
        die('Mohon upload file foto Anda.');
    }

    $tmp = $_FILES['gambar']['tmp_name'];
    $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    
    // Check the MIME type
    $whitelist = ['image/jpg', 'image/jpeg', 'image/png'];
    if(!in_array(mime_content_type($tmp), $whitelist)){
        die('Mohon hanya unggah file JPG, JPEG, atau PNG.');
    }

    // Check the extension
    $whitelist = ['jpg', 'jpeg', 'png'];
    if(!in_array(strtolower($ext), $whitelist)){
        die('Mohon unggah file dengan ekstensi yang benar.');
    }

    // Check size of image to verify the image type
    if(getimagesize($tmp) == 0){
        die('File gambar Anda tidak valid.');
    }

    // Buat nama file acak
    $random = md5($tmp);

    // Upload gambar
    $filename = $random.'.'.$ext;
    move_uploaded_file($tmp, 'file/'.$random.'.'.$ext);

    // Check the face
    $face_amount = shell_exec('cd detector; python3 detector.py ' . '../file/' . $filename);

    if($face_amount == 0){
        $verdict = "Wajah tidak terdeteksi!";
    }else{
        $verdict = "Wajah terdeteksi!";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="col-md-12 mt-3">
                <h3 class="col-md-12 mb-3"><?= $verdict ?></h3>
                <div class="col-md-12">
                    <img src='file/detected_<?= $filename ?>' style='width: 50%'>
                </div>                
            </div>
        </div>
    </body>
</html>