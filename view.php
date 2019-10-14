<?php include "inc/sessionStart.php"; ?>
<!DOCTYPE html>
<html>
    <?php
    // dont need title because it is set to header

    /* INCLUDES */
    include 'inc/header.php';
    include 'inc/config.php';
    include 'inc/database.php';
    ?>

    <body>
        <div data-role="page" data-theme="a">
            <div data-role="header" data-position="fixed" data-theme="a">
                <h1 class="lpr-title">View Plates - Smart License Plate</h1>
                <a class="ui-btn-right ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-notext ui-icon-home" href="index.php"></a>
            </div>

            <div data-role="content" data-theme="b">
                <p>Here you can see previously taken images.</p>
                <?php
                $format = 'Y-m-d G:i:s';
                $query = "SELECT * FROM details";
                $plates = DB::run($query)->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <table class="ui-responsive table-stroke" id="view-table" data-role="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Plate</th>
                            <th>State</th>
                            <th>View Full</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($plates as $plate) {
                            ?>
                            <tr>
                                <td>
                                    <?php
                                    $epoch = intval(substr($plate['epoch'], 0, 10));
                                    $dt = new DateTime("@$epoch");
                                    $timestamp = $dt->format(get_date_format());
                                    echo $timestamp;
                                    ?>
                                </td>
                                <td><?php echo $plate['plate']; ?></td>
                                <td><?php echo $plate['state']; ?></td>
                                <td>
                                    <form action="full.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $plate['id']; ?>" />
                                        <input type="submit" data-icon="arrow-r" data-iconpos="notext" />
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div data-role="footer" data-position="fixed" data-theme="b">
                <h1></h1>
            </div>
        </div>
    </body>
</html>