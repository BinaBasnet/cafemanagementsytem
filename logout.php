<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Optionally, redirect to login page or home
header("Location: login.php");
exit();
?>
