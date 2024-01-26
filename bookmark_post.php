<?php
require_once 'includes/dbh.inc.php';
session_start();

$query = "SELECT * FROM snippets;";
$result = mysqli_query($conn, $query);
$snippets = mysqli_fetch_all($result, MYSQLI_ASSOC);
//array of snippets

if (isset($_SESSION["userid"])){
    $current_index = (int)$_SESSION["snippetIds"][$_SESSION["current_index"]];
    $uId = $_SESSION["userid"];

    $query = "SELECT * FROM userbookmarks WHERE userId = $uId AND snippetId  = $current_index;";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Remove the bookmark if it already exists
        $query = "DELETE FROM userbookmarks WHERE userId = $uId AND snippetId = $current_index;";
        mysqli_query($conn, $query);
    } else {
        // Add the bookmark if it doesn't exist
        $query = "INSERT INTO userbookmarks (userId, snippetId) VALUES ($uId, $current_index);";
        mysqli_query($conn, $query);
    }
    $bookmarkQuery = "SELECT * FROM userbookmarks WHERE userId = '$uId' AND snippetId = '$current_index'";
    $bookmarkResult = mysqli_query($conn, $bookmarkQuery);
    $bookmarked = mysqli_num_rows($bookmarkResult) > 0;
    echo json_encode(array("bookmarked" => $bookmarked));
}
else if (!isset($_SESSION["userid"])){
    header("Location: https://localhost/simple/login.php");
    exit();
}