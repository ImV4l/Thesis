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

if(isset($_POST['update_account'])) {
$user_id = $_POST['id'];
$name = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$role_as = $_POST['role_as'];

$query = "UPDATE admin SET name='$name', username='$username', email='$email', password='$password', role_as='$role_as'
WHERE id = '$user_id'";
$query_run = mysqli_query($con, $query);

if ($query_run) {
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

if(isset($_POST['delete_user_permanent']))
{
    $user_id = mysqli_real_escape_string($con, $_POST['delete_user_permanent']);

    $query = "DELETE FROM admin WHERE id='$user_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "User Permanently Deleted Successfully";
        header("Location: restoreacc.php");
        exit(0);
    }
    else
    {
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

if(isset($_POST['delete_permanent']))
{
    $user_id = mysqli_real_escape_string($con, $_POST['delete_permanent']);

    $query = "DELETE FROM student_assistant WHERE id='$user_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Student Assistant Permanently Deleted Successfully";
        header("Location: restore.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong!";
        header("Location: restore.php");
        exit(0);
    }
}

if (isset($_POST['update_user'])) { 
    $user_id = $_POST['user_id'];
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $sex = mysqli_real_escape_string($con, $_POST['sex']);
    $civil_status = mysqli_real_escape_string($con, $_POST['civil_status']);
    $date_of_birth = mysqli_real_escape_string($con, $_POST['date_of_birth']);
    $city_address = mysqli_real_escape_string($con, $_POST['city_address']);
    $contact_no1 = mysqli_real_escape_string($con, $_POST['contact_no1']);
    $contact_no2 = mysqli_real_escape_string($con, $_POST['contact_no2']);
    $contact_no3 = mysqli_real_escape_string($con, $_POST['contact_no3']);
    $province_address = mysqli_real_escape_string($con, $_POST['province_address']);
    $guardian = mysqli_real_escape_string($con, $_POST['guardian']);
    $honor_award = mysqli_real_escape_string($con, $_POST['honor_award']);
    $past_scholar = mysqli_real_escape_string($con, $_POST['past_scholar']);
    $program = mysqli_real_escape_string($con, $_POST['program']);
    $year = mysqli_real_escape_string($con, $_POST['year']);
    $present_scholar = mysqli_real_escape_string($con, $_POST['present_scholar']);
    $work_experience = mysqli_real_escape_string($con, $_POST['work_experience']);
    $special_talent = mysqli_real_escape_string($con, $_POST['special_talent']);
    
    // References
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
    
    // Family Information
    $fathers_name = mysqli_real_escape_string($con, $_POST['fathers_name']);
    $fathers_occ = mysqli_real_escape_string($con, $_POST['fathers_occ']);
    $fathers_income = mysqli_real_escape_string($con, $_POST['fathers_income']);
    $mothers_name = mysqli_real_escape_string($con, $_POST['mothers_name']);
    $mothers_occ = mysqli_real_escape_string($con, $_POST['mothers_occ']);
    $mothers_income = mysqli_real_escape_string($con, $_POST['mothers_income']);
    $siblings = mysqli_real_escape_string($con, $_POST['siblings']);
    
    // Concatenate work names into a string
    $work_name = isset($_POST['work_name']) ? $_POST['work_name'] : [];
    $work_name_string = implode(", ", $work_name); // Using implode for a cleaner approach

    // Correct the query to use the column name 'work'
    $query = "UPDATE student_assistant SET 
        last_name = '$last_name', 
        first_name = '$first_name', 
        age = '$age', 
        sex = '$sex', 
        civil_status = '$civil_status', 
        date_of_birth = '$date_of_birth', 
        city_address = '$city_address', 
        contact_no1 = '$contact_no1',
        contact_no2 = '$contact_no2',
        contact_no3 = '$contact_no3', 
        province_address = '$province_address', 
        guardian = '$guardian',  
        honor_award = '$honor_award', 
        past_scholar = '$past_scholar', 
        program = '$program', 
        year = '$year', 
        present_scholar = '$present_scholar', 
        work_experience = '$work_experience', 
        special_talent = '$special_talent', 
        work = '$work_name_string',
        out_name1 = '$out_name1',
        comp_add1 = '$comp_add1',
        cn1 = '$cn1',
        out_name2 = '$out_name2',
        comp_add2 = '$comp_add2',
        cn2 = '$cn2',
        out_name3 = '$out_name3',
        comp_add3 = '$comp_add3',
        cn3 = '$cn3',
        from_wit1 = '$from_wit1',
        comp_add4 = '$comp_add4',
        cn4 = '$cn4',
        from_wit2 = '$from_wit2',
        comp_add5 = '$comp_add5',
        cn5 = '$cn5',
        from_wit3 = '$from_wit3',
        comp_add6 = '$comp_add6',
        cn6 = '$cn6',
        fathers_name = '$fathers_name',
        fathers_occ = '$fathers_occ',
        fathers_income = '$fathers_income',
        mothers_name = '$mothers_name',
        mothers_occ = '$mothers_occ',
        mothers_income = '$mothers_income',
        siblings = '$siblings'
        WHERE id = '$user_id'";

    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        header('Location: view-register.php');
        exit(0);
    } else {
        header('Location: view-register.php');
        exit(0);
    }
}

