<nav style="background-color: #F16E04;" class="sb-topnav navbar navbar-expand navbar-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.html">WIT Student Assistants</a>
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
                <i class="fas fa-user fa-fw"></i> User
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                    <form action="../allcode.php" method="POST" style="margin: 0;">
                        <button type="button" name="update_btn" class="dropdown-item">Update</button>
                        <hr class="dropdown-divider">
                        <button type="submit" name="logout_btn" class="dropdown-item">Logout</button>


                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="alertContainer"></div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="oldPassword" class="form-label">Old Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="oldPassword" name="old_password" required>
                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePasswordVisibility('oldPassword', 'oldPasswordToggle')">
                                <i id="oldPasswordToggle" class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password">
                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePasswordVisibility('password', 'passwordToggle')">
                                <i id="passwordToggle" class="fas fa-eye"></i>
                            </span>
                        </div>
                        <small class="form-text text-muted">Leave blank to keep current password</small>
                    </div>
                    <div class="mb-3">
                        <label for="profilePicture" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="profilePicture" name="profile_picture" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_profile" class="btn btn-primary">Save changes</button>
                </div>
            </form>
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

    document.addEventListener('DOMContentLoaded', function() {
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl)
        });

        // Add click event to Update button
        document.querySelector('button[name="update_btn"]').addEventListener('click', function(e) {
            e.preventDefault();

            // Fetch current user data
            fetch('get_user_data.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    // Populate modal fields
                    document.getElementById('firstName').value = data.first_name;
                    document.getElementById('lastName').value = data.last_name;
                    document.getElementById('email').value = data.email;

                    // Show modal
                    var updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
                    updateModal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error fetching user data: ' + error.message);
                });
        });

        // Add form submission handler
        document.querySelector('#updateModal form').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            // Clear any existing alerts
            const existingAlerts = form.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());

            fetch('update_profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Show success message
                        const successAlert = document.createElement('div');
                        successAlert.className = 'alert alert-success mt-3';
                        successAlert.textContent = data.message;
                        form.insertBefore(successAlert, form.firstChild);

                        // Optionally close the modal after a delay
                        setTimeout(() => {
                            bootstrap.Modal.getInstance(document.getElementById('updateModal')).hide();
                            location.reload(); // Refresh to show updated data
                        }, 1500);
                    } else {
                        // Show error message in modal
                        const errorAlert = document.createElement('div');
                        errorAlert.className = 'alert alert-danger mt-3';
                        errorAlert.textContent = data.message;
                        form.insertBefore(errorAlert, form.firstChild);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating your profile');
                });
        });
    });
</script>

<!-- Fetch profile picture from session -->
<?php
$profile_picture = $_SESSION['auth_user']['profile_picture'] ?? 'assets/default-profile.jpg';
