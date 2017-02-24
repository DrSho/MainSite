<?php
error_reporting(0);
ob_start();
session_start();
require_once 'dbcontroller.php';
$userName = "Guest";
$curLoc = basename($_SERVER['PHP_SELF'], ".php");
$error = false;

if (isset($_SESSION['user'])) {

    $userName = $_SESSION['user'];
    $full_name = $_SESSION['userFullName'];
    $email = $_SESSION['userEmail'];
}

if (isset($_POST['btn-submit'])) {

    // prevent sql injections/ clear user invalid inputs
    $full_name = trim($_POST['full_name']);
    $full_name = strip_tags($full_name);
    $full_name = htmlspecialchars($full_name);

    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $comment = trim($_POST['comment']);
    $comment = strip_tags($comment);
    $comment = htmlspecialchars($comment);

    if (empty($full_name)) {
        $error = true;
        $full_nameError = "Please enter your full name.<br>";
    } else if (strlen($full_name) < 3) {
        $error = true;
        $full_nameError = "Name must have at least 3 characters.<br>";
    } else if (!preg_match("/^[a-zA-Z ]+$/", $full_name)) {
        $error = true;
        $full_nameError = "Name must contain alphabets and space.<br>";
    }


    if (empty($email)) {
        $error = true;
        $emailError = "Please enter your email address.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    }


    // if there's no error, continue to submit data
    if (!$error) {

        $result = true;

        if ($result) {

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
        <title>Contact Us</title>
        <?php //include("scripts.php"); ?>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="style.css" type="text/css"/>
    </head>
    <body>


    <div id="wrapper">
        <?php include("navigation.php"); ?>

        <div class="container">

            <?php
            if (!($error) && isset($_POST['btn-submit'])) { ?>
                <div id="login-form">
                    <div class="col-lg-12">
                        <h2>Thank you.</h2>
                        <p>A representative will get back to you as soon as possible.</p>

                        <?php
                        if (!(isset($_SESSION['user']))) {

                        ?>
                        <div class="form-group">
                            <hr/>
                        </div>

                        <div class="form-group">
                            <a href="register.php">Sign Up Here...</a>
                        </div>

                        <?php } ?>

                    </div>
                </div>
                <?php
            } else { ?>

                <div id="login-form">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                          autocomplete="off">

                        <div class="col-lg-12">

                            <div class="form-group">
                                <h2>Contact Us.</h2>
                                <p>We welcome your comments and questions. Please fill out and submit the form below
                                    and we will get back to you as soon as possible.</p>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><span
                                            class="glyphicon glyphicon-user"></span></span>
                                    <input type="text" name="full_name" class="form-control"
                                           placeholder="Enter Your Name"
                                           value="<?php echo $full_name; ?>" maxlength="40"/>
                                </div>
                                <span class="text-danger"><?php echo $full_nameError; ?></span>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><span
                                            class="glyphicon glyphicon-envelope"></span></span>
                                    <input type="email" name="email" class="form-control" placeholder="Enter Your Email"
                                           value="<?php echo $email; ?>" maxlength="40"/>
                                </div>
                                <span class="text-danger"><?php echo $emailError; ?></span>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <label for="comment">Comment:</label>
                                    <textarea class="form-control" cols="55" rows="5" id="comment"></textarea>
                                </div>
                                <span class="text-danger"><?php echo $commentError; ?></span>
                            </div>


                            <div class="form-group">
                                <hr/>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn  btn-primary btn-lg" name="btn-submit" role="button">Submit
                                </button>
                            </div>


                        </div>
                    </form>

                </div>

            <?php } ?>
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