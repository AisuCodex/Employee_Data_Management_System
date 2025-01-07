<?php
include("employeeAuth.php");
include("../database/adminAcc_database.php");

// Handle leave message submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_leave'])) {
    $email = $_SESSION['email'];
    
    // Get employee ID
    $get_emp_id = "SELECT id FROM employee_data WHERE email = ?";
    $stmt = $conn->prepare($get_emp_id);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $emp_result = $stmt->get_result();
    $employee = $emp_result->fetch_assoc();
    $employee_id = $employee['id'];
    $stmt->close();
    
    $leave_type = mysqli_real_escape_string($conn, $_POST['leave_type']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert leave request
        $sql = "INSERT INTO leave_requests (email, leave_type, start_date, end_date, reason) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $email, $leave_type, $start_date, $end_date, $reason);
        $stmt->execute();
        $stmt->close();
        
        // Commit transaction
        $conn->commit();
        $_SESSION['success_message'] = "Leave request submitted successfully!";
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Get messages from session
$success_message = '';
$error_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Get user's leave history
$email = $_SESSION['email'];
$sql = "SELECT * FROM leave_requests WHERE email = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="../CSS/employeePage.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <!-- Loading screen element -->
    <div class="loading-overlay" id="loading-screen">
        <div class="loader"></div>
    </div>

    <div class="container">
        <header>
            <h1>Employee Dashboard</h1>
            <a href="#" id="logout-link" class="logout-btn">Logout <i class="fa-solid fa-right-from-bracket"></i></a>
        </header>

       <div class="card-container">

       <div class="card">
                <i class="fas fa-bullhorn"></i>
                <h3>Announcements</h3>
                <p>View company announcements</p>
                <a href="viewAnnouncements.php" class="view-btn" onclick="showLoadingScreen()">View</a>
            </div>

            <div class="card">
            <i class="fa-solid fa-clipboard-list"></i>
                <h3>Add my Info</h3>
                <p>Add your Info here</p>
                <a href="../PHP/create.php" class="view-btn" onclick="showLoadingScreen()">Add</a>
            </div>

       </div>


        <main>
            <section class="leave-request-form">
                <h2>Submit Leave Request</h2>
                <form method="POST" action="" onsubmit="showLoadingScreen()">
                    <div class="form-group">
                        <label for="leave_type">Leave Type:</label>
                        <select name="leave_type" id="leave_type" required>
                            <option value="vacation">Vacation Leave</option>
                            <option value="sick">Sick Leave</option>
                            <option value="personal">Personal Leave</option>
                            <option value="emergency">Emergency Leave</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" name="start_date" id="start_date" required>
                    </div>

                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" name="end_date" id="end_date" required>
                    </div>

                    <div class="form-group">
                        <label for="reason">Reason:</label>
                        <textarea name="reason" id="reason" required></textarea>
                    </div>

                    <button type="submit" name="submit_leave" class="submit-btn">Submit Leave Request</button>
                </form>

                <?php if ($success_message): ?>
                    <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
            </section>

            <section class="leave-history">
                <h2>History</h2>
                <div class="history-tabs">
                    <button class="tab-btn active" onclick="showTab('leave')">Leave Requests</button>
                    <button class="tab-btn" onclick="showTab('approval')">Data Approvals</button>
                </div>

                <div id="leave-history" class="tab-content active">
                    <div class="leave-list">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <div class="leave-item">
                                    <div class="leave-type"><?php echo ucfirst($row['leave_type']); ?> Leave</div>
                                    <div class="leave-dates">
                                        From: <?php echo $row['start_date']; ?><br>
                                        To: <?php echo $row['end_date']; ?>
                                    </div>
                                    <div class="leave-status <?php echo $row['status']; ?>">
                                        Status: <?php echo ucfirst($row['status']); ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No leave history found.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="approval-history" class="tab-content">
                    <div class="approval-list">
                        <?php
                        // Get user's approval history
                        $email = $_SESSION['email'];
                        $approval_sql = "SELECT * FROM pending_approvals WHERE email = ? ORDER BY created_at DESC";
                        $stmt = $conn->prepare($approval_sql);
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $approval_result = $stmt->get_result();
                        $stmt->close();

                        if ($approval_result && $approval_result->num_rows > 0):
                            while($row = $approval_result->fetch_assoc()):
                        ?>
                            <div class="approval-item">
                                <div class="approval-header">
                                    <span class="position"><?php echo htmlspecialchars($row['position']); ?></span>
                                    <div class="approval-status <?php echo $row['status']; ?>">
                                        Status: <?php echo ucfirst($row['status']); ?>
                                    </div>
                                </div>
                                <div class="approval-details">
                                    <div>Name: <?php echo htmlspecialchars($row['Fname']) . ' ' . htmlspecialchars($row['Lname']); ?></div>
                                    <div>Submitted: <?php echo date('F j, Y g:i A', strtotime($row['created_at'])); ?></div>
                                    <?php if ($row['action_date']): ?>
                                        <div>Action taken: <?php echo date('F j, Y g:i A', strtotime($row['action_date'])); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <p>No approval history found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <style>
                .history-tabs {
                    display: flex;
                    gap: 10px;
                    margin-bottom: 20px;
                }

                .tab-btn {
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    background-color: #808080;  
                    color: white;  
                    transition: all 0.3s ease;
                }

                .tab-btn:hover {
                    opacity: 0.9;
                }

                .tab-btn.active {
                    background-color: #556B2F;  
                    color: white;
                }

                .tab-content {
                    display: none;
                }

                .tab-content.active {
                    display: block;
                }

                .approval-item {
                    background-color: white;
                    border-radius: 8px;
                    padding: 15px;
                    margin-bottom: 15px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }

                .approval-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 10px;
                }

                .position {
                    font-weight: bold;
                    color: var(--primary-color);
                }

                .approval-status {
                    padding: 5px 10px;
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
                    margin-bottom: 5px;
                }
            </style>

            <script>
                function showTab(tabName) {
                    // Hide all tab contents
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('active');
                    });

                    // Remove active class from all buttons
                    document.querySelectorAll('.tab-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    // Show selected tab content
                    document.getElementById(tabName + '-history').classList.add('active');
                    
                    // Add active class to clicked button
                    event.target.classList.add('active');
                }
            </script>
        </main>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Logout Confirmation</h2>
            <p>Are you sure you want to log out?</p>
            <button id="confirm-logout">Yes, Log Out</button>
            <button id="cancel-logout" class="cancel">Cancel</button>
        </div>
    </div>

    <footer class="footer">
        <p style="margin: 0;">System created by  <span class="creator-name">Fatima Palisoc</span></p>
    </footer>

    <script>
        function showLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'flex';
        }

        // Get the modal and buttons
        var modal = document.getElementById('logout-modal');
        var logoutLink = document.getElementById('logout-link');
        var closeBtn = document.querySelector('.modal .close');
        var confirmLogoutBtn = document.getElementById('confirm-logout');
        var cancelLogoutBtn = document.getElementById('cancel-logout');

        // Show modal when logout is clicked
        logoutLink.onclick = function(event) {
            event.preventDefault();
            modal.style.display = 'block';
        }

        // Close modal when X is clicked
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }

        // Close modal when Cancel is clicked
        cancelLogoutBtn.onclick = function() {
            modal.style.display = 'none';
        }

        // Handle logout confirmation
        confirmLogoutBtn.onclick = function() {
            showLoadingScreen();
            window.location.href = 'employeePage.php?logout=1';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
