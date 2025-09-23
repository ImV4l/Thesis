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

if (isset($_POST['update_personal'])) {
    $user_id = $_POST['user_id'];
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $sex = mysqli_real_escape_string($con, $_POST['sex']);
    $civil_status = mysqli_real_escape_string($con, $_POST['civil_status']);
    $date_of_birth = mysqli_real_escape_string($con, $_POST['date_of_birth']);
    $city_address = mysqli_real_escape_string($con, $_POST['city_address']);
    $contact_no1 = mysqli_real_escape_string($con, $_POST['contact_no1']);
    $province_address = mysqli_real_escape_string($con, $_POST['province_address']);
    $contact_no2 = mysqli_real_escape_string($con, $_POST['contact_no2']);
    $guardian = mysqli_real_escape_string($con, $_POST['guardian']);
    $contact_no3 = mysqli_real_escape_string($con, $_POST['contact_no3']);
    $honor_award = mysqli_real_escape_string($con, $_POST['honor_award']);
    $past_scholar = mysqli_real_escape_string($con, $_POST['past_scholar']);
    $program = mysqli_real_escape_string($con, $_POST['program']);
    $year = mysqli_real_escape_string($con, $_POST['year']);
    $present_scholar = mysqli_real_escape_string($con, $_POST['present_scholar']);
    $work_experience = mysqli_real_escape_string($con, $_POST['work_experience']);
    $special_talent = mysqli_real_escape_string($con, $_POST['special_talent']);

    $query = "UPDATE student_assistant SET student_id='$student_id', last_name='$last_name', first_name='$first_name', age='$age', sex='$sex', civil_status='$civil_status', date_of_birth='$date_of_birth', city_address='$city_address', contact_no1='$contact_no1', province_address='$province_address', contact_no2='$contact_no2', guardian='$guardian', contact_no3='$contact_no3', honor_award='$honor_award', past_scholar='$past_scholar', program='$program', year='$year', present_scholar='$present_scholar', work_experience='$work_experience', special_talent='$special_talent' WHERE id='$user_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Personal Information Updated Successfully";
        header("Location: edit-register.php?id=".$user_id);
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong!";
        header("Location: edit-register.php?id=".$user_id);
        exit(0);
    }
}

