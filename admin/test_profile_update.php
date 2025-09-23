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

// Get form data
$name = mysqli_real_escape_string($con, $_POST['name']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

// Skip password verification for testing
echo json_encode([
    'status' => 'success', 
    'message' => 'Test successful - Name: ' . $name . ', Email: ' . $email . ', Password provided: ' . (!empty($_POST['password']) ? 'Yes' : 'No')
]);

exit();
?>
