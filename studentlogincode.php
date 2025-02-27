<?php
session_start();
include('admin/config/dbcon.php');

if (isset($_POST['student_login_btn'])) {
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Query to check student credentials
    $login_query = "SELECT rs.*, sa.image 
                    FROM register_sa rs
                    LEFT JOIN student_assistant sa ON rs.student_id = sa.student_id
                    WHERE rs.student_id='$student_id' 
                    LIMIT 1";
    $login_query_run = mysqli_query($con, $login_query);

    if (mysqli_num_rows($login_query_run) > 0) {
        // Student found, verify password
        $student_data = mysqli_fetch_assoc($login_query_run);
        $hashed_password = $student_data['password'];

        if (password_verify($password, $hashed_password)) {
            // Create session
            $_SESSION['auth'] = true;
            $_SESSION['auth_role'] = 'student';
            $_SESSION['auth_user'] = [
                'user_id' => $student_data['id'],
                'student_id' => $student_data['student_id'],
                'first_name' => $student_data['first_name'],
                'last_name' => $student_data['last_name'],
                'email' => $student_data['email'],
                'profile_picture' => $student_data['image']
            ];

            // Redirect to student dashboard
            $_SESSION['message'] = "Welcome to Student Dashboard";
            header("Location: admin/student_dashboard.php");
            exit(0);
        } else {
            // Invalid password
            $_SESSION['message'] = "Invalid Student ID or Password";
            header('Location: login.php?error_msg=Invalid Student ID or Password');
            exit(0);
        }
    } else {
        // Invalid credentials
        $_SESSION['message'] = "Invalid Student ID or Password";
        header('Location: login.php?error_msg=Invalid Student ID or Password');
        exit(0);
    }
} else {
    // Direct access prevention
    header("Location: login.php?error_msg=You are not allowed to access this page");
    exit(0);
}
