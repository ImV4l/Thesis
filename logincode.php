<?php
session_start();
include('admin/config/dbcon.php');

if (isset($_POST['login_btn'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $login_query = "SELECT * FROM admin WHERE username='$username' AND password='$password' LIMIT 1";
    $login_query_run = mysqli_query($con, $login_query);

    if (mysqli_num_rows($login_query_run) > 0) {
        foreach ($login_query_run as $data) {
            $user_id = $data['id'];
            $user_name = $data['name'];;
            $user_uname = $data['username'];
            $role_as = $data['role_as'];
        }
        $_SESSION['auth'] = true;
        $_SESSION['auth_role'] = $role_as;
        $_SESSION['auth_user'] = [
            'user_id' => $user_id,
            'user_name' => $user_name,
            'user_uname' => $user_uname,
        ];

        if ($_SESSION['auth_role'] == '1') {
            $_SESSION['messages'] = "Welcome to Dashboard";
            header("Location: admin/index.php");
            exit(0);
        } elseif ($_SESSION['auth_role'] == '0') {
            $_SESSION['messages'] = "You are logged in as a User";
            header("Location: admin/user-tito.php");
            exit(0);
        }
    } else {
        $_SESSION['message'] = "Invalid Email or Password";
        header('Location: login.php?error_msg=Invalid Email or Password');
        exit(0);
    }
} else {
    header("Location: login.php?error_msg=You are not allowed to access this system");
    exit(0);
}
