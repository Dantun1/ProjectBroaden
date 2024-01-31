<?php
// Gets all replies for a given parent comment ID
include 'includes/dbh.inc.php';
session_start();

$parentCommentId = $_GET['parentCommentId'];

$sql = "SELECT * FROM comments WHERE parentId = $parentCommentId ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$replies = mysqli_fetch_all($result, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($replies);
