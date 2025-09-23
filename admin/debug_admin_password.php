<?php
session_start();
include('config/dbcon.php');

// Check if user is logged in
if (!isset($_SESSION['auth'])) {
    echo "Please login first to debug password.";
    exit();
}

$admin_id = $_SESSION['auth_user']['user_id'];

echo "<h3>Admin Password Debug Information</h3>";
echo "<p><strong>Admin ID:</strong> " . $admin_id . "</p>";

// Get admin data
$query = "SELECT id, username, password FROM admin WHERE id = '$admin_id'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    $admin_data = mysqli_fetch_assoc($result);
    
    echo "<p><strong>Username:</strong> " . htmlspecialchars($admin_data['username']) . "</p>";
    echo "<p><strong>Password Hash:</strong> " . htmlspecialchars($admin_data['password']) . "</p>";
    
    // Check if password is hashed
    $password_info = password_get_info($admin_data['password']);
    echo "<p><strong>Password Algorithm:</strong> " . ($password_info['algo'] ? $password_info['algo'] : 'Not hashed (plain text)') . "</p>";
    
    if ($password_info['algo'] === null) {
        echo "<p style='color: orange;'><strong>Warning:</strong> Password is stored as plain text!</p>";
    } else {
        echo "<p style='color: green;'><strong>Good:</strong> Password is properly hashed.</p>";
    }
    
    // Test password verification
    echo "<h4>Test Password Verification</h4>";
    echo "<form method='POST'>";
    echo "<input type='password' name='test_password' placeholder='Enter current password to test' required>";
    echo "<button type='submit' name='test_verify'>Test Verification</button>";
    echo "</form>";
    
    if (isset($_POST['test_verify'])) {
        $test_password = $_POST['test_password'];
        echo "<p><strong>Testing password:</strong> " . htmlspecialchars($test_password) . "</p>";
        
        if ($password_info['algo'] === null) {
            // Plain text comparison
            $result = ($test_password === $admin_data['password']);
            echo "<p><strong>Plain text comparison result:</strong> " . ($result ? 'MATCH' : 'NO MATCH') . "</p>";
        } else {
            // Hashed password verification
            $result = password_verify($test_password, $admin_data['password']);
            echo "<p><strong>Hash verification result:</strong> " . ($result ? 'MATCH' : 'NO MATCH') . "</p>";
        }
    }
    
} else {
    echo "<p style='color: red;'>Admin not found!</p>";
}

echo "<hr>";
echo "<h4>Quick Fix Options</h4>";
echo "<p>If your password is stored as plain text, you can:</p>";
echo "<ol>";
echo "<li>Use the plain text password in the profile update form</li>";
echo "<li>Or click the button below to hash your current password</li>";
echo "</ol>";

if (isset($_POST['hash_password'])) {
    $current_password = $_POST['current_password'];
    $hashed_password = password_hash($current_password, PASSWORD_DEFAULT);
    
    $update_query = "UPDATE admin SET password = '$hashed_password' WHERE id = '$admin_id'";
    if (mysqli_query($con, $update_query)) {
        echo "<p style='color: green;'>Password has been hashed and updated successfully!</p>";
        echo "<p><strong>New hash:</strong> " . $hashed_password . "</p>";
    } else {
        echo "<p style='color: red;'>Error updating password: " . mysqli_error($con) . "</p>";
    }
}

echo "<form method='POST'>";
echo "<input type='password' name='current_password' placeholder='Enter current password to hash' required>";
echo "<button type='submit' name='hash_password'>Hash Current Password</button>";
echo "</form>";

mysqli_close($con);
?>
