<?php
    session_start();
    require_once('pagetitles.php');
    require_once('queryutils.php');
    $page_title = CARS_LOGIN_PAGE;
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
    <?php require_once('nav.php'); ?>
    <div class="card">
      <div class="card-body">
        <h1>Login To Your Account</h1>
        <hr/>
        <?php
        if (empty($_SESSION['user_id']) && isset($_POST['login_submission']))
        {
            // get user name and password
            $user_name = $_POST['user_name'];
            $password = $_POST['password'];

            if (!empty($user_name) && !empty($password))
            {
                require_once('config.php');

                require_once('queryutils.php');

                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or trigger_error(
                        'Error connecting to MySQL server for' . DB_NAME,
                        E_USER_ERROR );

                // check if user exists
                $query = "SELECT user_id, user_name, password_hash, privileges
                        FROM users WHERE user_name = ?";

                $results = parameterizedQuery($dbc, $query, 's', $user_name)
                        or trigger_error(mysqli_error($dbc), E_USER_ERROR);

                // if a user was found, validate password
                if (mysqli_num_rows($results) == 1)
                {
                    $row = mysqli_fetch_array($results);

                    if (password_verify($password, $row['password_hash']))
                    {
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['user_name'] = $row['user_name'];
                        $_SESSION['privileges'] = $row['privileges'];

                        // Redirect to the home page
                        $home_url = dirname($_SERVER['PHP_SELF']);
                        header('Location: ' . $home_url);
                        exit;
                    }
                    else
                    {
                        echo "<h3><p class='text-danger'>An incorrect user name 
                              or password was entered.</p></h3>";
                    }
                }
                else if (mysqli_num_rows($results) == 0) // User does not exist
                {
                    echo "<h4><p class='text-danger'>An account does not exist for this username:"
                        . "<span class='font-weight-bold'> (" . htmlspecialchars($user_name) . ")</span>. "
                        . "Please use a different user name.</p></h4><hr/>";
                }
                else
                {
                    echo "<h4><p class='text-danger'>Wait what the heck. How did that happen?</p></h4><hr/>";
                }
            }
            else
            {
                // Output error message
                echo "<h4><p class='text-danger'>You must enter both a user name and password.</p></h4><hr/>";
            }
        }
        if (empty($_SESSION['user_id'])):
        ?>
          <form class="needs-validation" novalidate method="POST"
                action="<?= $_SERVER['PHP_SELF'] ?>">
            <div class="form-group row">
              <label for="user_name" class="col-sm-2 col-form-label-lg">User
                  Name</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" id="user_name"
                    name="user_name" placeholder="Enter a user name"
                    required>
                <div class="invalid-feedback">
                    Please provide a valid user name.
                </div>
              </div>
            </div>
              <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label-lg">Password</label>
                <div class="col-sm-4">
                  <input type="password" class="form-control"
                      id="password" name="password"
                      placeholder="Enter a password" required>
                  <div class="invalid-feedback">
                      Please provide a valid password.
                  </div>
                </div>
              </div>
              <button class="btn btn-primary" type="submit"
                  name="login_submission">Log In
              </button>
          </form>
        <?php
        elseif (isset($_SESSION['user_name'])):
            echo "<h4><p class='text-success'>You are logged in as:
                  <strong>" . htmlspecialchars($_SESSION['user_name']) . "</strong>.</p></h4>";

            echo "<a href='logout.php'>Log Out</a>";
        endif;
        ?>
      </div>
    </div>
  <script>
      // JavaScript for disabling form submissions if there are invalid fields
      (function () {
          'use strict';
          window.addEventListener('load', function () {
              // Fetch all the forms we want to apply custom Bootstrap validation styles to
              var forms = document.getElementsByClassName('needs-validation');
              // Loop over them and prevent submission
              var validation = Array.prototype.filter.call(forms, function (form) {
                  form.addEventListener('submit', function (event) {
                      if (form.checkValidity() === false) {
                          event.preventDefault();
                          event.stopPropagation();
                      }
                      form.classList.add('was-validated');
                  }, false);
              });
          }, false);
      })();
  </script>
<?php require_once 'footer.php';