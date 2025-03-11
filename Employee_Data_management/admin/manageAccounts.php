<?php
include 'adminAuth.php';
include '../database/adminAcc_database.php';

// Delete account functionality
if (isset($_POST['delete_account'])) {
    $user_id = $_POST['user_id'];
    
    // Delete the user from the database
    $delete_query = "DELETE FROM employee_acc WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $success_message = "Account deleted successfully!";
    } else {
        $error_message = "Error deleting account: " . $conn->error;
    }
}

// Fetch all employee_acc accounts including the recovery code
$query = "SELECT id, full_name, email, code FROM employee_acc";
$result = $conn->query($query);

if (!$result) {
    $error_message = "Error fetching accounts: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employee Accounts</title>
    <link rel="stylesheet" href="../CSS/leaveRequests.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .account-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            animation: fadeIn 0.5s ease-out;
        }

        .account-card:hover {
            transform: translateY(-5px);
        }

        .account-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .account-id {
            font-weight: bold;
            color: var(--darker-shade);
        }

        .account-details {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .account-email, .account-code {
            color: var(--base-color);
            font-size: 1.1em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .account-name {
            color: var(--base-color);
            font-size: 1.1em;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .account-name em {
            color: #999;
            font-style: italic;
            font-weight: normal;
        }

        .account-code {
            background: rgba(85, 107, 47, 0.1);
            padding: 8px 12px;
            border-radius: 6px;
            font-family: monospace;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .code-placeholder {
            font-family: monospace;
            letter-spacing: 2px;
            color: #777;
        }

        .show-code-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--base-color);
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .show-code-btn:hover {
            color: var(--darker-shade);
            background: rgba(85, 107, 47, 0.1);
        }

        .delete-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background-color: #dc3545;
            color: white;
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .no-accounts {
            text-align: center;
            padding: 40px;
            background-color: white;
            border-radius: 10px;
            grid-column: 1 / -1;
        }

        .no-accounts i {
            font-size: 3em;
            color: var(--base-color);
            margin-bottom: 15px;
        }

        .no-accounts p {
            color: var(--darker-shade);
            font-size: 1.2em;
        }

        .search-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-bar {
            width: 100%;
            max-width: 500px;
            padding: 12px 20px;
            border: 2px solid var(--base-color);
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-bar:focus {
            border-color: var(--darker-shade);
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .highlight {
            background-color: #fff3cd;
            padding: 2px;
            border-radius: 3px;
        }

        .copy-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--base-color);
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .copy-btn:hover {
            color: var(--darker-shade);
            background: rgba(85, 107, 47, 0.1);
        }

        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 100px;
            background-color: var(--darker-shade);
            color: white;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -50px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.8em;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        /* Print button styles */
        .print-btn {
            background-color: #556B2F;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-left: 10px;
            vertical-align: middle;
        }

        .print-btn:hover {
            background-color: #3d4d22;
        }

        /* Print-specific styles */
        @media print {
            .loading-overlay, .back-btn, .search-container, .print-btn, .delete-btn,
            .copy-btn, .tooltip, .success-message, .error-message {
                display: none !important;
            }
            body {
                background-color: white;
                padding: 20px;
            }
            .container {
                padding: 0;
                max-width: none;
            }
            header {
                margin-bottom: 20px;
            }
            .account-card {
                box-shadow: none;
                border: 1px solid #000;
                break-inside: avoid;
                margin-bottom: 20px;
            }
            .account-header {
                border-bottom: 1px solid #000;
            }
            .account-code {
                background: none;
                border: 1px solid #000;
            }
            .requests-container {
                display: block;
            }
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
                <h1>Manage Employee Accounts</h1>
            </div>
        </header>

        <main>
            <div class="search-container">
                <input type="text" id="searchBar" class="search-bar" placeholder="Search by ID, email, or recovery code..." onkeyup="searchAccounts()">
                <button type="button" onclick="window.print()" class="print-btn">
                    <i class="fas fa-print"></i> Print Report
                </button>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="requests-container">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="account-card">
                            <div class="account-header">
                                <span class="account-id">ID: <?php echo htmlspecialchars($row['id']); ?></span>
                                <form method="POST" style="display: inline;" onsubmit="return confirmDelete(event);">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_account" class="delete-btn">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                            <div class="account-details">
                                <div class="account-name">
                                    <i class="fas fa-user"></i> <?php echo !empty($row['full_name']) ? htmlspecialchars($row['full_name']) : '<em>No name provided</em>'; ?>
                                </div>
                                <div class="account-email">
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?>
                                </div>
                                <div class="account-code">
                                    <i class="fas fa-key"></i> 
                                    Recovery Code: 
                                    <span class="code-value" style="display: none;"><?php echo htmlspecialchars($row['code']); ?></span>
                                    <span class="code-placeholder">●●●●●●●●●●</span>
                                    <div class="tooltip">
                                        <button class="show-code-btn" onclick="toggleCodeVisibility(this, '<?php echo htmlspecialchars($row['code']); ?>')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <span class="tooltiptext">Show/Hide code</span>
                                    </div>
                                    <div class="tooltip" style="display: none;" id="copy-btn-container-<?php echo $row['id']; ?>">
                                        <button class="copy-btn" onclick="copyCode('<?php echo htmlspecialchars($row['code']); ?>')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <span class="tooltiptext">Copy code</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-accounts">
                        <i class="fas fa-users-slash"></i>
                        <p>No employee accounts found</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function showLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'flex';
        }

        function confirmDelete(event) {
            if (!confirm('Are you sure you want to delete this account? This action cannot be undone.')) {
                event.preventDefault();
                return false;
            }
            showLoadingScreen();
            return true;
        }

        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                const btn = event.currentTarget;
                const icon = btn.querySelector('i');
                const originalClass = icon.className;
                
                // Change to checkmark
                icon.className = 'fas fa-check';
                
                // Revert back after 1.5 seconds
                setTimeout(() => {
                    icon.className = originalClass;
                }, 1500);
            });
        }

        function toggleCodeVisibility(button, code) {
            const codeContainer = button.closest('.account-code');
            const codeValue = codeContainer.querySelector('.code-value');
            const codePlaceholder = codeContainer.querySelector('.code-placeholder');
            const copyBtnContainer = codeContainer.querySelector('[id^="copy-btn-container-"]');
            const icon = button.querySelector('i');
            
            if (codeValue.style.display === 'none') {
                // Show the code
                codeValue.style.display = 'inline';
                codePlaceholder.style.display = 'none';
                copyBtnContainer.style.display = 'inline-block';
                icon.className = 'fas fa-eye-slash';
                button.querySelector('.tooltiptext').textContent = 'Hide code';
            } else {
                // Hide the code
                codeValue.style.display = 'none';
                codePlaceholder.style.display = 'inline';
                copyBtnContainer.style.display = 'none';
                icon.className = 'fas fa-eye';
                button.querySelector('.tooltiptext').textContent = 'Show code';
            }
        }

        function searchAccounts() {
            const searchText = document.getElementById('searchBar').value.toLowerCase();
            const cards = document.getElementsByClassName('account-card');
            
            Array.from(cards).forEach(card => {
                const id = card.querySelector('.account-id').textContent.toLowerCase();
                const email = card.querySelector('.account-email').textContent.toLowerCase();
                const code = card.querySelector('.account-code').textContent.toLowerCase();
                
                if (id.includes(searchText) || email.includes(searchText) || code.includes(searchText)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
