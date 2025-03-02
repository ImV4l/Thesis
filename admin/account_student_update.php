<?php
session_start();
include('config/dbcon.php');

if (isset($_POST['update_student'])) {
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Validate password match
    if (!empty($new_password) && $new_password !== $confirm_password) {
        $_SESSION['message'] = "Passwords do not match";
        header("Location: account_student.php");
        exit(0);
    }

    try {
        // Start transaction
        mysqli_begin_transaction($con);

        // Update email
        $update_query = "UPDATE register_sa SET email='$email'";

        // Update password if provided
        if (!empty($new_password)) {
            $update_query .= ", password='$new_password'";
        }

        $update_query .= " WHERE student_id='$student_id'";

        $update_query_run = mysqli_query($con, $update_query);

        if ($update_query_run) {
            mysqli_commit($con);
            $_SESSION['message'] = "Student account updated successfully";
        } else {
            throw new Exception("Failed to update student account");
        }
    } catch (Exception $e) {
        mysqli_rollback($con);
        $_SESSION['message'] = "Error: " . $e->getMessage();
    }

    header("Location: account_student.php");
    exit(0);
} else {
    $_SESSION['message'] = "Invalid request";
    header("Location: account_student.php");
    exit(0);
}
