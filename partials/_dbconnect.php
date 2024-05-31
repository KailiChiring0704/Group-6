<?php
$server = "db";
$username = "root";
$password = "root";
$database = "billing-system-db";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}
