<?php
include('authentication.php');
// Make sure this file includes your $con connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attid = mysqli_real_escape_string($con, $_POST['attid']);
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $time_in = mysqli_real_escape_string($con, $_POST['time_in']);
    $time_out = mysqli_real_escape_string($con, $_POST['time_out']);

    // Calculate work hours if both time_in and time_out are provided
    $num_hours = 0;
    if (!empty($time_in) && !empty($time_out)) {
        $time_in_obj = new DateTime($time_in);
        $time_out_obj = new DateTime($time_out);

        // Calculate the difference
        $interval = $time_in_obj->diff($time_out_obj);

        // Convert to hours
        $hours = $interval->h + ($interval->days * 24);
        $minutes = $interval->i;
        $num_hours = $hours + ($minutes / 60);
    }

    // Update the attendance record with calculated hours
    $query = "UPDATE attendance 
              SET date = ?, 
                  time_in = ?, 
                  time_out = ?, 
                  num_hour = ?,
                  status = CASE 
                            WHEN time_out IS NOT NULL THEN 'Completed'
                            ELSE 'Present'
                          END
              WHERE id = ?";

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "sssdi", $date, $time_in, $time_out, $num_hours, $attid);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Attendance updated successfully',
            'hours' => number_format($num_hours, 2)
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => mysqli_error($con),
            'message' => 'Failed to update attendance'
        ]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method',
        'message' => 'Only POST requests are allowed'
    ]);
}
