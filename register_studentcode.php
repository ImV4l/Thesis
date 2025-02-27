<?php
session_start();
include('admin/config/dbcon.php');

if (isset($_POST['register_btn'])) {
    // Sanitize and validate input data
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

    // Validate password match
    if ($password !== $cpassword) {
        $_SESSION['message'] = "Passwords do not match";
        header("Location: register_student.php");
        exit(0);
    }

    // Check if student ID already exists
    $check_query = "SELECT * FROM register_sa WHERE student_id='$student_id'";
    $check_query_run = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_query_run) > 0) {
        $_SESSION['message'] = "Student ID already exists";
        header("Location: register_student.php");
        exit(0);
    }

    // Check if email already exists
    $check_email_query = "SELECT * FROM register_sa WHERE email='$email'";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        $_SESSION['message'] = "Email already exists";
        header("Location: register_student.php");
        exit(0);
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into database
    $query = "INSERT INTO register_sa (first_name, last_name, student_id, email, password) 
              VALUES ('$first_name', '$last_name', '$student_id', '$email', '$hashed_password')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Registration successful! Please login.";
        header("Location: login.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Registration failed";
        header("Location: register_student.php");
        exit(0);
    }
} else {
    $_SESSION['message'] = "Invalid request";
    header("Location: register_student.php");
    exit(0);
}
