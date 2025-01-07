<?php
include '../Employee_Database/config.php';
include 'employeeAuth.php';

// Get user's approval history
$email = $_SESSION['email'];
$approval_sql = "SELECT * FROM pending_approvals WHERE email = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($approval_sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$approval_result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Approval History</title>
    <link rel="stylesheet" href="../CSS/employeePage.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .back-btn-container {
            padding: 1rem;
        }

        .back-btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #556B2F;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #3d4d22;
        }

        .approval-history {
            margin: 2rem;
            padding: 1rem;
        }

        .approval-list {
            display: grid;
            gap: 1rem;
        }

        .approval-item {
            background-color: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .approval-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .position {
            font-weight: bold;
            color: #556B2F;
        }

        .approval-status {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .approval-status.pending {
            background-color: #ffc107;
            color: #000;
        }

        .approval-status.approved {
            background-color: #28a745;
            color: white;
        }

        .approval-status.denied {
            background-color: #dc3545;
            color: white;
        }

        .approval-details {
            font-size: 0.9em;
            color: #666;
        }

        .approval-details > div {
            margin-bottom: 0.5rem;
        }

        .no-history {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        h1 {
            text-align: center;
            color: #556B2F;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <!-- Loading screen element -->
    <div class="loading-overlay" id="loading-screen">
        <div class="loader"></div>
    </div>

    <div class="back-btn-container">
        <a href="employeePage.php" class="back-btn" onclick="showLoadingScreen()">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <h1>Data Approval History</h1>

    <section class="approval-history">
        <div class="approval-list">
            <?php if ($approval_result && $approval_result->num_rows > 0): ?>
                <?php while($row = $approval_result->fetch_assoc()): ?>
                    <div class="approval-item">
                        <div class="approval-header">
                            <span class="position">
                                <i class="fas fa-briefcase"></i>
                                <?php echo htmlspecialchars($row['position']); ?>
                            </span>
                            <div class="approval-status <?php echo $row['status'] ?? 'pending'; ?>">
                                <i class="fas <?php 
                                    echo $row['status'] === 'approved' ? 'fa-check' : 
                                        ($row['status'] === 'denied' ? 'fa-times' : 'fa-clock'); 
                                ?>"></i>
                                Status: <?php echo ucfirst($row['status'] ?? 'pending'); ?>
                            </div>
                        </div>
                        <div class="approval-details">
                            <div>
                                <i class="fas fa-user"></i>
                                Name: <?php echo htmlspecialchars($row['Fname']) . ' ' . htmlspecialchars($row['Lname']); ?>
                            </div>
                            <div>
                                <i class="fas fa-calendar"></i>
                                Submitted: <?php echo date('F j, Y g:i A', strtotime($row['created_at'])); ?>
                            </div>
                            <?php if ($row['action_date']): ?>
                                <div>
                                    <i class="fas fa-clock"></i>
                                    Action taken: <?php echo date('F j, Y g:i A', strtotime($row['action_date'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-history">
                    <i class="fas fa-info-circle"></i>
                    <p>No data approval history found.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script>
        function showLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'flex';
        }
    </script>
</body>
</html>
