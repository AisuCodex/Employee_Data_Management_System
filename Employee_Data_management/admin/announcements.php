<?php
session_start();
require_once('../database/adminAcc_database.php');

// Create announcements table if it doesn't exist
$create_table_query = "CREATE TABLE IF NOT EXISTS announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    posted_by VARCHAR(100) NOT NULL,
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    importance ENUM('normal', 'important', 'urgent') DEFAULT 'normal',
    FOREIGN KEY (posted_by) REFERENCES admin_acc(email)
)";
$conn->query($create_table_query);

// Handle form submission for new announcement
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'create') {
            $title = $_POST['title'];
            $message = $_POST['message'];
            $importance = $_POST['importance'];
            $posted_by = $_SESSION['email'];

            $stmt = $conn->prepare("INSERT INTO announcements (title, message, importance, posted_by) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $message, $importance, $posted_by);
            
            if ($stmt->execute()) {
                $success_message = "Announcement posted successfully!";
            } else {
                $error_message = "Error posting announcement.";
            }
        } elseif ($_POST['action'] == 'delete' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $success_message = "Announcement deleted successfully!";
            } else {
                $error_message = "Error deleting announcement.";
            }
        }
    }
}

// Fetch all announcements
$result = $conn->query("SELECT * FROM announcements ORDER BY date_posted DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements</title>
    <link rel="stylesheet" href="../CSS/leaveRequests.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .announcement-form {
            padding: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--darker-shade);
        }

        .form-group input[type="text"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .announcement-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .announcement-card:hover {
            transform: translateY(-5px);
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .announcement-title {
            font-size: 1.2em;
            font-weight: bold;
            color: var(--darker-shade);
        }

        .importance-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
        }

        .importance-normal {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .importance-important {
            background-color: #fff3cd;
            color: #856404;
        }

        .importance-urgent {
            background-color: #f8d7da;
            color: #721c24;
        }

        .announcement-meta {
            font-size: 0.9em;
            color: #666;
            margin-top: 10px;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
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
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: bold;
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
                <h1>Manage Announcements</h1>
            </div>
        </header>

        <main>
            <?php if (isset($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>


            <div class="announcement-form">
                <h2>Create New Announcement</h2>
                <form method="POST" onsubmit="showLoadingScreen()">
                    <input type="hidden" name="action" value="create">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="importance">Importance:</label>
                        <select id="importance" name="importance">
                            <option value="normal">Normal</option>
                            <option value="important">Important</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <button type="submit" class="view-btn">Post Announcement</button>
                </form>
            </div>
            <div class="search-container">
                <input type="text" id="searchBar" class="search-bar" placeholder="Search announcements..." onkeyup="searchAnnouncements()">
            </div>

            <div class="requests-container">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="announcement-card" data-searchable>
                            <div class="announcement-header">
                                <span class="announcement-title searchable"><?php echo htmlspecialchars($row['title']); ?></span>
                                <div>
                                    <span class="importance-badge importance-<?php echo $row['importance']; ?> searchable">
                                        <?php echo ucfirst($row['importance']); ?>
                                    </span>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="delete-btn" onclick="showLoadingScreen()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="announcement-content searchable">
                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                            </div>
                            <div class="announcement-meta">
                                <i class="fas fa-user"></i> Posted by: <span class="searchable"><?php echo htmlspecialchars($row['posted_by']); ?></span>
                                <br>
                                <i class="fas fa-clock"></i> Posted on: <span class="searchable"><?php echo date('F d, Y h:i A', strtotime($row['date_posted'])); ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="announcement-card">
                        <p>No announcements found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function showLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'flex';
        }

        function searchAnnouncements() {
            const searchText = document.getElementById('searchBar').value.toLowerCase();
            const announcementCards = document.querySelectorAll('.announcement-card[data-searchable]');
            
            announcementCards.forEach(card => {
                const searchableElements = card.getElementsByClassName('searchable');
                let found = false;

                // Remove existing highlights
                Array.from(searchableElements).forEach(element => {
                    element.innerHTML = element.textContent;
                });

                if (searchText) {
                    // Check if any element contains the search text
                    Array.from(searchableElements).forEach(element => {
                        const text = element.textContent.toLowerCase();
                        if (text.includes(searchText)) {
                            found = true;
                            // Add highlight
                            element.innerHTML = element.textContent.replace(
                                new RegExp(`(${searchText})`, 'gi'),
                                '<span class="highlight">$1</span>'
                            );
                        }
                    });
                } else {
                    found = true;
                }

                // Show/hide card based on search match
                card.style.display = found ? '' : 'none';
            });
        }
    </script>
</body>
</html>
