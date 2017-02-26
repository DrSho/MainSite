<?php
error_reporting(0);
ob_start();
session_start();
include_once("../config.php");
require_once '../include/dbcontroller.php';
require_once '../include/dashboardcontroller.php';
require_once '../include/accountcontroller.php';
require_once '../include/commoncontroller.php';
require_once 'include/admincontroller.php';

$db_handle = new DBController();
$dashboard = new DashboardController();
$account = new AccountController();
$cct = new CommonController();

$updatePass = false;
$updateEmail = false;
$error = false;
$minPasswordLength = 6;
$minAddressLength = 3;
$minTextLength = 1;
$minZipCodeLength = 5;

//Current location
$curLoc = "dashboard";

// if session is not set this will redirect to home page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit;
} else {
    $userName = $_SESSION['user'];
}


if (!isset($_POST['btn-update'])) {

    $query = "SELECT * FROM admin WHERE id='" . $_SESSION['userId'] . "'";
    $result = $db_handle->runQuery($query);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    $email = $row['email'];
    $_SESSION['email'] = $email;
    $password = $row['password'];
    $_SESSION['password'] = $password;
    $fname = $row['first_name'];
    $mname = $row['middle_name'];
    $lname = $row['last_name'];
    $title = $row['title'];
    $gender = $row['gender'];
    $gender == "M" ? $maleSelected = " checked " : $femaleSelected = " checked ";

} else {

    // clean user inputs to prevent sql injections
    $fname = trim($_POST['fname']);
    $fname = strip_tags($fname);
    $fname = htmlspecialchars($fname);

    $mname = trim($_POST['mname']);
    $mname = strip_tags($mname);
    $mname = htmlspecialchars($mname);

    $lname = trim($_POST['lname']);
    $lname = strip_tags($lname);
    $lname = htmlspecialchars($lname);

    $newEmail = trim($_POST['email']);
    $newEmail = strip_tags($newEmail);
    $newEmail = htmlspecialchars($newEmail);

    $title = trim($_POST['title']);
    $title = strip_tags($title);
    $title = htmlspecialchars($title);

    $newPassword = trim($_POST['password']);
    $newPassword = strip_tags($newPassword);
    $newPassword = htmlspecialchars($newPassword);

    $gender = trim($_POST['gender']);
    $gender == "M" ? $maleSelected = " checked " : $femaleSelected = " checked ";


    // basic first name validation
    if (empty($fname)) {
        $error = true;
        $fnameError = "Please enter your first name.<br>";
    } else if (strlen($fname) < $minTextLength) {
        $error = true;
        $fnameError = "First name must have at least 3 characters.<br>";
    } else if (!preg_match("/^[a-zA-Z ]+$/", $fname)) {
        $error = true;
        $fnameError = "First name must contain alphabets and space.<br>";
    }

    // basic middle name validation
    if (!empty($mname)) {
        if (!preg_match("/^[a-zA-Z ]+$/", $mname)) {
            $error = true;
            $mnameError = "Middle name must contain alphabets and space.<br>";
        }
    }

    // basic last name validation
    if (empty($lname)) {
        $error = true;
        $lnameError = "Please enter your last name.<br>";
    } else if (strlen($lname) < $minTextLength) {
        $error = true;
        $lnameError = "Last name must have at least 3 character.<br>";
    } else if (!preg_match("/^[a-zA-Z ]+$/", $lname)) {
        $error = true;
        $lnameError = "Last name must contain alphabets and space.<br>";
    }

    //basic email validation
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    } else {

        if ($_SESSION['email'] <> $newEmail) {
            // check email exist or not
            $query = "SELECT email FROM admin WHERE email='$newEmail'";
            $count = $db_handle->numRows($query);
            //$count = mysqli_num_rows($result);
            if ($count != 0) {
                $error = true;
                $emailError = "Provided Email is already in use.";
            } else {
                $_SESSION['email'] = $newEmail;
                $updateEmail = true;
            }
        }
    }


    // password validation
    if ($_SESSION['password'] != $newPassword) {
        $_SESSION['password'] = hash('sha256', $newPassword);
        $updatePass = true;
    }

    // feet validation
    if (empty($title)) {
        $error = true;
        $titleError = "Please enter a valid title.";
    }


    // if there's no error, continue to signup
    if (!$error) {

        //Insert into user_account table
        if ($updateEmail) {
            $query = "UPDATE admin SET email = '" . $_SESSION['email'] . "' WHERE id ='" . $_SESSION['userId'] . "'";
            $result = $db_handle->updateQuery($query);
        }

        //Insert into user_password table
        if ($updatePass) {
            $query = "UPDATE admin SET password = '" . $_SESSION['password'] . "' WHERE id ='" . $_SESSION['userId'] . "'";
            $result = $db_handle->updateQuery($query);
        }

        //Insert into user_address table
        $query = "UPDATE admin SET first_name = '$fname', middle_name = '$mname', last_name = '$lname', title='$title', gender='$gender' WHERE id ='" . $_SESSION['userId'] . "'";

        $result = $db_handle->updateQuery($query);

        if ($result) {
            $success = true;
            $successMSG = "Successfully updated!";
            $_SESSION['dob'] = $year . "/" . $month . "/" . $day;

        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
        }

    }

}
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Account Information</title>
        <?php //include("scripts.php"); ?>
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="../style.css" type="text/css"/>
        <link rel="stylesheet" href="admin.css" type="text/css"/>
    </head>
    <body>

    <div id="wrapper">

        <?php include("include/navigation.php"); ?>

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

                    </div>
                    <?php
                } else {

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


                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                          autocomplete="off">

                        <div class="col-md-18">


                            <div class="form-group">
                                <h2 class=""><?= $userName ?>'s Profile</h2>
                                <p>Make any changes and click Update.</p>
                            </div>

                            <div class="form-group">
                                <hr/>
                            </div>

                            <div class="well">


                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"></span>
                                        <input id="fname" type="text" name="fname" class="form-control"
                                               placeholder="First Name"
                                               maxlength="50" value="<?php echo $fname ?>" required/>

                                        <input id="mname" type="text" name="mname" class="form-control"
                                               placeholder="Middle"
                                               maxlength="5" value="<?php echo $mname ?>"/>

                                        <input id="lname" type="text" name="lname" class="form-control"
                                               placeholder="Last Name"
                                               maxlength="50" value="<?php echo $lname ?>" required/>
                                    </div>
                                    <span class="text-danger"><?php echo $fnameError; ?></span>
                                    <span class="text-danger"><?php echo $mnameError; ?></span>
                                    <span class="text-danger"><?php echo $lnameError; ?></span>

                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"></span>
                                        <input type="email" name="email" class="form-control"
                                               placeholder="Your Email (your.name@domain.com)"
                                               maxlength="40" value="<?php echo $email ?>" required/>
                                    </div>
                                    <span class="text-danger"><?php echo $emailError; ?></span>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"></span>
                                        <input type="password" name="password" class="form-control"
                                               placeholder="Password (min <?= $minPasswordLength ?> characters)"
                                               maxlength="15"
                                               required/>
                                    </div>
                                    <span class="text-danger"><?php echo $passwordError; ?></span>
                                </div>

                                <!-- Title -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"></span>
                                        <input type="text" name="title" class="form-control"
                                               placeholder="Your Title (e.g. Dr., RN, NP, Nurse)"
                                               maxlength="25" value="<?php echo $title ?>"/>
                                    </div>
                                    <span class="text-danger"><?php echo $titleError; ?></span>
                                </div>


                                <!-- Gender -->
                                <div class="form-group">
                                    <div class="input-group">

                                        What is your gender?<br>

                                        <input type="radio" name="gender" <?= $maleSelected ?> value="M"> Male
                                        <input type="radio" name="gender" <?= $femaleSelected ?> value="F"> Female

                                    </div>
                                    <span class="text-danger"><?php echo $genderError; ?></span>
                                </div>

                            </div>

                            <div class="form-group">
                                <hr/>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-lg btn-warning" name="btn-update">Update
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

    <?php include "../include/footer.php"; ?>
    <script src="../assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    </body>
    </html>
<?php ob_end_flush(); ?>