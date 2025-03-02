<?php
include('authentication.php');
include('includes/header.php');

// Display success/error message if set
if (isset($_SESSION['message'])) {
    $message_type = $_SESSION['message_type'] ?? 'info';
?>
    <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
    // Clear the message after displaying it
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Accounts</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="accounts.php">Back to Accounts</a></li>
        <li class="breadcrumb-item active">Student Assistant Accounts</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <!-- <?php include('message.php'); ?> -->
            <div class="card">
                <div class="card-header">
                    <h4>Registered Student Assistants</h4>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Email</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Modified query to fetch from register_sa table
                            $query = "SELECT student_id, last_name, first_name, email FROM register_sa";
                            $query_run = mysqli_query($con, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $row) {
                            ?>
                                    <tr>
                                        <td><?= $row['student_id']; ?></td>
                                        <td><?= $row['last_name']; ?></td>
                                        <td><?= $row['first_name']; ?></td>
                                        <td><?= $row['email']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal"
                                                data-id="<?= $row['student_id']; ?>"
                                                data-lastname="<?= htmlspecialchars($row['last_name']); ?>"
                                                data-firstname="<?= htmlspecialchars($row['first_name']); ?>"
                                                data-email="<?= htmlspecialchars($row['email']); ?>">
                                                Update
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-id="<?= $row['student_id']; ?>"
                                                data-name="<?= htmlspecialchars($row['last_name'] . ', ' . $row['first_name']); ?>">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="5">No Student Assistants Found</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="studentName"></span>?
            </div>
            <div class="modal-footer">
                <form action="code.php" method="POST" id="deleteForm">
                    <input type="hidden" name="delete_user1" id="deleteUserId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Student Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="account_student_update.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="student_id" id="updateStudentId">

                    <div class="mb-3">
                        <label for="updateEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="updateEmail" name="email">
                    </div>

                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" name="new_password">
                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePasswordVisibility('newPassword', 'newPasswordToggle')">
                                <i id="newPasswordToggle" class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password">
                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePasswordVisibility('confirmPassword', 'confirmPasswordToggle')">
                                <i id="confirmPasswordToggle" class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_student" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget;
            let userId = button.getAttribute('data-id');
            let userName = button.getAttribute('data-name');
            let modalTitle = deleteModal.querySelector('.modal-title');
            let modalBody = deleteModal.querySelector('.modal-body #studentName');
            let deleteForm = document.getElementById('deleteForm');
            let deleteUserId = document.getElementById('deleteUserId');

            modalTitle.textContent = 'Confirm Deletion';
            modalBody.textContent = 'Are you sure you want to delete ' + userName + '?';
            deleteUserId.value = userId;
        });

        let updateModal = document.getElementById('updateModal');
        updateModal.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget;
            let studentId = button.getAttribute('data-id');
            let studentEmail = button.getAttribute('data-email');

            document.getElementById('updateStudentId').value = studentId;
            document.getElementById('updateEmail').value = studentEmail;
        });

        // Password validation only if both fields are filled
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        function validatePassword() {
            if (newPassword.value || confirmPassword.value) {
                if (newPassword.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity("Passwords don't match");
                } else {
                    confirmPassword.setCustomValidity('');
                }
            } else {
                confirmPassword.setCustomValidity('');
            }
        }

        newPassword.onchange = validatePassword;
        confirmPassword.onkeyup = validatePassword;
    });

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
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>