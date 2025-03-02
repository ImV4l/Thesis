<?php
include('config/dbcon.php');

// Fetch all daily work hours
$query = "SELECT DATE(a.date) AS work_date, 
                 SUM(TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out) / 60.0) AS total_hours
          FROM attendance a
          GROUP BY work_date
          ORDER BY work_date ASC";

$result = mysqli_query($con, $query);

$dates = [];
$hours = [];

while ($row = mysqli_fetch_assoc($result)) {
    $dates[] = $row['work_date'];
    $hours[] = (float)$row['total_hours'];
}

// Return data as JSON
echo json_encode([
    'dates' => $dates,
    'hours' => $hours
]);

mysqli_close($con);
?> 