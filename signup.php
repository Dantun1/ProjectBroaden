
<?php
include_once 'header.php';
?>
<!-- Signup form page  -->
<section class="signup-form">
    <form class="box" action="includes/signup.inc.php" method="post">
        <h1>Sign Up</h1>
        <input type="text" name="name" placeholder="Full name">
        <input type="text" name="email" placeholder="Email">
        <input type="text" name="uid" placeholder="Username">
        <input type="password" name="pwd" placeholder="Password">
        <input type="password" name="pwdrepeat" placeholder="Confirm password">
        <button class="submit-button" type="submit" name="submit">Submit</button>
    </form>
    <div class = "error-message">
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<p class='error-text' >Please fill in all the fields</p>";
            } else if ($_GET["error"] == "invaliduid") {
                echo "<p class='error-text'>Please enter a valid username</p>";
            } else if ($_GET["error"] == "invalidemail") {
                echo "<p class='error-text'>Please enter a valid email</p>";
            } else if ($_GET["error"] == "passwordsdontmatch") {
                echo "<p class='error-text'>Your passwords don't match, please try again.</p>";
            } else if ($_GET["error"] == "stmtfailed") {
                echo "<p class='error-text'>Something went wrong, please try again</p>";
            } else if ($_GET["error"] == "usernametaken") {
                echo "<p class='error-text'>Please choose another username</p>";
            } else if ($_GET["error"] == "none") {
                echo "<p class ='successful-signup'>You have signed up!</p>";
            }
        }
        ?>

    </div>

</section>

<?php
include_once 'footer.php';
?>

</body>

</html>
