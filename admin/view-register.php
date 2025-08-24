<?php

include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Student Assistants</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">View Student Assistants</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <!-- <?php include('message.php'); ?> -->
            <div class="card">
                <div class="card-header" style="background-color: #F16E04; color: white;">
                    <h4>Student Assistants

                        <a href="add1.php" data-toggle="modal" class="btn btn-primary float-end"><i class="fa fa-plus"></i> New</a>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Program</th>
                                    <th>Year</th>
                                    <th>Work In</th>
                                    <th>Option</th>
                                    <th>Status</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM student_assistant WHERE status!='2'";
                                $query_run = mysqli_query($con, $query);

                                if (mysqli_num_rows($query_run) > 0) {
                                    foreach ($query_run as $row) {
                                ?>
                                        <tr>
                                            <td><?= $row['student_id']; ?></td>
                                            <td><?= $row['last_name']; ?></td>
                                            <td><?= $row['first_name']; ?></td>
                                            <td><?= $row['program']; ?></td>
                                            <td><?= $row['year']; ?></td>
                                            <td><?= $row['work']; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="view-info.php?id=<?= $row['id']; ?>" class="btn btn-info">View</a></li>
                                                        <li><a class="dropdown-item" href="edit-register.php?id=<?= $row['id']; ?>" class="btn btn-success">Edit</a></li>
                                                    </ul>
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $row['id']; ?>" data-name="<?= htmlspecialchars($row['last_name'] . ' ' . $row['first_name']); ?>">Delete</button>
                                            </td>
                                            <td>
                                                <select name="status1" class="form-select status-select" data-id="<?= $row['id']; ?>" style="background-color: <?= ($row['status1'] == '0') ? '#d4edda' : '#f8d7da'; ?>">
                                                    <option value="0" <?= ($row['status1'] == '0') ? 'selected' : ''; ?>>Active</option>
                                                    <option value="1" <?= ($row['status1'] == '1') ? 'selected' : ''; ?>>Not Active</option>
                                                </select>
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
                    <input type="hidden" name="delete_user" id="deleteUserId">
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

        // Handle status change
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const userId = this.getAttribute('data-id');
                const newStatus = this.value;

                // Update the background color immediately
                this.style.backgroundColor = (newStatus === '0') ? '#d4edda' : '#f8d7da';

                fetch('update_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: userId,
                            status1: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Failed to update status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the status');
                    });
            });
        });
    });
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
include('includes/sa_modal.php');
?>
