<?php

// This file handles the login process. It checks if the "submit" button has been pressed then runs the loginUser function, taking in the inputted
// values as arguments. If the user has not entered anything, it shows an empty input error.
// 

if (isset($_POST["submit"])) {

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];

    if (emptyInputLogin($username,$pwd,) !== false){
        header("location: ../login.php?error=emptyinput");
        exit();
    }
    loginUser($conn, $username, $pwd);
}
else {
    header("location:../login.php");
    exit();
}
