<?php
include('authentication.php');
include('config/dbcon.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attid = $_POST['attid'];

    if (!empty($attid)) {
        $query = "DELETE FROM attendance WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $attid);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid attendance ID']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
} 