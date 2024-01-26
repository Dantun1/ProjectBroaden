<?php

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "*Danulik2005";
$dbName = "broaden";


$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
} 