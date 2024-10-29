<?php
session_start();
// Check if the session cookie exists
if (!isset($_COOKIE['PHPSESSID']) || !isset($_SESSION['email'])) {
    // Redirect to login page if cookie is not set or user is not logged in
    header("Location: ../PHP/adminLogin.php");
    exit();
}

// Logout handling
if (isset($_GET['logout'])) {
    // Destroy the session
    session_unset();
    session_destroy();

    // Clear the session cookie
    if (isset($_COOKIE['PHPSESSID'])) {
        // Set the cookie expiration time to a past date to delete it
        setcookie('PHPSESSID', '', time() - 3600, '/'); // Adjust the path if necessary
    }

    // Redirect to login page
    header("Location: ../PHP/adminLogin.php");
    exit();
}
?>