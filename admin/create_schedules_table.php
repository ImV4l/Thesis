<?php
include('config/dbcon.php');

// Read the SQL file
$sql = file_get_contents('config/schedules_table.sql');

// Execute the SQL
if (mysqli_query($con, $sql)) {
    echo "Schedules table created successfully!";
} else {
    echo "Error creating table: " . mysqli_error($con);
}

mysqli_close($con);
?>
