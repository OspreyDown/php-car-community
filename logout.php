<?php
    session_start();

    // If user is logged inm delete session variables and redirect to the home page
    if (isset($_SESSION['user_id']))
    {
        $_SESSION = array();
        session_destroy();
    }

    // Redirect to the home page
    header('Location: index.php');
    exit;
?>