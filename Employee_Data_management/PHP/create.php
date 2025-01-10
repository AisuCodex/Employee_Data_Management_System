<?php
include '../Employee_Database/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Fname = $_POST['Fname'];
    $Lname = $_POST['Lname'];
    $gender = $_POST['gender'];
    $date_birth = $_POST['date_birth'];
    $Address = $_POST['Address'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    
    // Check for negative salary
    if ($salary < 0) {
        echo '
        <div id="error-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header error">
                    <h2>Error</h2>
                </div>
                <div class="modal-body">
                    <i class="fas fa-exclamation-circle error-icon"></i>
                    <p>Salary cannot be negative!</p>
                    <button onclick="goBack()" class="continue-btn">Go Back</button>
                </div>
            </div>
        </div>';
        include_modal_styles();
        exit();
    }

    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check if email already exists in employee_data table
    $check_sql = "SELECT email FROM employee_data WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);

    if (!$check_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo '
        <div id="error-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header error">
                    <h2>Error</h2>
                </div>
                <div class="modal-body">
                    <i class="fas fa-exclamation-circle error-icon"></i>
                    <p>This email is already registered!</p>
                    <button onclick="goBack()" class="continue-btn">Go Back</button>
                </div>
            </div>
        </div>';
        include_modal_styles();
        exit();
    }

    // Corrected SQL statement
    $sql = "INSERT INTO pending_approvals (Fname, Lname, gender, date_birth, Address, position, salary, email, phone) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($sql);

    if (!$insert_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $insert_stmt->bind_param("ssssssiss", $Fname, $Lname, $gender, $date_birth, $Address, $position, $salary, $email, $phone);

    if ($insert_stmt->execute()) {
        echo '
        <div id="success-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header success">
                    <h2>Success</h2>
                </div>
                <div class="modal-body">
                    <i class="fas fa-check-circle success-icon"></i>
                    <p>Your information has been submitted for approval.</p>
                    <button onclick="redirectToEmployeePage()" class="continue-btn">Continue</button>
                </div>
            </div>
        </div>';
        include_modal_styles();
        exit();
    } else {
        echo '
        <div id="error-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header error">
                    <h2>Error</h2>
                </div>
                <div class="modal-body">
                    <i class="fas fa-exclamation-circle error-icon"></i>
                    <p>Error: ' . $insert_stmt->error . '</p>
                    <button onclick="goBack()" class="continue-btn">Go Back</button>
                </div>
            </div>
        </div>';
        include_modal_styles();
    }
}

function include_modal_styles() {
    echo '
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --darkest-shade: #2a3417;
            --darker-shade: #3f4a22;
            --base-color: #556b2f;
            --lighter-tint: #758b4d;
            --lightest-tint: #99b27a;
            --extra-lightest-tint: #dbe4d0;
            --error-color: #dc3545;
            --success-color: #28a745;
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

        .modal-header.error {
            background: linear-gradient(135deg, var(--error-color), #e74c3c);
        }

        .modal-header.success {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
        }

        .modal-header h2 {
            color: white;
            margin: 0;
            font-size: 1.5em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .modal-body {
            padding: 20px 30px 30px;
        }

        .modal-body i {
            font-size: 4em;
            margin-bottom: 20px;
        }

        .error-icon {
            color: var(--error-color);
        }

        .success-icon {
            color: var(--success-color);
        }

        .modal-body p {
            color: var(--darker-shade);
            margin-bottom: 25px;
            font-size: 1.1em;
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

        .continue-btn {
            background: var(--base-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s ease;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .continue-btn:hover {
            background: var(--lighter-tint);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-body .continue-btn {
            min-width: 200px;
        }
    </style>
    <script>
        function goBack() {
            window.history.back();
        }

        function redirectToEmployeePage() {
            window.location.href = "employeePage.php";
        }

        // Show loading screen during redirect
        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll(".continue-btn");
            buttons.forEach(button => {
                button.addEventListener("click", function() {
                    document.getElementById("loading-screen").style.display = "flex";
                });
            });
        });
    </script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add your Info</title>
    <link rel="stylesheet" href="../CSS/Create.css">
</head>
<body>
    <div class="back-btn-container">
        <a class="back-btn" href="employeePage.php">Back</a>
    </div>
    <h1>Add your Info</h1>
    <form method="POST">
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="Fname" required><br>

        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="Lname" required><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>

        <label for="date_birth">Date of Birth:</label>
        <input type="date" id="date_birth" name="date_birth" required><br>

        <label for="Address">Address:</label>
        <input type="text" id="Address" name="Address" required><br>

        <label for="position">Position:</label>
        <input type="text" id="position" name="position" required><br>

        <label for="salary">Salary:</label>
        <input type="number" id="salary" name="salary" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" required><br>

        <button type="submit">Send info</button>
    </form>
</body>
</html>
