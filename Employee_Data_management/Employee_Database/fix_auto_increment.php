<?php
include 'config.php';

// Alter the table to reset auto-increment to 1
$sql = "ALTER TABLE employee_data AUTO_INCREMENT = 1";

if ($conn->query($sql) === TRUE) {
    echo "Auto-increment value has been reset to 1 successfully";
} else {
    echo "Error updating auto-increment: " . $conn->error;
}

// Redirect back to index page
header("Location: index.php");
?>
