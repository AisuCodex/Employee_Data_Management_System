<?php
session_name('employee_session');  // Set a unique session name for employees
session_start();

// Include database connection
include("../database/adminAcc_database.php");

// Check if the employee session cookie exists
if (!isset($_COOKIE['employee_session']) || !isset($_SESSION['email'])) {
    // Redirect to login page if cookie is not set or user is not logged in
    header("Location: loginPage.php");
    exit();
}

// Logout handling
if (isset($_GET['logout'])) {
    $email = $_SESSION['email'];
    
    // Update all active sessions for this user to logged_out
    $update_sql = "UPDATE employee_logs SET status = 'logged_out', logout_time = NOW() WHERE email = ? AND status = 'active'";
    $update_stmt = $conn->prepare($update_sql);
    if ($update_stmt) {
        $update_stmt->bind_param("s", $email);
        $update_stmt->execute();
        $update_stmt->close();
    }

    // Destroy only the employee session
    session_unset();
    session_destroy();

    // Clear the employee session cookie
    if (isset($_COOKIE['employee_session'])) {
        setcookie('employee_session', '', time() - 3600, '/');
    }

    // Close database connection
    $conn->close();

    // Redirect to login page
    header("Location: loginPage.php");
    exit();
}
?>
