<?php

/*
 * CustomImage Class.
 * Used for editing images server-side.
 */

class CustomImage {

    private $image;
    private $image_type;

    public function __construct(string $filename = null) {
        if (!empty($filename)) {
            $this->load($filename);
        }
    }

    public function __destruct() {
        if (is_resource($this->image)) {
            imagedestroy($this->image);
        }
    }

    public function load(string $filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];

        if ($this->image_type === IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type === IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        } elseif ($this->image_type === IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } else {
            // image is not valid
			echo 'error not valid';
        }
    }

    public function save($filename, $image_type = '', $permissions = null) {
        if ($image_type === '') {
            $image_type = $this->image_type;
        }

        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        }

        if ($permissions != null) {
            chmod($filename, $permissions);
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
}
