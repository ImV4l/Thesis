<?php
session_start();
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
                        <h3 class="text-white mb-0 fw-bold"><i class="fas fa-key me-2"></i>FORGOT PASSWORD</h3>
                    </div>
                    <div class="card-body px-lg-5 py-4">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-success">
                                <?= $_SESSION['message']; unset($_SESSION['message']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="send_reset_link.php" method="POST" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label class="form-label text-secondary">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control form-control-lg"
                                        placeholder="Enter your email" required>
                                </div>
                            </div>

                            <button type="submit" name="send_reset_link_btn" class="btn btn-gradient btn-lg w-100 text-white fw-bold">
                                <i class="fas fa-paper-plane me-2"></i>SEND RESET LINK
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
include('includes/footer.php');
?>