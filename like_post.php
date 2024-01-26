<?php
session_start();

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "*Danulik2005";
$dbName = "broaden";


$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
} 
//connection to database

$query = "SELECT * FROM snippets;";
$result = mysqli_query($conn, $query);
$snippets = mysqli_fetch_all($result, MYSQLI_ASSOC);
//array of snippets

if (isset($_SESSION["userid"])){
    $current_index = (int)$_SESSION["snippetIds"][$_SESSION["current_index"]];
    $uId = $_SESSION["userid"];

    $query = "SELECT * FROM userlikes WHERE userId = $uId AND snippetId  = $current_index;";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Remove the like if it already exists
        $query = "DELETE FROM userlikes WHERE userId = $uId AND snippetId = $current_index;";
        mysqli_query($conn, $query);
    } else {
        // Add the like if it doesn't exist
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