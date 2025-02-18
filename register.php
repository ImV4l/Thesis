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

    .password-requirements {
        display: none;
        background: rgba(248, 249, 250, 0.9);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 0.5rem;
    }

    .list-group-item {
        background: transparent;
        border: none;
        padding: 0.3rem 0;
        font-size: 0.875rem;
    }

    .list-group-item.text-success {
        color: #198754 !important;
    }

    .list-group-item.text-danger {
        color: #dc3545 !important;
    }
</style>

<div class="py-5" style="background-image: url('assets/witbg.jpg'); background-size: cover; background-position: center; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-9">
                <div class="card auth-card border-0 overflow-hidden">
                    <div class="auth-header text-center py-4">
                        <h3 class="text-white mb-0 fw-bold"><i class="fas fa-user-plus me-2"></i>CREATE ACCOUNT</h3>
                    </div>
                    <div class="card-body px-lg-5 py-4">
                        <form action="registercode.php" method="POST" class="needs-validation" onsubmit="return validateRegistrationForm()" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary">Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text input-icon">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" name="name" class="form-control form-control-lg" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text input-icon">
                                            <i class="fas fa-at text-muted"></i>
                                        </span>
                                        <input type="text" name="username" class="form-control form-control-lg" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-secondary">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text input-icon">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                        <input type="email" name="email" class="form-control form-control-lg"
                                            placeholder="example@email.com" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text input-icon">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" name="password" id="password"
                                            class="form-control form-control-lg"
                                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
                                            style="padding-right: 40px;"
                                            required>
                                        <div class="position-absolute" style="right: 0; top: 50%; transform: translateY(-50%); z-index: 10; padding: 0.5rem;">
                                            <i id="passwordToggle" class="fas fa-eye text-muted"
                                                style="cursor: pointer;"
                                                onclick="togglePasswordVisibility('password', 'passwordToggle')"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text input-icon">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" name="cpassword" id="confirm_password"
                                            class="form-control form-control-lg"
                                            style="padding-right: 40px;"
                                            required>
                                        <div class="position-absolute" style="right: 0; top: 50%; transform: translateY(-50%); z-index: 10; padding: 0.5rem;">
                                            <i id="confirmToggle" class="fas fa-eye text-muted"
                                                style="cursor: pointer;"
                                                onclick="togglePasswordVisibility('confirm_password', 'confirmToggle')"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div id="passwordHelpBlock" class="password-requirements">
                                        <p class="mb-2 text-secondary fw-bold">Password Requirements:</p>
                                        <ul class="list-group mb-0" id="passwordRules">
                                            <li class="list-group-item" id="rule-letter">
                                                <i class="fas fa-circle-notch me-2"></i>At least 1 letter
                                            </li>
                                            <li class="list-group-item" id="rule-number">
                                                <i class="fas fa-circle-notch me-2"></i>At least 1 number
                                            </li>
                                            <li class="list-group-item" id="rule-capital">
                                                <i class="fas fa-circle-notch me-2"></i>At least 1 capital letter
                                            </li>
                                            <li class="list-group-item" id="rule-length">
                                                <i class="fas fa-circle-notch me-2"></i>Minimum of 8 characters
                                            </li>
                                            <li class="list-group-item" id="rule-unique">
                                                <i class="fas fa-circle-notch me-2"></i>At least 3 unique characters
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" name="register_btn" class="btn btn-gradient btn-lg w-100 text-white fw-bold">
                                        <i class="fas fa-user-plus me-2"></i>CREATE ACCOUNT
                                    </button>
                                </div>

                                <div class="col-12 text-center">
                                    <span class="text-muted small">Already have an account?</span>
                                    <a href="login.php" class="small text-decoration-none text-orange ms-2">Login Here</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, toggleId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(toggleId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    const passwordField = document.getElementById('password');
    const requirements = document.getElementById('passwordHelpBlock');

    // Show requirements when password field is focused
    passwordField.addEventListener('focus', function() {
        requirements.style.display = 'block';
    });

    // Hide requirements if password field is empty and blurred
    passwordField.addEventListener('blur', function() {
        if (this.value === "") {
            requirements.style.display = 'none';
        }
    });

    // Password validation
    passwordField.addEventListener('input', function() {
        var password = this.value;
        var ruleLetter = /[a-zA-Z]/.test(password);
        var ruleNumber = /\d/.test(password);
        var ruleCapital = /[A-Z]/.test(password);
        var ruleLength = password.length >= 8;
        var ruleUnique = (new Set(password)).size >= 3;

        updateRule('rule-letter', ruleLetter);
        updateRule('rule-number', ruleNumber);
        updateRule('rule-capital', ruleCapital);
        updateRule('rule-length', ruleLength);
        updateRule('rule-unique', ruleUnique);
    });

    function updateRule(ruleId, isValid) {
        const element = document.getElementById(ruleId);
        const icon = element.querySelector('i');

        element.classList.toggle('text-success', isValid);
        element.classList.toggle('text-danger', !isValid);

        icon.classList.remove('fa-circle-notch', 'fa-check-circle', 'fa-times-circle');
        icon.classList.add(isValid ? 'fa-check-circle' : 'fa-times-circle');
    }

    function validateRegistrationForm() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;

        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return false;
        }

        var ruleLetter = /[a-zA-Z]/.test(password);
        var ruleNumber = /\d/.test(password);
        var ruleCapital = /[A-Z]/.test(password);
        var ruleLength = password.length >= 8;
        var ruleUnique = (new Set(password)).size >= 3;

        if (!(ruleLetter && ruleNumber && ruleCapital && ruleLength && ruleUnique)) {
            alert("Please ensure all password requirements are met!");
            return false;
        }

        return true;
    }
</script>

<?php include('includes/footer.php'); ?>