<?php include("inc/sessionStart.php"); ?>
<!DOCTYPE html>
<html>

    <?php
    if (!defined('ROOT_DIR')) {
        define('ROOT_DIR', dirname(__DIR__) . '/');
    }
    if (!defined('LP_DIR')) {
        define('LP_DIR', dirname(__FILE__) . '/');
    }
    // Folder path of classes
    if (!defined('LP_CLASSES')) {
        define('LP_CLASSES', LP_DIR . "classes/");
    }
    // Folder path of includes
    if (!defined('LP_INCLUDES')) {
        define('LP_INCLUDES', LP_DIR . "inc/");
    }
    // Folder path of images
    if (!defined('LP_IMAGES')) {
        define('LP_IMAGES', LP_DIR . "images/");
    }
    // Folder path of uploads
    if (!defined('LP_UPLOADS')) {
        define('LP_UPLOADS', LP_DIR . "uploads/");
    }
    // Testing Environment
    if (!defined('LP_TESTING')) {
        define('LP_TESTING', 'ON');
    }

    define('MOBILE_LPR', TRUE);

    /* INCLUDES */
    include LP_INCLUDES . "header.php";
    include LP_INCLUDES . "functions.php";
    include "secrets.php";
    include "config.php";
    ?>

    <body>
        <div data-role="page" data-theme="c">
            <div data-role="header" data-position="fixed" data-theme="c">
                <h1>Smart License Plate</h1>
            </div>

            <div data-role="content" data-theme="b">
                <h2>Camera Test</h2>
                <!--<div id="progress-wrp" style="display:none">
                        <div class="progress-bar"></div>
                        <div class="status">0%</div>
                </div>
                
                <input type="file" accept="image/*" capture="camera" id="camera-file" name="image">
                <button type="button" onclick="file_submit()">Upload</button>
                
                <br>
                <img id="img-preview" src=""> -->
                <select>
                    <?php
                    $zones = create_zones();

                    foreach ($zones as $zone) {
                        echo '<option value="' . $zone . '">' . $zone . '</option>';
                    }
                    ?>
                </select>
                <form id="lpr-file">
                    <input type="file" accept="image/*" capture="camera" id="lpr-camera-file" name="image">
                </form>
                <br>
                <button type="button" data-icon="camera" onclick="lpr_submit()">LPR</button>
                <br>

                <h2>Result</h2>
                <div id="decode-result"></div><br>

                <div>
                    <h2>API Call</h2>
                    <button type="button" id="api_call">Press</button>
                </div>
            </div>

            <div data-role="footer" data-position="fixed" data-theme="b">
                <h1>Camera Upload</h1>
            </div>

        </div>
    </body>
</html>