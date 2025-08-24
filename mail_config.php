<?php
return [
    'host' => 'smtp.gmail.com',
    'SMTPAuth' => true,
    'username' => 'v.espinosa711@gmail.com', // Replace with your Gmail address
    'password' => 'akaufsgtyujhaiuv',    // Replace with your Gmail App Password (16 characters, no spaces)
    'SMTPSecure' => 'tls',
    'port' => 587,
    'from_email' => 'v.espinosa711@gmail.com', // Replace with your Gmail address
    'from_name' => 'WIT Student Assistant System'
];

/*
IMPORTANT SETUP INSTRUCTIONS:

1. Replace 'your_email@gmail.com' with your actual Gmail address
2. Replace 'your_app_password' with your Gmail App Password (NOT your regular password)

To get Gmail App Password:
1. Go to https://myaccount.google.com/
2. Security → 2-Step Verification (enable if not already)
3. Security → App Passwords
4. Select "Mail" and "Other (Custom name)"
5. Enter "WIT Student Assistant" as name
6. Click "Generate"
7. Copy the 16-character password (remove spaces)

Example:
'username' => 'john.doe@gmail.com',
'password' => 'abcd efgh ijkl mnop',  // Remove spaces when pasting
'from_email' => 'john.doe@gmail.com',
*/
?> 