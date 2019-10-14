<?php include "inc/sessionStart.php"; ?>
<!DOCTYPE html>
<html>
    <?php
    // dont need title because it is set to header

    /* INCLUDES */
    include 'inc/header.php';
    include 'inc/config.php';
    include 'inc/database.php';
    include 'inc/functions.php';
    ?>

    <body>
        <div data-role="page" data-theme="a">
            <div data-role="header" data-position="fixed" data-theme="a">
                <a class="ui-btn-left ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-notext ui-icon-arrow-l" href="view.php"></a>
                <h1 class="lpr-title">Plate - Smart License Plate</h1>
                <a class="ui-btn-right ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-notext ui-icon-home" href="index.php"></a>
            </div>

            <div data-role="content" data-theme="b">
                <?php
                if (!isset($_POST['id'])) {
                    die('Nothing to display');
                }

                $id = $_POST['id'];
                $query = "SELECT * FROM details WHERE id=?";
                $args = [$id];
                $plate = DB::run($query, $args)->fetchAll(PDO::FETCH_ASSOC);
//                dump_custom($plate);
                ?>
                <label id="db-plate">Plate: <strong><?php echo $plate[0]['plate']; ?></strong></label>
                <label id="db-state">State: <strong><?php echo $plate[0]['state']; ?></strong></label>
                <table class="my-table">
                    <tr>
                        <th></th>
                        <th>LPR</th>
                        <th>Rego Check</th>
                    </tr>
                    <tr>
                        <td>Make</td>
                        <td id="lpr-make"><?php echo $plate[0]['lpr_make']; ?></td>
                        <td id="rego-make"><?php echo $plate[0]['rego_make']; ?></td>
                    </tr>
                    <tr>
                        <td>Model</td>
                        <td id="lpr-model"><?php echo $plate[0]['lpr_model']; ?></td>
                        <td id="rego-model"><?php echo $plate[0]['rego_model']; ?></td>
                    </tr>
                    <tr>
                        <td>Colour</td>
                        <td id="lpr-colour"><?php echo $plate[0]['lpr_colour']; ?></td>
                        <td id="rego-colour"><?php echo $plate[0]['rego_colour']; ?></td>
                    </tr>
                </table>
                <img id="preview-img" src="<?php echo 'uploads/' . $plate[0]['filename']; ?>" />
            </div>

            <div data-role="footer" data-position="fixed" data-theme="b">
                <h1></h1>
            </div>
        </div>
    </body>
</html>