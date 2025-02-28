<?php
session_start();
include('admin/config/dbcon.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = mysqli_real_escape_string($con, $_POST['token']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Validate inputs
    if (empty($token) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: student_reset_password.php?token=$token");
        exit();
    }

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: student_reset_password.php?token=$token");
        exit();
    }

    // Verify token and check expiration
    $current_time = date("Y-m-d H:i:s");
    $query = "SELECT * FROM register_sa WHERE reset_token='$token' AND reset_token_expires > '$current_time'";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $student = mysqli_fetch_assoc($query_run);
        $student_id = $student['student_id'];
        
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password and clear reset token
        $update_query = "UPDATE register_sa SET 
                        password='$hashed_password', 
                        reset_token=NULL, 
                        reset_token_expires=NULL 
                        WHERE student_id='$student_id'";
        
        if (mysqli_query($con, $update_query)) {
            $_SESSION['message'] = "Password has been reset successfully. Please login with your new password.";
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['error'] = "Failed to update password. Please try again.";
            header("Location: student_reset_password.php?token=$token");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid or expired reset token. Please request a new reset link.";
        header('Location: student_forgot_password.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    header('Location: student_forgot_password.php');
    exit();
} 