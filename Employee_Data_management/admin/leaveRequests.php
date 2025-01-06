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
$sql = "SELECT * FROM leave_requests ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check if table exists
if ($result === false) {
    // Table doesn't exist, create it
    $create_table_sql = file_get_contents("../database/create_leave_table.sql");
    if ($conn->multi_query($create_table_sql)) {
        do {
            // Clear out the results
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        
        // Now fetch the requests again
        $sql = "SELECT * FROM leave_requests ORDER BY created_at DESC";
        $result = $conn->query($sql);
    }
}
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
    <style>
        /* Search bar styling */
        .search-container {
            margin: 20px auto;
            text-align: center;
            max-width: 600px;
        }

        .search-bar {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid var(--base-color);
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .search-bar:focus {
            border-color: var(--darker-shade);
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        /* Highlight styling */
        .highlight {
            background-color: #fff3cd;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: bold;
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
                <h1>Leave Requests Management</h1>
            </div>
        </header>

        <main>
            <div class="search-container">
                <input type="text" id="searchBar" class="search-bar" placeholder="Search leave requests..." onkeyup="searchRequests()">
            </div>

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
                        <div class="request-card <?php echo $row['status']; ?>" data-searchable>
                            <div class="request-header">
                                <span class="employee-email searchable"><?php echo htmlspecialchars($row['email']); ?></span>
                                <span class="status-badge <?php echo $row['status']; ?> searchable">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </div>
                            <div class="request-details">
                                <p><strong>Leave Type:</strong> <span class="searchable"><?php echo ucfirst($row['leave_type']); ?></span></p>
                                <p><strong>From:</strong> <span class="searchable"><?php echo $row['start_date']; ?></span></p>
                                <p><strong>To:</strong> <span class="searchable"><?php echo $row['end_date']; ?></span></p>
                                <p><strong>Reason:</strong> <span class="searchable"><?php echo htmlspecialchars($row['reason']); ?></span></p>
                                <p><strong>Submitted:</strong> <span class="searchable"><?php echo date('M d, Y H:i', strtotime($row['created_at'])); ?></span></p>
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

        function searchRequests() {
            const searchText = document.getElementById('searchBar').value.toLowerCase();
            const requestCards = document.querySelectorAll('.request-card[data-searchable]');
            
            requestCards.forEach(card => {
                const searchableElements = card.getElementsByClassName('searchable');
                let found = false;

                // Remove existing highlights
                Array.from(searchableElements).forEach(element => {
                    element.innerHTML = element.textContent;
                });

                if (searchText) {
                    // Check if any element contains the search text
                    Array.from(searchableElements).forEach(element => {
                        const text = element.textContent.toLowerCase();
                        if (text.includes(searchText)) {
                            found = true;
                            // Add highlight
                            element.innerHTML = element.textContent.replace(
                                new RegExp(`(${searchText})`, 'gi'),
                                '<span class="highlight">$1</span>'
                            );
                        }
                    });
                } else {
                    found = true;
                }

                // Show/hide card based on search match
                card.style.display = found ? '' : 'none';
            });
        }
    </script>
</body>
</html>
