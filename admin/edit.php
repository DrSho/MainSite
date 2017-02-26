<?php
error_reporting(0);
ob_start();
session_start();
include_once("../config.php");
require_once '../include/dbcontroller.php';
require_once '../include/dashboardcontroller.php';

$db_handle = new DBController();
$dashboard = new DashboardController();

$userName = "";
$recordID = $_GET['id'];

// if session is not set this will redirect to home page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit;
} else {
    $userName = $_SESSION['user'];
}

//Current location
$curLoc = "dashboard";

if (isset($_POST['btn-update'])) {

    // clean user inputs to prevent sql injections
    $date_exam = ($_POST['date_exam']);
    $date_exam = date_create_from_format('m/d/Y', $date_exam);
    $date_exam = $date_exam->format('Y-m-d');

    $weight = ($_POST['weight']);
    $LDL = ($_POST['LDL']);
    $HDL = ($_POST['HDL']);
    $cholesterol = ($_POST['cholesterol']);
    $triglycerides = ($_POST['triglycerides']);
    $id = $_POST['id'];

    //Update table
    $query = "UPDATE user_health_record 
     SET health_type1_level = '$triglycerides', health_type2_level = '$LDL', health_type3_level = '$HDL', health_type4_level = '$cholesterol', weight = '$weight', date = '$date_exam' WHERE id='$id'";

    $result = $db_handle->updateQuery($query);

    if ($result) {
        $success = true;
        $successMSG = "Successfully updated!";
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
        <title>Edit Health Data</title>
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="../style.css" type="text/css"/>
        <link rel="stylesheet" href="admin.css" type="text/css"/>

    </head>
    <body>


    <div id="wrapper">

        <?php include("include/navigation.php") ?>

        <div class="container">


            <div id="login-form">


                <?php

                if (isset($success) && $success) {

                    ?>
                    <div class="form-group">
                        <div class="alert alert-success">
                            <span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?>
                        </div>

                        <a href="index.php" class="btn  btn-primary " role="button">Dashboard</a>
                        <a href="details.php?id=<?=$_POST['patientID']?>" class="btn  btn-primary " role="button">Back to Details</a>

                    </div>
                    <?php
                } else { ?>

                    <form method="post" class="form-horizontal"
                          action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

                        <div class="col-md-12">


                            <div class="form-group">
                                <h1>Edit</h1>
                                <h3 class="">Make your changes below then click Update.</h3>
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

                                $query = "SELECT * FROM user_health_record WHERE id='$recordID'";
                                $result = $db_handle->runQuery($query);
                                $row = $result->fetch_array(MYSQLI_ASSOC);

                                $date_exam = date_create_from_format('Y-m-d', $row['date']);
                                $date_exam = $date_exam->format('m/d/Y');

                                $query2 = "SELECT user_account_id, first_name, last_name, gender FROM user_address WHERE user_account_id = '".$_SESSION['patientID']."'";
                                $result2 = $db_handle->runQuery($query2);
                                $row2 = $result2->fetch_array(MYSQLI_ASSOC);

                                ?>

                                <p style="float:right;"><b>Patient ID:</b> <?=$_SESSION['patientID']?></p>
                                <p><b>Patient Name:</b> <?=$row2['first_name']?> <?=$row2['last_name']?> (<?=$row2['gender']?>)</p>
                                <br>

                                <!-- Date of exam -->
                                <div class="form-group">
                                    <label for="date_exam" class="control-label col-sm-4">Date of Exam:</label>

                                    <div class="input-group col-sm-5">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-edit"></span></span>
                                        <input type="text" class="form-control" id="date_exam" name="date_exam"
                                               placeholder="mm/dd/yyyy"

                                               onkeyup="
        var v = this.value;
        if (v.match(/^\d{2}$/) !== null) {
            this.value = v + '/';
        } else if (v.match(/^\d{2}\/\d{2}$/) !== null) {
            this.value = v + '/';
        }"

                                               required value="<?= $date_exam ?>" minlength="10" maxlength="10">

                                    </div>
                                </div>

                                <!-- Weight -->
                                <div class="form-group">
                                    <label for="date_exam" class="control-label col-sm-4">Weight:</label>
                                    <div class="input-group col-sm-5">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-edit"></span></span>
                                        <input type="number" class="form-control" id="weight" name="weight"
                                               placeholder="in lbs." min="1"
                                               max="1000" required value="<?= $row['weight'] ?>">
                                    </div>
                                </div>


                                <!-- LDL -->
                                <div class="form-group">
                                    <label for="LDL" class="control-label col-sm-4">LDL Level:</label>
                                    <div class="input-group col-sm-5">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-edit"></span></span>
                                        <input type="number" class="form-control" id="LDL" name="LDL"
                                               placeholder="Between 50-300 mmol/L"
                                               min="50" max="300" required value="<?= $row['health_type2_level'] ?>">
                                    </div>

                                </div>


                                <!-- HDL -->
                                <div class="form-group">
                                    <label for="HDL" class="control-label col-sm-4">HDL Level:</label>
                                    <div class="input-group col-sm-5">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-edit"></span></span>
                                        <input type="number" class="form-control" id="HDL" name="HDL"
                                               placeholder="Between 20-90 mmol/L"
                                               min="20" max="90" required value="<?= $row['health_type3_level'] ?>">
                                    </div>

                                </div>

                                <!-- Total Cholesterol -->
                                <div class="form-group">
                                    <label for="cholesterol" class="control-label col-sm-4">Total Cholesterol:</label>
                                    <div class="input-group col-sm-5">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-edit"></span></span>
                                        <input type="number" class="form-control" id="cholesterol" name="cholesterol"
                                               placeholder="Between 80-500 mmol/L" required min="80" max="500"
                                               value="<?= $row['health_type4_level'] ?>">
                                    </div>

                                </div>

                                <!-- Triglycerides -->
                                <div class="form-group">
                                    <label for="triglycerides" class="control-label col-sm-4">Triglycerides:</label>
                                    <div class="input-group col-sm-5">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-edit"></span></span>
                                        <input type="number" class="form-control" id="triglycerides"
                                               name="triglycerides"
                                               placeholder="Between 0-1000  mmol/L" min="0" max="1000" required
                                               value="<?= $row['health_type1_level'] ?>">
                                    </div>

                                </div>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary" name="btn-update">Update
                                </button>
                            </div>

                            <div class="form-group">
                                <hr/>
                            </div>

                            <input type="hidden" name="patientID" value="<?= $_SESSION['patientID'] ?>"


                        </div>


                    </form>

                    <?php

                } ?>

            </div>


        </div>

    </div>

    <?php include "../include/footer.php"; ?>
    <script src="../assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    </body>
    </html>
<?php

$db_handle->closeDB();

ob_end_flush(); ?>