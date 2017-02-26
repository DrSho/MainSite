<?php
error_reporting(0);
ob_start();
session_start();

require_once 'dbcontroller.php';
require_once 'accountcontroller.php';

require_once 'dashboardcontroller.php';


// if session is not set this will redirect to home page
if (!isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit;
}

$db_handle = new DBController();
$dashboard = new DashboardController();
$account = new AccountController();

$_SESSION['account'] = $account;

//Load data to class instances
$dashboard->loadData($db_handle,$account);
$dashboard->loadBenchmark($db_handle,$account);

$_SESSION['user'] = $account->first_name;
$_SESSION['userFullName'] = $account->first_name . " " . $account->last_name;

$userName = $_SESSION['user'];

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
                <h1><?= $account->first_name?>'s Health Exam History</h1>
            </div>

            <div class="well">

                <div class="panel-group" id="accordion">

                    <?php
                    $dashboard->showrecords($db_handle, $account, $result);

                    ?>
                </div>
                <div>
                    <a href="record.php" class="btn btn-primary" role="button">Enter Health Data</a>

                    <button class="btn <?= $disabled ?>" onclick="window.print();" role="button">Print</button>


                </div>

            </div>

        </div>
    </div>

    <?php include "footer.php"; ?>


    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    </body>
    </html>
<?php ob_end_flush(); ?>