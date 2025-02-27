<?php
session_start();
include('config/dbcon.php');

if (!isset($_SESSION['auth'])) {
    echo json_encode(['status' => 'error', 'message' => 'You need to login first']);
    exit();
}

$student_id = $_SESSION['auth_user']['student_id'];

// Get form data
$first_name = mysqli_real_escape_string($con, $_POST['first_name']);
$last_name = mysqli_real_escape_string($con, $_POST['last_name']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

// Verify old password if new password is provided
if (!empty($_POST['password'])) {
    $old_password = mysqli_real_escape_string($con, $_POST['old_password']);

    // Get current password from database
    $check_query = "SELECT password FROM register_sa WHERE student_id = '$student_id'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $user_data = mysqli_fetch_assoc($check_result);
        if (!password_verify($old_password, $user_data['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Old password is incorrect']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit();
    }
}

// Handle profile picture upload
$profile_picture = null;
if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "../uploads/profiles/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $new_filename = "profile_" . $student_id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
        $profile_picture = $new_filename;
    }
}

// Start transaction
mysqli_begin_transaction($con);

try {
    // Update register_sa table
    $query1 = "UPDATE register_sa SET 
              first_name = '$first_name', 
              last_name = '$last_name', 
              email = '$email'";

    if ($password) {
        $query1 .= ", password = '$password'";
    }

    $query1 .= " WHERE student_id = '$student_id'";

    if (!mysqli_query($con, $query1)) {
        throw new Exception('Error updating register_sa: ' . mysqli_error($con));
    }

    // Update student_assistant table
    $query2 = "UPDATE student_assistant SET 
              first_name = '$first_name', 
              last_name = '$last_name'";

    if ($profile_picture) {
        $query2 .= ", image = '$profile_picture'";
    }

    $query2 .= " WHERE student_id = '$student_id'";

    if (!mysqli_query($con, $query2)) {
        throw new Exception('Error updating student_assistant: ' . mysqli_error($con));
    }

    // Commit transaction
    mysqli_commit($con);

    // Update session data
    $_SESSION['auth_user']['first_name'] = $first_name;
    $_SESSION['auth_user']['last_name'] = $last_name;
    $_SESSION['auth_user']['email'] = $email;
    if ($profile_picture) {
        $_SESSION['auth_user']['profile_picture'] = $profile_picture;
    }

    echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($con);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

exit();
