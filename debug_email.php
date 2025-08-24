<?php
// Debug email configuration
// This script will help you identify the exact issue with your email setup

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
$config = require 'mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<h2>Email Configuration Debug</h2>";
echo "<hr>";

// Display current configuration
echo "<h3>Current Configuration:</h3>";
echo "<ul>";
echo "<li><strong>SMTP Host:</strong> " . $config['host'] . "</li>";
echo "<li><strong>SMTP Port:</strong> " . $config['port'] . "</li>";
echo "<li><strong>SMTP Secure:</strong> " . $config['SMTPSecure'] . "</li>";
echo "<li><strong>Username:</strong> " . $config['username'] . "</li>";
echo "<li><strong>Password:</strong> " . (strlen($config['password']) > 0 ? "***SET***" : "***NOT SET***") . "</li>";
echo "<li><strong>From Email:</strong> " . $config['from_email'] . "</li>";
echo "</ul>";

// Check if configuration is complete
$errors = [];
if ($config['username'] === 'your_email@gmail.com') {
    $errors[] = "Username is still set to default value. Please update with your Gmail address.";
}
if ($config['password'] === 'your_app_password') {
    $errors[] = "Password is still set to default value. Please update with your Gmail App Password.";
}
if ($config['from_email'] === 'your_email@gmail.com') {
    $errors[] = "From email is still set to default value. Please update with your Gmail address.";
}

if (!empty($errors)) {
    echo "<div style='background: #ffe6e6; padding: 15px; border: 1px solid #ff9999; margin: 10px 0;'>";
    echo "<h4>Configuration Issues Found:</h4>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
    echo "</div>";
    exit;
}

// Test SMTP connection
echo "<h3>Testing SMTP Connection...</h3>";

$mail = new PHPMailer(true);

try {
    // Enable debug output
    $mail->SMTPDebug = 3; // Enable verbose debug output
    $mail->Debugoutput = function($str, $level) {
        echo "<div style='font-family: monospace; font-size: 12px; background: #f5f5f5; padding: 5px; margin: 2px 0; border-left: 3px solid #007cba;'>";
        echo htmlspecialchars($str);
        echo "</div>";
    };

    // Server settings
    $mail->isSMTP();
    $mail->Host       = $config['host'];
    $mail->SMTPAuth   = $config['SMTPAuth'];
    $mail->Username   = $config['username'];
    $mail->Password   = $config['password'];
    $mail->SMTPSecure = $config['SMTPSecure'];
    $mail->Port       = $config['port'];

    // Test connection only (don't send email)
    echo "<p>Attempting to connect to SMTP server...</p>";
    
    // This will show detailed connection information
    $mail->smtpConnect();
    
    echo "<div style='background: #e6ffe6; padding: 15px; border: 1px solid #99ff99; margin: 10px 0;'>";
    echo "<h4>✅ SMTP Connection Successful!</h4>";
    echo "<p>Your email configuration is working correctly.</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #ffe6e6; padding: 15px; border: 1px solid #ff9999; margin: 10px 0;'>";
    echo "<h4>❌ SMTP Connection Failed!</h4>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    
    // Provide specific solutions based on error
    if (strpos($e->getMessage(), 'Could not authenticate') !== false) {
        echo "<h5>🔧 Solution for Authentication Error:</h5>";
        echo "<ol>";
        echo "<li>Make sure you're using a Gmail App Password (not your regular password)</li>";
        echo "<li>Go to <a href='https://myaccount.google.com/' target='_blank'>Google Account Settings</a></li>";
        echo "<li>Security → 2-Step Verification (enable if not already)</li>";
        echo "<li>Security → App Passwords</li>";
        echo "<li>Select 'Mail' and generate a new app password</li>";
        echo "<li>Copy the 16-character password and update mail_config.php</li>";
        echo "</ol>";
    }
    
    if (strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "<h5>🔧 Solution for Connection Error:</h5>";
        echo "<p>Check if your firewall or antivirus is blocking the connection.</p>";
    }
    
    echo "</div>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>If connection failed, fix the issues above and refresh this page</li>";
echo "<li>If connection succeeded, go to <a href='test_email.php'>test_email.php</a> to send a test email</li>";
echo "<li>Once email is working, test the forgot password feature</li>";
echo "</ol>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3, h4, h5 { color: #333; }
hr { border: 1px solid #ddd; margin: 20px 0; }
ul, ol { line-height: 1.6; }
a { color: #007cba; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
