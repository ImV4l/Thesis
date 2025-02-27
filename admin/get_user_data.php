<?php
session_start();
include('config/dbcon.php');

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is authenticated
if (!isset($_SESSION['auth'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    $student_id = $_SESSION['auth_user']['student_id'];

    // Modified query to fetch data from both tables
    $query = "SELECT rs.first_name, rs.last_name, rs.email, sa.image 
              FROM register_sa rs
              LEFT JOIN student_assistant sa ON rs.student_id = sa.student_id
              WHERE rs.student_id = '$student_id'";

    $result = mysqli_query($con, $query);

    if (!$result) {
        throw new Exception('Database query failed: ' . mysqli_error($con));
    }

    if (mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);
        echo json_encode($user_data);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
