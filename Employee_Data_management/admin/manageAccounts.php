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

// Fetch all employee_acc accounts
$query = "SELECT id, email FROM employee_acc";  // Excluding password for security
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

        .account-email {
            color: var(--base-color);
            font-size: 1.1em;
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

        /* Search bar styling */
        .search-container {
            margin: 20px auto;
            text-align: center;
            max-width: 600px;
        }

        .search-bar {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid var(--base-color);
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .search-bar:focus {
            border-color: var(--darker-shade);
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        /* Highlight styling */
        .highlight {
            background-color: #fff3cd;
            padding: 2px;
            border-radius: 3px;
        }

        /* Footer styling */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        .container {
            flex: 1;
        }

        .footer {
            position: sticky;
            bottom: 0;
            width: 100%;
            background-color: rgba(42, 52, 23, 0.9); 
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 0.9em;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        .creator-name {
            color: #99b27a; 
            font-weight: 600;
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
                <h1>Employee Accounts Management</h1>
            </div>
        </header>

        <main>
            <div class="search-container">
                <input type="text" class="search-bar" id="searchBar" placeholder="Search accounts..." onkeyup="searchAccounts()">
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert error">
                    <?php echo $error_message; ?>
                </div>
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
                            <div class="account-email">
                                <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?>
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

    <footer class="footer">
        <p style="margin: 0;">System created by  <span class="creator-name">Fatima Palisoc</span></p>
    </footer>

    <script>
        function showLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'flex';
        }

        function confirmDelete(event) {
            if (confirm('Are you sure you want to delete this account? This action cannot be undone.')) {
                showLoadingScreen();
                return true;
            }
            event.preventDefault();
            return false;
        }

        function searchAccounts() {
            const searchText = document.getElementById('searchBar').value.toLowerCase();
            const accountCards = document.querySelectorAll('.account-card');
            
            accountCards.forEach(card => {
                const cardText = card.textContent.toLowerCase();
                const shouldShow = cardText.includes(searchText);
                card.style.display = shouldShow ? '' : 'none';
                
                if (shouldShow && searchText) {
                    // Remove existing highlights
                    card.innerHTML = card.innerHTML.replace(/<mark class="highlight">(.*?)<\/mark>/g, '$1');
                    
                    // Add new highlights
                    const regex = new RegExp(searchText, 'gi');
                    const elements = card.getElementsByClassName('account-id');
                    elements[0].innerHTML = elements[0].textContent.replace(regex, match => `<mark class="highlight">${match}</mark>`);
                    
                    const emailElements = card.getElementsByClassName('account-email');
                    emailElements[0].innerHTML = emailElements[0].innerHTML.replace(regex, match => `<mark class="highlight">${match}</mark>`);
                }
            });
        }
    </script>
</body>
</html>
