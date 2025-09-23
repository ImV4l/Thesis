<?php

include('authentication.php');
include('includes/header.php');

// Get selected date from the request or default to today
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Parse the selected date
$date_parts = explode('-', $selected_date);
$selected_year = $date_parts[0];
$selected_month = $date_parts[1];
$selected_day = $date_parts[2];

// if(!isset($_SESSION['auth'])){
//     header("Location: login.php?error_msg=Invalid Access");
// }
?>


<div class="container-fluid px-4"><div class="container-fluid px-4">
    <h4></h4>
    <ol class="breadcrumb mb-4">
        
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #F16E04; color: white;">
                    <h4>Student </h4>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Work In</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT student_id, last_name, first_name, work FROM student_assistant WHERE status = '0'";
                            $query_run = mysqli_query($con, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $row) {
                            ?>
                                    <tr>
                                        <td><?= $row['student_id']; ?></td>
                                        <td><?= $row['last_name']; ?></td>
                                        <td><?= $row['first_name']; ?></td>
                                        <td><?= $row['work']; ?></td>
                                        <td>
                                            <a href="student_dtr.php?student_id=<?= $row['student_id']; ?>" class="btn btn-info btn-sm">
                                                View DTR
                                            </a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="5">No Record Found</td>
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
