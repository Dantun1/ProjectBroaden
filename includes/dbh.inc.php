<?php
// Database connection, password and name must be set to work
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "";


$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
} 
