<?php
session_start();
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
include('admin/config/dbcon.php'); // Include database connection
$config = require 'mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['student_send_reset_link_btn'])) {
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if student exists
    $check_query = "SELECT * FROM register_sa WHERE student_id='$student_id' AND email='$email'";
    $check_query_run = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_query_run) > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hour expiration

        // Save token to database
        $update_query = "UPDATE register_sa SET 
                        reset_token='$token', 
                        reset_token_expires='$expires' 
                        WHERE student_id='$student_id' AND email='$email'";
        
        if (mysqli_query($con, $update_query)) {
            // Create PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = $config['host'];
                $mail->SMTPAuth   = $config['SMTPAuth'];
                $mail->Username   = $config['username'];
                $mail->Password   = $config['password'];
                $mail->SMTPSecure = $config['SMTPSecure'];
                $mail->Port       = $config['port'];
                $mail->SMTPDebug  = 2; // Enable verbose debug output

                // Recipients
                $mail->setFrom($config['from_email'], $config['from_name']);
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                
                $resetLink = "http://localhost/YourProjectFolder/student_reset_password.php?token=$token";
                $mail->Body    = "Click this link to reset your password: <a href='$resetLink'>$resetLink</a>";
                $mail->AltBody = "Click this link to reset your password: $resetLink";

                $mail->send();
                $_SESSION['message'] = 'Password reset link has been sent to your email.';
                header('Location: student_forgot_password.php');
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                header('Location: student_forgot_password.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Failed to save reset token. Please try again.";
            header('Location: student_forgot_password.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Student ID and email combination not found.";
        header('Location: student_forgot_password.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: student_forgot_password.php");
    exit(0);
} 