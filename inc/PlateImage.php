<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PlateImage {
    private $image;
    private $image_type;
    
    public function __construct(string $file_data) {
        if (!empty($file_data)) {
            $this->load($file_data);
        }
    }
    
    public function __destruct() {
        if (is_resource($this->image)) {
            imagedestroy($this->image);
        }
    }
    
    public function load(string $file_data) {
        $this->image = imagecreatefromstring($file_data);
        
        $finfo = finfo_open();
        $file_mime_type = finfo_buffer($finfo, $file_data, FILEINFO_MIME_TYPE);

        if ($file_mime_type == 'image/jpeg' || $file_mime_type == 'image/jpg') {
            $this->image_type = IMAGETYPE_JPEG;
        } else if ($file_mime_type == 'image/png') {
            $this->image_type = IMAGETYPE_PNG;
        } else if ($file_mime_type == 'image/gif') {
            $this->image_type = IMAGETYPE_GIF;
        } else {
            // only accept the above filetypes
            echo 'error not valid';
            $this->image_type = 'other';
        }
    }
    
    public function save($filename, $image_type = '', $permissions = null) {
        $result = false;
        
        if ($image_type === '') {
            $image_type = $this->image_type;
        }

        if ($image_type == IMAGETYPE_JPEG) {
            $result = imagejpeg($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            $result = imagepng($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_GIF) {
            $result = imagegif($this->image, $filename);
        }

        if ($permissions != null) {
            chmod($filename, $permissions);
        }
        
        if ($result) {
            //success
        } else {
            //fail
            echo 'Failed to save image';
        }
    }

    public function getWidth() {
        return imagesx($this->image);
    }

    public function getHeight() {
        return imagesy($this->image);
    }

    /**
     * function drawline
     * Draws a line between 2 points
     *
     * @param   integer     x1      first point x coordinate
     * @param   integer     y1      first point y coordinate
     * @param   integer     x2      second point x coordinate
     * @param   integer     y2      second point y coordinate
     * @param   integer     thick   thickness of line
     * @param   integer     r       red (0 to 255)
     * @param   integer     g       green (0 to 255)
     * @param   integer     b       blue (0 to 255)
     * @param   integer     alpha   (0 to 127)
     *
     * @access  public
     */
    public function drawLine(int $x1, int $y1, int $x2, int $y2, int $thick = 1, int $r = 255, int $g = 255, int $b = 255, int $alpha = 0) {
        $colour = imagecolorallocatealpha($this->image, $r, $g, $b, $alpha);

        if ($thick == 1) {
            return imageline($this->image, $x1, $y1, $x2, $y2, $colour);
        }
        $t = $thick / 2 - 0.5;
        if ($x1 == $x2 || $y1 == $y2) {
            return imagefilledrectangle($this->image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $colour);
        }
        $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
        $a = $t / sqrt(1 + pow($k, 2));
        $points = array(
            round($x1 - (1 + $k) * $a), round($y1 + (1 - $k) * $a),
            round($x1 - (1 - $k) * $a), round($y1 - (1 + $k) * $a),
            round($x2 + (1 + $k) * $a), round($y2 - (1 - $k) * $a),
            round($x2 + (1 - $k) * $a), round($y2 + (1 + $k) * $a),
        );
        imagefilledpolygon($this->image, $points, 4, $colour);
        imagepolygon($this->image, $points, 4, $colour);
    }

    public function addString(int $font, int $x, int $y, string $string, int $r = 255, int $g = 255, int $b = 255, int $alpha = 0) {
        $colour = imagecolorallocatealpha($this->image, $r, $g, $b, $alpha);

        imagestring($this->image, $font, $x, $y, $string, $colour);
    }

    public function addTimestamp(int $font, int $x, int $y, int $r = 255, int $g = 255, int $b = 255, int $alpha = 0) {
        $timestamp = date('Y-m-d G:i:s');

        $this->addString($font, $x, $y, $timestamp, $r, $g, $b, $alpha);
    }
    
    public function getImageType() {
        return $this->image_type;
    }
    
    public function getImage() {
        return $this->image;
    }
}

