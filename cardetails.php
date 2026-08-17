<?php
    session_start();
    require_once('pagetitles.php');
    require_once('config.php');
    require_once('queryutils.php');
    require_once('carimagefileutil.php');

    $page_title = CARS_DETAILS_PAGE;

    if (!isset($_GET['id'])) {
        header('Location: browsecars.php');
        exit;
    }

    $id = (int)$_GET['id'];

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);

    $stmt = mysqli_prepare($dbc, "SELECT make, model, year, description,
            image_path, user_name FROM cars WHERE id = ? AND deleted = 0");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt)
        or trigger_error('Error querying cars table', E_USER_ERROR);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) !== 1) {
        header('Location: browsecars.php');
        exit;
    }

    $make = $row['make'];
    $model = $row['model'];
    $year = $row['year'];
    $description = $row['description'];
    $image_file = $row['image_path'];
    $owner = $row['user_name'];

    if (empty($image_file)) {
        $image_file = UPLOAD_PATH . DEFAULT_IMAGE_NAME;
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= htmlspecialchars($page_title) ?></title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet" crossorigin="anonymous">
    </head>
    <body>
        <?php require_once('nav.php'); ?>

        <main class="container py-4">
            <div class="card shadow-sm">

                <div class="card-header bg-white text-black">
                    <h1 class="h3 mb-0">Car Details</h1>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col mb-3 mb-md-0 text-center">
                            <img src="<?= htmlspecialchars($image_file) ?>"
                            class="img-fluid rounded" alt="Car image">
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <h2 class="h2 mb-3"><?= htmlspecialchars("{$make} {$model}") ?></h2>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                            <th class="px-1 py-2" scope="row">Make</th>
                            <td class="px-1 py-2"><?= htmlspecialchars($make) ?></td>
                            </tr>
                            <tr>
                            <th class="px-1 py-2" scope="row">Model</th>
                            <td class="px-1 py-2"><?= htmlspecialchars($model) ?></td>
                            </tr>
                            <tr>
                            <th class="px-1 py-2" scope="row">Year</th>
                            <td class="px-1 py-2"><?= htmlspecialchars($year) ?></td>
                            </tr>
                            <tr>
                            <th class="px-1 py-2" scope="row">Description</th>
                            <td class="px-1 py-2"><?= nl2br(htmlspecialchars($description)) ?></td>
                            </tr>
                            <tr>
                            <th class="px-1 py-2" scope="row">Owner</th>
                            <td class="px-1 py-2"><?= htmlspecialchars($owner) ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between">
                        <a href="browsecars.php" class="btn btn-outline-secondary">Back To Browsing</a>
                    </div>
                </div>
            </div>
        </div>
        </main>

        <script
            src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+..."
            crossorigin="anonymous"
        ></script>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
            integrity="sha384-wHAiFf..."
            crossorigin="anonymous"
        ></script>
        <script
            src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
            integrity="sha384-B0Ugly..."
            crossorigin="anonymous"
        ></script>
    </body>
</html>