<?php // include "inc/sessionStart.php";                  ?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        /* INCLUDES */
        include "inc/header.php";
        include 'inc/config.php';
        include 'inc/functions.php';
        ?>
    </head>

    <body>
        <div data-role="page" data-theme="c">
            <div data-role="header" data-position="fixed" data-theme="c">
                <h1>Smart License Plate</h1>
            </div>

            <div data-role="content" data-theme="b">
                <?php 
                $zones = create_zones();
                
                $p = [152.9714, -27.5957];
                $points = $zones['Home'];
                echo point_to_polygon($p, $points);
                ?>
                <img id="img-preview" src="">

                <form action="lpr.php" method="POST" enctype="multipart/form-data" data-ajax="false">
                    <input type="file" name="image">
                    <input type="submit" value="Submit">
                </form>

                <div id="testing-div">
                    <form>
                        <label for="lpr-plate"><strong>Plate</strong> Confidence: 77%</label>
                        <div id="lpr-plate" data-role="fieldcontain">
                            <input type="text" name="plate" id="plate" value="ASD123" />
                            <label>
                                <input type="checkbox" class="lpr-check" />Correct?
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            <table class="my-table">
                <tr>
                    <th>Property</th>
                    <th>Confidence (%)</th>
                </tr>
                <tr>
                    <td>Plate: <?php echo $results[0]['plate']; ?></td>
                    <td><?php echo $results[0]['confidence']; ?></td>
                </tr>
                <tr>
                    <td>Region: <?php echo $results[0]['region']; ?></td>
                    <td><?php echo $results[0]['region_confidence']; ?></td>
                </tr>
                <tr>
                    <td>Make: <?php echo $results[0]['vehicle']['make'][0]['name']; ?></td>
                    <td><?php echo $results[0]['vehicle']['make'][0]['confidence']; ?></td>
                </tr>
                <tr>
                    <td>Model: <?php echo $results[0]['vehicle']['make_model'][0]['name']; ?></td>
                    <td><?php echo $results[0]['vehicle']['make_model'][0]['confidence']; ?></td>
                </tr>
                <tr>
                    <td>Body Type: <?php echo $results[0]['vehicle']['body_type'][0]['name']; ?></td>
                    <td><?php echo $results[0]['vehicle']['body_type'][0]['confidence']; ?></td>
                </tr>
                <tr>
                    <td>Colour: <?php echo $results[0]['vehicle']['color'][0]['name']; ?></td>
                    <td><?php echo $results[0]['vehicle']['color'][0]['confidence']; ?></td>
                </tr>
            </table>

            <div data-role="footer" data-position="fixed" data-theme="b">
                <h1>Camera Upload</h1>
            </div>
        </div>
    </body>
</html>