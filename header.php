<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broaden</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <header>
        <div class="logo">
        </div>
        <ul class="nav__links">
            <li><a href="discover.php">Discover</a></li>
            <li><a class="brand" href="index.php">Broaden</a></li>
            <li><a href ="aboutus.php">About Us</a></li>
        </ul>
        <div class = "account-box">
            <?php
            if (isset($_SESSION["useruid"])) {
                echo "<li><a class='profile-page' href='profile.php'>Profile</a></li>";
                echo "<li><a  class='logout' href='includes/logout.inc.php'>Log out</a></li>";
            } else {
                echo "<li><a class='login' href='login.php'>Login</a></li>";
                echo "<a class='cta' href='signup.php'><button class ='sign-up-button'>Sign Up</button></a>";
            }
            ?>
        </div>
    </header>