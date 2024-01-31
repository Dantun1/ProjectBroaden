<?php
// Gets username of a given user ID
include 'includes/dbh.inc.php';
session_start();

$userId = (int)$_GET['userId'];

$sql = "SELECT usersUid FROM users WHERE usersId = '$userId';";
$result = mysqli_query($conn, $sql);
$username = mysqli_fetch_assoc($result);
header('Content-Type: application/json');

echo json_encode($username);
