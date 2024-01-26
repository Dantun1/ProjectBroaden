<?php
session_start();
?>

<?php
    include_once 'header.php';
?>

<body>
    <section class="signup-form">
        <div class = "welcome-box">
            <div class = "profile-pic">
                <span class="material-symbols-outlined" style="font-size:10em;">
                    account_circle
                </span>
            </div>
            <div class = "welcome-text">
                <p>Welcome <?php echo("{$_SESSION['useruid']}");?>!</p>
            </div>
        </div>

        <div class = "userinfo-container">
            <div class="user-posts">
                <h1 class= 'liked-snippet-heading'>Bookmarked Posts</h1>
                <div class = "liked-post-links">
                    <?php
                    include_once 'includes/dbh.inc.php';
                    include_once 'includes/functions.inc.php';

                    $userId = $_SESSION["userid"];
                    $bookmarksQuery = "SELECT * FROM userbookmarks WHERE userId = $userId;";
                    $result = mysqli_query($conn, $bookmarksQuery);
                    $bookmarkDict = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    foreach ($bookmarkDict as $bookmark){
                        $snippet = getSnippetInfo($conn,$bookmark['snippetId']);


                        echo '<button class = "liked-snippet-link" data-snippet-id ='.$snippet['snippetId'].'>'.$snippet['title'].'</button>';
                    };
            ?>

                </div>
            </div>
            <div class="user-posts">
                <h1 class= 'liked-snippet-heading'>Liked Posts</h1>
                <div class = "liked-post-links">
                    <?php
                    include_once 'includes/dbh.inc.php';

                    $userId = $_SESSION["userid"];
                    $likesQuery = "SELECT * FROM userlikes WHERE userId = $userId;";
                    $result = mysqli_query($conn, $likesQuery);
                    $likeDict = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    foreach ($likeDict as $like){
                        $snippet = getSnippetInfo($conn,$like['snippetId']);


                        echo '<button class = "liked-snippet-link" data-snippet-id ='.$snippet['snippetId'].' >'.$snippet['title'].'</button>';
                    };
            ?>

                </div>


            </div>

        </div>

    </section>
    <script src = "profile.js"></script>
</body>

</html>

<?php
    include_once 'footer.php';
?>