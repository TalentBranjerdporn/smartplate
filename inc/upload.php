<?php

include 'CustomImage.php';

$upload_file = filter_input(INPUT_POST, 'upload_file');
$camera_file = filter_input(INPUT_POST, 'camera_file');
$credit_cost = filter_input(INPUT_POST, 'credit_cost');

if (isset($camera_file)) {
    // there is a file there.
    list($type, $data) = explode(';', $camera_file);
    list(, $data) = explode(',', $data);
    $file_data = base64_decode($data);

    $finfo = finfo_open();
    $file_mime = finfo_buffer($finfo, $file_data, FILEINFO_MIME_TYPE);
    
    $cust_img = new CustomImage($file_data, true);
    
// File extension from mime type
    if ($file_mime_type == 'image/jpeg' || $file_mime_type == 'image/jpg')
        $file_type = 'jpeg';
    else if ($file_mime_type == 'image/png')
        $file_type = 'png';
    else if ($file_mime_type == 'image/gif')
        $file_type = 'gif';
    else
        $file_type = 'other';

    // Validate type of file
    if (in_array($file_type, ['jpeg', 'png', 'gif'])) {
        // Set a unique name to the file and save
        $file_name = uniqid() . '.' . $file_type;
        file_put_contents("../uploads/" . $file_name, $file_data);
    } else {
        echo 'Error : Only JPEG, PNG & GIF allowed';
    }

    $img = new CustomImage();

    die();
} else {
    die();
}

// process input from file
if (isset($upload_file)) {
    $errors = array();
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $valid_ext = array("jpeg", "jpg", "png");
    $upload_dir = "../uploads/";
    $upload_loc = $upload_dir . basename($file_name);
    $check = getimagesize($file_tmp);

    // this is a bad way to check for an image - assuming no malicious content
    if (!$check) {
        $errors[] = "File is not an image";
    }

    // check for valid extentions
    if (!in_array($file_ext, $valid_ext)) {
        $errors[] = "Extension not allowed, please choose a JPEG or PNG file.";
    }

    if ($file_size > 20971520) {
        $errors[] = "File size can't exceed 20 MB";
    }

    if (!move_uploaded_file($file_tmp, $upload_loc)) {
        $errors[] = 'An error occurred while uploading';
    }

    if (empty($errors)) {
        $output = array(
            'result' => 'success',
            'path' => $file_name
        );
    } else {
        $output = array(
            'result' => 'failed',
            'errors' => $errors
        );
    }

    echo json_encode($output);

    // create html output here possibly
}

die();
