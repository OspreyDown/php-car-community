<?php
    session_start();
    require_once('pagetitles.php');
    require_once('config.php');
    $page_title = CARS_BROWSE_PAGE;
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
    <body>
        <?php
            include('nav.php');
        ?>
        <main>

            <section class="py-1 text-center container">
                <div class="row py-lg-3">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">Upload your car now!</h1>
                    <p class="lead text-muted">Not happy with the selection? Register now and you can help
                        expand the car collection.
                    </p>
                    <p>
                    <a href="submit.php" class="btn btn-primary my-2">Upload Car</a>
                    </p>
                </div>
                </div>
            </section>

            <div class="album py-5 bg-light">
                <div class="container">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

                        <?php
                            $db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                or trigger_error('Error Connecting To DB', E_USER_ERR);
                            
                            $sql = "SELECT * FROM cars WHERE deleted != 1 ORDER BY id DESC";
                            $result = mysqli_query($db, $sql)
                                or trigger_error('Error Querying Database', E_USER_ERR);

                            
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
                                                <a class="btn btn-primary" href="cardetails.php?id=<?= $row['id'] ?>">View</a>
                                            </div>
                                            <small class="text-muted"><?= htmlspecialchars($row['user_name']) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile?>
                    </div>
                </div>
            </div>

        </main>
<?php
include 'footer.php';
?>