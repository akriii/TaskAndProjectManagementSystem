<?php
session_start(); // Start session to access session variables

// Unset all session variables
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Destroy the session
session_destroy();

// Redirect to login page (or homepage)

header("Location: indexLogin.php"); // Change to your login page
exit;
?>
