
<?php
session_start();
include_once 'header.php';
?>

<body>
    <section class="interactive-article-space">
        <div class = comment-section-open id = "comment-section">
            <div class = "comment-section-header">
                <h1 class = "comment-section-heading">Comments</h1>
            </div>
            <div id="comment-container" class = "comment-container">
            </div>
            <div id="comment-input-container" class = "comment-input-container">
                <form class = "comment-form" id="comment-form">
                    <input class = "comment-input-box" type="text" id="comment-input" placeholder="Write your comment...">
                    <button class = "comment-post-button" id="submit-comment">Post Comment</button>
                </form>
            </div>
        </div>

        <div class="article-frame">
            <div class = "book">
                <div class="side-bar">
                    <div class = "interaction-buttons">
                        <?php
                            include_once 'includes/dbh.inc.php';

                            $query = "SELECT * FROM snippets;";
                            $result = mysqli_query($conn, $query);
                            $snippets = mysqli_fetch_all($result, MYSQLI_ASSOC);

                            if (isset($_SESSION["userid"])) {
                                $current_index = (int)$_SESSION["snippetIds"][$_SESSION["current_index"]];
                                $uId = $_SESSION["userid"];
                                $query = "SELECT * FROM userlikes WHERE userId = $uId AND snippetId  = $current_index;";
                                $result = mysqli_query($conn, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<button class='like-button-full' data-post-id = 'like' id ='like' name='like'><span class='material-symbols-outlined'>favorite</span>";
                                    } else {
                                    echo "<button class='like-button' data-post-id = 'like' id ='like' name='like'><span class='material-symbols-outlined'>favorite</span>";
                                    }
                                }
                            else{
                                echo "<button class='like-button' data-post-id = 'like' id ='like' name='like'><span class='material-symbols-outlined'>favorite</span>";
                            }
                        ?>
                        <button class="comment-button" id="comment" data-post-id = "comment">
                            <span class="material-symbols-outlined">
                                comment
                            </span>
                        </button>
                        <?php
                            include_once 'includes/dbh.inc.php';

                            $query = "SELECT * FROM snippets;";
                            $result = mysqli_query($conn, $query);
                            $snippets = mysqli_fetch_all($result, MYSQLI_ASSOC);

                            if (isset($_SESSION["userid"])) {
                                $current_index = (int)$_SESSION["snippetIds"][$_SESSION["current_index"]];
                                $uId = $_SESSION["userid"];
                                $query = "SELECT * FROM userbookmarks WHERE userId = $uId AND snippetId  = $current_index;";
                                $result = mysqli_query($conn, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<button class='bookmark-full' data-post-id = 'bookmark' id ='bookmark' name='bookmark'><span class='material-symbols-outlined'>bookmark</span>";
                                    } else {
                                    echo "<button class='bookmark' data-post-id = 'bookmark' id ='bookmark' name='bookmark'><span class='material-symbols-outlined'>bookmark</span>";
                                    }
                                }
                            else{
                                echo "<button class='bookmark' data-post-id = 'bookmark' id ='bookmark' name='bookmark'><span class='material-symbols-outlined'>bookmark</span>";
                            }
                            ?>
                    </div>
                    <button class ='previous-post' id = 'previous-post'>
                                <span class="material-symbols-outlined">
                                    arrow_back
                                </span>
                    </button>
                </div>
                <div class="article-page" id = "snippet">
                    <div class="time-to-broaden">
                        <p class="snippet-welcome"> Let's Broaden!</p>
                    </div>
                </div>
                <button class="page-turn-area" id = "next-article">
                </button>
            </div>

        </div>

    </section>
    <?php
    include_once 'footer.php';
    ?>
    <script src = "discover.js"></script>
</body>

</html>