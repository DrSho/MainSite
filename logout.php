<?php

	session_start();

	//Check if user is already logged in and redirect
	if (!isset($_SESSION['user']) || !(isset($_SESSION['admin']))) {
		header("Location: index.php");
	} else if(isset($_SESSION['user'])!="") {
		header("Location: dashboard.php");
	} else if(isset($_SESSION['admin'])!="") {
    header("Location: admin/index.php");
}

//Unset and Destroy sessions
if (isset($_GET['logout'])) {
		unset($_SESSION['user']);
		unset($_SESSION['admin']);
		unset($_SESSION['userId']);
		session_unset();
		session_destroy();
		header("Location: index.php");
		exit;
	}