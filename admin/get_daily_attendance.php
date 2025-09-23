<?php
include('config/dbcon.php');

// Fetch daily attendance counts
$query = "SELECT DATE(date) AS attendance_date, 
                 COUNT(DISTINCT sa_id) AS total_attendance
          FROM attendance 
          WHERE time_in IS NOT NULL AND time_out IS NOT NULL
          GROUP BY attendance_date
          ORDER BY attendance_date ASC";

$result = mysqli_query($con, $query);

$dates = [];
$attendance = [];

while ($row = mysqli_fetch_assoc($result)) {
    $dates[] = $row['attendance_date'];
    $attendance[] = (int)$row['total_attendance'];
}

// Return data as JSON
echo json_encode([
    'dates' => $dates,
    'attendance' => $attendance
]);

mysqli_close($con);
?>
