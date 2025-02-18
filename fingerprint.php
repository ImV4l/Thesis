<?php
header('Content-Type: application/json');
include('admin/config/dbcon.php');

$data = json_decode(file_get_contents('php://input'), true);

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
