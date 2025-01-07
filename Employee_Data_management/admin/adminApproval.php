<?php
include 'adminAuth.php';
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
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssssssdss", 
            $row['Fname'], 
            $row['Lname'], 
            $row['gender'], 
            $row['date_birth'], 
            $row['Address'], 
            $row['position'], 
            $row['salary'], 
            $row['email'], 
            $row['phone']
        );
        
        if ($stmt->execute()) {
            // Update status in pending_approvals
            $update_sql = "UPDATE pending_approvals SET status = 'approved', action_date = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $success_message = "Application approved successfully!";
        } else {
            $error_message = "Error approving application: " . $conn->error;
        }
    } else if ($action === 'deny') {
        $update_sql = "UPDATE pending_approvals SET status = 'denied', action_date = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $success_message = "Application denied successfully!";
        } else {
            $error_message = "Error denying application: " . $conn->error;
        }
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
    <link rel="stylesheet" href="../CSS/leaveRequests.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .approval-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            animation: fadeIn 0.5s ease-out;
            margin-bottom: 20px;
        }

        .approval-card:hover {
            transform: translateY(-5px);
        }

        .approval-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .employee-name {
            font-weight: bold;
            color: var(--darker-shade);
            font-size: 1.2em;
        }

        .employee-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            color: var(--base-color);
        }

        .info-item i {
            margin-right: 8px;
            width: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .approve-btn, .deny-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .approve-btn {
            background-color: #28a745;
            color: white;
        }

        .approve-btn:hover {
            background-color: #218838;
        }

        .deny-btn {
            background-color: #dc3545;
            color: white;
        }

        .deny-btn:hover {
            background-color: #c82333;
        }

        .search-container {
            text-align: center;
            margin: 20px 0;
        }

        .search-bar {
            width: 100%;
            max-width: 500px;
            padding: 12px 20px;
            border: 2px solid var(--base-color);
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-bar:focus {
            border-color: var(--darker-shade);
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .no-approvals {
            text-align: center;
            padding: 40px;
            background-color: white;
            border-radius: 10px;
            grid-column: 1 / -1;
        }

        .no-approvals i {
            font-size: 3em;
            color: var(--base-color);
            margin-bottom: 15px;
        }

        .no-approvals p {
            color: var(--darker-shade);
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <!-- Loading screen -->
    <div class="loading-overlay" id="loading-screen">
        <div class="loader"></div>
    </div>

    <div class="container">
        <header>
            <div class="header-content">
                <a href="adminPage.php" class="back-btn" onclick="showLoadingScreen()">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <h1>Employee Approval Requests</h1>
            </div>
        </header>

        <main>
            <div class="search-container">
                <input type="text" id="searchBar" class="search-bar" placeholder="Search by name or position..." onkeyup="searchApprovals()">
            </div>

            <?php if (isset($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="requests-container">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="approval-card">
                            <div class="approval-header">
                                <span class="employee-name">
                                    <i class="fas fa-user"></i>
                                    <?php echo htmlspecialchars($row['Fname']) . ' ' . htmlspecialchars($row['Lname']); ?>
                                </span>
                                <div class="action-buttons">
                                    <form method="POST" style="display: inline;" onsubmit="return confirmAction(event, 'approve')">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="action" value="approve" class="approve-btn">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form method="POST" style="display: inline;" onsubmit="return confirmAction(event, 'deny')">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="action" value="deny" class="deny-btn">
                                            <i class="fas fa-times"></i> Deny
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="employee-info">
                                <div class="info-item">
                                    <i class="fas fa-briefcase"></i>
                                    <?php echo htmlspecialchars($row['position']); ?>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-envelope"></i>
                                    <?php echo htmlspecialchars($row['email']); ?>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-phone"></i>
                                    <?php echo htmlspecialchars($row['phone']); ?>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-venus-mars"></i>
                                    <?php echo htmlspecialchars($row['gender']); ?>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo htmlspecialchars($row['date_birth']); ?>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($row['Address']); ?>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-money-bill"></i>
                                    ₱<?php echo number_format($row['salary'], 2); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-approvals">
                        <i class="fas fa-clipboard-check"></i>
                        <p>No pending approval requests</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function showLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'flex';
        }

        function confirmAction(event, action) {
            const message = action === 'approve' 
                ? 'Are you sure you want to approve this employee?' 
                : 'Are you sure you want to deny this employee?';
            if (!confirm(message)) {
                event.preventDefault();
                return false;
            }
            showLoadingScreen();
            return true;
        }

        function searchApprovals() {
            const searchText = document.getElementById('searchBar').value.toLowerCase();
            const cards = document.getElementsByClassName('approval-card');

            Array.from(cards).forEach(card => {
                const name = card.querySelector('.employee-name').textContent.toLowerCase();
                const position = card.querySelector('.info-item').textContent.toLowerCase();
                const shouldShow = name.includes(searchText) || position.includes(searchText);
                card.style.display = shouldShow ? '' : 'none';
            });
        }
    </script>
</body>
</html>
