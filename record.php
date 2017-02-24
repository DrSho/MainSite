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

//Current location
$curLoc = "dashboard";


if (isset($_POST['btn-submit'])) {


    // clean user inputs to prevent sql injections
    $date_exam = ($_POST['date_exam']);

    $date_exam = date_create_from_format('m/d/Y', $date_exam);
    $date_exam = $date_exam->format('Y-m-d');


    $weight = ($_POST['weight']);
    $LDL = ($_POST['LDL']);
    $HDL = ($_POST['HDL']);
    $cholesterol = ($_POST['cholesterol']);
    $triglycerides = ($_POST['triglycerides']);

    //Insert into user_account table

    $healthTypes = array(1 => "Triglycerides", 2 => "LDL", 3 => "HDL", 4 => "Cholesterol");
    $healthTypeID = array();

    for ($i = 0; $i < count($healthTypes); $i++) {
        $healthTypeID[$i] = array_keys($healthTypes)[$i];
    }

    $query = "INSERT INTO user_health_record (id, user_account_id, health_type1_id, health_type2_id, health_type3_id, health_type4_id, health_type1, health_type2, health_type3, health_type4, health_type1_level, health_type2_level, health_type3_level, health_type4_level, weight, date) VALUES (NULL, '" . $_SESSION['userId'] . "', '$healthTypeID[0]', '$healthTypeID[1]', '$healthTypeID[2]', '$healthTypeID[3]', '$healthTypes[1]', '$healthTypes[2]', '$healthTypes[3]', '$healthTypes[4]', '$triglycerides', '$LDL', '$HDL', '$cholesterol', '$weight', '$date_exam')";


    $result = $db_handle->runQuery($query);


    if ($result) {
        $success = true;
        $successMSG = "Successfully added!";
    } else {
        $errType = "danger";
        $errMSG = "Something went wrong, try again later...";
    }

}

?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Health Data</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="style.css" type="text/css"/>

    </head>
    <body>


    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div class="container">


            <div id="login-form">


                <?php

                if (isset($success) && $success) {
                    ?>
                    <div class="form-group">
                        <div class="alert alert-success">
                            <span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?>
                        </div>

                        <a href="dashboard.php" class="btn  btn-primary " role="button">Dashboard</a>

                    </div>
                    <?php
                } else { ?>

                    <form method="post" class="form-horizontal"
                          action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

                        <div class="col-md-12">


                            <div class="form-group">
                                <h2 class="">Complete the form below.</h2>
                            </div>

                            <div class="form-group">
                                <hr/>
                            </div>

                            <div class="well">

                                <?php


                                if (isset($errMSG)) {

                                    ?>
                                    <div class="form-group">
                                        <div class="alert alert-danger">
                                            <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>

                                <!-- Date of exam -->
                                <div class="form-group">
                                    <label for="date_exam" class="control-label col-sm-4">Date of Exam:</label>

                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="date_exam" name="date_exam"
                                               placeholder="mm/dd/yyyy"
                                               required onkeyup="
        var v = this.value;
        if (v.match(/^\d{2}$/) !== null) {
            this.value = v + '/';
        } else if (v.match(/^\d{2}\/\d{2}$/) !== null) {
            this.value = v + '/';
        }" minlength="10" maxlength="10">
                                    </div>
                                </div>


                                <!-- Weight -->
                                <div class="form-group">
                                    <label for="date_exam" class="control-label col-sm-4">Weight:</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" id="weight" name="weight"
                                               placeholder="in lbs." min="1"
                                               max="1000" required>

                                    </div>
                                </div>


                                <!-- LDL -->
                                <div class="form-group">
                                    <label for="LDL" class="control-label col-sm-4">LDL Level:</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" id="LDL" name="LDL"
                                               placeholder="Between 50-300 mmol/L"
                                               min="50" max="300" required>
                                    </div>

                                </div>


                                <!-- HDL -->
                                <div class="form-group">
                                    <label for="HDL" class="control-label col-sm-4">HDL Level:</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" id="HDL" name="HDL"
                                               placeholder="Between 20-90 mmol/L"
                                               min="20" max="90" required>
                                    </div>

                                </div>

                                <!-- Total Cholesterol -->
                                <div class="form-group">
                                    <label for="cholesterol" class="control-label col-sm-4">Total Cholesterol:</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" id="cholesterol" name="cholesterol"
                                               placeholder="Between 80-500 mmol/L" required min="80" max="500">
                                    </div>

                                </div>

                                <!-- Triglycerides -->
                                <div class="form-group">
                                    <label for="triglycerides" class="control-label col-sm-4">Triglycerides:</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" id="triglycerides"
                                               name="triglycerides"
                                               placeholder="Between 0-1000  mmol/L" min="0" max="1000" required>
                                    </div>

                                </div>

                            </div>


                            <div class="form-group">
                                <hr/>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary" name="btn-submit">Submit
                                </button>
                            </div>

                            <div class="form-group">
                                <hr/>
                            </div>


                        </div>

                    </form>

                <?php } ?>

            </div>


        </div>

    </div>

    <?php include "footer.php"; ?>
    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    </body>
    </html>
<?php ob_end_flush(); ?>