if (isset($_POST['update_work'])) {
    $user_id = $_POST['user_id'];
    $work_in = $_POST['work_in']; 
    $work_in_string = implode(',', $work_in); 

    $query = "UPDATE student_assistant SET work = '$work_in_string' WHERE id = '$user_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Work Information Updated Successfully";
        header("Location: edit-register.php?id=".$user_id);
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong!";
        header("Location: edit-register.php?id=".$user_id);
        exit(0);
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

// Update a schedule entry (AJAX-friendly)
if (isset($_POST['update_schedule'])) {
    $schedule_id = mysqli_real_escape_string($con, $_POST['schedule_id']);
    $student_id = isset($_POST['student_id']) ? mysqli_real_escape_string($con, $_POST['student_id']) : null;
    $weekday = mysqli_real_escape_string($con, $_POST['weekday']);
    $time_in = mysqli_real_escape_string($con, $_POST['time_in']);
    $time_out = mysqli_real_escape_string($con, $_POST['time_out']);

    $where_student = $student_id ? " AND student_id = '$student_id'" : "";
    $query = "UPDATE schedules SET weekday='$weekday', time_in='$time_in', time_out='$time_out' WHERE id='$schedule_id'" . $where_student . " LIMIT 1";

    $ok = mysqli_query($con, $query);
    header('Content-Type: application/json');
    if ($ok) {
        echo json_encode([ 'success' => true ]);
    } else {
        http_response_code(500);
        echo json_encode([ 'success' => false, 'error' => mysqli_error($con) ]);
    }
    exit();
}

// Delete a schedule entry (AJAX-friendly)
if (isset($_POST['delete_schedule'])) {
    $schedule_id = mysqli_real_escape_string($con, $_POST['schedule_id']);
    $student_id = isset($_POST['student_id']) ? mysqli_real_escape_string($con, $_POST['student_id']) : null;

    $where_student = $student_id ? " AND student_id = '$student_id'" : "";
    $query = "DELETE FROM schedules WHERE id='$schedule_id'" . $where_student . " LIMIT 1";

    $ok = mysqli_query($con, $query);
    header('Content-Type: application/json');
    if ($ok) {
        echo json_encode([ 'success' => true ]);
    } else {
        http_response_code(500);
        echo json_encode([ 'success' => false, 'error' => mysqli_error($con) ]);
    }
    exit();
}

if (isset($_POST['update_reference'])) {
    $user_id = $_POST['user_id'];
    $out_name1 = mysqli_real_escape_string($con, $_POST['out_name1']);
    $comp_add1 = mysqli_real_escape_string($con, $_POST['comp_add1']);
    $cn1 = mysqli_real_escape_string($con, $_POST['cn1']);
    $out_name2 = mysqli_real_escape_string($con, $_POST['out_name2']);
    $comp_add2 = mysqli_real_escape_string($con, $_POST['comp_add2']);
    $cn2 = mysqli_real_escape_string($con, $_POST['cn2']);
    $out_name3 = mysqli_real_escape_string($con, $_POST['out_name3']);
    $comp_add3 = mysqli_real_escape_string($con, $_POST['comp_add3']);
    $cn3 = mysqli_real_escape_string($con, $_POST['cn3']);
    $from_wit1 = mysqli_real_escape_string($con, $_POST['from_wit1']);
    $comp_add4 = mysqli_real_escape_string($con, $_POST['comp_add4']);
    $cn4 = mysqli_real_escape_string($con, $_POST['cn4']);
    $from_wit2 = mysqli_real_escape_string($con, $_POST['from_wit2']);
    $comp_add5 = mysqli_real_escape_string($con, $_POST['comp_add5']);
    $cn5 = mysqli_real_escape_string($con, $_POST['cn5']);
    $from_wit3 = mysqli_real_escape_string($con, $_POST['from_wit3']);
    $comp_add6 = mysqli_real_escape_string($con, $_POST['comp_add6']);
    $cn6 = mysqli_real_escape_string($con, $_POST['cn6']);

    $query = "UPDATE student_assistant SET out_name1='$out_name1', comp_add1='$comp_add1', cn1='$cn1', out_name2='$out_name2', comp_add2='$comp_add2', cn2='$cn2', out_name3='$out_name3', comp_add3='$comp_add3', cn3='$cn3', from_wit1='$from_wit1', comp_add4='$comp_add4', cn4='$cn4', from_wit2='$from_wit2', comp_add5='$comp_add5', cn5='$cn5', from_wit3='$from_wit3', comp_add6='$comp_add6', cn6='$cn6' WHERE id='$user_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Reference Information Updated Successfully";
        header("Location: edit-register.php?id=".$user_id);
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong!";
        header("Location: edit-register.php?id=".$user_id);
        exit(0);
    }
}

if (isset($_POST['update_family'])) {
    $user_id = $_POST['user_id'];
    $fathers_name = mysqli_real_escape_string($con, $_POST['fathers_name']);
    $fathers_occ = mysqli_real_escape_string($con, $_POST['fathers_occ']);
    $fathers_income = mysqli_real_escape_string($con, $_POST['fathers_income']);
    $mothers_name = mysqli_real_escape_string($con, $_POST['mothers_name']);
    $mothers_occ = mysqli_real_escape_string($con, $_POST['mothers_occ']);
    $mothers_income = mysqli_real_escape_string($con, $_POST['mothers_income']);

    $query = "UPDATE student_assistant SET fathers_name='$fathers_name', fathers_occ='$fathers_occ', fathers_income='$fathers_income', mothers_name='$mothers_name', mothers_occ='$mothers_occ', mothers_income='$mothers_income' WHERE id='$user_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Family Information Updated Successfully";
        header("Location: edit-register.php?id=".$user_id);
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong!";
        header("Location: edit-register.php?id=".$user_id);
        exit(0);
    }
}
