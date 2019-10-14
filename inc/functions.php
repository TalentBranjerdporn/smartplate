<?php

function dump_custom($val) {
    echo '<pre>';
    var_dump($val);
    echo '</pre>';
}

function inside_polygon($p, $points) {
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

function point_to_polygon($p, $points) {
    $x = $p[0];
    $y = $p[1];
    
    $min;
    for ($i = 0; $i < count($points) - 1; $j = $i++) {
        $xi = $points[$i][0];
        $yi = $points[$i][1];
        $xj = $points[$i + 1][0];
        $yj = $points[$i + 1][1];

        $a = $x - $xi;
        $b = $y - $yi;
        $c = $xj - $xi;
        $d = $yj - $yi;

        $dot = ($a * $c) + ($b * $d);
        $len_sq = ($c * $c) + ($d * $d);

        $param = -1;
        if ($len_sq != 0) {
            $param = $dot / $len_sq;
        }
        
        if ($param < 0) {
            $xx = $xi;
            $yy = $yi;
        } else if ($param > 1) {
            $xx = $xj;
            $yy = $yj;
        } else {
            $xx = $xi + ($param * $c);
            $yy = $yi + ($param * $d);
        }
        $dx = $x - $xx;
        $dy = $y - $yy;
        
        $dist = sqrt($dx * $dx + $dy + $dy);
        
        if ($i == 0) {
            $min = $dist;
        } else if ($dist < $min) {
            $min = $dist;
        }
    }
    
    return $min;
}
