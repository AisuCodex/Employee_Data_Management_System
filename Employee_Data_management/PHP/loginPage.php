<?php
// Include the database connection
include("../database/adminAcc_database.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check current database
$result = $conn->query("SELECT DATABASE()");
$row = $result->fetch_row();
error_log("Current database: " . $row[0]);

// Start employee session with unique name
session_name('employee_session');
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Debug output
    error_log("Login attempt for email: " . $email);

    // Check if employee_logs table exists
    $table_check = $conn->query("SHOW TABLES LIKE 'employee_logs'");
    if ($table_check->num_rows == 0) {
        error_log("WARNING: employee_logs table does not exist!");
    } else {
        error_log("employee_logs table exists");
    }

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM employee_acc WHERE email = ? AND password = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $email, $password);
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            // Check if email and password are correct
            if ($result->num_rows > 0) {
                // Password is correct, set session variables
                $_SESSION['email'] = $email;
                
                // First, update any existing active sessions for this user to logged_out
                $update_sql = "UPDATE employee_logs SET status = 'logged_out', logout_time = NOW() WHERE email = ? AND status = 'active'";
                $update_stmt = $conn->prepare($update_sql);
                if ($update_stmt) {
                    $update_stmt->bind_param("s", $email);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                
                // Now insert the new login record
                $log_sql = "INSERT INTO employee_logs (email, login_time, status) VALUES (?, NOW(), 'active')";
                $log_stmt = $conn->prepare($log_sql);
                if ($log_stmt) {
                    $log_stmt->bind_param("s", $email);
                    if ($log_stmt->execute()) {
                        $_SESSION['login_id'] = $log_stmt->insert_id;
                        error_log("Successfully recorded login with ID: " . $log_stmt->insert_id);
                        
                        // Verify the record was inserted
                        $verify = $conn->query("SELECT * FROM employee_logs WHERE id = " . $log_stmt->insert_id);
                        if ($verify && $verify->num_rows > 0) {
                            error_log("Verified log record exists");
                        } else {
                            error_log("WARNING: Could not verify log record!");
                        }
                    } else {
                        error_log("Failed to record login: " . $log_stmt->error);
                    }
                    $log_stmt->close();
                } else {
                    error_log("Failed to prepare log statement: " . $conn->error);
                }
                
                header("Location: employeePage.php");
                exit();
            } else {
                // Invalid email or password
                $errorMessage = 'Invalid email or password.';
                error_log("Invalid login attempt for email: " . $email);
            }
        } else {
            $errorMessage = 'Database error: ' . $stmt->error;
            error_log("Database error during login: " . $stmt->error);
        }
        $stmt->close();
    } else {
        $errorMessage = 'Database error: ' . $conn->error;
        error_log("Failed to prepare login statement: " . $conn->error);
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>employee Login</title>
  <link rel="stylesheet" href="../CSS/login.css">
  <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css"> <!-- Link to the loading screen CSS -->
</head>
<body>

<!-- Loading screen element -->
<div class="loading-overlay" id="loading-screen">
  <div class="loader"></div>
</div>

<div class="secondContainer">
    <div class="child">
      <div class="backBtn-div">
        <a class="backBtn" href="adminOrEmployee.php" onclick="showLoadingScreen()">X</a>
      </div>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="showLoadingScreen()">
        <h2>Employee Login</h2>
        <div class="inputBox">
          <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>">
          <span id="email-text">Email</span>
        </div>
        <div class="inputBox">
          <input type="password" class="password" id="password" name="password" required>
          <span id="password-text">Password</span>
          <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">👁️</button>
        </div>
        <div class="choice">
          <input class="register" type="submit" name="submit" value="Login">

        </div>
        <!-- Forgot Password button -->
        <div class="forgot">
        <a class="already" href="../PHP/register_process.php" onclick="showLoadingScreen()">Don't have an account?</a>
        </div>

        <br>
      <!-- Show success or error message -->
      <?php if (isset($errorMessage)): ?>
        <div class="error-message">
          <p><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></p>
        </div>
      <?php endif; ?>
      </form>
    </div>
  </div>

  <script>
    // JavaScript for handling the cooldown timer
    document.addEventListener('DOMContentLoaded', function () {
    });

    function showLoadingScreen() {
      var loadingScreen = document.getElementById('loading-screen');
      loadingScreen.style.display = 'flex';
    }

    function togglePasswordVisibility() {
  const passwordInput = document.getElementById('password');
  const toggleButton = document.querySelector('.toggle-password');
  
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    toggleButton.textContent = '✅'; // Change icon to indicate "hide"
  } else {
    passwordInput.type = 'password';
    toggleButton.textContent = '👁️'; // Change icon back to "show"
  }
}

  </script>
</body>
</html>
