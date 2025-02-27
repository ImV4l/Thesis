<?php
session_start();
include('admin/config/dbcon.php');

if (isset($_POST['register_btn'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['cpassword']);
    $role_as = '0'; // Default role as User

    // Validate password match
    if ($password !== $confirm_password) {
        $_SESSION['message'] = "Password and Confirm Password do not match";
        $_SESSION['message_type'] = "error";
        header("Location: register.php");
        exit(0);
    }

    // Check if username already exists
    $check_username = "SELECT username FROM admin WHERE username='$username'";
    $check_username_run = mysqli_query($con, $check_username);

    if (mysqli_num_rows($check_username_run) > 0) {
        $_SESSION['message'] = "Username already exists";
        $_SESSION['message_type'] = "error";
        header("Location: register.php");
        exit(0);
    }

    // Check if email already exists
    $check_email = "SELECT email FROM admin WHERE email='$email'";
    $check_email_run = mysqli_query($con, $check_email);

    if (mysqli_num_rows($check_email_run) > 0) {
        $_SESSION['message'] = "Email already exists";
        $_SESSION['message_type'] = "error";
        header("Location: register.php");
        exit(0);
    }

    // Insert into admin table without hashing the password
    $query = "INSERT INTO admin (name, username, email, password, role_as) 
              VALUES ('$name', '$username', '$email', '$password', '$role_as')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Registered Successfully! Please login.";
        $_SESSION['message_type'] = "success";
        header("Location: login.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Registration failed! Please try again.";
        $_SESSION['message_type'] = "error";
        header("Location: register.php");
        exit(0);
    }
} else {
    $_SESSION['message'] = "Invalid request!";
    $_SESSION['message_type'] = "error";
    header("Location: register.php");
    exit(0);
}
