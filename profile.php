<?php
    require_once('config.php');
    require_once('queryutils.php');
    require_once('pagetitles.php');
    $page_title = CARS_PROFILE_PAGE;
    session_start();
    if (!isset($_SESSION['user_id'])) {
      header('Location: login.php');
      exit();
    }

    $user_id   = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or trigger_error('Error connecting to MySQL server.', E_USER_ERROR);

    $sql = "SELECT * FROM cars WHERE user_name = ? AND deleted != 1";
    $result = parameterizedQuery($dbc, $sql, 's', $user_name);
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $page_title ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet" integrity="sha384-…" crossorigin="anonymous">
        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
    </head>
    <body>
        <?php
            include('nav.php');
        ?>
        <div class="container-fluid">
            <div class="row min-vh-100">
                <!-- Left contrast column -->
                <aside class="col-lg-2 d-none d-lg-block bg-secondary p-0">
                <!-- you can put anything here (ads, nav, nothing) -->
                </aside>

                <main class="col-12 col-lg-8 px-4 py-5">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h1 class="my-4"><?= htmlspecialchars($_SESSION['user_name']) ?>'s Profile and Posts</h1>
                            <div class="album py-5 bg-light">
                                <div class="container">
                                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                                        <?php
                                            
                                            while($row = mysqli_fetch_assoc($result)) :
                                        ?>
                                            <div class="col">
                                                <div class="card shadow-sm">
                                                    <img class="bd-placeholder-img card-img-top" src="<?= $row['image_path'] ?>" width="100%" height="225">
                                                    <div class="card-body">
                                                        <h3 class="card-text"><?= htmlspecialchars($row['make']) ?> <?= htmlspecialchars($row['model']) ?></h3>
                                                        <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="btn-group">
                                                                <a href="editpost.php?id_to_edit=<?= $row['id'] ?>" class="btn btn-primary">Edit</a>
                                                            </div>
                                                            <a href="removepost.php?id_to_delete=<?= $row['id'] ?>" title="Delete">
                                                            <i class='fas fa-trash-alt'></i></a>
                                                            <small class="text-muted"><?= htmlspecialchars($row['user_name']) ?></small>

                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <!-- Right contrast column -->
                <aside class="col-lg-2 d-none d-lg-block bg-secondary p-0">
                <!-- maybe social links or leave blank -->
                </aside>
            </div>
        </div>
        <?php
        include 'footer.php';
        ?>
