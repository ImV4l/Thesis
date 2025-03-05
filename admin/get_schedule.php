<?php
include('config/dbcon.php');

if (isset($_GET['student_id'])) {
    $student_id = mysqli_real_escape_string($con, $_GET['student_id']);

    $query = "SELECT * FROM schedules WHERE student_id = '$student_id'";
    $result = mysqli_query($con, $query);

    $schedules = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Remove the date conversion
        $schedules[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($schedules);
    exit();
}
