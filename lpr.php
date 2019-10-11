<?php include "inc/sessionStart.php"; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Smart Plate</title>
        <?php
        /* INCLUDES */
        require dirname(__FILE__) . '/inc/header.php';
        require dirname(__FILE__) . '/inc/functions.php';
        require dirname(__FILE__) . '/inc/config.php';
        require dirname(__FILE__) . '/inc/database.php';
        require dirname(__FILE__) . '/inc/PlateImage.php';
        ?>
    </head>

    <body>
        <div data-role="page" data-theme="c">
            <div data-role="header" data-position="fixed" data-theme="c">
                <h1>Smart License Plate</h1>
            </div>

            <div data-role="content" data-theme="b">
                <h2>Please check that all the details are correct</h2>
                <?php
                if (empty($_FILES)) {
                    die('no direct access');
                }
                $tmp_file = $_FILES['image']['tmp_name'];

                $secret_key = 'sk_348399cb8002863d001acb77';
                $url = 'https://api.openalpr.com/v2/recognize_bytes?secret_key=sk_348399cb8002863d001acb77&recognize_vehicle=1&country=au';

                $file_data = file_get_contents($tmp_file);
                $base64 = base64_encode($file_data);
                $len = strlen($base64);

                $base_head = 'Content-type: application/json\r\n' . "Content-length: $len";

                $options = array(
                    'http' => array(
                        'header' => $base_head,
                        'method' => 'POST',
                        'content' => $base64
                    )
                );
                $context = stream_context_create($options);
                $response = file_get_contents($url, false, $context);
                $lpr_results = json_decode($response, true);
                
                $results = $lpr_results['results'];
                $epoch_time = $lpr_results['epoch_time'];

                $img = new PlateImage($tmp_file);
                $img->addTimestamp(200, 40, 200, intval(substr($epoch_time, 0, 10)), 255);

                // detect all plates
                foreach ($results as $plate) {
                    $name = $plate['plate'];
                    $coords = $plate['coordinates'];

                    for ($i = 0; $i < count($coords); $i++) {
                        $x1 = $coords[$i]['x'];
                        $y1 = $coords[$i]['y'];
                        if ($i == count($coords) - 1) {
                            $x2 = $coords[0]['x'];
                            $y2 = $coords[0]['y'];
                        } else {
                            $x2 = $coords[$i + 1]['x'];
                            $y2 = $coords[$i + 1]['y'];
                        }

                        $img->drawLine($x1, $y1, $x2, $y2, 10, 255);
                    }
                }

                switch ($img->getImageType()) {
                    case 1:
                        $file_type = 'gif';
                        break;
                    case 2:
                        $file_type = 'jpeg';
                        break;
                    case 3:
                        $file_type = 'png';
                        break;
                    default:
                        $file_type = 'other';
                }

                $file_name = uniqid() . '.' . $file_type;
                $img->save('uploads/' . $file_name);
                // free up memory when finished
                unset($img);
                echo "<img id='preview-img' src='uploads/$file_name' />";
                ?>
                <p>Confirm that all details are correct by checking all the boxes</p>
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
                <form action="rego.php" method="post" data-ajax="false">
                    <div id="lpr-plate" data-role="fieldcontain">
                        <label for="plate">Plate:</label>
                        <input type="text" name="plate" id="plate" value="<?php echo $results[0]['plate']; ?>" />
                        <input type="checkbox" class="lpr-check" name="completed-0" />
                    </div>
                    <div id="lpr-state" data-role="fieldcontain">
                        <label for="state">State:</label>
                        <input type="text" name="state" id="state" value="<?php echo $results[0]['region']; ?>" />
                        <input type="checkbox" class="lpr-check" name="completed-1" />
                    </div>
                    <div id="lpr-make" data-role="fieldcontain">
                        <label for="make">Make:</label>
                        <input type="text" name="make" id="make" value="<?php echo $results[0]['vehicle']['make'][0]['name']; ?>" />
                        <input type="checkbox" class="lpr-check" name="completed-2" />
                    </div>
                    <div id="lpr-model" data-role="fieldcontain">
                        <label for="model">Model:</label>
                        <input type="text" name="model" id="model" value="<?php echo $results[0]['vehicle']['make_model'][0]['name']; ?>" />
                        <input type="checkbox" class="lpr-check" name="completed-3" />
                    </div>
                    <div id="lpr-colour" data-role="fieldcontain">
                        <label for="colour">Colour:</label>
                        <input type="text" name="colour" id="colour" value="<?php echo $results[0]['vehicle']['color'][0]['name']; ?>" />
                        <input type="checkbox" class="lpr-check" name="completed-4" />
                    </div>
                    <input type="hidden" name="epoch" value="<?php echo $epoch_time; ?>" />
                    <input type="hidden" name="filename" value="<?php echo $file_name; ?>" />
                    <input id="rego-submit" type="submit" value="Yes" disabled="true"/>
                </form>
                <form action="test.php">
                    <input type="submit" value="Restart"/>
                </form>
            </div>
        </div>
    </body>
</html>