<?php
error_reporting(0);
ob_start();
session_start();

require_once 'dbcontroller.php';

$db_handle = new DBController();

$curLoc = basename($_SERVER['PHP_SELF'], ".php");

if (isset($_SESSION['user'])) {
    $userName = $_SESSION['user'];
} else {
    $userName = "Guest";
}


$error = false;
$email = "";
$password="";

if (isset($_POST['btn-login'])) {

    // prevent sql injections/ clear user invalid inputs
    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);
    // prevent sql injections / clear user invalid inputs

    if (empty($email)) {
        $error = true;
        $emailError = "Please enter your email address.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    }

    if (empty($password)) {
        $error = true;
        $passwordError = "Please enter your password.";
    }

    // if there's no error, continue to login
    if (!$error) {

        $password = hash('sha256', $password); // password hashing using SHA256

        //Retrieve email
        $query = "SELECT * FROM user_account
                    JOIN user_password
                    ON user_account.user_account_id = user_password.user_account_id
                    WHERE user_account.email='$email'";

        $result = $db_handle->runQuery($query);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $count = $db_handle->numRows($query); // if uname/pass correct it returns must be 1 row


        //Compare the results
        if ($count == 1) {

            $_SESSION['userId'] = $row['user_account_id'];
            $_SESSION['userEmail'] = $row['email'];


            header("Location: dashboard.php");
        } else {
            $errMSG = "Incorrect Credentials, Try again...";
        }
    }

}
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Dr. Sho - Login & Registration System</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="style.css" type="text/css"/>
    </head>
    <body>

    <div id="wrapper">
        <?php include("navigation.php") ?>

        <div class="container">

        <div id="login-form">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

                <div class="col-md-12">

                    <div class="form-group">
                        <h2 class="">Sign In.</h2>
                    </div>

                    <div class="form-group">
                        <hr/>
                    </div>

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

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                            <input type="email" name="email" class="form-control" placeholder="Your Email"
                                   value="<?php echo $email; ?>" maxlength="40"/>
                        </div>
                        <span class="text-danger"><?php echo $emailError; ?></span>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" name="password" class="form-control" placeholder="Your Password"
                                   maxlength="15"/>
                        </div>
                        <span class="text-danger"><?php echo $passwordError; ?></span>
                    </div>

                    <div class="form-group">
                        <hr/>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary" name="btn-login">Sign In</button>
                    </div>

                    <div class="form-group">
                        <hr/>
                    </div>

                    <div class="form-group">
                        <a href="register.php">Sign Up Here...</a>
                    </div>

                </div>

            </form>
        </div>
    </div>

    <?php include "footer.php"; ?>

    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    </body>
    </html>
<?php ob_end_flush(); ?>