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

    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        color: #F16E04;
        border-bottom: 2px solid #F16E04;
        background-color: transparent;
    }

    .nav-tabs .nav-link:hover {
        color: #F16E04;
    }

    .tab-content {
        margin-top: 1.5rem;
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

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs justify-content-center" id="loginTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button" role="tab" aria-controls="admin" aria-selected="true">
                            Admin Login
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="student-tab" data-bs-toggle="tab" data-bs-target="#student" type="button" role="tab" aria-controls="student" aria-selected="false">
                            Student Login
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content" id="loginTabsContent">
                    <!-- Admin Login Tab -->
                    <div class="tab-pane fade show active" id="admin" role="tabpanel" aria-labelledby="admin-tab">
                        <div class="card auth-card border-0 overflow-hidden">
                            <div class="auth-header text-center py-4">
                                <h3 class="text-white mb-0 fw-bold"><i class="fas fa-user-shield me-2"></i>ADMIN LOGIN</h3>
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
                                            <span class="input-group-text input-icon">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" name="password" id="adminPassword"
                                                class="form-control form-control-lg"
                                                placeholder="••••••••"
                                                style="padding-right: 40px;"
                                                required>
                                            <div class="position-absolute" style="right: 0; top: 50%; transform: translateY(-50%); z-index: 10; padding: 0.5rem;">
                                                <i id="adminPasswordToggle" class="fas fa-eye text-muted"
                                                    style="cursor: pointer;"
                                                    onclick="togglePasswordVisibility('adminPassword', 'adminPasswordToggle')"></i>
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
                                        <a href="register.php" class="small text-decoration-none text-orange ms-2">Register Here</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Student Login Tab -->
                    <div class="tab-pane fade" id="student" role="tabpanel" aria-labelledby="student-tab">
                        <div class="card auth-card border-0 overflow-hidden">
                            <div class="auth-header text-center py-4">
                                <h3 class="text-white mb-0 fw-bold"><i class="fas fa-user-graduate me-2"></i>STUDENT LOGIN</h3>
                            </div>
                            <div class="card-body px-lg-5 py-4">
                                <form action="studentlogincode.php" method="POST" class="needs-validation" novalidate>
                                    <div class="mb-4">
                                        <label class="form-label text-secondary">Student ID</label>
                                        <div class="input-group">
                                            <span class="input-group-text input-icon">
                                                <i class="fas fa-id-card text-muted"></i>
                                            </span>
                                            <input type="text" name="student_id" class="form-control form-control-lg"
                                                placeholder="Enter your student ID" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-secondary">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text input-icon">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" name="password" id="studentPassword"
                                                class="form-control form-control-lg"
                                                placeholder="••••••••"
                                                style="padding-right: 40px;"
                                                required>
                                            <div class="position-absolute" style="right: 0; top: 50%; transform: translateY(-50%); z-index: 10; padding: 0.5rem;">
                                                <i id="studentPasswordToggle" class="fas fa-eye text-muted"
                                                    style="cursor: pointer;"
                                                    onclick="togglePasswordVisibility('studentPassword', 'studentPasswordToggle')"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <a href="student_forgot_password.php" class="small text-decoration-none text-orange">Forgot Password?</a>
                                    </div>

                                    <button type="submit" name="student_login_btn" class="btn btn-gradient btn-lg w-100 text-white fw-bold">
                                        <i class="fas fa-sign-in-alt me-2"></i>LOGIN
                                    </button>

                                    <div class="text-center mt-4">
                                        <span class="text-muted small">Don't have an account?</span>
                                        <a href="register_student.php" class="small text-decoration-none text-orange ms-2">Register Here</a>
                                    </div>
                                </form>
                            </div>
                        </div>
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

    document.addEventListener('DOMContentLoaded', function() {
        var tabTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tab"]'));
        tabTriggerList.map(function(tabTriggerEl) {
            return new bootstrap.Tab(tabTriggerEl);
        });
    });
</script>

<?php
include('includes/footer.php');
?>