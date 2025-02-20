<?php
include('authentication.php');
include('config/dbcon.php');

header('Content-Type: application/json');

$user_id = $_GET['user_id'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

$query = "SELECT date, time_in, time_out FROM attendance 
          WHERE sa_id = ? AND date BETWEEN ? AND ?
          ORDER BY date ASC, time_in ASC";

$stmt = $con->prepare($query);
$stmt->bind_param("iss", $user_id, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$attendance = [];

while ($row = $result->fetch_assoc()) {
    $date = $row['date'];
    $timeIn = new DateTime($row['time_in']);
    $timeOut = new DateTime($row['time_out']);

    // Determine if it's AM or PM attendance
    $isPM = $timeIn->format('H') >= 12;

    if (!isset($attendance[$date])) {
        $attendance[$date] = [
            'am' => ['timeIn' => '', 'timeOut' => ''],
            'pm' => ['timeIn' => '', 'timeOut' => '']
        ];
    }

    if ($isPM) {
        $attendance[$date]['pm']['timeIn'] = $timeIn->format('H:i:s');
        $attendance[$date]['pm']['timeOut'] = $timeOut->format('H:i:s');
    } else {
        $attendance[$date]['am']['timeIn'] = $timeIn->format('H:i:s');
        $attendance[$date]['am']['timeOut'] = $timeOut->format('H:i:s');
    }
}

echo json_encode($attendance);
