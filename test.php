<?php // include "inc/sessionStart.php";    ?>
<!DOCTYPE html>
<html>
    <?php
    /* INCLUDES */
    include "inc/header.php";
    ?>

    <body>
        <div data-role="page" data-theme="c">
            <div data-role="header" data-position="fixed" data-theme="c">
                <h1>Smart License Plate</h1>
            </div>

            <div data-role="content" data-theme="b">
                <img id="img-preview" src="">

                <form action="lpr.php" method="POST" enctype="multipart/form-data" data-ajax="false">
                    <input type="file" name="image">
                    <input type="submit" value="Submit">
                </form>

                <div id="testing-div">
                </div>
            </div>

            <div data-role="footer" data-position="fixed" data-theme="b">
                <h1>Camera Upload</h1>
            </div>
        </div>
    </body>
</html>