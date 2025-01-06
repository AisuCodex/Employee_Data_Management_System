<?php
// Include the database connection
include("../database/adminAcc_database.php");

// Initialize error and success messages
$errorMessage = '';
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
    } else {
        $sql = "INSERT INTO employee_acc (email, password) VALUES ('$email', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: ../PHP/loginPage.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
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
  <title>Register</title>
  <link rel="stylesheet" href="../CSS/register.css">
  <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
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
      
      <!-- Registration form only displays if there is no success message -->
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="showLoadingScreen()">
        <h2>Register</h2>
        <div class="inputBox">
          <input type="email" id="email" name="email" required>
          <span id="email-text">Email</span>
          <i id="email-focus"></i>
        </div>
        <div class="inputBox">
          <input type="password" id="password" name="password" required>
          <span id="password-text">Password</span>
          <i id="password-focus"></i>
          <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password', this)">👁️</button>
        </div>
        <div class="inputBox">
          <input type="password" id="confirm_password" name="confirm_password" required>
          <span id="confirm-password-text">Confirm Password</span>
          <i id="confirm-password-focus"></i>
          <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirm_password', this)">👁️</button>
        </div>
        <div class="choice">
          <input class="register" type="submit" name="submit" value="Register">
          <a class="already" href="../PHP/loginPage.php" onclick="showLoadingScreen()">Already have an account?</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    function togglePasswordVisibility(fieldId, toggleButton) {
        const passwordField = document.getElementById(fieldId);
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleButton.textContent = '✅'; // Change icon to indicate "hide"
        } else {
            passwordField.type = 'password';
            toggleButton.textContent = '👁️'; // Change icon back to "show"
        }
    }
  </script>
</body>
</html>