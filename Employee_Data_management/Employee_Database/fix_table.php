<?php
include 'config.php';

// Drop the existing table if it exists
$sql = "DROP TABLE IF EXISTS employee_data";
$conn->query($sql);

// Create the table with proper auto-increment starting from 1
$sql = "CREATE TABLE employee_data (
    id INT PRIMARY KEY AUTO_INCREMENT,
    Fname VARCHAR(50) NOT NULL,
    Lname VARCHAR(50) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    date_birth DATE NOT NULL,
    position VARCHAR(50) NOT NULL,
    salary DECIMAL(10,2) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL
) AUTO_INCREMENT = 1";

if ($conn->query($sql) === TRUE) {
    echo "Table recreated successfully with auto-increment starting from 1";
} else {
    echo "Error creating table: " . $conn->error;
}

// Redirect back to index page after 3 seconds
header("refresh:3;url=index.php");
?>
