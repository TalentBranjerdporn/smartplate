<?php
require_once 'database.php';
require_once 'functions.php';
require_once 'config.php';

$func = filter_input(INPUT_POST, 'func');

function save() {
    $epoch = filter_input(INPUT_POST, 'epoch');
    $filename = filter_input(INPUT_POST, 'filename');
    $plate = filter_input(INPUT_POST, 'plate');
    $state = filter_input(INPUT_POST, 'state');
    $lpr_make = filter_input(INPUT_POST, 'lpr_make');
    $lpr_model = filter_input(INPUT_POST, 'lpr_model');
    $lpr_colour = filter_input(INPUT_POST, 'lpr_colour');
    $rego_make = filter_input(INPUT_POST, 'rego_make');
    $rego_model = filter_input(INPUT_POST, 'rego_model');
    $rego_colour = filter_input(INPUT_POST, 'rego_colour');
    $infringe = filter_input(INPUT_POST, 'infringe');

    $query = 'INSERT INTO details (epoch, filename, plate, state, lpr_make, lpr_model, lpr_colour, rego_make, rego_model, rego_colour, infringe) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
    $args = [$epoch, $filename, $plate, $state, $lpr_make, $lpr_model, $lpr_colour, $rego_make, $rego_model, $rego_colour, $infringe];
    $stmt = DB::run($query, $args);

    if ($stmt->rowCount() === 1) {
        echo 'Saved successfully';
    } else {
        echo 'error';
    }
}

function gps() {
    $x = filter_input(INPUT_POST, 'x');
    $y = filter_input(INPUT_POST, 'y');

    $p = [$x, $y];

    $zones = create_zones();

    $inside = false;
    $inside_zone = "";
    foreach ($zones as $key => $zone) {
        if (inside_polygon($p, $zone)) {
            $inside = true;
            $inside_zone = $key;
            break;
        }
    }

    if ($inside) {
        foreach ($zones as $key => $zone) {
            if ($key == $inside_zone) {
                echo '<option value="' . $key . '"selected>' . $key . '</option>';
            } else {
                echo '<option value="' . $key . '">' . $key . '</option>';
            }
        }
    } else {
        // check distance to polygon
        $min;
        foreach($zones as $zone) {
            $dist = point_to_polygon($p, $zone);
        }
    }
}

switch ($func) {
    case 'save':
        save();
        break;
    case 'gps':
        gps();
        break;
    default:
        echo 'Error: ';
        dump_custom($_POST);
}

die();
