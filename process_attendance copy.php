<?php
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
        $check_query = "SELECT * FROM attendance WHERE sa_id = ? ORDER BY date DESC, time_in DESC LIMIT 1";
        $check_stmt = mysqli_prepare($con, $check_query);
        mysqli_stmt_bind_param($check_stmt, "i", $sa['id']);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        $last_attendance = mysqli_fetch_assoc($check_result);
        
        $current_date = date('Y-m-d');
        
        if (!$last_attendance || $last_attendance['date'] != $current_date) {
            $query = "INSERT INTO attendance (sa_id, date, day, time_in, status) 
                     VALUES (?, CURDATE(), DAYNAME(CURDATE()), NOW(), 'Present')";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "i", $sa['id']);
            $action = "Time In";
        } else if (!$last_attendance['time_out']) {
            $query = "UPDATE attendance SET time_out = NOW() 
                     WHERE id = ? AND date = CURDATE()";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "i", $last_attendance['id']);
            $action = "Time Out";
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Already completed attendance for today'
            ]);
            exit;
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
                    'time' => date('H:i:s')
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
?>
