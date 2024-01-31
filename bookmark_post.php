<?php
require_once 'includes/dbh.inc.php';
session_start();

// File handles the bookmark button toggling. It checks if the user has/has not bookmarked the current snippet ID in the userbookmarks table and either inserts/removes a row. 

$query = "SELECT * FROM snippets;";
$result = mysqli_query($conn, $query);
$snippets = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_SESSION["userid"])){
    $current_index = (int)$_SESSION["snippetIds"][$_SESSION["current_index"]];
    $uId = $_SESSION["userid"];

    $query = "SELECT * FROM userbookmarks WHERE userId = $uId AND snippetId  = $current_index;";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $query = "DELETE FROM userbookmarks WHERE userId = $uId AND snippetId = $current_index;";
        mysqli_query($conn, $query);
    } else {
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
