<?php
session_start();
require_once('pagetitles.php');
require_once('config.php');
require_once('queryutils.php');
require_once('carimagefileutil.php');

$page_title = CARS_SUBMIT_PAGE;

if (!isset($_SESSION['user_id']))
{
    header('Location: login.php');
    exit;
}

$make = '';
$model = '';
$year = '';
$description = '';
$errors = [];
$show_submit_form = true;

if (isset($_POST['make'], $_POST['model'], $_POST['year'], $_POST['description']))
{
    $user_id = $_SESSION['user_id'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $description = $_POST['description'];
    $user_name = $_SESSION['user_name'];

    $file_error_message = validateCarImageFile();

    if ($file_error_message)
    {
        $errors[] = $file_error_message;
    }

    if (empty($errors))
    {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or trigger_error(
            'Error connecting to MySQL server for' . DB_NAME, E_USER_ERROR
        );

        $image_path = addcarImageFileReturnPathLocation();

        if (empty($image_path))
        {
            $image_path = UPLOAD_PATH . DEFAULT_IMAGE_NAME;
        }

        $stmt = mysqli_prepare($dbc,
                "INSERT INTO cars (user_id, make, model, year, description, user_name, image_path)
                VALUES (?, ?, ?, ?, ?, ?, ?)");

        mysqli_stmt_bind_param($stmt, 'ississs',
            $_SESSION['user_id'],
            $make,
            $model,
            $year,
            $description,
            $_SESSION['user_name'],
            $image_path);

        mysqli_stmt_execute($stmt);

        $show_submit_form = false;

        ?>
        <?php require_once('nav.php'); ?>
        <h1>Thanks for your upload! See it in our collection <a href="browsecars.php">now!</a></h1>
        <h1><?= htmlspecialchars($make) ?> <?= htmlspecialchars($model) ?></h1>
        <div class="row">
            <div class="col-2">
                <img src="<?= htmlspecialchars($image_path) ?>"
                    class="img-thumbnail" style="max-height: 200px;" alt="Picture of a car that a user uploaded">
            </div>
            <div class="col">
                <table class="table table-striped">
                <tbody>
                    <tr>
                        <th scope="row">Make</th>
                        <td><?= htmlspecialchars($make) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Model</th>
                        <td><?= htmlspecialchars($model) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Year</th>
                        <td><?= htmlspecialchars($year) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Description</th>
                        <td><?= htmlspecialchars($description) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Author</th>
                        <td><?= htmlspecialchars($user_name) ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
}
?>
<!doctype html>
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?= $page_title ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet" crossorigin="anonymous">
        </head>
        <?php require_once('nav.php'); ?>
        <body>
        <main class="container py-4">
        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err) : ?>
                    <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
                <?php
                    if ($show_submit_form)
                    {
                        $make = '';
                        $model = '';
                        $year = '';
                        $description = '';
                ?>
                <div>
                    <form action="submit.php" method="post" enctype="multipart/form-data">
                        <div class="row g-3">
                        <div class="col-md-6">
                            <label for="make" class="form-label">Make</label>
                            <input type="text" class="form-control" id="make" name="make"
                                required value="<?= htmlspecialchars($_POST['make'] ?? '' )?>">
                        </div>
                        <div class="col-md-6">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control" id="model" name="model"
                                required value="<?= htmlspecialchars($_POST['model'] ?? '' )?>">
                        </div>
                        <div class="col-md-4">
                            <label for="year" class="form-label">Year</label>
                            <input type="number" class="form-control" id="year" name="year"
                                min="1886" max="<?= date('Y') ?>" required
                                value="<?= htmlspecialchars($_POST['year'] ?? '' )?>">
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"
                            rows="3"><?= htmlspecialchars($_POST['description'] ?? ''  )?></textarea>
                        </div>
                        <div class="col-12">
                            <label for="image" class="form-label">Car Image</label>
                            <input class="form-control" type="file" id="image" name="image">
                        </div>
                        </div>

                        <button type="submit" class="btn btn-success mt-4">Submit Car</button>
                    </form>
                </div>
            <?php } ?>
            </div>
        </main>

<?php
require_once ('footer.php');
?>