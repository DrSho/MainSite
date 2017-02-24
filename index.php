<?php
//error_reporting(0);
ob_start();
session_start();
require_once 'dbcontroller.php';
$userName = "Guest";
$curLoc = basename($_SERVER['PHP_SELF'], ".php");

if (isset($_SESSION['user'])) {

    $userName = $_SESSION['user'];
}
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Welcome - <?php echo $userRow['userEmail'];?></title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="style.css" type="text/css"/>
    </head>
    <body>



    <div id="wrapper">
        <?php include("navigation.php"); ?>

        <div class="container">

            <div id="login-form">

                <h2>Welcome!</h2>
            </div>


            <main>
                <section class="left-panel">

                    <img src="images/healthy.jpg">

                </section>

                <aside>

                    <iframe width="320" height="160" src="https://www.youtube.com/embed/bEF6AuGwZTc" frameborder="0" allowfullscreen></iframe>

                </aside>


                <section class="left-panel">



                </section>

        </div>

    </div>

    <?php include "footer.php"; ?>
    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    </body>
    </html>
<?php ob_end_flush(); ?>