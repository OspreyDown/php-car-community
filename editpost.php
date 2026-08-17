<?php
session_start();
require_once('pagetitles.php');
require_once('config.php');
require_once('queryutils.php');
require_once('carimagefileutil.php');

$page_title = CARS_EDIT_PAGE;

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$show_edit_form = true;
$make = '';
$model = '';
$year = '';
$description = '';
$current_image_path = '';
$image_displayed = UPLOAD_PATH . DEFAULT_IMAGE_NAME;

// Fetch existing car details for editing
if (isset($_GET['id_to_edit'])) {
    $id_to_edit = $_GET['id_to_edit'];
    $user_id = $_SESSION['user_id'];

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);

    $stmt = mysqli_prepare($dbc, "SELECT make, model, year, description, image_path FROM cars WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $id_to_edit, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt)
        or trigger_error('Error querying cars table', E_USER_ERROR);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $make = $row['make'];
        $model = $row['model'];
        $year = $row['year'];
        $description = $row['description'];
        $current_image_path = $row['image_path'];

        if (!empty($current_image_path)) {
            $image_displayed = $current_image_path;
        }
    } else {
        header('Location: browsecars.php');
        exit;
    }
}
// Handle form submission for updating
elseif (isset(
                $_POST['edit_car_submission'],
                $_POST['make'], $_POST['model'],
                $_POST['year'], $_POST['description'],
                $_POST['id_to_update'],
                $_SESSION['user_id']
        )) {

    $id_to_update       = $_POST['id_to_update'];
    $make               = $_POST['make'];
    $model              = $_POST['model'];
    $year               = $_POST['year'];
    $description        = $_POST['description'];
    $current_image_path = $_POST['current_image_path'] ?? '';
    $user_id            = $_SESSION['user_id'];

    // Validate uploaded image
    $file_error = validateCarImageFile();
    if ($file_error) {
        $errors[] = $file_error;
    }

    if (empty($errors)) {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);

        $new_image_path = addcarImageFileReturnPathLocation();

        if (!empty($new_image_path)) {
            // Remove old image if it isn't default
            if (!empty($current_image_path) && $current_image_path !== UPLOAD_PATH . DEFAULT_IMAGE_NAME) {
                removeCarImageFile($current_image_path);
            }
            $current_image_path = $new_image_path;
        }

        if (empty($current_image_path)) {
            $current_image_path = UPLOAD_PATH . DEFAULT_IMAGE_NAME;
        }

        $stmt = mysqli_prepare(
            $dbc,
            "UPDATE cars SET make = ?, model = ?, year = ?, description = ?, 
                image_path = ? WHERE id = ? AND user_id = ?"
        );
        mysqli_stmt_bind_param(
            $stmt,
            'ssissii',
            $make,
            $model,
            $year,
            $description,
            $current_image_path,
            $id_to_update,
            $user_id
        );
        mysqli_stmt_execute($stmt)
            or trigger_error('Failed to update car listing', E_USER_ERROR);

        header('Location: profile.php');
        exit;
    }
} else {
    header('Location: browsecars.php');
    exit;
}
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
    <h1>Edit Car</h1>
    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err) : ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label for="make" class="form-label">Make</label>
            <input type="text" class="form-control" id="make" name="make" required value="<?= htmlspecialchars($make) ?>">
        </div>
        <div class="col-md-6">
            <label for="model" class="form-label">Model</label>
            <input type="text" class="form-control" id="model" name="model" required value="<?= htmlspecialchars($model) ?>">
        </div>
        <div class="col-md-4">
            <label for="year" class="form-label">Year</label>
            <input type="number" class="form-control" id="year" name="year" min="1886" max="<?= date('Y') ?>" required value="<?= htmlspecialchars($year) ?>">
        </div>
        <div class="col-12">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($description) ?></textarea>
        </div>
        <div class="col-12">
            <label for="image" class="form-label">Car Image</label>
            <input class="form-control" type="file" id="image" name="image">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary mt-3" name="edit_car_submission">Update Car</button>
        </div>
        <input type="hidden" name="id_to_update" value="<?= htmlspecialchars($id_to_edit ?? '') ?>">
        <input type="hidden" name="current_image_path" value="<?= htmlspecialchars($current_image_path) ?>">
    </form>

    <div class="mt-4">
        <img src="<?= htmlspecialchars($image_displayed) ?>" class="img-thumbnail" style="max-height: 400px;" alt="Car image">
    </div>
</main>
<?php require_once('footer.php'); ?>
</body>
</html>
