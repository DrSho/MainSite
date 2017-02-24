<?php
/**
 * Created by PhpStorm.
 * User: Genesis
 * Date: 2/24/2017
 * Time: 12:31 AM
 */

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

$curLoc = "dashboard";

if(!isset($_GET['id'])){
    header("Location: dashboard.php");
    exit;
}else {

    //Retrieve patient records
    $query = "DELETE FROM user_health_record WHERE id = '" . $_GET['id'] . "'";

    $result = $db_handle->deleteQuery($query);
    header("Location: dashboard.php");
    exit;
}
