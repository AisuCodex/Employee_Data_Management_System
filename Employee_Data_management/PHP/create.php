<?php
include '../Employee_Database/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Fname = $_POST['Fname'];
    $Lname = $_POST['Lname'];
    $gender = $_POST['gender'];
    $date_birth = $_POST['date_birth'];
    $Address = $_POST['Address'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    
    // Check for negative salary
    if ($salary < 0) {
        echo "<script>alert('Salary cannot be negative!'); window.history.back();</script>";
        exit();
    }

    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check if email already exists in employee_data table
    $check_sql = "SELECT email FROM employee_data WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);

    if (!$check_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('This email is already registered!'); window.history.back();</script>";
        exit();
    }

    // Corrected SQL statement
    $sql = "INSERT INTO pending_approvals (Fname, Lname, gender, date_birth, Address, position, salary, email, phone) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($sql);

    if (!$insert_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $insert_stmt->bind_param("ssssssiss", $Fname, $Lname, $gender, $date_birth, $Address, $position, $salary, $email, $phone);

    if ($insert_stmt->execute()) {
        echo "<script>alert('Your information has been submitted for approval.'); window.location.href='employeePage.php';</script>";
        exit();
    } else {
        echo "Error: " . $insert_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add your Info</title>
    <link rel="stylesheet" href="../CSS/Create.css">
</head>
<body>
    <div class="back-btn-container">
        <a class="back-btn" href="employeePage.php">Back</a>
    </div>
    <h1>Add your Info</h1>
    <form method="POST">
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="Fname" required><br>

        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="Lname" required><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>

        <label for="date_birth">Date of Birth:</label>
        <input type="date" id="date_birth" name="date_birth" required><br>

        <label for="Address">Address:</label>
        <input type="text" id="Address" name="Address" required><br>

        <label for="position">Position:</label>
        <input type="text" id="position" name="position" required><br>

        <label for="salary">Salary:</label>
        <input type="number" id="salary" name="salary" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" required><br>

        <button type="submit">Send info</button>
    </form>
</body>
</html>
