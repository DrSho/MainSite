<?php
error_reporting(0);
ob_start();
session_start();
if (isset($_SESSION['user']) != "") {
    header("Location: index.php");
}
include_once 'dbcontroller.php';
include_once 'commoncontroller.php';

$db_handle = new DBController();
$cct = new CommonController();

$curLoc = basename($_SERVER['PHP_SELF'], ".php");
$userName = "Guest";
$error = false;
$minPasswordLength = 6;
$minAddressLength = 3;
$minTextLength = 1;
$minZipCodeLength = 5;

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

    $address = trim($_POST['address']);
    $address = strip_tags($address);
    $address = htmlspecialchars($address);

    $address2 = trim($_POST['address2']);
    $address2 = strip_tags($address2);
    $address2 = htmlspecialchars($address2);

    $city = trim($_POST['city']);
    $city = strip_tags($city);
    $city = htmlspecialchars($city);

    $state = trim($_POST['state']);

    $zip = trim($_POST['zip']);
    $zip = strip_tags($zip);
    $zip = htmlspecialchars($zip);

    $month = trim($_POST['month']);
    $day = trim($_POST['day']);
    $year = trim($_POST['year']);
    $feet = trim($_POST['feet']);
    $inches = trim($_POST['inches']);

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
    if (empty($address)) {
        $error = true;
        $addressError = "Please enter a street address.";
    } else if (strlen($address) < $minAddressLength) {
        $error = true;
        $addressError = "Street address must have at least $minAddressLength characters.";
    }

    // street address2 validation
    if (!empty($address2)) {
        if ((strlen($address2) < $minAddressLength)) {
            $error = true;
            $address2Error = "Street address2 must have at least $minAddressLength characters.";
        }
    }

    // state validation
    if (empty($state) == 'State') {
        $error = true;
        $stateError = "Please select a valid State.";
    }

    // zipcode validation
    if (empty($zip)) {
        $error = true;
        $zipError = "Please enter a zip code.";
    } else if (strlen($zip) < $minZipCodeLength) {
        $error = true;
        $zipError = "Zip code must have at least $minZipCodeLength characters.";
    }

    // month validation
    if (empty($month) || (empty($day)) || (empty($year))) {
        $error = true;
        $dobError = "Please select a valid Date of Birth.";
    }

    // feet validation
    if (empty($feet) || (empty($inches))) {
        $error = true;
        $heightError = "Please select a valid height.";
    }


    // if there's no error, continue to signup
    if (!$error) {

        //Insert into user_account table
        $query = "INSERT INTO user_account(user_account_id,email) VALUES(NULL,'$email')";
        $result = $db_handle->runQuery($query);
       // $result->close();
        //Get last inserted ID

        $result = $db_handle->runQuery("SELECT user_account_id FROM user_account WHERE email='$email'");
        $row = $result->fetch_array();

        //Foreign key
        $uid = $row[0];

        //Insert into user_password table
        $query = "INSERT INTO user_password(user_account_id,password) VALUES('$uid','$password')";
        $result = $db_handle->runQuery($query);
        //$result->close();

        //Insert into user_address table
        $query = "INSERT INTO user_address(user_address_id,first_name,middle_name,last_name,address,address2,city,state,zip,month,day,year,feet,inches,gender,user_account_id)
              VALUES(NULL,'$fname','$mname','$lname','$address','$address2','$city','$state','$zip','$month','$day','$year','$feet','$inches','$gender','$uid')";


        $result = $db_handle->runQuery($query);


        if ($result) {
            $success = true;
            $successMSG = "Successfully registered, you may login now";
            $_SESSION['dob'] = $year."/".$month."/".$day;

            unset($fname);
            unset($email);
            unset($password);
            unset($address);
            unset($address2);
            unset($state);
            unset($city);
            unset($zip);
            unset($month);
            unset($day);
            unset($year);
            unset($feet);
            unset($inches);
        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
        }

       // $result->close();

    }

}
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Dr. Sho - Registration System</title>
        <?php //include("scripts.php"); ?>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="style.css" type="text/css"/>
    </head>
    <body>

    <div id="wrapper">

        <?php include("navigation.php"); ?>

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
                                <h2 class="">Sign Up.</h2>
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
                                           maxlength="15"
                                           required/>
                                </div>
                                <span class="text-danger"><?php echo $passwordError; ?></span>
                            </div>


                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"></span>
                                    <input type="text" name="address" class="form-control" placeholder="Street Address"
                                           maxlength="25" value="<?php echo $address ?>" required/>
                                </div>
                                <span class="text-danger"><?php echo $addressError; ?></span>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"></span>
                                    <input type="text" name="address2" class="form-control"
                                           placeholder="Street Address 2 (e.g. Apt No, Suite No., Unit No.)"
                                           maxlength="25" value="<?php echo $address2 ?>"/>
                                </div>
                                <span class="text-danger"><?php echo $address2Error; ?></span>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"></span>

                                    <input id="city" type="text" name="city" class="form-control" placeholder="City"
                                           maxlength="25" value="<?php echo $city ?>" required/>

                                    <select id="state" name="state" class="form-control" required>
                                        <?php
                                        echo $state;
                                        $cct->statesOptions($state);
                                        ?>

                                    </select>


                                    <input id="zip" type="text" name="zip" class="form-control"
                                           placeholder="Zip Code"
                                            maxlength="5" value="<?php echo $zip ?>" required/>
                                </div>
                                <span class="text-danger"><?php echo $cityError; ?></span>
                                <span class="text-danger"><?php echo $stateError; ?></span>
                                <span class="text-danger"><?php echo $zipError; ?></span>
                            </div>

                            <div class="form-group">
                                <div class="input-group">

                                    Date of Birth:<br>

                                    <select id="month" name="month" class="form-control" required>
                                        <?php
                                        echo $month;
                                        $cct->monthsOptions($month);
                                        ?>
                                    </select>

                                    <select id="day" name="day" class="form-control" required>
                                        <?php
                                        echo $day;
                                        $cct->daysOptions($day);
                                        ?>

                                    </select>

                                    <select id="year" name="year" class="form-control" required>
                                        <?php
                                        echo $year;
                                        $cct->yearsOptions($year);
                                        ?>

                                    </select>

                                </div>
                                <span class="text-danger"><?php echo $dobError; ?></span>
                            </div>

                            <div class="form-group">
                                <div class="input-group">

                                    Height (feet and inches):<br>

                                    <select id="feet" name="feet" class="form-control" required>
                                        <?php
                                        echo $feet;
                                        $cct->feetOptions($feet);

                                        ?>

                                    </select>

                                    <select id="inches" name="inches" class="form-control" required>
                                        <?php
                                        echo $inches;
                                        $cct->inchesOptions($inches);

                                        ?>

                                    </select>

                                </div>
                                <span class="text-danger"><?php echo $heightError; ?></span>
                            </div>


                            <!-- Gender -->
                            <div class="form-group">
                                <div class="input-group">

                                    What is your gender?<br>

                                    <input type="radio" name="gender" <?= $maleSelected ?> value="M" required> Male
                                    <input type="radio" name="gender" <?= $femaleSelected ?> value="F" required> Female

                                </div>
                                <span class="text-danger"><?php echo $address2Error; ?></span>
                            </div>


                            <div class="form-group">
                                <hr/>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-lg btn-primary" name="btn-signup">Sign
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
    <?php include "footer.php"; ?>
    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    </body>
    </html>
<?php ob_end_flush(); ?>