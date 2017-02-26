<?php
error_reporting(0);
ob_start();
session_start();
if (isset($_SESSION['admin']) != "") {
    header("Location: index.php");
}
include_once("../config.php");
include_once '../include/dbcontroller.php';
include_once '../include/commoncontroller.php';

$db_handle = new DBController();
$cct = new CommonController();

$curLoc = basename($_SERVER['PHP_SELF'], ".php");
$userName = "Guest";
$error = false;
$minPasswordLength = 6;
$minTitleLength = 2;

if (isset($_POST['btn-signup'])) {

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

    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);

    $title = trim($_POST['title']);
    $title = strip_tags($title);
    $title = htmlspecialchars($title);

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
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    } else {
        // check email exist or not
        $query = "SELECT email FROM user_account WHERE email='$email'";
        $result = $db_handle->runQuery($query);
        $count = mysqli_num_rows($result);
        if ($count != 0) {
            $error = true;
            $emailError = "Provided Email is already in use.";
        }
    }

    // password validation
    if (empty($password)) {
        $error = true;
        $passwordError = "Please enter password.";
    } else if (strlen($password) < $minPasswordLength) {
        $error = true;
        $passwordError = "Password must have at least $minPasswordLength characters.";
    }


    // password encrypt using SHA256();
    $password = hash('sha256', $password);

    // street address1 validation
    if (empty($title)) {
        $error = true;
        $addressError = "Please enter your title.";
    } else if (strlen($title) < $minTitleLength) {
        $error = true;
        $titleError = "Title must have at least $minTitleLength characters.";
    }

    // if there's no error, continue to signup
    if (!$error) {

        //Insert into user_account table
        $query = "INSERT INTO admin (id,email,password,first_name,middle_name,last_name,title,gender) VALUES(NULL,'$email','$password','$fname','$mname','$lname','$title','$gender')";
        $result = $db_handle->runQuery($query);

        //Get last inserted ID

        $result = $db_handle->runQuery("SELECT id FROM admin WHERE email='$email'");
        $row = $result->fetch_array();

        $uid = $row[0];

        if ($result) {
            $success = true;
            $successMSG = "Successfully registered, you may login now";

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
        <title>Dr. Sho - Admin Registration System</title>
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

                        <a href="login.php" class="btn  btn-primary " role="button">Login</a>

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

                        <div class="col-md-12">


                            <div class="form-group">
                                <h2 class="">Admin Sign Up</h2>
                            </div>

                            <div class="form-group">
                                <hr/>
                            </div>


                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"></span>
                                    <input id="fname" type="text" name="fname" class="form-control"
                                           placeholder="First Name"
                                           maxlength="50" value="<?php echo $fname ?>" required/>

                                    <input id="mname" type="text" name="mname" class="form-control" placeholder="Middle"
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
                                           maxlength="50"
                                           required/>
                                </div>
                                <span class="text-danger"><?php echo $passwordError; ?></span>
                            </div>


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

                                    What is your gender?

                                    <input type="radio" name="gender" <?= $maleSelected ?> value="M" required> Male
                                    <input type="radio" name="gender" <?= $femaleSelected ?> value="F" required> Female

                                </div>
                            </div>


                            <div class="form-group">
                                <hr/>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-lg btn-warning" name="btn-signup">Sign
                                    Up
                                </button>
                            </div>

                            <div class="form-group">
                                <hr/>
                            </div>

                            <div class="form-group">
                                <a href="login.php">Already registered? Sign in Here...</a>
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