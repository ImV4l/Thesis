<?php
include('authentication.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Student Assistants</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">Offices</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Offices</h4>
                </div>
                <div class="card-body">
                    <table id="myTable"class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Program</th>
                                <th>Year</th>
                                <th>Work In</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "
                           SELECT sa.*, w.work_name 
                           FROM student_assistant sa
                           JOIN work w 
                           ON sa.work LIKE CONCAT('%', w.work_name, '%')
                           WHERE w.type = 'Office';
                       ";
                            $query_run = mysqli_query($con, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $row) {
                            ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['last_name']; ?></td>
                                        <td><?= $row['first_name']; ?></td>
                                        <td><?= $row['program']; ?></td>
                                        <td><?= $row['year']; ?></td>
                                        <td><?= $row['work']; ?></td>
                                        <td>Active</td>


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


<?php
include('includes/footer.php');
include('includes/scripts.php');
?>