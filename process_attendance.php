<?php
date_default_timezone_set('Asia/Manila');
session_start();
include('admin/config/dbcon.php');

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$fingerprintId = $data['fingerprintId'] ?? null;

if (!$fingerprintId) {
    echo json_encode([
        'success' => false,
        'message' => 'No fingerprint ID provided'
    ]);
    exit;
}

try {
    $sa_query = "SELECT id, first_name, last_name FROM student_assistant WHERE fingerprint_id = ?";
    $stmt = mysqli_prepare($con, $sa_query);
    mysqli_stmt_bind_param($stmt, "i", $fingerprintId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($sa = mysqli_fetch_assoc($result)) {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $day = (date('l')); // Get the day name in uppercase (e.g., "TUESDAY")

        $check_query = "SELECT * FROM attendance WHERE sa_id = ? AND date = ? AND time_out IS NULL";
        $check_stmt = mysqli_prepare($con, $check_query);
        mysqli_stmt_bind_param($check_stmt, "is", $sa['id'], $date);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {
            $status = 'Completed';
            $query = "UPDATE attendance SET time_out = ?, status = ?, day = ? WHERE sa_id = ? AND date = ? AND time_out IS NULL";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "sssis", $time, $status, $day, $sa['id'], $date);
            $action = "Time Out";
        } else {
            $status = 'Present';
            $query = "INSERT INTO attendance (sa_id, date, time_in, status, day) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "issss", $sa['id'], $date, $time, $status, $day);
            $action = "Time In";
        }

        if (mysqli_stmt_execute($stmt)) {
            $message = "{$action} recorded for {$sa['first_name']} {$sa['last_name']}";
            echo json_encode([
                'success' => true,
                'message' => $message,
                'data' => [
                    'sa_id' => $sa['id'],
                    'name' => $sa['first_name'] . ' ' . $sa['last_name'],
                    'action' => $action,
                    'time' => $time,
                    'day' => $day
                ]
            ]);
        } else {
            throw new Exception("Failed to record attendance");
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No matching Student Assistant found'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
