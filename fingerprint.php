<?php
header('Content-Type: application/json');
include('admin/config/dbcon.php');

// Optional simple API key for device-authenticated requests
$DEVICE_API_KEY = 'f4b8d72c5a1e93b0d8762fe431c9a0fdb1c47e8259ab63d4fe20bc19d75e84a2';

$data = json_decode(file_get_contents('php://input'), true);

// 1) ESP32 path: device sends a matched fingerprint ID and we toggle attendance
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    (isset($data['finger_id']) || isset($data['fingerprintId']))
) {
    $providedKey = isset($_SERVER['HTTP_X_API_KEY']) ? $_SERVER['HTTP_X_API_KEY'] : '';
    if ($DEVICE_API_KEY && $providedKey !== $DEVICE_API_KEY) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Unauthorized device'
        ]);
        exit;
    }

    $fingerprintId = isset($data['finger_id']) ? intval($data['finger_id']) : intval($data['fingerprintId']);
    if (!$fingerprintId) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid fingerprint ID'
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
            $check_query = "SELECT * FROM attendance WHERE sa_id = ? AND date = CURDATE() ORDER BY time_in DESC LIMIT 1";
            $check_stmt = mysqli_prepare($con, $check_query);
            mysqli_stmt_bind_param($check_stmt, "i", $sa['id']);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            $last_attendance = mysqli_fetch_assoc($check_result);

            if (!$last_attendance) {
                $query = "INSERT INTO attendance (sa_id, date, day, time_in, status) VALUES (?, CURDATE(), DAYNAME(CURDATE()), NOW(), 'Present')";
                $stmt2 = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt2, "i", $sa['id']);
                $action = 'Time In';
            } else if (empty($last_attendance['time_out'])) {
                $query = "UPDATE attendance SET time_out = NOW(), status = 'Completed' WHERE id = ?";
                $stmt2 = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt2, "i", $last_attendance['id']);
                $action = 'Time Out';
            } else {
                $query = "INSERT INTO attendance (sa_id, date, day, time_in, status) VALUES (?, CURDATE(), DAYNAME(CURDATE()), NOW(), 'Present')";
                $stmt2 = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt2, "i", $sa['id']);
                $action = 'Time In';
            }

            if (mysqli_stmt_execute($stmt2)) {
                echo json_encode([
                    'success' => true,
                    'message' => $action . ' recorded successfully',
                    'data' => [
                        'sa_id' => $sa['id'],
                        'name' => $sa['first_name'] . ' ' . $sa['last_name'],
                        'action' => $action,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s')
                    ]
                ]);
            } else {
                throw new Exception('Failed to record attendance');
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
    exit;
}

// 2) Template path: a client sends a captured template (hex) and we match against DB
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['template'])) {
    $template = $data['template'];

    function matchFingerprint($template, $con) {
        try {
            $templateBinary = hex2bin($template);
            if ($templateBinary === false) {
                error_log("Failed to convert template from hex");
                return null;
            }

            $query = "SELECT id, first_name, last_name, image, fingerprint_image 
                     FROM student_assistant 
                     WHERE fingerprint_image IS NOT NULL AND status != '2'";
            $result = mysqli_query($con, $query);

            $bestMatch = null;
            $highestScore = 0;

            while ($row = mysqli_fetch_assoc($result)) {
                $score = compareTemplates($templateBinary, $row['fingerprint_image']);
                error_log("Comparing with SA id " . $row['id'] . " score: " . ($score * 100) . "%");
                if ($score > $highestScore) {
                    $highestScore = $score;
                    $bestMatch = $row;
                }
            }

            return ($highestScore >= 0.55) ? $bestMatch : null;

        } catch (Exception $e) {
            error_log("Fingerprint matching error: " . $e->getMessage());
            return null;
        }
    }

    function compareTemplates($template1, $template2) {
        $len1 = strlen($template1);
        $len2 = strlen($template2);
        
        if ($len1 === 0 || $len2 === 0) {
            error_log("Empty template detected");
            return 0;
        }

        if ($len1 !== $len2) {
            error_log("Template length mismatch: $len1 vs $len2");
            $minLen = min($len1, $len2);
            $template1 = substr($template1, 0, $minLen);
            $template2 = substr($template2, 0, $minLen);
        }

        $matches = 0;
        $total = strlen($template1);

        for ($i = 0; $i < $total; $i++) {
            if ($template1[$i] === $template2[$i]) {
                $matches++;
            }
        }

        $score = $matches / $total;
        error_log("Template match score: " . ($score * 100) . "%");
        
        return $score;
    }

    $matchedSA = matchFingerprint($template, $con);

    if ($matchedSA) {
        $sa_id = $matchedSA['id'];

        $attendance_query = "SELECT * FROM attendance WHERE sa_id = ? ORDER BY date DESC, time_in DESC LIMIT 1";
        $stmt = mysqli_prepare($con, $attendance_query);
        mysqli_stmt_bind_param($stmt, "i", $sa_id);
        mysqli_stmt_execute($stmt);
        $attendance_result = mysqli_stmt_get_result($stmt);

        if ($attendance_record = mysqli_fetch_assoc($attendance_result)) {
            if (empty($attendance_record['time_out'])) {
                $update_query = "UPDATE attendance SET time_out = NOW() WHERE id = ?";
                $update_stmt = mysqli_prepare($con, $update_query);
                mysqli_stmt_bind_param($update_stmt, "i", $attendance_record['id']);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
                $message = "Time Out recorded successfully for " . $matchedSA['first_name'] . " " . $matchedSA['last_name'];
            } else {
                $insert_query = "INSERT INTO attendance (sa_id, date, day, time_in, status) VALUES (?, CURDATE(), DAYNAME(CURDATE()), NOW(), 'Present')";
                $insert_stmt = mysqli_prepare($con, $insert_query);
                mysqli_stmt_bind_param($insert_stmt, "i", $sa_id);
                mysqli_stmt_execute($insert_stmt);
                mysqli_stmt_close($insert_stmt);
                $message = "Time In recorded successfully for " . $matchedSA['first_name'] . " " . $matchedSA['last_name'];
            }
        } else {
            $insert_query = "INSERT INTO attendance (sa_id, date, day, time_in, status) VALUES (?, CURDATE(), DAYNAME(CURDATE()), NOW(), 'Present')";
            $insert_stmt = mysqli_prepare($con, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "i", $sa_id);
            mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);
            $message = "Time In recorded successfully for " . $matchedSA['first_name'] . " " . $matchedSA['last_name'];
        }

        echo json_encode([
            'success' => true,
            'message' => $message,
            'sa' => [
                'id' => $matchedSA['id'],
                'name' => $matchedSA['first_name'] . ' ' . $matchedSA['last_name'],
                'image' => $matchedSA['image']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No matching Student Assistant found.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);
}
?>
