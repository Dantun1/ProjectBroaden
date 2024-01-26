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

$increment = $_GET["increment"];

$query = "SELECT * FROM snippets;";
$snippet_result = mysqli_query($conn, $query);
$snippets = mysqli_fetch_all($snippet_result, MYSQLI_ASSOC);

if (isset($_SESSION["userid"])){
    if ($increment == 1){
        $_SESSION["current_index"]++;
    }
    else {
        $_SESSION["current_index"]--;
    }

    if (($_SESSION["current_index"]) >= count($_SESSION["snippetIds"])){
        $_SESSION["current_index"] = 0;
    }
    else if ($_SESSION["current_index"] < 0){
        $_SESSION["current_index"] = count($_SESSION["snippetIds"]) - 1;

    }

    if (count($_SESSION["snippetIds"]) === 0){
        echo json_encode(array("bookmarked" => false, "liked" => false,"insession" => true, "title" => "", "body" => "You've viewed all the snippets!", "snippetId" =>-1));
    }
    else {
        $current_index = (int)$_SESSION["snippetIds"][$_SESSION["current_index"]];
    
        $uId = $_SESSION["userid"];
    
        $seen_query = "INSERT INTO userseen(userId,snippetId) VALUES(?,?);";
        $stmt = mysqli_prepare($conn, $seen_query);
        mysqli_stmt_bind_param($stmt,"ii", $uId, $current_index);
        mysqli_stmt_execute($stmt);
    
        $like_query = "SELECT * FROM userlikes WHERE userId = $uId AND snippetId  = $current_index;";
        $like_result = mysqli_query($conn, $like_query);
        $liked_post = false;
        if (mysqli_num_rows($like_result) > 0) {
            $liked_post = true;
            } else {
            $liked_post = false;
            }
    
        $bookmark_query = "SELECT * FROM userbookmarks WHERE userId = $uId AND snippetId  = $current_index;";
        $bookmark_result = mysqli_query($conn, $bookmark_query);
        $bookmarked_post = false;
        if (mysqli_num_rows($bookmark_result) > 0) {
            $bookmarked_post = true;
            } else {
            $bookmarked_post = false;
            }
        
        
        $post_query = "SELECT * FROM snippets WHERE  snippetId = $current_index;";
        $post_result = mysqli_query($conn, $post_query);
        $snippet = mysqli_fetch_assoc($post_result);
    
        echo json_encode(array("bookmarked" => $bookmarked_post, "liked" => $liked_post,"insession" => true, "title" => $snippet["title"], "body" => $snippet["content"], "snippetId" =>$current_index));
    

    }
}
else {
    echo json_encode(array("insession" => false, "body" => "Please sign up or log in to view our snippets!", "snippetId" =>$current_index));
}


mysqli_close($conn);