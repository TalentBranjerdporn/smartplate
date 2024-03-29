<?php

function create_zones() {
    // longitude latitude
    $zones = array(
        "ZONE1" => array(
            [153.0145301, -27.4994674],
            [153.0148037, -27.5002335],
            [153.015898, -27.4999051],
            [153.0157156, -27.4990867]
        ),
        "ZONE2" => array(
            [153.0150934, -27.4952372],
            [153.0151577, -27.4968169],
            [153.0166276, -27.4969692],
            [153.0169011, -27.4952276]
        ),
        "ZONE3" => array(
            [153.0130665, -27.4999864],
            [153.013501, -27.5005812],
            [153.0145202, -27.5001006],
            [153.0141984, -27.4995534]
        ),
        "ZONE4" => array(
            [153.0687426, -27.6016075],
            [153.0683456, -27.6028721],
            [153.0700139, -27.6029339],
            [153.0702661, -27.60185]
        )
    );

    return $zones;
}

function get_date_format() {
    $format = 'Y-m-d G:i:s';

    return $format;
}
