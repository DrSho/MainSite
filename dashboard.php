<?php
error_reporting(0);
ob_start();
session_start();
require_once 'dbcontroller.php';
require_once 'dashboardcontroller.php';

$db_handle = new DBController();
$dashboard = new DashboardController();

$userName = "";

// if session is not set this will redirect to home page
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
} else {
    $userName = $_SESSION['user'];
}

$curLoc = basename($_SERVER['PHP_SELF'], ".php");

//Retrieve patient records
$query = "SELECT * FROM user_health_record 
                    WHERE user_account_id = '" . $_SESSION[userId] . "' 
                    ORDER BY date ASC";

$result = $db_handle->runQuery($query);

$rowcount = mysqli_num_rows($result);

//disable button or links
$disabled = "btn-primary";
//If no entry then no need to print
if (($rowcount == 0)) {
    $disabled = "btn-default disabled";
}

?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Welcome - <?php echo $userName; ?></title>
        <?php //include("scripts.php"); ?>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="style.css" type="text/css"/>

        <script>
            function myFunction() {
                var x;
                if (confirm("Press a button!") == true) {
                    x = "You pressed OK!";
                } else {
                    x = "You pressed Cancel!";
                }
                document.getElementById("demo").innerHTML = x;
            }
        </script>


    </head>
    <body>


    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div class="container">

            <div class="page-header">
                <h1>My Health Exam History</h1>
            </div>


            <div class="panel-group" id="accordion">

                <?php
                $dashboard->showrecords($db_handle,$result);

                ?>
            </div>
            <div>
                <a href="record.php" class="btn btn-primary" role="button">Enter Health Data</a>

                <a href="print.php" class="btn <?= $disabled ?>" role="button">Print</a>

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