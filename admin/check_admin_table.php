<?php
include('config/dbcon.php');

// Check if profile_image column exists in admin table
$check_column_query = "SHOW COLUMNS FROM admin LIKE 'profile_image'";
$column_result = mysqli_query($con, $check_column_query);

if (mysqli_num_rows($column_result) == 0) {
    echo "profile_image column does not exist. Adding it now...<br>";
    
    // Add profile_image column
    $add_column_query = "ALTER TABLE admin ADD COLUMN profile_image VARCHAR(255) NULL";
    if (mysqli_query($con, $add_column_query)) {
        echo "profile_image column added successfully!<br>";
    } else {
        echo "Error adding profile_image column: " . mysqli_error($con) . "<br>";
    }
} else {
    echo "profile_image column already exists.<br>";
}

// Show current admin table structure
echo "<br>Current admin table structure:<br>";
$show_table_query = "DESCRIBE admin";
$table_result = mysqli_query($con, $show_table_query);

echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";

while ($row = mysqli_fetch_assoc($table_result)) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . $row['Default'] . "</td>";
    echo "<td>" . $row['Extra'] . "</td>";
    echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>
