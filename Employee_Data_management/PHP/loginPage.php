<?php
// Include the database connection
include("../database/adminAcc_database.php");

// Start employee session with unique name
session_name('employee_session');
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM employee_acc WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if email and password are correct
    if ($result->num_rows > 0) {
        // Password is correct, set session variable
        $_SESSION['email'] = $email;
        header("Location: employeePage.php");
        exit();
    } else {
        // Invalid email or password
        $errorMessage = 'Invalid email or password.';
    }

    // Close statement
    $stmt->close();
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
        <a class="backBtn" href="adminOrStudent.php" onclick="showLoadingScreen()">X</a>
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
