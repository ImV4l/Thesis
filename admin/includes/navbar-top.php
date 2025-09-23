<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<nav style="background-color: #F16E04;" class="sb-topnav navbar navbar-expand navbar-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.php">WIT Student Assistant</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <!-- <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div> -->
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i> Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                        Profile
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="../allcode.php" method="POST">
                        <button type="submit" name="logout_btn" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Admin Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                // Get admin profile information
                $admin_id = $_SESSION['auth_user']['user_id'];
                $admin_query = "SELECT * FROM admin WHERE id = '$admin_id'";
                $admin_result = mysqli_query($con, $admin_query);
                $admin_data = mysqli_fetch_assoc($admin_result);
                ?>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <?php if (!empty($admin_data['profile_image']) && file_exists("../uploads/profiles/" . $admin_data['profile_image'])): ?>
                                <img src="../uploads/profiles/<?php echo htmlspecialchars($admin_data['profile_image']); ?>" 
                                     alt="Profile Image" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                            <?php else: ?>
                                <i class="fas fa-user-circle fa-5x text-muted"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name:</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($admin_data['name'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username:</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($admin_data['username'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email:</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($admin_data['email'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role:</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary">Administrator</span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Login:</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($admin_data['last_login'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Edit Form Section (Hidden by default) -->
                <div id="editFormSection" style="display: none;">
                    <hr>
                    <h6 class="mb-3">Edit Profile Information</h6>
                    <form id="adminUpdateForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="profileImage" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="profileImage" name="profile_image" accept="image/*" onchange="previewImage(this)">
                            <div class="form-text">Upload a new profile image (JPG, PNG, GIF - Max 2MB)</div>
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="previewImg" src="" alt="Preview" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" value="<?php echo htmlspecialchars($admin_data['name'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($admin_data['email'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">New Password (leave blank to keep current)</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="editPassword" name="password">
                                <span class="input-group-text" style="cursor: pointer;" onclick="togglePasswordVisibility('editPassword', 'editPasswordToggle')">
                                    <i id="editPasswordToggle" class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password">
                                <span class="input-group-text" style="cursor: pointer;" onclick="togglePasswordVisibility('confirmPassword', 'confirmPasswordToggle')">
                                    <i id="confirmPasswordToggle" class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password (required for changes)</label>
                            <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editToggleBtn" onclick="toggleEditMode()">Edit Profile</button>
                <button type="button" class="btn btn-success" id="saveBtn" onclick="saveProfile()" style="display: none;">Save Changes</button>
                <button type="button" class="btn btn-warning" id="cancelBtn" onclick="cancelEdit()" style="display: none;">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password match validation
        const password = document.getElementById('editPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        function validatePassword() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity("Passwords don't match");
            } else {
                confirmPassword.setCustomValidity('');
            }
        }

        if (password && confirmPassword) {
            password.onchange = validatePassword;
            confirmPassword.onkeyup = validatePassword;
        }
    });

    // Toggle password visibility
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

    // Preview uploaded image
    function previewImage(input) {
        const file = input.files[0];
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        if (file) {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                input.value = '';
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }

    // Toggle edit mode
    function toggleEditMode() {
        const editSection = document.getElementById('editFormSection');
        const editBtn = document.getElementById('editToggleBtn');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');

        editSection.style.display = 'block';
        editBtn.style.display = 'none';
        saveBtn.style.display = 'inline-block';
        cancelBtn.style.display = 'inline-block';
    }

    // Cancel edit mode
    function cancelEdit() {
        const editSection = document.getElementById('editFormSection');
        const editBtn = document.getElementById('editToggleBtn');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');

        editSection.style.display = 'none';
        editBtn.style.display = 'inline-block';
        saveBtn.style.display = 'none';
        cancelBtn.style.display = 'none';

        // Reset form
        document.getElementById('adminUpdateForm').reset();
        
        // Hide image preview
        document.getElementById('imagePreview').style.display = 'none';
    }

    // Save profile
    function saveProfile() {
        const form = document.getElementById('adminUpdateForm');
        const formData = new FormData(form);

        // Validate password match
        const password = document.getElementById('editPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (password && password !== confirmPassword) {
            alert('Passwords do not match!');
            return;
        }

        // Show loading state
        const saveBtn = document.getElementById('saveBtn');
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveBtn.disabled = true;

        fetch('test_profile_update.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Profile updated successfully!');
                location.reload(); // Reload to show updated data
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the profile.');
        })
        .finally(() => {
            // Reset button state
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    }
</script>