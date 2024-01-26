<?php

include_once 'dbh.inc.php';

function emptyInputSignup($name,$email,$username,$pwd, $pwdrepeat) {
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
    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;"; //grabs user database
    $stmt =  mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt); //gets back all usernames/emails which are duplicates

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
    $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd) VALUES (?, ?, ?, ?);"; //inserts into databse
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
    $userId = $_SESSION['userid'];
    $api = "http://localhost:8080/get_predictions?user_id=".$userId;
    $apiResponse = file_get_contents($api);
    $snippetIds = json_decode($apiResponse, true);

    return $snippetIds;
}


function getSnippetIds($conn){
    $query = "SELECT snippetId FROM snippets;";
    $result = mysqli_query($conn, $query);
    $snippetIds = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $snippetIds;

}

function getSnippetInfo($conn, $snippetId){
    $query = "SELECT * FROM snippets WHERE snippetId =$snippetId;";
    $result = mysqli_query($conn, $query);
    $snippet = mysqli_fetch_assoc($result);

    return $snippet;
}