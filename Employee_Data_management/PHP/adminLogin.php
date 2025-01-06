<?php
// Include the database connection
include("../database/adminAcc_database.php");

// Initialize error message
$errorMessage = '';

// Start session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize email input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $domain = "gmail.com";
    $domainLength = strlen($domain);

    // Sanitize and validate password input
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate email
    if (substr($email, -$domainLength) !== $domain) {
        $errorMessage = 'Use a email address.';
    }
    // Validate password length
    elseif (strlen($password) < 4) {
        $errorMessage = 'Password must be at least 4 characters long.';
    } 
    else {
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT password FROM admin_acc WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

    // Check if email exists
    if ($stmt->num_rows === 1) {
      $stmt->bind_result($storedPassword);
      $stmt->fetch();

      // Directly compare the passwords
      if ($password === $storedPassword) {
          // Password is correct, set session variable
          $_SESSION['email'] = $email;
          header("Location: ../admin/adminPage.php");
          exit();
      } else {
          $errorMessage = 'Invalid password.';
      }
    } else {
      $errorMessage = 'Email not found.';
    }


        // Close statement
        $stmt->close();
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
  <title>Admin Login</title>
  <link rel="stylesheet" href="../CSS/adminLogin.css">
  <link rel="stylesheet" href="../CSS/Loading_screen.css">
</head>
<body>
<div class="loading-overlay" id="loading-screen">
  <div class="loader"></div>
</div>

<div class="secondContainer">
    <div class="child">
      <div class="backBtn-div">
        <a class="backBtn" href="adminOrEmployee.php" onclick="showLoadingScreen()">X</a>
      </div>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="showLoadingScreen()">
        <h2>Admin Login</h2>
        <div class="inputBox">
          <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>">
          <span id="email-text">Email</span>
          <i id="email-focus"></i>
        </div>
        <div class="inputBox">
          <input type="password" id="password" name="password" required>
          <span id="password-text">Password</span>
          <i id="password-focus"></i>
        </div>
        <div class="choice">
          <input class="register" type="submit" name="submit" value="Login">
        </div>
        <br>
        <?php if (!empty($errorMessage)): ?>
          <div class="error"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></div>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <script src="../JavaScripts/adminLoginFunction.js"></script>
</body>
</html>
