<?php
include('admin/config/dbcon.php');

$student_id = 'test_student_id'; // Use a valid student ID
$email = 'test@example.com'; // Use a valid email
$token = bin2hex(random_bytes(50));
$expires = date("Y-m-d H:i:s", time() + 3600);

$query = "UPDATE register_sa SET 
          reset_token='$token', 
          reset_token_expires='$expires' 
          WHERE student_id='$student_id' AND email='$email'";

if (mysqli_query($con, $query)) {
    echo "Token saved successfully";
} else {
    echo "Error: " . mysqli_error($con);
}
