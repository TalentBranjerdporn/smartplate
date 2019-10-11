<?php
include 'PlateImage.php';
include 'functions.php';

$upload_file = filter_input(INPUT_POST, 'upload_file');
$camera_file = filter_input(INPUT_POST, 'camera_file');
$credit_cost = filter_input(INPUT_POST, 'credit_cost');
$lpr_results = $_POST['lpr_results'];

// important ones to use later possibly
$epoch_time = $lpr_results['epoch_time'];
$results = $lpr_results['results'];

//dump_custom($epoch_time);

if (isset($camera_file)) {
    // there is a file there.
    list($type, $data) = explode(';', $camera_file);
    list(, $data) = explode(',', $data);
    $file_data = base64_decode($data);

    $img = new PlateImage($file_data);
    // change to epoch time
    $img->addTimestamp(200, 40, 200, intval(substr($epoch_time, 0, 10)), 255);

    // detect all plates
    foreach ($results as $plate) {
        $name = $plate['plate'];
        $coords = $plate['coordinates'];

        for ($i = 0; $i < count($coords); $i++) {
            $x1 = $coords[$i]['x'];
            $y1 = $coords[$i]['y'];
            if ($i == count($coords) - 1) {
                $x2 = $coords[0]['x'];
                $y2 = $coords[0]['y'];
            } else {
                $x2 = $coords[$i + 1]['x'];
                $y2 = $coords[$i + 1]['y'];
            }

            $img->drawLine($x1, $y1, $x2, $y2, 10, 255);
        }
    }

    switch ($img->getImageType()) {
        case 1:
            $file_type = 'gif';
            break;
        case 2:
            $file_type = 'jpeg';
            break;
        case 3:
            $file_type = 'png';
            break;
        default:
            $file_type = 'other';
    }

    // Validate type of file
    if (in_array($file_type, ['jpeg', 'png', 'gif'])) {
        // Set a unique name to the file and save
        $file_name = uniqid() . '.' . $file_type;
        $img->save('../uploads/' . $file_name);
        // free up memory when finished
        unset($img);
        // here return image data to be checked?
        echo "<img id='preview-img' src='uploads/$file_name' />";
        ?>
        <h4>Are these details correct</h4>
        <table class="my-table">
            <tr>
                <th>Property</th>
                <th>Confidence (%)</th>
            </tr>
            <tr>
                <td>Plate: <?php echo $results[0]['plate']; ?></td>
                <td><?php echo $results[0]['confidence']; ?></td>
            </tr>
            <tr>
                <td>Region: <?php echo $results[0]['region']; ?></td>
                <td><?php echo $results[0]['region_confidence']; ?></td>
            </tr>
            <tr>
                <td>Make: <?php echo $results[0]['vehicle']['make'][0]['name']; ?></td>
                <td><?php echo $results[0]['vehicle']['make'][0]['confidence']; ?></td>
            </tr>
            <tr>
                <td>Model: <?php echo $results[0]['vehicle']['make_model'][0]['name']; ?></td>
                <td><?php echo $results[0]['vehicle']['make_model'][0]['confidence']; ?></td>
            </tr>
            <tr>
                <td>Body Type: <?php echo $results[0]['vehicle']['body_type'][0]['name']; ?></td>
                <td><?php echo $results[0]['vehicle']['body_type'][0]['confidence']; ?></td>
            </tr>
            <tr>
                <td>Colour: <?php echo $results[0]['vehicle']['color'][0]['name']; ?></td>
                <td><?php echo $results[0]['vehicle']['color'][0]['confidence']; ?></td>
            </tr>
        </table>
        <button id="details-correct" type="button">Yes</button>
        <button id="details-incorrect" type="button">No</button>
        <?php
    } else {
        echo 'Error : Only JPEG, PNG & GIF allowed';
    }

    die();
} else {
    die();
}

// process input from file. Old version
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
