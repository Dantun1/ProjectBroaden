<?php
    include_once 'header.php';
?>

<section class="signup-form">
        <form class="box" action="includes/login.inc.php" method="post">
            <h1>Log In</h1>
            <input type="text" name="uid" placeholder="Username/Email">
            <input type="password" name="pwd" placeholder="Password">
            <button class="submit-button" type="submit" name="submit">Submit</button>
        </form>
        <div class = "error-message">
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<p class='error-text' >Please fill in all the fields</p>";
            } else if ($_GET["error"] == "wronglogin") {
                echo "<p class='error-text'>Incorrect login information, please try again</p>";
            } 

        }
        ?>

    </div>
</section>

</body>

</html>

<?php
    include_once 'footer.php';
?>