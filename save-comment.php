<?php

include 'includes/dbh.inc.php';
session_start();

$postData = json_decode(file_get_contents('php://input'), true);

$content = $postData['content'];
$parentId = $postData['parentId'];
$userId = $_SESSION['userid'];
$snippetId = (int)$_SESSION["snippetIds"][$_SESSION["current_index"]];

if ($parentId === null) {
    $sql = "INSERT INTO comments(userId, snippetId, content, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $userId, $snippetId, $content);
  } else {
    $sql = "INSERT INTO comments(parentId, userId, snippetId, content, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiis", $parentId, $userId, $snippetId, $content);
  }
mysqli_stmt_execute($stmt);

$lastInsertId = mysqli_insert_id($conn);
$sql = "SELECT * FROM comments WHERE commentId = $lastInsertId";
$result = mysqli_query($conn, $sql);

$savedComment = mysqli_fetch_assoc($result);

$savedComment['username'] = $_SESSION['useruid'];

header('Content-Type: application/json');
echo json_encode($savedComment);