<?php

require_once 'database.php';
require_once 'functions.php';

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

switch ($func) {
    case 'save':
        save();
        break;
    default:
        echo 'Error: ';
        dump_custom($_POST);
}

die();
