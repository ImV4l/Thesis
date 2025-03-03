<?php
include('authentication.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Bin</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Bin</li>
        <li class="breadcrumb-item active">Accounts Bin</li>
    </ol>
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #F16E04; color: white;">
                    <h4>Deleted Accounts</h4>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM admin WHERE status = 2 ";
                            $query_run = mysqli_query($con, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $row) {
                            ?>
                                    <tr>
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
                                            <button type="button" name="restore_btn1" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#restoreModal" data-id="<?= $row['id']; ?>" data-name="<?= htmlspecialchars($row['name']); ?>">Restore</button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $row['id']; ?>" data-name="<?= htmlspecialchars($row['name']); ?>">Delete Permanently</button>
                                        </td>
                                    </tr>

                                <?php

                                }
                            } else {

                                ?>
                                <tr>
                                    <td colspan="6">No Record Found</td>
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

<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreModalLabel">Confirm Restore</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"><span id="studentName"></span>?
            </div>
            <div class="modal-footer">
                <form action="code.php" method="POST" id="restoreForm">
                    <input type="hidden" name="restore_user1" id="restoreUserId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Restore</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Permanent Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger">Warning: This action cannot be undone!</p>
                <p>Are you sure you want to permanently delete <span id="deleteStudentName"></span>?</p>
            </div>
            <div class="modal-footer">
                <form action="code.php" method="POST" id="deleteForm">
                    <input type="hidden" name="delete_user_permanent" id="deleteUserId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Permanently</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let restoreModal = document.getElementById('restoreModal');
        restoreModal.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget;
            let userId = button.getAttribute('data-id');
            let userName = button.getAttribute('data-name');
            let modalTitle = restoreModal.querySelector('.modal-title');
            let modalBody = restoreModal.querySelector('.modal-body #studentName');
            let restoreForm = document.getElementById('restoreForm');
            let restoreUserId = document.getElementById('restoreUserId');

            modalTitle.textContent = 'Confirm Restore';
            modalBody.textContent = 'Are you sure you want to restore ' + userName + '?';
            restoreUserId.value = userId;
        });

        let deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget;
            let userId = button.getAttribute('data-id');
            let userName = button.getAttribute('data-name');
            let modalBody = deleteModal.querySelector('.modal-body #deleteStudentName');
            let deleteForm = document.getElementById('deleteForm');
            let deleteUserId = document.getElementById('deleteUserId');

            modalBody.textContent = userName;
            deleteUserId.value = userId;
        });
    });
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>