<?php
// Test email configuration
// Run this file to test if your email setup is working correctly

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
$config = require 'mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_email = $_POST['test_email'] ?? '';
    
    if (empty($test_email)) {
        $error = "Please enter a test email address.";
    } else {
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
            $mail->SMTPDebug  = 2; // Enable verbose debug output for testing

            // Recipients
            $mail->setFrom($config['from_email'], $config['from_name']);
            $mail->addAddress($test_email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'WIT Student Assistant - Email Test';
            $mail->Body    = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <div style='background: linear-gradient(45deg, #F16E04, #FF9F4B); color: white; padding: 20px; text-align: center;'>
                    <h2>WIT Student Assistant System</h2>
                    <h3>Email Configuration Test</h3>
                </div>
                <div style='padding: 30px; background-color: #f9f9f9;'>
                    <p>Hello,</p>
                    <p>This is a test email to verify that your email configuration is working correctly.</p>
                    <p>If you received this email, your SMTP settings are properly configured!</p>
                    <p>Best regards,<br>WIT Student Assistant System</p>
                </div>
            </div>";
            $mail->AltBody = "This is a test email to verify your email configuration is working correctly.";

            $mail->send();
            $success = "Test email sent successfully to $test_email! Check your inbox.";
        } catch (Exception $e) {
            $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Configuration Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(45deg, #F16E04, #FF9F4B); min-height: 100vh;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4><i class="fas fa-envelope me-2"></i>Email Configuration Test</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i><?= $success ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="test_email" class="form-label">Test Email Address</label>
                                <input type="email" class="form-control" id="test_email" name="test_email" 
                                       placeholder="Enter email address to test" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-paper-plane me-2"></i>Send Test Email
                            </button>
                        </form>
                        
                        <hr>
                        <div class="mt-3">
                            <h6>Current Email Configuration:</h6>
                            <ul class="list-unstyled">
                                <li><strong>SMTP Host:</strong> <?= $config['host'] ?></li>
                                <li><strong>SMTP Port:</strong> <?= $config['port'] ?></li>
                                <li><strong>From Email:</strong> <?= $config['from_email'] ?></li>
                                <li><strong>From Name:</strong> <?= $config['from_name'] ?></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <a href="student_forgot_password.php" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-arrow-left me-2"></i>Back to Forgot Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 