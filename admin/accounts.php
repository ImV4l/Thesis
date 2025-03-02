<?php

include('authentication.php');
include('includes/header.php');

// if(!isset($_SESSION['auth'])){
//     header("Location: login.php?error_msg=Invalid Access");
// }
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Accounts</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Accounts</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Users Accounts
                    <?php
                    // Query to count registered users in admin table
                    $user_query = "SELECT COUNT(*) as user_total FROM admin WHERE role_as='0'";
                    $user_query_run = mysqli_query($con, $user_query);

                    if ($user_total = mysqli_fetch_assoc($user_query_run)) {
                        echo '<h4 class="mb-0">' . $user_total['user_total'] . ' </h4>';
                    } else {
                        echo '<h4 class="mb-0"> No Data </h4>';
                    }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="account_user.php">View Users Accounts</a>
                    <div class="small text-white"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Student Assistant
                    <?php
                    // Query to count registered students in register_sa table
                    $student_query = "SELECT COUNT(*) as student_total FROM register_sa";
                    $student_query_run = mysqli_query($con, $student_query);

                    if ($student_total = mysqli_fetch_assoc($student_query_run)) {
                        echo '<h4 class="mb-0">' . $student_total['student_total'] . ' </h4>';
                    } else {
                        echo '<h4 class="mb-0"> No Data </h4>';
                    }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="account_student.php">View Student Assistant Accounts</a>
                    <div class="small text-white"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>