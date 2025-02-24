<?php
session_start();
include('config/dbcon.php');

// Get student information from session
$student_id = $_SESSION['auth_user']['student_id'];

// Get date range from request
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Build base query
$query = "SELECT a.* 
          FROM attendance a
          JOIN student_assistant sa ON a.sa_id = sa.id
          WHERE sa.id = (SELECT id FROM student_assistant WHERE student_id = '$student_id')";

// Add date range filter if provided
if ($start_date && $end_date) {
    $query .= " AND a.date BETWEEN '$start_date' AND '$end_date'";
}

$query .= " ORDER BY a.date DESC";

$attendance_run = mysqli_query($con, $query);

if (mysqli_num_rows($attendance_run) > 0) {
    echo '<table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Hours Worked</th>
                    <th>Salary (₱15/hour)</th>
                </tr>
            </thead>
            <tbody>';

    $total_salary = 0;
    while ($attendance = mysqli_fetch_assoc($attendance_run)) {
        $time_in = new DateTime($attendance['time_in']);
        $time_out = new DateTime($attendance['time_out']);
        $interval = $time_in->diff($time_out);
        $hours_worked = $interval->h + ($interval->i / 60);
        $salary = $hours_worked * 15;
        $total_salary += $salary;

        echo '<tr>
                <td>'.date('M d, Y', strtotime($attendance['date'])).'</td>
                <td>'.date('h:i A', strtotime($attendance['time_in'])).'</td>
                <td>'.date('h:i A', strtotime($attendance['time_out'])).'</td>
                <td>'.number_format($hours_worked, 2).' hours</td>
                <td>₱'.number_format($salary, 2).'</td>
              </tr>';
    }

    echo '</tbody>
          <tfoot>
            <tr>
                <th colspan="4" class="text-end">Total Salary</th>
                <th>₱'.number_format($total_salary, 2).'</th>
            </tr>
          </tfoot>
        </table>';
} else {
    echo '<div class="alert alert-info">No attendance records found for the selected date range</div>';
}
?> 