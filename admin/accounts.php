<?php
include('authentication.php');
include('includes/header.php');
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
                                                }elseif ($row['role_as'] == '0') {
                                                    echo 'User';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="edit-account.php?id=<?= $row['id']; ?>" class="btn btn-success">Edit</a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $row['id']; ?>" data-name="<?= htmlspecialchars($row['name']); ?>">Delete</button>
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
    });
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>