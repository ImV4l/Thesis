<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "sa_management_systen";

$con = mysqli_connect("$host", "$username", "$password", "$database");

if (!$con) {
    header("Location: ../errors/dberror.php");
    die();
}
