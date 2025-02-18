<?php
session_start();
include('admin/config/dbcon.php');
header('Content-Type: application/json');

try {
    $query = "SELECT sa.*, a.time_in, a.time_out, a.date 
              FROM student_assistant sa 
              JOIN attendance a ON sa.id = a.sa_id 
              WHERE sa.status != '2' AND a.date = CURDATE()
              ORDER BY COALESCE(a.time_out, a.time_in) DESC
              LIMIT 1";
    
    $result = mysqli_query($con, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $row['id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'image' => $row['image'] ?? 'images/defaultProfile.png',
                'program' => $row['program'],
                'work' => $row['work'],
                'year' => $row['year'],
                'time_in' => $row['time_in'],
                'time_out' => $row['time_out'],
                'date' => $row['date']
                
                
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No recent activity found'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
