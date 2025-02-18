<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect third-page data
    $_SESSION['family_info'] = $_POST['family_info'];

    // Now, insert all session data into the database
    $last_name = $_SESSION['last_name'];
$first_name = $_SESSION['first_name'];
$age = $_SESSION['age'];
$sex = $_SESSION['sex'];
$civil_status = $_SESSION['civil_status'];
$date_of_birth = $_SESSION['date_of_birth'];

// Retrieving contact information
$city_address = $_SESSION['city_address'];
$contact_no1 = $_SESSION['contact_no1'];
$province_address = $_SESSION['province_address'];
$contact_no2 = $_SESSION['contact_no2'];

// Retrieving guardian information
$guardian = $_SESSION['guardian'];
$contact_no3 = $_SESSION['contact_no3'];

// Retrieving academic information
$honor_award = $_SESSION['honor_award'];
$past_scholar = $_SESSION['past_scholar'];
$program = $_SESSION['program'];
$year = $_SESSION['year'];

// Retrieving work experience and skills
$present_scholar = $_SESSION['present_scholar'];
$work_experience = $_SESSION['work_experience'];
$special_talent = $_SESSION['special_talent'];

    // Database connection and insertion logic here

    // Clear session after data is saved
    session_destroy();

    header('Location: addcode.php');
    exit();
}
?>
