<?php
    session_start();
    require_once('pagetitles.php');
    require_once('queryutils.php');
    $page_title = CARS_REGISTER_PAGE;
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $page_title ?></title>
        <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"/>
    </head>
    <body>
        <?php require_once('nav.php'); ?>
        <?php
            if (isset($_SESSION['user_name'])) {
                header('Location: profile.php');
            }
        ?>
        <div class="card">
            <div class="card-body">
                <h1><?= $page_title ?></h1>
                <hr/>
                <?php
                    $show_sign_up_form = true;

                    if (isset($_POST['signup_submission']))
                    {
                        $user_name = $_POST['user_name'];
                        $password = $_POST['password'];

                        if (!empty($user_name) && !empty($password))
                        {
                            require_once('config.php');
                            require_once('queryutils.php');

                            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                or trigger_error(
                                    'error connecting to MySql server for' . DB_NAME,
                                    E_USER_ERROR
                                );

                            $query = "SELECT * FROM users WHERE user_name = ?";

                            $results = parameterizedQuery($dbc, $query, 's', $user_name)
                                or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                            
                            // if user does not exist, create an account for them
                            if (mysqli_num_rows($results) == 0)
                            {
                                $salted_hashed_password = password_hash($password, PASSWORD_DEFAULT);

                                $query = "INSERT INTO users (`user_name`, `password_hash`)
                                        VALUES (?, ?)";
                                $results = parameterizedQuery($dbc, $query, 'ss', $user_name, $salted_hashed_password)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                                
                                // Direct the user to the login page
                                echo "<h4><p class='text-success'>Thank you for signing up <strong>" . htmlspecialchars($user_name) . " </strong>! "
                                    . "Your new account has been created.<br/>"
                                    . "You're now ready to <a href='login.php'>log in</a>."
                                    . "Now get out there and share some cars!</p></h4>";

                                $show_sign_up_form = false;
                            }
                            else // An account already exists for this user
                            {
                                echo "<h4><p class='text-danger'>An account already exists
                                for this username:<span class='font-weight-bold'> (" . htmlspecialchars($user_name) . ")</span>.
                                Please use a different user name.</p></h4><hr/>";
                            }
                        }
                        else
                        {
                            // output error message
                            echo "<h4><p class='text-danger'>You must enter a "
                                . "user name AND password.</p></h4><hr/>";
                        }
                    }
                    if ($show_sign_up_form):
                ?>
                <form class="needs-validation" novalidate method="POST"
                    action="<?= $_SERVER['PHP_SELF'] ?>">
                    <div class="form-group row">
                        <label for="user_name"
                            class="col-sm-2 col-form-label-lg">User Name</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control"
                            id="user_name" name="user_name"
                            placeholder="Enter a user name" required>
                            <div class="invalid-feedback">
                                Please provide a valid user name.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password"
                            class="col-sm-2 col-form-label-lg">Password</label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control"
                                id="password" name="password"
                                placeholder="Enter a password" required>
                            <div class="form-group form-check">
                                <input type="checkbox"
                                    class="form-check-input"
                                    id="show_password_check"
                                    onclick="togglePassword()">
                                <label class="form-check-label"
                                    for="show_password_check">Show Password</label>
                            </div>
                            <div class="invalid-feedback">
                                Please provide a valid password.
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit"
                        name="signup_submission">Sign Up</button>
                </form>
                <?php
                    endif;
                ?>
            </div>
        </div>
    <script>
        // JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
                }, false);
            });
            }, false);
        })();

        // JavaScript for showing and hiding the password
        function togglePassword() {
            var password_entry = document.getElementById("password");
            if (password_entry.type === "password") {
                password_entry.type = "text";
            } else {
                password_entry.type = "password";
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous">
    </script>
<?php
include('footer.php');
?>