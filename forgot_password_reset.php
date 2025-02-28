<?php
session_start();
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Generate reset token
    $token = bin2hex(random_bytes(50));
    $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hour expiration

    // Save token to database (you'll need to implement this)
    // Example: saveTokenToDatabase($email, $token, $expires);

    // Create PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com'; // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@example.com'; // SMTP username
        $mail->Password   = 'your_password'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('no-reply@example.com', 'Student Assistant System');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        
        $resetLink = "https://yourdomain.com/reset_password.php?token=$token";
        $mail->Body    = "Click this link to reset your password: <a href='$resetLink'>$resetLink</a>";
        $mail->AltBody = "Click this link to reset your password: $resetLink";

        $mail->send();
        $_SESSION['message'] = 'Password reset link has been sent to your email.';
        header('Location: login.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        header('Location: forgot_password.php');
        exit();
    }
}

include('includes/header.php');
include('includes/navbar.php');
?>

<style>
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .auth-header {
        background: linear-gradient(45deg, #F16E04, #FF9F4B);
        border-radius: 20px 20px 0 0;
    }

    .btn-gradient {
        background: linear-gradient(45deg, #F16E04, #FF9F4B);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(241, 110, 4, 0.3);
    }

    .input-icon {
        background-color: #f8f9fa;
        border-right: 0;
    }

    .form-control:focus {
        border-color: #F16E04;
        box-shadow: 0 0 0 0.25rem rgba(241, 110, 4, 0.25);
    }

    .input-group {
        position: relative;
    }
</style>

<div class="py-5" style="background-image: url('assets/witbg.jpg'); background-size: cover; background-position: center; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="card auth-card border-0 overflow-hidden">
                    <div class="auth-header text-center py-4">
                        <h3 class="text-white mb-0 fw-bold"><i class="fas fa-key me-2"></i>RESET PASSWORD</h3>
                    </div>
                    <div class="card-body px-lg-5 py-4">
                        <form action="process_reset_password.php" method="POST" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label class="form-label text-secondary">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="new_password" id="newPassword"
                                        class="form-control form-control-lg"
                                        placeholder="Enter new password"
                                        style="padding-right: 40px;"
                                        required>
                                    <div class="position-absolute" style="right: 0; top: 50%; transform: translateY(-50%); z-index: 10; padding: 0.5rem;">
                                        <i id="newPasswordToggle" class="fas fa-eye text-muted"
                                            style="cursor: pointer;"
                                            onclick="togglePasswordVisibility('newPassword', 'newPasswordToggle')"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-secondary">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="confirm_password" id="confirmPassword"
                                        class="form-control form-control-lg"
                                        placeholder="Confirm new password"
                                        style="padding-right: 40px;"
                                        required>
                                    <div class="position-absolute" style="right: 0; top: 50%; transform: translateY(-50%); z-index: 10; padding: 0.5rem;">
                                        <i id="confirmPasswordToggle" class="fas fa-eye text-muted"
                                            style="cursor: pointer;"
                                            onclick="togglePasswordVisibility('confirmPassword', 'confirmPasswordToggle')"></i>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="reset_password_btn" class="btn btn-gradient btn-lg w-100 text-white fw-bold">
                                <i class="fas fa-sync-alt me-2"></i>RESET PASSWORD
                            </button>

                            <div class="text-center mt-4">
                                <a href="login.php" class="small text-decoration-none text-orange">Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

<?php
include('includes/footer.php');
?>