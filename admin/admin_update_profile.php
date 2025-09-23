<?php
session_start();
include('config/dbcon.php');

// Set content type to JSON
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['auth'])) {
    echo json_encode(['status' => 'error', 'message' => 'You need to login first']);
    exit();
}

$admin_id = $_SESSION['auth_user']['user_id'];

// Check if profile_image column exists in admin table
$check_column_query = "SHOW COLUMNS FROM admin LIKE 'profile_image'";
$column_result = mysqli_query($con, $check_column_query);
$profile_image_column_exists = mysqli_num_rows($column_result) > 0;

// Get form data
$name = mysqli_real_escape_string($con, $_POST['name']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
$current_password = mysqli_real_escape_string($con, $_POST['current_password']);

// Handle profile image upload (only if column exists)
$profile_image = null;
if ($profile_image_column_exists && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "../uploads/profiles/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    $file_extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, PNG, and GIF files are allowed.']);
        exit();
    }
    
    $new_filename = "admin_" . $admin_id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
        $profile_image = $new_filename;
        
        // Delete old profile image if exists
        $old_image_query = "SELECT profile_image FROM admin WHERE id = '$admin_id'";
        $old_image_result = mysqli_query($con, $old_image_query);
        if (mysqli_num_rows($old_image_result) > 0) {
            $old_data = mysqli_fetch_assoc($old_image_result);
            if (!empty($old_data['profile_image']) && file_exists($target_dir . $old_data['profile_image'])) {
                unlink($target_dir . $old_data['profile_image']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
        exit();
    }
}

// Verify current password
$check_query = "SELECT password FROM admin WHERE id = '$admin_id'";
$check_result = mysqli_query($con, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    $admin_data = mysqli_fetch_assoc($check_result);
    
    // Debug: Log password verification details
    error_log("Admin ID: " . $admin_id);
    error_log("Current password provided: " . $current_password);
    error_log("Stored password hash: " . $admin_data['password']);
    error_log("Password verification result: " . (password_verify($current_password, $admin_data['password']) ? 'true' : 'false'));
    
    // Check if password is hashed or plain text
    if (password_get_info($admin_data['password'])['algo'] === null) {
        // Password is not hashed (plain text), compare directly
        if ($current_password !== $admin_data['password']) {
            echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
            exit();
        }
    } else {
        // Password is hashed, use password_verify
        if (!password_verify($current_password, $admin_data['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
            exit();
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Admin not found']);
    exit();
}

// Start transaction
mysqli_begin_transaction($con);

try {
    // Update admin table
    $query = "UPDATE admin SET 
              name = '$name', 
              email = '$email'";

    if ($password) {
        $query .= ", password = '$password'";
    }

    if ($profile_image && $profile_image_column_exists) {
        $query .= ", profile_image = '$profile_image'";
    }

    $query .= " WHERE id = '$admin_id'";

    // Debug: Log the query
    error_log("Update Query: " . $query);

    if (!mysqli_query($con, $query)) {
        throw new Exception('Error updating admin profile: ' . mysqli_error($con));
    }

    // Commit transaction
    mysqli_commit($con);

    // Update session data
    $_SESSION['auth_user']['name'] = $name;
    $_SESSION['auth_user']['email'] = $email;
    if ($profile_image) {
        $_SESSION['auth_user']['profile_image'] = $profile_image;
    }

    echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($con);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

exit();
?>
