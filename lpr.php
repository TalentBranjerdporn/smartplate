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
        <div data-role="page" data-theme="a">
            <div data-role="header" data-position="fixed" data-theme="a">
                <h1 class="lpr-title">LPR Results - Smart License Plate</h1>
            </div>

            <div data-role="content" data-theme="b">
                <?php
                if (empty($_FILES)) {
                    ?>
                    <a href="index.php">Please go back</a>
                    <?php
                    die();
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

//                dump_custom($lpr_results);

                $results = $lpr_results['results'];

                if (empty($results)) {
                    ?>
                    No plate found. Please go back and <a href="index.php">try again</a>
                    <?php
                    die();
                }
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
                ?>
                <h2>Please check that all the details are correct</h2>
                <?php
                echo "<img id='preview-img' src='uploads/$file_name' />";
                ?>
                <p>Confirm that all details and check the boxes if correct.</p>
                <form action="rego.php" method="post" data-ajax="false">
                    <label for="lpr-plate">Plate Confidence: <?php echo round($results[0]['confidence'], 2); ?>%</label>
                    <div id="lpr-plate" data-role="fieldcontain" class="lpr-confirm-field">
                        <input type="text" name="plate" value="<?php echo $results[0]['plate']; ?>" />
                        <label>
                            <input type="checkbox" class="lpr-check" />Correct?
                        </label>
                    </div>
                    <label for="lpr-state">State Confidence: <?php echo round($results[0]['region_confidence'], 2); ?>%</label>
                    <div id="lpr-state" data-role="fieldcontain" class="lpr-confirm-field">
                        <select name="state">
                            <?php
                            $states = ['QLD', 'NSW', 'ACT', 'NT', 'SA', 'TAS', 'VIC', 'WA', 'Unknown please check'];

                            $selected_state = strtoupper(substr($results[0]['region'], 3, 6));
                            $done = false;
                            foreach ($states as $key => $state) {
                                if ($state == $selected_state) {
                                    echo '<option value="' . $state . '" selected>' . $state . '</option>';
                                    $done = true;
                                } else {
                                    if ($key === (count($states) - 1) && !$done) {
                                        echo '<option value="-1" selected>' . $state . '</option>';
                                    } else {
                                        echo '<option value="' . $state . '">' . $state . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                        <label>
                            <input type="checkbox" class="lpr-check" />Correct?
                        </label>
                    </div>
                    <label for="lpr-make">Make Confidence: <?php echo round($results[0]['vehicle']['make'][0]['confidence'], 2); ?>%</label>
                    <div id="lpr-make" data-role="fieldcontain" class="lpr-confirm-field">
                        <input type="text" name="make" value="<?php echo $results[0]['vehicle']['make'][0]['name']; ?>" />
                        <label>
                            <input type="checkbox" class="lpr-check" />Correct?
                        </label>
                    </div>
                    <label for="lpr-model">Model Confidence: <?php echo round($results[0]['vehicle']['make_model'][0]['confidence'], 2); ?>%</label>
                    <div id="lpr-model" data-role="fieldcontain" class="lpr-confirm-field">
                        <input type="text" name="model" value="<?php echo $results[0]['vehicle']['make_model'][0]['name']; ?>" />
                        <label>
                            <input type="checkbox" class="lpr-check" />Correct?
                        </label>
                    </div>
                    <label for="lpr-colour">Colour Confidence: <?php echo round($results[0]['vehicle']['color'][0]['confidence'], 2); ?>%</label>
                    <div id="lpr-colour" data-role="fieldcontain" class="lpr-confirm-field">
                        <input type="text" name="colour" value="<?php echo $results[0]['vehicle']['color'][0]['name']; ?>" />
                        <label>
                            <input type="checkbox" class="lpr-check" />Correct?
                        </label>
                    </div>
                    <input type="hidden" name="epoch" value="<?php echo $epoch_time; ?>" />
                    <input type="hidden" name="filename" value="<?php echo $file_name; ?>" />
                    <input id="rego-submit" class="loading-on" type="submit" value="Yes" disabled="true" data-icon="arrow-r" data-iconpos="right"/>
                </form>
                <form action="index.php">
                    <input type="submit" value="Restart" data-icon="delete" data-iconpos="right"/>
                </form>
            </div>
        </div>
    </body>
</html>