<?php

function dump_custom($val) {
    echo '<pre>';
    var_dump($val);
    echo '</pre>';
}

function inside_point($p, $points) {
    $x = $p[0];
    $y = $p[1];

    $inside = false;

    for ($i = 0, $j = count($points) - 1; $i < count($points); $j = $i++) {
        $xi = $points[$i][0];
        $yi = $points[$i][1];
        $xj = $points[$j][0];
        $yj = $points[$j][1];

        $intersect = (($yi > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);

        if ($intersect === true) {
            $inside = !$inside;
        }
    }

    return $inside;
}
