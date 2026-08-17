<nav class="navbar navbar-expand-md navbar-dark bg-dark border-bottom border-5 border-black">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><h1>Home</h1></a>
        <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">

                <li class="nav-item">
                    <a class="nav-link<?= $page_title == CARS_BROWSE_PAGE ? ' active' : '' ?>" href="browsecars.php">
                        Browse Cars</a>
                </li>

                <?php if (!isset($_SESSION['user_id'])) : ?>
                <li class="nav-item">
                    <a class="nav-link<?= $page_title == CARS_LOGIN_PAGE ? ' active' : '' ?>" href="login.php">
                        Login</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link<?= $page_title == CARS_REGISTER_PAGE ? ' active' : '' ?>" href="register.php">
                        Register</a>
                </li>

                <?php endif;
                if (isset($_SESSION['user_id'])) :
                ?>

                    <li class="nav-item">
                        <a class="nav-link<?= $page_title == CARS_SUBMIT_PAGE ? ' active' : '' ?>" href="submit.php">
                            Upload Car</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            Logout(<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            Profile</a>
                    </li>

                <?php endif;
                ?>
            </ul>
        </div>
    </div>
</nav>