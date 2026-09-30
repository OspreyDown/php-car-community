<?php
    session_start();
    require_once('pagetitles.php');
    $page_title = CARS_HOME_PAGE;
?>
<!doctype html>
<html>
    
    <?php require_once('head.php'); ?>

    <body>

    <?php require_once('nav.php'); ?>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Left contrast column -->
            <aside class="col-lg-2 d-none d-lg-block bg-secondary p-0">
            <!-- you can put anything here (ads, nav, nothing) -->
            </aside>

            <main class="col-12 col-lg-8 px-4 py-5">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h1 class="my-4">Welcome To The Car Show!</h1>
                        <p class="lead">Here at the car show we don't discourage any type of ride.
                            Whether it's a beater or a muscle car we like them all!
                            Everyone should have the opportunity to share their
                            passion for cars.
                        </p>
                        <div class="container">
                            <div class="row row-cols-1 row-cols-md-3 g-3 text-center">
                                <div class="col">
                                    <img src="resources/car1.jpg"
                                        class="img-fluid border border-3 border-black rounded"
                                        style="height:180px; object-fit:cover;"
                                        alt="Lamborghini">
                                </div>
                                <div class="col">
                                    <img src="resources/car2.jpg"
                                        class="img-fluid border border-3 border-black rounded"
                                        style="height:180px; object-fit:cover;"
                                        alt="Ferarri">
                                </div>
                                <div class="col">
                                    <img src="resources/car3.jpg"
                                        class="img-fluid border border-3 border-black rounded"
                                        style="height:180px; object-fit:cover;"
                                        alt="Mclaren">
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="col text-center">
                            <h1 class="my-4">Browsing The Car Show</h1>
                            <p class="lead">This digital car show is like a physical car show. Anyone is allowed to look at the cars
                            , but to participate in the car show you must register. Navigate to our <a href="browsecars.php">
                            browse cars</a> page to take a look!
                            </p>
                        </div>
                        <hr/>
                        <div class="col text-center">
                            <h1 class="my-4">How To Share Cars</h1>
                            <p class="lead">Sharing your car is easy! Simply create an account on
                            our <a href="register.php">register account</a> page. Then
                            fill out the information of your car and choose your picture.</p>
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
require_once('footer.php');