<?php
include('authentication.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Accounts</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Accounts</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <?php include('message.php'); ?>
            <div class="card">
                <div class="card-header">
                    <h4>Registered Account
                        <a href="accounts.php" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">

                    <?php
                    if (isset($_GET['id'])) {
                        $user_id = $_GET['id'];
                        $users = "SELECT * FROM admin WHERE id='$user_id'";
                        $users_run = mysqli_query($con, $users);

                        if (mysqli_num_rows($users_run) > 0) {
                            foreach ($users_run as $user) {
                            }
                    ?>



                            <form action="code.php" method="POST">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Name</label>
                                        <input type="text" name="name" value="<?= $user['name'] ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Username</label>
                                        <input type="text" name="username" value="<?= $user['username'] ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Email</label>
                                        <input type="text" name="email" value="<?= $user['email'] ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Role</label>
                                        <select name="role_as" required class="form-control">
                                            <option value="">--Select Role--</option>
                                            <option value="1" <?= $user['role_as'] == '1' ? 'selected' : '' ?>>Admin</option>
                                            <option value="0" <?= $user['role_as'] == '0' ? 'selected' : '' ?>>User</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" name="update_account" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal" data-id="<?= $row['id']; ?>" data-name="<?= htmlspecialchars($row['last_name']); ?>" >Update</button>
                                    </div>
                                </div>
                            </form>
                        <?php

                        } else {
                        ?>
                            <h4>No Record Found</h4>
                    <?php
                        }
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Confirm Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="studentName"></span>?
            </div>
            <div class="modal-footer">
                <form action="code.php" method="POST" id="updateForm">
                    <input type="hidden" name="update_account" id="updateUserId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let updateModal = document.getElementById('updateModal');
    updateModal.addEventListener('show.bs.modal', function(event) {
        let button = event.relatedTarget;
        let userId = button.getAttribute('data-id');
        let userName = button.getAttribute('data-name');
        let modalTitle = deleteModal.querySelector('.modal-title');
        let modalBody = deleteModal.querySelector('.modal-body #studentName');
        let updateForm = document.getElementById('updateForm');
        let updateUserId = document.getElementById('updateUserId');
        
        modalTitle.textContent = 'Confirm Update';
        modalBody.textContent = 'Are you sure you want to update ' + userName + '?';
        updateUserId.value = userId;
    });
});
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>