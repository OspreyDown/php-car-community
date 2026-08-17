<?php
    session_start();
    require_once('pagetitles.php');
    require_once('config.php');
    require_once('queryutils.php');
    require_once('carimagefileutil.php');

    $page_title = CARS_REMOVE_PAGE;

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= htmlspecialchars($page_title) ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    </head>
    <?php require_once('nav.php'); ?>
    <body>
        <main class="container py-4">
            <div class="card">
                <div class="card-body">
                    <?php
                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);

                    if (isset($_POST['delete_car_submission'], $_POST['id'])) {
                        $id = $_POST['id'];
                        
                        // Fetch existing image path
                        $stmt = mysqli_prepare($dbc, "SELECT image_path FROM cars WHERE id = ? AND user_id = ?");
                        mysqli_stmt_bind_param($stmt, 'ii', $id, $user_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt)
                            or trigger_error('Error querying cars table', E_USER_ERROR);

                        if (mysqli_num_rows($result) === 1) {
                            $row = mysqli_fetch_assoc($result);
                            $image_path = $row['image_path'];
                            if (!empty($image_path)) {
                                removeCarImageFile($image_path);
                            }
                        }

                        // Soft-delete the record
                        $deleted_flag = 1;
                        $stmt = mysqli_prepare($dbc, "UPDATE cars SET deleted = ? WHERE id = ? AND user_id = ?");
                        mysqli_stmt_bind_param($stmt, 'ii', $deleted_flag, $id, $user_id);
                        mysqli_stmt_execute($stmt) or trigger_error('Error updating cars table', E_USER_ERROR);

                        header('Location: profile.php');
                        exit;

                    } elseif (isset($_POST['do_not_delete_car_submission'])) {
                        header('Location: profile.php');
                        exit;

                    } elseif (isset($_GET['id_to_delete'])) {
                        $id = $_GET['id_to_delete'];
                        $user_id = $_SESSION['user_id'];

                        $stmt = mysqli_prepare($dbc, "SELECT make, model, year, description, image_path FROM cars WHERE id = ? AND user_id = ?");
                        mysqli_stmt_bind_param($stmt, 'ii', $id, $user_id);
                        mysqli_stmt_execute($stmt);

                        $result = mysqli_stmt_get_result($stmt)
                            or trigger_error('Error querying cars table', E_USER_ERROR);

                        if (mysqli_num_rows($result) === 1) {
                            $row = mysqli_fetch_assoc($result);
                            $make        = $row['make'];
                            $model       = $row['model'];
                            $year        = $row['year'];
                            $description = $row['description'];
                            $image_display = !empty($row['image_path']) ? $row['image_path'] : UPLOAD_PATH . DEFAULT_IMAGE_NAME;
                            ?>
                                <h1 class="text-danger">Confirm Deletion of the Following Car:</h1>
                                <h2><?= htmlspecialchars($make . ' ' . $model) ?></h2>
                                <div class="row mb-3">
                                    <div class="col-2">
                                        <img src="<?= htmlspecialchars($image_display) ?>" class="img-thumbnail" style="max-height: 200px;" alt="Car image">
                                    </div>
                                    <div class="col">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr><th scope="row">Make</th><td><?= htmlspecialchars($make) ?></td></tr>
                                                <tr><th scope="row">Model</th><td><?= htmlspecialchars($model) ?></td></tr>
                                                <tr><th scope="row">Year</th><td><?= htmlspecialchars($year) ?></td></tr>
                                                <tr><th scope="row">Description</th><td><?= htmlspecialchars($description) ?></td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                                    <button class="btn btn-danger" type="submit" name="delete_car_submission">Delete Car</button>
                                    <button class="btn btn-secondary ms-2" type="submit" name="do_not_delete_car_submission">Cancel</button>
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                                </form>
                            <?php
                        } else {
                            echo '<h3>No Car Details found</h3>';
                        }
                    } else {
                        header('Location: browsecars.php');
                        exit;
                    }
                    ?>
                </div>
            </div>
        </main>
        <?php require_once('footer.php'); ?>
    </body>
</html>
