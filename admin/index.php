<?php
error_reporting(0);
ob_start();
session_start();
include_once("../config.php");
require_once '../include/dbcontroller.php';
require_once '../include/accountcontroller.php';
require_once '../include/dashboardcontroller.php';
require_once 'include/admincontroller.php';


// if session is not set this will redirect to home page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit;
}

$db_handle = new DBController();
$dashboard = new DashboardController();
$account = new AccountController();
$admin = new AdminController();

$_SESSION['account'] = $account;
$noneFound = "";
//Load data to class instances
$admin->loadData($db_handle);
$dashboard->loadData($db_handle, $account);

$_SESSION['user'] = $admin->getAdminFirstName();
$_SESSION['userFullName'] = $admin->getAdminFirstName() . " " . $admin->getAdminLastName();

$userName = $_SESSION['user'];

$curLoc = basename($_SERVER['PHP_SELF'], ".php");


if (!isset($_POST['btn-search'])) {

//Retrieve patient records
    $query = "SELECT * FROM user_address ORDER BY last_name ASC";
    $result = $db_handle->runQuery($query);
    $rowcount = mysqli_num_rows($result);
} else {
    //Search for keywords in first_name, middle_name, last_name, patient_id
    $keyword = trim(htmlspecialchars($_POST['keyword']));
    $query = "SELECT * FROM user_address WHERE (first_name LIKE '%$keyword%') OR (middle_name LIKE '%$keyword%') OR (last_name LIKE '%$keyword%') OR (user_account_id LIKE '%$keyword%')";

    $result = $db_handle->runQuery($query);
    $rowcount = mysqli_num_rows($result);

    if (($rowcount == 0)) {
        $disabled = "btn-default disabled";
        $noneFound = "No records for \"<span id='keyword'>".$keyword."\"</span>.";
}

}

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
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="../style.css" type="text/css"/>
        <link rel="stylesheet" href="admin.css" type="text/css"/>


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

        <?php include("include/navigation.php") ?>

        <div class="container">

            <div class="page-header">
                <h1>Admin Dashboard</h1>
            </div>

            <div class="well">
                <div style="float: left;">
                    <h3>Click a record for a detail view.</h3>
                </div>
                <div class="search-box">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                        <input type="text" name="keyword" id="myInput" placeholder="Search for names.."
                               title="Type in a name">
                        <input id="btn-search" type="submit" name="btn-search" value="Search">
                    </form>
                </div>

                <p id="not-found"><?=$noneFound?></p>

                <div class="panel-group" id="accordion">

                    <?php
                    $admin->showrecords($result);

                    ?>
                </div>
                <div>

                    <button class="btn <?= $disabled ?>" onclick="window.print();" role="button">Print</button>


                </div>

            </div>

        </div>
    </div>


    <?php include "../include/footer.php"; ?>

    <script src="../assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    </body>
    </html>
<?php ob_end_flush(); ?>