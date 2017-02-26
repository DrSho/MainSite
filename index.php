<?php
error_reporting(0);
ob_start();
session_start();
include_once("config.php");
require_once 'include/dbcontroller.php';

$curLoc = basename($_SERVER['PHP_SELF'], ".php");

if (isset($_SESSION['user'])) {
    $userName = $_SESSION['user'];
} else {
    $userName = "Guest";
}

?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Welcome - <?php echo $userRow['userEmail']; ?></title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="style.css" type="text/css"/>
    </head>
    <body>


    <div id="wrapper">
        <?php include("include/navigation.php"); ?>

        <div class="container">

            <div class="center-page">

                <div class="page-header">
                    <h1>Welcome to the Dr. Sho Web Site!</h1>
                </div>


                <div>


                    <img src="images/healthy.jpg">

                </div>

                <iframe width="560" height="315" src="https://www.youtube.com/embed/bEF6AuGwZTc" frameborder="0"
                        allowfullscreen></iframe>

            </div>
        </div>


        <?php include "include/footer.php"; ?>
        <script src="assets/jquery-1.11.3-jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>

    </body>
    </html>
<?php ob_end_flush(); ?>