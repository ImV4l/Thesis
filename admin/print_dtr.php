<?php
if(isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="attendance_report.xls"');

    include('db_connection.php');

    $query = "SELECT s.id, s.first_name, s.last_name, s.work, a.date, a.time_in, a.time_out
              FROM student_assistant s 
              LEFT JOIN attendance a ON a.sa_id = s.id 
              WHERE a.date BETWEEN '$start_date' AND '$end_date'";

    $result = mysqli_query($con, $query);

    echo "ID\tName\tWork\tDate\tTime In\tTime Out\n";
    while($row = mysqli_fetch_assoc($result)) {
        echo "{$row['id']}\t{$row['first_name']} {$row['last_name']}\t{$row['work']}\t{$row['date']}\t{$row['time_in']}\t{$row['time_out']}\n";
    }
} else {
    echo "Invalid date range.";
}
?>
