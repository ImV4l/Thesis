<?php
session_start();
include('config/dbcon.php');

if (isset($_SESSION['registration_in_progress']) && $_SESSION['registration_in_progress'] === true) {
    $allowed_pages = ['add1.php', 'add2.php', 'add3.php', 'add4.php', 'addcode.php'];
    $current_page = basename($_SERVER['PHP_SELF']);
    if (!in_array($current_page, $allowed_pages)) {
        // Destroy registration session data
        unset($_SESSION['registration_in_progress']);
        // Unset other registration-related session variables if any
    }
}

if (!isset($_SESSION['auth'])) {

    header("location: ../login.php");
    exit(0);
} else {
}
?>