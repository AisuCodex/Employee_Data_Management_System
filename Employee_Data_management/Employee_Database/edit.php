<?php
include 'config.php';

if (isset($_GET['id'])) {
    $employee_id = (int)$_GET['id'];

    // Fetch the existing data for the employee
    $sql = "SELECT * FROM employee_data WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
    } else {
        echo "Employee not found.";
        exit;
    }

    // Handle form submission for updating the employee
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $gender = $_POST['gender'];
        $date_birth = $_POST['date_birth'];
        $position = $_POST['position'];
        $salary = $_POST['salary'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $update_sql = "UPDATE employee_data SET Fname = ?, Lname = ?, gender = ?, date_birth = ?, position = ?, salary = ?, email = ?, phone = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        
        if ($update_stmt === false) {
            die("Error preparing the update statement: " . $conn->error);
        }

        $update_stmt->bind_param("ssssssssi", $fname, $lname, $gender, $date_birth, $position, $salary, $email, $phone, $employee_id);

        if ($update_stmt->execute()) {
            echo "Employee updated successfully.";
            header("Location: employeeData.php");
            exit;
        } else {
            echo "Error updating employee: " . $update_stmt->error; // Changed to $update_stmt for more specific error
        }
    }
} else {
    echo "No employee ID specified.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="../CSS/Edit.css">
</head>
<body>
    <div class="back-btn-container">
        <a class="back-btn" href="../Employee_Database/index.php">Back</a>
    </div>
    <h1>Edit Employee</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $employee_id; ?>">
        <label for="fname">First Name:</label>
        <input type="text" name="fname" id="fname" value="<?php echo htmlspecialchars($employee['Fname'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="lname">Last Name:</label>
        <input type="text" name="lname" id="lname" value="<?php echo htmlspecialchars($employee['Lname'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="gender">Gender:</label>
        <input type="text" name="gender" id="gender" value="<?php echo htmlspecialchars($employee['gender'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="date_birth">Date of Birth:</label>
        <input type="date" name="date_birth" id="date_birth" value="<?php echo htmlspecialchars($employee['date_birth'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="position">Position:</label>
        <input type="text" name="position" id="position" value="<?php echo htmlspecialchars($employee['position'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="salary">Salary:</label>
        <input type="number" name="salary" id="salary" value="<?php echo htmlspecialchars($employee['salary'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($employee['email'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="phone">Phone:</label>
        <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($employee['phone'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <button type="submit" name="update_employee">Update Employee</button>
    </form>
</body>
</html>
