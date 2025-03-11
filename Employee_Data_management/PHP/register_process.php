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
    $fullName = $_POST['full_name'];
    
    if ($password !== $confirmPassword) {
        $errorMessage = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check_sql = "SELECT email FROM employee_acc WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errorMessage = "This email is already registered!";
        } else {
            // Generate a random 6-character code
            $recovery_code = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
            
            // Email doesn't exist, proceed with registration
            $insert_sql = "INSERT INTO employee_acc (full_name, email, password, code) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ssss", $fullName, $email, $password, $recovery_code);
            
            if ($insert_stmt->execute()) {
                echo '
                <div id="code-modal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Registration Successful!</h2>
                        </div>
                        <div class="code-display">
                            <p>Your recovery code is:</p>
                            <div class="code-box">
                                <span class="code">' . $recovery_code . '</span>
                                <button class="copy-btn" onclick="copyCode(\'' . $recovery_code . '\')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <p class="code-warning">Please save this code! You will need it to recover your password if you forget it.</p>
                        <button onclick="redirectToLogin()" class="continue-btn">Continue to Login</button>
                    </div>
                </div>
                <style>
                    :root {
                        --darkest-shade: #2a3417;
                        --darker-shade: #3f4a22;
                        --base-color: #556b2f;
                        --lighter-tint: #758b4d;
                        --lightest-tint: #99b27a;
                        --extra-lightest-tint: #dbe4d0;
                    }

                    .modal {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(42, 52, 23, 0.8);
                        backdrop-filter: blur(10px);
                        -webkit-backdrop-filter: blur(10px);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        z-index: 1000;
                    }

                    .modal-content {
                        background: var(--extra-lightest-tint);
                        padding: 0;
                        border-radius: 15px;
                        position: relative;
                        width: 90%;
                        max-width: 400px;
                        text-align: center;
                        animation: modalAppear 0.3s ease-out;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                        overflow: hidden;
                    }

                    .modal-header {
                        background: linear-gradient(135deg, var(--base-color), var(--lighter-tint));
                        padding: 20px;
                        color: white;
                        margin-bottom: 20px;
                    }

                    .modal-header h2 {
                        color: white;
                        margin: 0;
                        font-size: 1.5em;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                    }

                    @keyframes modalAppear {
                        from {
                            opacity: 0;
                            transform: translateY(-20px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    .code-display {
                        padding: 0 30px;
                        margin: 20px 0;
                    }

                    .code-display p {
                        color: var(--darker-shade);
                        margin-bottom: 10px;
                        font-weight: 500;
                    }

                    .code-box {
                        background: rgba(255, 255, 255, 0.9);
                        padding: 15px;
                        border-radius: 8px;
                        margin: 10px 0;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 10px;
                        border: 2px solid var(--base-color);
                    }

                    .code {
                        font-family: monospace;
                        font-size: 24px;
                        letter-spacing: 2px;
                        color: var(--darker-shade);
                        font-weight: bold;
                    }

                    .copy-btn {
                        background: none;
                        border: none;
                        color: var(--base-color);
                        cursor: pointer;
                        padding: 8px;
                        border-radius: 50%;
                        transition: all 0.3s ease;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .copy-btn:hover {
                        background: var(--extra-lightest-tint);
                        transform: scale(1.1);
                    }

                    .copy-btn i {
                        font-size: 1.2em;
                    }

                    .code-warning {
                        color: #dc3545;
                        font-size: 0.9em;
                        margin: 15px 30px;
                        background: rgba(220, 53, 69, 0.1);
                        padding: 10px;
                        border-radius: 6px;
                    }

                    .continue-btn {
                        background: var(--base-color);
                        color: white;
                        border: none;
                        padding: 12px 30px;
                        border-radius: 50px;
                        cursor: pointer;
                        font-size: 1em;
                        transition: all 0.3s ease;
                        margin: 20px 0 30px;
                        font-weight: 500;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                    }

                    .continue-btn:hover {
                        background: var(--lighter-tint);
                        transform: translateY(-2px);
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                    }
                </style>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
                <script>
                    function copyCode(code) {
                        navigator.clipboard.writeText(code).then(() => {
                            const btn = event.currentTarget;
                            const icon = btn.querySelector("i");
                            const originalClass = icon.className;
                            
                            // Change to checkmark
                            icon.className = "fas fa-check";
                            
                            // Revert back after 1.5 seconds
                            setTimeout(() => {
                                icon.className = originalClass;
                            }, 1500);
                        });
                    }

                    function redirectToLogin() {
                        window.location.href = "loginPage.php";
                    }

                    // Show loading screen during redirect
                    document.querySelector(".continue-btn").addEventListener("click", function() {
                        document.getElementById("loading-screen").style.display = "flex";
                    });
                </script>';
                exit();
            } else {
                $errorMessage = "Error: " . $insert_stmt->error;
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
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
        
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>
        
        <!-- Registration form only displays if there is no success message -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="showLoadingScreen()">
            <h2>Register</h2>
            <div class="inputBox">
                <input type="text" id="full_name" name="full_name" required>
                <span id="fullname-text">Full Name</span>
                <i id="fullname-focus"></i>
            </div>
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
