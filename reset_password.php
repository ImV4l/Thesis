<?php
session_start();
include('admin/config/dbcon.php');
include('includes/header.php');
include('includes/navbar.php');

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($con, $_GET['token']);
    
    // Verify token from database
    $current_time = date("Y-m-d H:i:s");
    $query = "SELECT * FROM register_sa WHERE reset_token='$token' AND reset_token_expires > '$current_time'";
    $query_run = mysqli_query($con, $query);
    
    if (mysqli_num_rows($query_run) > 0) {
        $user = mysqli_fetch_assoc($query_run);
        // Show reset password form
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
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger">
                                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form action="process_reset_password.php" method="POST">
                                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                                    
                                    <div class="mb-4">
                                        <label class="form-label text-secondary">New Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text input-icon">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" name="new_password" class="form-control form-control-lg" 
                                                   placeholder="Enter new password" required minlength="6">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-secondary">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text input-icon">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" name="confirm_password" class="form-control form-control-lg" 
                                                   placeholder="Confirm new password" required minlength="6">
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
        <?php
    } else {
        $_SESSION['error'] = "Invalid or expired reset token. Please request a new password reset link.";
        header('Location: forgot_password.php');
        exit();
    }
} else {
    $_SESSION['error'] = "No reset token provided.";
    header('Location: forgot_password.php');
    exit();
}

include('includes/footer.php');
?>
