<?php
error_reporting(0);
ob_start();
session_start();
include_once("config.php");

require_once 'include/dbcontroller.php';
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
        <?php include("include/navigation.php"); ?>
        <div class="container">


            <div id="login-form">
                <div class="col-lg-12">
                    <h2>Our Mission</h2>
                    <p>We have seen how access to medical records is crucial, we know that keeping health
                        records is very important. It is our goal to make it easy for individuals to aggregate all their
                        health information in a central, secure location making it easy for them and their healthcare
                        providers to monitor and track their progress.</p>

                    <p>We are aware of the many circumstances under which medical records can either be lost or just not
                        be accessible, whether it be due to re-location, changing health insurance or changing doctors
                        or an unexpected visit to the ER. We understand how much of a struggle it can be to maintain and
                        track health statistics, that is why we have created a web application where anyone can keep
                        their medical information in one central and secure location where they can access their records
                        anytime they need or share it health care providers using the healthcare application.</p>
                </div>
                <div class="form-group">
                    <hr/>
                </div>

                <div class="col-lg-12">
                    <h2>Our Team</h2>


                    <div id="sponsors">
                        <img src="images/sponsors.png">
                        <h4>Alex Elentukh, Michael Boccafola</h4>
                        <p>Sponsors</p>

                    </div>

                    <div class="team-member">
                        <img src="images/esdras.png">
                        <h4>Esdras Brito Silva</h4>
                        <p>Team Lead</p>
                    </div>

                    <div class="team-member">
                        <img src="images/christian.jpg">
                        <h4>Christian Hur</h4>
                        <p>Developer</p>

                    </div>
                    <div class="team-member">
                        <img src="images/claire.png">
                        <h4>Clair Odour</h4>
                        <p>Tester</p>
                    </div>

                    <div class="team-member">
                        <img src="images/dipa.png">
                        <h4>Dipa Thakkar</h4>
                        <p>Quality Assurance (QA)</p>
                    </div>

                </div>

            </div>


        </div>
        <?php include "include/footer.php"; ?>
        <script src="assets/jquery-1.11.3-jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>

    </body>
    </html>
<?php ob_end_flush(); ?>