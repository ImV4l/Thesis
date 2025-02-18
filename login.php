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

    .input-group .position-absolute i:hover {
        color: #F16E04 !important;
    }
</style>

<div class="py-5" style="background-image: url('assets/witbg.jpg'); background-size: cover; background-position: center; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <?php if (isset($_GET['error_msg'])): ?>
                    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        <?= htmlspecialchars($_GET['error_msg']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card auth-card border-0 overflow-hidden">
                    <div class="auth-header text-center py-4">
                        <h3 class="text-white mb-0 fw-bold"><i class="fas fa-user-shield me-2"></i>WIT PORTAL LOGIN</h3>
                    </div>
                    <div class="card-body px-lg-5 py-4">
                        <form action="logincode.php" method="POST" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label class="form-label text-secondary">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" name="username" class="form-control form-control-lg"
                                        placeholder="Enter your username" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-secondary">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="loginPassword"
                                        class="form-control form-control-lg"
                                        placeholder="••••••••"
                                        style="padding-right: 40px;"
                                        required>
                                    <div class="position-absolute" style="right: 0; top: 50%; transform: translateY(-50%); z-index: 10; padding: 0.5rem;">
                                        <i id="passwordToggle" class="fas fa-eye text-muted"
                                            style="cursor: pointer;"
                                            onclick="togglePasswordVisibility()"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <a href="forgot_password.php" class="small text-decoration-none text-orange">Forgot Password?</a>
                            </div>

                            <button type="submit" name="login_btn" class="btn btn-gradient btn-lg w-100 text-white fw-bold">
                                <i class="fas fa-sign-in-alt me-2"></i>LOGIN
                            </button>

                            <div class="text-center mt-4">
                                <span class="text-muted small">Don't have an account?</span>
                                <a href="register.php" class="small text-decoration-none text-orange ms-2">Create Account</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('loginPassword');
        const toggleIcon = document.getElementById('passwordToggle');

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