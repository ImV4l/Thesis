<?php
session_start();
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
include('admin/config/dbcon.php'); // Include database connection
$config = require 'mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['send_reset_link_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if email exists in register_sa table
    $check_query = "SELECT * FROM register_sa WHERE email='$email'";
    $check_query_run = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_query_run) > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hour expiration

        // Save token to database
        $update_query = "UPDATE register_sa SET 
                        reset_token='$token', 
                        reset_token_expires='$expires' 
                        WHERE email='$email'";
        
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
                $mail->SMTPDebug  = 0; // Set to 0 for production, 2 for debugging

                // Recipients
                $mail->setFrom($config['from_email'], $config['from_name']);
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'WIT Student Assistant - Password Reset Request';
                
                // Get the current domain for the reset link
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'];
                $resetLink = "$protocol://$host/Thesis/reset_password.php?token=$token";
                
                $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <div style='background: linear-gradient(45deg, #F16E04, #FF9F4B); color: white; padding: 20px; text-align: center;'>
                        <h2>WIT Student Assistant System</h2>
                        <h3>Password Reset Request</h3>
                    </div>
                    <div style='padding: 30px; background-color: #f9f9f9;'>
                        <p>Hello,</p>
                        <p>You have requested to reset your password for the WIT Student Assistant System.</p>
                        <p>Please click the button below to reset your password:</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='$resetLink' style='background: linear-gradient(45deg, #F16E04, #FF9F4B); color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>
                                Reset Password
                            </a>
                        </div>
                        <p>Or copy and paste this link in your browser:</p>
                        <p style='word-break: break-all; color: #666;'>$resetLink</p>
                        <p><strong>Important:</strong></p>
                        <ul>
                            <li>This link will expire in 1 hour</li>
                            <li>If you didn't request this password reset, please ignore this email</li>
                            <li>For security reasons, this link can only be used once</li>
                        </ul>
                        <p>Best regards,<br>WIT Student Assistant System</p>
                    </div>
                </div>";
                
                $mail->AltBody = "Password Reset Request\n\nClick this link to reset your password: $resetLink\n\nThis link will expire in 1 hour.";

                $mail->send();
                $_SESSION['message'] = 'Password reset link has been sent to your email. Please check your inbox and spam folder.';
                header('Location: forgot_password.php');
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                header('Location: forgot_password.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Failed to save reset token. Please try again.";
            header('Location: forgot_password.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Email address not found in our records.";
        header('Location: forgot_password.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: forgot_password.php");
    exit(0);
} 