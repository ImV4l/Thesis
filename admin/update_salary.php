<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rate_file = 'salary_rate.txt';
    $new_rate = $_POST['hourlyRate'] ?? null;

    if ($new_rate !== null && is_numeric($new_rate) && $new_rate >= 0) {
        if (file_put_contents($rate_file, $new_rate) !== false) {
            echo json_encode(['success' => true, 'message' => 'Salary rate updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to write to the salary rate file.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid hourly rate provided.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
