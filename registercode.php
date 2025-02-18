<?php
session_start();
include('admin/config/dbcon.php');

if (isset($_POST['register_btn'])) {

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['cpassword']);

    if ($password == $confirm_password) {
        // Check Username 
        $checkusername = "SELECT username FROM admin WHERE username='$username'";
        $checkusername_run = mysqli_query($con, $checkusername);

        if (mysqli_num_rows($checkusername_run) > 0) {
            // Already Username Exist 
            $_SESSION['message'] = "Username already exists";
            exit(0);
        } else {
            $user_query = "INSERT INTO admin (name,username,password) VALUES ('$name','$username','$password')";
            $user_query_run = mysqli_query($con, $user_query);

            if ($user_query_run) {
                $_SESSION['message'] = "Regesterd Successfully";
                header("Location: login.php");
                exit(0);
            } else {
                $_SESSION['message'] = "Something went Wrong!";
                header("Location: register.php");
                exit(0);
            }
        }
    } else {
        $_SESSION['message'] = "Password and Confirm Password does not Match";
        header("Location: register.php");
        exit(0);
    }
} else {
    header("Location: register.php");
    exit(0);
}
