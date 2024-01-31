<?php

include_once 'dbh.inc.php';

function emptyInputSignup($name,$email,$username,$pwd, $pwdrepeat) {
    // Checks if any of the signup variables are empty. Returns true if one or more are empty, False if not.

    $result = false;
    if (empty($name) || empty($email) || empty($username) || empty($pwd) || empty($pwdrepeat)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function invalidUid($username) {
    // Checks that inputted username contains exclusively numbers or letters. Returns True if there are invalid characters, False if not.

    $result= false;
    if (!preg_match("/^[a-zA-Z0-9]*$/",$username)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function invalidEmail($email) {
    // Checks that email is valid. Returns True if invalid, False if not. 

    $result= false;
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function pwdMatch($pwd,$pwdrepeat) {
    // Checks that password and confirm password inputs are the same. Returns True if they don't match, False if not

    $result = false;
    if ($pwd !== $pwdrepeat){
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function uidExists($conn,$username,$email) {
    // Checks in database to see if username already exists. 

    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;"; 
    $stmt =  mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt); 

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        mysqli_stmt_close($stmt);
        $result = false;
        return $result;
    }
}

function createUser($conn,$name,$email,$username,$pwd) {
    // Adds user information to database if all inputs are valid.

    $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd) VALUES (?, ?, ?, ?);";
    $stmt =  mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $username, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../signup.php?error=none");
    exit();
}

function emptyInputLogin($username,$pwd) {
    // Checks if user is missing any login inputs. Returns True if missing, False if not.
    $result = false;
    if (empty($username) || empty($pwd)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function loginUser($conn, $username, $pwd) {
    // Checks if the username and password are valid by comparing the hashed password in the database to the inputted password.
    // If the information is correct, a user session is created and the session variables are initialized.
    // 
    // The recommended snippet IDs are stored as an array in descending order of predicted rating as calculated by the recommender system.
    // The current_index is stored as a pointer to the article being viewed and the user increments this in the discovery page by cycling back/forth
    // The viewrequests represent the number of times a user has clicked on a previously liked/bookmarked article to view it.

    $uidExists = uidExists($conn,$username,$username); 

    if($uidExists === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    }
    $pwdHashed = $uidExists["usersPwd"];
    $checkPwd = password_verify($pwd,$pwdHashed);

    if($checkPwd === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    }
    else if ($checkPwd === true) {
        session_start();
        $_SESSION["userid"] = $uidExists["usersId"];
        $_SESSION["useruid"] = $uidExists["usersUid"];
        $_SESSION["current_index"] = 0;
        $predictions = getPredictions();
        $_SESSION['snippetIds'] = $predictions;
        $_SESSION['viewrequests'] = 0;
        header("location: ../index.php");
        exit();
    }
}

function getPredictions(){
    // Performs an api call to the Flask application running on port 8080. This application computes the predictions and returns the IDs as a JSON file.

    $userId = $_SESSION['userid'];
    $api = "http://localhost:8080/get_predictions?user_id=".$userId;
    $apiResponse = file_get_contents($api);
    $snippetIds = json_decode($apiResponse, true);

    return $snippetIds;
}


function getSnippetIds($conn){
    // Function to get all snippet IDs from the database.

    $query = "SELECT snippetId FROM snippets;";
    $result = mysqli_query($conn, $query);
    $snippetIds = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $snippetIds;

}

function getSnippetInfo($conn, $snippetId){
    // Function to get all information about a snippet given its id.
    $query = "SELECT * FROM snippets WHERE snippetId =$snippetId;";
    $result = mysqli_query($conn, $query);
    $snippet = mysqli_fetch_assoc($result);

    return $snippet;
}
