<?php
include '../Employee_Database/config.php';

// Handle approval/denial
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $action = $_POST['action'];
    
    if ($action === 'approve') {
        // Get the pending data
        $select_sql = "SELECT * FROM pending_approvals WHERE id = $id";
        $result = $conn->query($select_sql);
        $row = $result->fetch_assoc();
        
        // Insert into employee_data
        $insert_sql = "INSERT INTO employee_data (Fname, Lname, gender, date_birth, Address, position, salary, email, phone) 
                      VALUES ('{$row['Fname']}', '{$row['Lname']}', '{$row['gender']}', '{$row['date_birth']}', 
                              '{$row['Address']}', '{$row['position']}', '{$row['salary']}', '{$row['email']}', '{$row['phone']}')";
        if ($conn->query($insert_sql)) {
            // Update status in pending_approvals
            $update_sql = "UPDATE pending_approvals SET status = 'approved' WHERE id = $id";
            $conn->query($update_sql);
        }
    } else if ($action === 'deny') {
        $update_sql = "UPDATE pending_approvals SET status = 'denied' WHERE id = $id";
        $conn->query($update_sql);
    }
}

// Get pending approvals
$sql = "SELECT * FROM pending_approvals WHERE status = 'pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .approval-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .actions {
            margin-top: 10px;
        }
        .approve-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .deny-btn {
            background-color: #f44336;
            color: white;
            padding: 5px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-left: 10px;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <a href="adminPage.php" class="back-btn">Back to Admin Page</a>
    <h1>Pending Approvals</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="approval-item">
                <h3><?php echo htmlspecialchars($row['Fname']) . ' ' . htmlspecialchars($row['Lname']); ?></h3>
                <p>Position: <?php echo htmlspecialchars($row['position']); ?></p>
                <p>Email: <?php echo htmlspecialchars($row['email']); ?></p>
                <p>Phone: <?php echo htmlspecialchars($row['phone']); ?></p>
                <p>Gender: <?php echo htmlspecialchars($row['gender']); ?></p>
                <p>Date of Birth: <?php echo htmlspecialchars($row['date_birth']); ?></p>
                <p>Address: <?php echo htmlspecialchars($row['Address']); ?></p>
                <p>Salary: <?php echo htmlspecialchars($row['salary']); ?></p>
                
                <div class="actions">
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                        <button type="submit" name="action" value="deny" class="deny-btn">Deny</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No pending approvals.</p>
    <?php endif; ?>
</body>
</html>
