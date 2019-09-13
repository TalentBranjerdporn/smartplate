<?php

// process input from file
if (isset($_POST['upload_file']) && $_POST['upload_file']) {
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
?>