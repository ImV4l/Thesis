<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Verify token (implement your database check here)
    // $isValidToken = verifyToken($token);
    
    // For now, let's assume the token is valid
    $isValidToken = true;

    if ($isValidToken) {
        // Show reset password form
        ?>
        <div class="py-5" style="background-image: url('assets/witbg.jpg'); background-size: cover; background-position: center; min-height: 100vh;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8">
                        <div class="card auth-card border-0 overflow-hidden">
                            <div class="auth-header text-center py-4">
                                <h3 class="text-white mb-0 fw-bold"><i class="fas fa-key me-2"></i>RESET PASSWORD</h3>
                            </div>
                            <div class="card-body px-lg-5 py-4">
                                <form action="process_reset_password.php" method="POST">
                                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                                    
                                    <div class="mb-4">
                                        <label class="form-label text-secondary">New Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text input-icon">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" name="new_password" class="form-control form-control-lg" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-secondary">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text input-icon">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" name="confirm_password" class="form-control form-control-lg" required>
                                        </div>
                                    </div>

                                    <button type="submit" name="reset_password_btn" class="btn btn-gradient btn-lg w-100 text-white fw-bold">
                                        <i class="fas fa-sync-alt me-2"></i>RESET PASSWORD
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        $_SESSION['error'] = "Invalid or expired reset token.";
        header('Location: student_forgot_password.php');
        exit();
    }
} else {
    $_SESSION['error'] = "No reset token provided.";
    header('Location: student_forgot_password.php');
    exit();
}

include('includes/footer.php'); 