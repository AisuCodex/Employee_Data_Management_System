<?php
include("employeeAuth.php");
include("../database/adminAcc_database.php");

// Handle leave message submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_leave'])) {
    $email = $_SESSION['email'];
    $leave_type = mysqli_real_escape_string($conn, $_POST['leave_type']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    
    $sql = "INSERT INTO leave_requests (email, leave_type, start_date, end_date, reason) 
            VALUES (?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $email, $leave_type, $start_date, $end_date, $reason);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Leave request submitted successfully!";
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
    }
    $stmt->close();
    
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
                <h2>Leave History</h2>
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
            </section>
            
            <div class="card">
                <i class="fas fa-bullhorn"></i>
                <h3>Announcements</h3>
                <p>View company announcements</p>
                <a href="viewAnnouncements.php" class="view-btn" onclick="showLoadingScreen()">View</a>
            </div>
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
