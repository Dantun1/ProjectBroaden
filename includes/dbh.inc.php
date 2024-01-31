<?php
// Database connection
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "";


$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
} 
