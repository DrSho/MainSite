<?php
error_reporting(0);
ob_start();
session_start();
require_once 'dbcontroller.php';
require_once 'dashboardcontroller.php';
require_once 'accountcontroller.php';
require_once 'commoncontroller.php';

$db_handle = new DBController();
$dashboard = new DashboardController();
$account = new AccountController();
$cct = new CommonController();

$userName = "";
$password = "";
$updatePass = false;
$updateEmail = false;
$email = "";
$error = false;
$minPasswordLength = 6;
$minAddressLength = 3;
$minTextLength = 1;
$minZipCodeLength = 5;

$fname = "";
$mname = "";
$lname = "";
$address = "";
$address2 = "";
$city = "";
$state = "";
$zip = "";
$month = "";
$day = "";
$year = "";
$feet = "";
$inches = "";
$gender = "";
$maleSelected = "";
$femaleSelected = "";

//Current location
$curLoc = "dashboard";

// if session is not set this will redirect to home page
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
} else {
    $userName = $_SESSION['user'];
}


if (!isset($_POST['btn-update'])) {

    $query = "SELECT email FROM user_account WHERE user_account_id='" . $_SESSION['userId'] . "'";
    $result = $db_handle->runQuery($query);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    $email = $row['email'];
    $_SESSION['email'] = $email;

    $query = "SELECT password FROM user_password WHERE user_account_id='" . $_SESSION['userId'] . "'";
    $result = $db_handle->runQuery($query);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    $password = $row['password'];
    $_SESSION['password'] = $password;

    $query = "SELECT * FROM user_address WHERE user_account_id='" . $_SESSION['userId'] . "'";
    $result = $db_handle->runQuery($query);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    $fname = $row['first_name'];
    $mname = $row['middle_name'];
    $lname = $row['last_name'];
    $address = $row['address'];
    $address2 = $row['address2'];
    $city = $row['city'];
    $state = $row['state'];
    $zip = $row['zip'];
    $month = $row['month'];
    $day = $row['day'];
    $year = $row['year'];
    $feet = $row['feet'];
    $inches = $row['inches'];
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

    $newPassword = trim($_POST['password']);
    $newPassword = strip_tags($newPassword);
    $newPassword = htmlspecialchars($newPassword);

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

    $month = $cct->getMonthNumeric($month);

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
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    } else {

        echo "EMAIL: " . $email . " - NEW: " . $newEmail;

        if ($_SESSION['email'] <> $newEmail) {
            // check email exist or not
            $query = "SELECT email FROM user_account WHERE email='$newEmail'";
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
        if ($updateEmail) {
            $query = "UPDATE user_account SET email = '" . $_SESSION['email'] . "' WHERE user_account_id='" . $_SESSION['userId'] . "'";
            $result = $db_handle->updateQuery($query);
        }

        //Insert into user_password table
        if ($updatePass) {
            $query = "UPDATE user_password SET password = '" . $_SESSION['password'] . "' WHERE user_account_id='" . $_SESSION['userId'] . "'";
            $result = $db_handle->updateQuery($query);
        }

        //Insert into user_address table
        $query = "UPDATE user_address
        SET first_name = '$fname', middle_name = '$mname', last_name = '$lname', address = '$address', address2 = '$address2',city = '$city',state = '$state',zip = '$zip',month='$month',day='$day',year='$year',feet='$feet',inches='$inches', gender='$gender' WHERE user_account_id='" . $_SESSION['userId'] . "'";

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


                        <a href="dashboard.php" class="btn  btn-primary " role="button">Dashboard</a>

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


                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"></span>
                                        <input type="text" name="address" class="form-control"
                                               placeholder="Street Address"
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
                                            $cct->statesOptions($state);
                                            ?>

                                        </select>


                                        <input id="zip" type="text" name="zip" class="form-control"
                                               placeholder="Zip Code"
                                               maxlength="10" value="<?php echo $zip ?>" required/>
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

                                            $cct->monthsOptions($month);
                                            ?>
                                        </select>

                                        <select id="day" name="day" class="form-control" required>
                                            <?php
                                            $cct->daysOptions($day);
                                            ?>

                                        </select>

                                        <select id="year" name="year" class="form-control" required>
                                            <?php
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
                                            $cct->feetOptions($feet);

                                            ?>

                                        </select>

                                        <select id="inches" name="inches" class="form-control" required>
                                            <?php
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

                                        <input type="radio" name="gender" <?= $maleSelected ?> value="M"> Male
                                        <input type="radio" name="gender" <?= $femaleSelected ?> value="F"> Female

                                    </div>
                                    <span class="text-danger"><?php echo $address2Error; ?></span>
                                </div>

                            </div>

                            <div class="form-group">
                                <hr/>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-lg btn-primary" name="btn-update">Update
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