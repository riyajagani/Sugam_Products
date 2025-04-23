<?php
// Start session
session_start();

// Clear any existing session data
$_SESSION = array();
session_destroy();

// Redirect to login page
header('Location: login.php');
exit();