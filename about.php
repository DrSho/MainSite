<?php
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
        <title>About Us</title>

        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="style.css" type="text/css"/>
    </head>
    <body>


    <div id="wrapper">
        <?php include("navigation.php"); ?>
        <div class="container">


            <div id="login-form">
                <div class="col-lg-12">
                    <h1>About Us</h1>
                </div>
            </div>

        </div>

        <footer>
            <?php include "footer.php"; ?>
        </footer>

    </div>

    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    </body>
    </html>
<?php ob_end_flush(); ?>