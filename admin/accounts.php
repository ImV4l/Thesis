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
    <h4 class="mt-4">Registered Users</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Accounts</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <!-- <?php include('message.php'); ?> -->
            <div class="card">
                <div class="card-header">
                    <h4>Registered Users

                    </h4>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>User Name </th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Option</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM admin WHERE status!='2'";
                            $query_run = mysqli_query($con, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $row) {
                            ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['name']; ?></td>
                                        <td><?= $row['username']; ?></td>
                                        <td><?= $row['email']; ?></td>
                                        <td>
                                            <?php
                                            if ($row['role_as'] == '1') {
                                                echo 'Admin';
                                            } elseif ($row['role_as'] == '0') {
                                                echo 'User';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateModal"
                                                data-id="<?= $row['id']; ?>"
                                                data-name="<?= htmlspecialchars($row['name']); ?>"
                                                data-username="<?= htmlspecialchars($row['username']); ?>"
                                                data-email="<?= htmlspecialchars($row['email']); ?>"
                                                data-role="<?= $row['role_as']; ?>">
                                                Update
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-id="<?= $row['id']; ?>"
                                                data-name="<?= htmlspecialchars($row['name']); ?>">
                                                Delete
                                            </button>
                                        </td>



                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="10">No Record Found</td>
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
                <h5 class="modal-title" id="updateModalLabel">Update Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="updateUserId">
                    <div class="mb-3">
                        <label for="updateName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="updateName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="updateUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="updateUsername" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="updateEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="updateEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="updateRole" class="form-label">Role</label>
                        <select class="form-select" id="updateRole" name="role_as" required>
                            <option value="1">Admin</option>
                            <option value="0">User</option>
                        </select>
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
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" name="new_password">
                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePasswordVisibility('newPassword', 'newPasswordToggle')">
                                <i id="newPasswordToggle" class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_account" class="btn btn-primary">Update</button>
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
            let userId = button.getAttribute('data-id');
            let userName = button.getAttribute('data-name');
            let userUsername = button.getAttribute('data-username');
            let userEmail = button.getAttribute('data-email');
            let userRole = button.getAttribute('data-role');

            document.getElementById('updateUserId').value = userId;
            document.getElementById('updateName').value = userName;
            document.getElementById('updateUsername').value = userUsername;
            document.getElementById('updateEmail').value = userEmail;
            document.getElementById('updateRole').value = userRole;
        });
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