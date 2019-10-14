<?php include "inc/sessionStart.php"; ?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        // dont need title because it is set to header

        /* INCLUDES */
        include 'inc/header.php';
        include 'inc/config.php';
        ?>
    </head>

    <body>
        <div data-role="page" data-theme="a">
            <div data-role="header" data-position="fixed" data-theme="a">
                <h1 class="lpr-title">Smart License Plate</h1>
            </div>

            <div data-role="content" data-theme="b">
                <p>Start by selecting a zone and then take a picture of the full front or back of the vehicle including the license plate. Press submit to continue.</p>
                <form action="lpr.php" method="POST" enctype="multipart/form-data" data-ajax="false">
                    <select id="select-zones">
                        <?php
                        $zones = create_zones();

                        foreach ($zones as $key => $zone) {
                            echo '<option value="' . $key . '">' . $key . '</option>';
                        }
                        ?>
                    </select>
                    <?php
                    //<button id="gps-btn" data-icon="location" data-iconpos="right">Select Zone with GPS</button>
                    ?>
                    <input type="file" accept="image/*" capture="camera" id="lpr-camera-file" name="image">
                    <input class="loading-on" data-icon="arrow-r" data-iconpos="right" type="submit" value="Submit">
                </form>

                <h2>View Plates</h2>
                <form action="view.php">
                    <input type="submit" value="Go" data-icon="search" data-iconpos="right"/>
                </form>
            </div>

            <div data-role="footer" data-position="fixed" data-theme="b">
                <h1></h1>
            </div>
        </div>
    </body>
</html>