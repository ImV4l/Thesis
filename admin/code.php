<?php
session_start();
include('config/dbcon.php');

if (isset($_POST['restore_user1'])) {
    $user_id = $_POST['restore_user1'];

    $query = "UPDATE admin SET status='0' WHERE id='$user_id' LIMIT 1";

    $query_run = mysqli_query($con, $query);

    if ($query_run) {

        header('Location: restoreacc.php');
        exit(0);
    } else {

        header('Location: restoreacc.php');
        exit(0);
    }
}

if (isset($_POST['restore_user'])) {
    $user_id = $_POST['restore_user'];

    $query = "UPDATE student_assistant SET status='0' WHERE id='$user_id' LIMIT 1";

    $query_run = mysqli_query($con, $query);

    if ($query_run) {

        header('Location: restore.php');
        exit(0);
    } else {

        header('Location: restore.php');
        exit(0);
    }
}

if (isset($_POST['update_account'])) {
    $user_id = $_POST['id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $role_as = mysqli_real_escape_string($con, $_POST['role_as']);
    $old_password = mysqli_real_escape_string($con, $_POST['old_password']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Get current password from database
    $check_query = "SELECT password FROM admin WHERE id='$user_id'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $user_data = mysqli_fetch_assoc($check_result);
        $current_password = $user_data['password'];

        // Initialize password update
        $password_update = "";

        // Check if new password is being updated
        if (!empty($new_password)) {
            // Verify old password
            if ($old_password !== $current_password) {
                $_SESSION['message'] = "Old password is incorrect";
                header('Location: accounts.php');
                exit(0);
            }

            // Check if new passwords match
            if ($new_password !== $confirm_password) {
                $_SESSION['message'] = "New passwords do not match";
                header('Location: accounts.php');
                exit(0);
            }

            // Set new password
            $password_update = ", password='$new_password'";
        }

        // Update query
        $query = "UPDATE admin SET 
                  name='$name', 
                  username='$username', 
                  email='$email', 
                  role_as='$role_as'
                  $password_update
                  WHERE id='$user_id'";

        $query_run = mysqli_query($con, $query);

        if ($query_run) {
            $_SESSION['message'] = "Account updated successfully";
            $_SESSION['message_type'] = "success";
            header('Location: accounts.php');
            exit(0);
        } else {
            $_SESSION['message'] = "Failed to update account: " . mysqli_error($con);
            $_SESSION['message_type'] = "danger";
            header('Location: accounts.php');
            exit(0);
        }
    } else {
        $_SESSION['message'] = "User not found";
        header('Location: accounts.php');
        exit(0);
    }
}

if (isset($_POST['delete_user1'])) {
    $user_id = $_POST['delete_user1'];

    $query = "UPDATE admin SET status='2' WHERE id='$user_id' LIMIT 1";

    $query_run = mysqli_query($con, $query);

    if ($query_run) {

        $_SESSION['message'] = "Deleted Successfully!";
        header('Location: accounts.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Delete Failed!";
        header('Location: accounts.php');
        exit(0);
    }
}

if (isset($_POST['delete_user_permanent'])) {
    $user_id = mysqli_real_escape_string($con, $_POST['delete_user_permanent']);

    $query = "DELETE FROM admin WHERE id='$user_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "User Permanently Deleted Successfully";
        header("Location: restoreacc.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong!";
        header("Location: restoreacc.php");
        exit(0);
    }
}

if (isset($_POST['delete_user'])) {
    $user_id = $_POST['delete_user'];

    $query = "UPDATE student_assistant SET status='2' WHERE id='$user_id' LIMIT 1";

    $query_run = mysqli_query($con, $query);

    if ($query_run) {

        $_SESSION['message'] = "Deleted Successfully!";
        header('Location: view-register.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Delete Failed!";
        header('Location: view-register.php');
        exit(0);
    }
}

if (isset($_POST['delete_permanent'])) {
    $user_id = mysqli_real_escape_string($con, $_POST['delete_permanent']);

    $query = "DELETE FROM student_assistant WHERE id='$user_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Student Assistant Permanently Deleted Successfully";
        header("Location: restore.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong!";
        header("Location: restore.php");
        exit(0);
    }
}

if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $work_in = $_POST['work_in']; // This will be an array of selected values
    $work_in_string = implode(',', $work_in); // Convert array to string

    // Update the database
    $query = "UPDATE student_assistant SET work = '$work_in_string' WHERE id = '$user_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Student Assistant updated successfully";
        header('Location: view-register.php');
        exit();
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: edit-register.php?id=' . $user_id);
        exit();
    }
}

if (isset($_POST['add_schedule'])) {
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
    $weekday = mysqli_real_escape_string($con, $_POST['weekday']);
    $time_in = mysqli_real_escape_string($con, $_POST['time_in']);
    $time_out = mysqli_real_escape_string($con, $_POST['time_out']);

    $query = "INSERT INTO schedules (student_id, weekday, time_in, time_out) 
              VALUES ('$student_id', '$weekday', '$time_in', '$time_out')";

    if (mysqli_query($con, $query)) {
        $_SESSION['status'] = "Schedule added successfully!";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status'] = "Error adding schedule: " . mysqli_error($con);
        $_SESSION['status_type'] = "danger";
    }

    header("Location: student_schedule.php");
    exit();
}
