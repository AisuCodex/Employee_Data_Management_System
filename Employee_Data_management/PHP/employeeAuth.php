<?php
session_name('employee_session');  // Set a unique session name for employees
session_start();
// Check if the employee session cookie exists
if (!isset($_COOKIE['employee_session']) || !isset($_SESSION['email'])) {
    // Redirect to login page if cookie is not set or user is not logged in
    header("Location: loginPage.php");
    exit();
}

// Logout handling
if (isset($_GET['logout'])) {
    // Destroy only the employee session
    session_unset();
    session_destroy();

    // Clear the employee session cookie
    if (isset($_COOKIE['employee_session'])) {
        setcookie('employee_session', '', time() - 3600, '/');
    }

    // Redirect to login page
    header("Location: loginPage.php");
    exit();
}
?>
