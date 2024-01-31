<?php
    include_once 'header.php';
    session_start()
?>
<!-- Contact us page -->

<body>
    <section class="signup-form">
        <div class="info-text">
            <h1 class="info-page-heading">
                Contact us
            </h1>
            <p1>
                Our contact details are...
            </p1>
            <p2>
            </p2>

            <?php
                include_once 'includes/dbh.inc.php';
                $snippetIds = $_SESSION["snippetIds"];
                echo $snippetIds[0];
            ?>
        </div>
    </section>
</body>

</html>

<?php
    include_once 'footer.php';
?>
