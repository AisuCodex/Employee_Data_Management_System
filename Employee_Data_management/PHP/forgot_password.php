<?php
// Include the database connection
include("../database/adminAcc_database.php");

// Start session
session_start();

// Initialize variables
$errorMessage = '';
$successMessage = '';
$showResetPasswordForm = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['code_submit'])) {
        // Retrieve and sanitize the input code
        $inputCode = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);

        // Prepare the query to check if the code exists in the database
        $stmt = $conn->prepare("SELECT * FROM employee_acc WHERE code = ?");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        
        $stmt->bind_param("s", $inputCode);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the code exists
        if ($result->num_rows === 1) {
            // Code exists, display reset password form
            $_SESSION['reset_permission'] = true;
            $_SESSION['reset_code'] = $inputCode;
            $showResetPasswordForm = true;
            $successMessage = 'Code verified. Please reset your password below.';
        } else {
            // Code does not exist
            $errorMessage = 'Invalid recovery code. Please try again.';
        }

        // Close statement
        $stmt->close();
    } elseif (isset($_POST['password_submit']) && isset($_SESSION['reset_permission']) && $_SESSION['reset_permission'] === true) {
        // Handle reset password form submission
        $newPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

        // Validate passwords match
        if ($newPassword !== $confirmPassword) {
            $errorMessage = 'Passwords do not match.';
        } else {
            // Update password in the database
            $stmt = $conn->prepare("UPDATE employee_acc SET password = ? WHERE code = ?");
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }
            
            $stmt->bind_param("ss", $newPassword, $_SESSION['reset_code']);

            if ($stmt->execute()) {
                $successMessage = 'Password successfully reset. Redirecting to login page...';
                // Clear reset permission
                unset($_SESSION['reset_permission']);
                unset($_SESSION['reset_code']);
                // Redirect to login page after 2 seconds
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'loginPage.php';
                    }, 2000);
                </script>";
            } else {
                $errorMessage = 'Failed to reset password. Please try again later.';
            }

            // Close statement
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../CSS/forgotPass.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
</head>
<body>
    <!-- Loading screen -->
    <div class="loading-overlay" id="loading-screen">
        <div class="loader"></div>
    </div>

    <div class="container">
        <div class="form-container">
            <a href="loginPage.php" class="close-btn" onclick="showLoadingScreen()">×</a>
            <h2>Password Recovery</h2>
            
            <?php if (!empty($errorMessage)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($successMessage)): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <?php if (!$showResetPasswordForm): ?>
                <!-- Code verification form -->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="recovery-form">
                    <div class="input-group">
                        <label for="code">Enter Recovery Code:</label>
                        <input type="text" id="code" name="code" required maxlength="6" placeholder="Enter your 6-character code">
                    </div>
                    <button type="submit" name="code_submit" class="submit-btn">Verify Code</button>
                </form>
            <?php else: ?>
                <!-- Password reset form -->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="recovery-form">
                    <div class="input-group">
                        <label for="password">New Password:</label>
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password')">👁️</button>
                    </div>
                    <div class="input-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirm_password')">👁️</button>
                    </div>
                    <button type="submit" name="password_submit" class="submit-btn">Reset Password</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function showLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'flex';
        }
    </script>
</body>
</html>
