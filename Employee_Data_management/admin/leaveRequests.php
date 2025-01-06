<?php
include 'adminAuth.php';
include("../database/adminAcc_database.php");

// Handle approve/deny actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['request_id'])) {
    $action = $_POST['action'];
    $request_id = $_POST['request_id'];
    
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    
    $sql = "UPDATE leave_requests SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $request_id);
    
    if ($stmt->execute()) {
        $success_message = "Request has been " . $status;
    } else {
        $error_message = "Error updating request: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all leave requests
$sql = "SELECT lr.*, ea.email FROM leave_requests lr 
        JOIN employee_acc ea ON lr.email = ea.email 
        ORDER BY lr.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests Management</title>
    <link rel="stylesheet" href="../CSS/leaveRequests.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
                <h1>Leave Requests Management</h1>
            </div>
        </header>

        <main>
            <?php if (isset($success_message)): ?>
                <div class="alert success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert error">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="requests-container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="request-card <?php echo $row['status']; ?>">
                            <div class="request-header">
                                <span class="employee-email"><?php echo htmlspecialchars($row['email']); ?></span>
                                <span class="status-badge <?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </div>
                            <div class="request-details">
                                <p><strong>Leave Type:</strong> <?php echo ucfirst($row['leave_type']); ?></p>
                                <p><strong>From:</strong> <?php echo $row['start_date']; ?></p>
                                <p><strong>To:</strong> <?php echo $row['end_date']; ?></p>
                                <p><strong>Reason:</strong> <?php echo htmlspecialchars($row['reason']); ?></p>
                                <p><strong>Submitted:</strong> <?php echo date('M d, Y H:i', strtotime($row['created_at'])); ?></p>
                            </div>
                            <?php if ($row['status'] === 'pending'): ?>
                                <div class="request-actions">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn approve" onclick="showLoadingScreen()">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="submit" name="action" value="deny" class="btn deny" onclick="showLoadingScreen()">
                                            <i class="fas fa-times"></i> Deny
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-requests">
                        <i class="fas fa-inbox"></i>
                        <p>No leave requests found</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function showLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'flex';
        }
    </script>
</body>
</html>
