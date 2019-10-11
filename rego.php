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

        define('TESTING', false);
        ?>
    </head>

    <body>
        <div data-role="page" data-theme="c">
            <div data-role="header" data-position="fixed" data-theme="c">
                <h1>Smart License Plate</h1>
            </div>

            <div data-role="content" data-theme="b">
                <?php
//                dump_custom($_POST);
                if (empty($_POST)) {
                    $_POST = array('plate' => '511TYP',
                        'state' => 'au-qld',
                        'make' => 'toyota',
                        'model' => 'toyota_corolla',
                        'colour' => 'silver-gray',
                        'epoch' => '1570750237895',
                        'filename' => '5d9fbf1e38476.jpeg');
                }

                if (TESTING) {
                    $plate = 'XXXXXXXXXXXXXXXXX';
                    $state = 'NSW';
                } else {
                    $plate = $_POST['plate'];
                    $state = strtoupper(substr($_POST['state'], 3, 6));
                }
//                dump_custom($plate);
//                dump_custom($state);

                $query = <<<EOD
                        query {
                            nevdisPlateSearch_v2(plate: "$plate", state: $state) {
                                vin
                                plate {
                                  number
                                  state
                                }
                                make
                                model
                                engine_number
                                vehicle_type
                                body_type
                                colour
                            }
                        }
EOD;
                $data = json_encode(array('query' => $query));
                $len = strlen($data);

                $url = 'https://ubuxgyols2.execute-api.ap-southeast-2.amazonaws.com/prod/';
                $jwt = 'JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJjMzZlMDIyZS02ODZkLTQ0NzMtYmRjOS1kODAyOGFjZjkyNmMiLCJuYW1lIjoiVGhlIFVuaXZlcnNpdHkgb2YgUXVlZW5zbGFuZCIsImlhdCI6MTUxNjIzOTAyMn0.k2RscL-28hP2oggWlfjGr7DULk2Hsb6fBMb9V-VuF7s';

                $head = "Authorization: $jwt\r\n" .
                        'Content-type: application/json\r\n' .
                        "Content-length: $len";

                $options = array(
                    'http' => array(
                        'header' => $head,
                        'method' => 'POST',
                        'content' => $data
                    )
                );
                $context = stream_context_create($options);
                $response = file_get_contents($url, false, $context);
                $rego_results = json_decode($response, true);

                $results = $rego_results['data']['nevdisPlateSearch_v2'][0];
                ?>
                <label id="db-plate">Plate: <strong><?php echo $_POST['plate']; ?></strong></label>
                <label id="db-state">State: <strong><?php echo $_POST['state']; ?></strong></label>
                <label id="db-epoch">Epoch: <strong><?php echo $_POST['epoch']; ?></strong></label>
                <label id="db-filename">Filename: <strong><?php echo $_POST['filename']; ?></strong></label>
                <table class="my-table">
                    <tr>
                        <th></th>
                        <th>LPR</th>
                        <th>Rego Check</th>
                    </tr>
                    <tr>
                        <td>Make</td>
                        <td id="lpr-make"><?php echo $_POST['make']; ?></td>
                        <td id="rego-make"><?php echo $results['make']; ?></td>
                    </tr>
                    <tr>
                        <td>Model</td>
                        <td id="lpr-model"><?php echo $_POST['model']; ?></td>
                        <td id="rego-model"><?php echo $results['model']; ?></td>
                    </tr>
                    <tr>
                        <td>Colour</td>
                        <td id="lpr-colour"><?php echo $_POST['colour']; ?></td>
                        <td id="rego-colour"><?php echo $results['colour']; ?></td>
                    </tr>
                </table>
                <p>Are these details correct?</p>
                <form class="details-form">
                    <input type="hidden" name="func" value="save"/>
                    <input type="hidden" name="epoch" value="<?php echo $_POST['epoch']; ?>" />
                    <input type="hidden" name="filename" value="<?php echo $_POST['filename']; ?>" />
                    <input type="hidden" name="plate" value="<?php echo $_POST['plate']; ?>" />
                    <input type="hidden" name="state" value="<?php echo $_POST['state']; ?>" />
                    <input type="hidden" name="lpr_make" value="<?php echo $_POST['make']; ?>" />
                    <input type="hidden" name="lpr_model" value="<?php echo $_POST['model']; ?>" />
                    <input type="hidden" name="lpr_colour" value="<?php echo $_POST['colour']; ?>" />
                    <input type="hidden" name="rego_make" value="<?php echo $results['make']; ?>" />
                    <input type="hidden" name="rego_model" value="<?php echo $results['model']; ?>" />
                    <input type="hidden" name="rego_colour" value="<?php echo $results['colour']; ?>" />
                    <input type="hidden" name="infringe" value="<?php echo true; ?>" />
                    <input type="submit" value="Submit" />
                </form>
                <form action="test.php">
                    <input type="submit" value="Restart"/>
                </form>
            </div>
        </div>
    </body>
</html>