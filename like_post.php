<?php
session_start();

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = ;
$dbName = ;
// File toggles the like button, similar to bookmark_post.php

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
} 


$query = "SELECT * FROM snippets;";
$result = mysqli_query($conn, $query);
$snippets = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_SESSION["userid"])){
    $current_index = (int)$_SESSION["snippetIds"][$_SESSION["current_index"]];
    $uId = $_SESSION["userid"];

    $query = "SELECT * FROM userlikes WHERE userId = $uId AND snippetId  = $current_index;";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $query = "DELETE FROM userlikes WHERE userId = $uId AND snippetId = $current_index;";
        mysqli_query($conn, $query);
    } else {
        $query = "INSERT INTO userlikes (userId, snippetId) VALUES ($uId, $current_index);";
        mysqli_query($conn, $query);
    }
    $likeQuery = "SELECT * FROM userlikes WHERE userId = '$uId' AND snippetId = '$current_index'";
    $likeResult = mysqli_query($conn, $likeQuery);
    $liked = mysqli_num_rows($likeResult) > 0;
    echo json_encode(array("liked" => $liked));
}
else if (!isset($_SESSION["userid"])){
    header("Location: https://localhost/simple/login.php");
    exit();
}
