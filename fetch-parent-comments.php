<?php
// Gets all parent comments for a given article as a JSON file.
include 'includes/dbh.inc.php';
session_start();

$snippetId = (int)$_SESSION["snippetIds"][$_SESSION["current_index"]];


$sql = "SELECT * FROM comments WHERE parentId IS NULL AND snippetId = $snippetId ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$parentComments = mysqli_fetch_all($result, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($parentComments);
