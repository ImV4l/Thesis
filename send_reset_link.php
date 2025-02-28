<?php
session_start();
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('admin/config/dbcon.php');

if (isset($_POST['send_reset_link_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if email exists
    $check_query = "SELECT * FROM register_sa WHERE email='$email'";
    $check_query_run = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_query_run) > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hour expiration

        // Update user record with reset token
        $update_query = "UPDATE register_sa 
                        SET reset_token='$token', reset_token_expires='$expires' 
                        WHERE email='$email'";
        $update_query_run = mysqli_query($con, $update_query);

        if ($update_query_run) {
            // Send email with reset link (using PHPMailer or your email function)
            $resetLink = "http://localhost/Thesis/student_reset_password.php?token=$token";
            
            // TODO: Implement your email sending function here
            // Example: sendResetEmail($email, $resetLink);

            $_SESSION['message'] = "Password reset link has been sent to your email.";
            header("Location: login.php");
            exit(0);
        } else {
            $_SESSION['error'] = "Failed to generate reset token.";
            header("Location: forgot_password.php");
            exit(0);
        }
    } else {
        $_SESSION['error'] = "Email address not found.";
        header("Location: forgot_password.php");
        exit(0);
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: forgot_password.php");
    exit(0);
} 