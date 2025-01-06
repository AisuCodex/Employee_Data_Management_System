<?php
include 'employeeAuth.php';
include '../database/adminAcc_database.php';

// Fetch all announcements ordered by date (newest first) and importance
$query = "SELECT * FROM announcements ORDER BY importance = 'urgent' DESC, 
          importance = 'important' DESC, date_posted DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="../CSS/leaveRequests.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .announcement-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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

        .announcement-content {
            margin: 15px 0;
            line-height: 1.6;
        }

        .announcement-meta {
            font-size: 0.9em;
            color: #666;
            margin-top: 10px;
        }

        .no-announcements {
            text-align: center;
            padding: 40px;
            background-color: white;
            border-radius: 10px;
        }

        .no-announcements i {
            font-size: 3em;
            color: var(--base-color);
            margin-bottom: 15px;
        }

        .no-announcements p {
            color: var(--darker-shade);
            font-size: 1.2em;
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
                <a href="employeePage.php" class="back-btn" onclick="showLoadingScreen()">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <h1>Announcements</h1>
            </div>
        </header>

        <main>
            <div class="requests-container">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="announcement-card">
                            <div class="announcement-header">
                                <span class="announcement-title"><?php echo htmlspecialchars($row['title']); ?></span>
                                <span class="importance-badge importance-<?php echo $row['importance']; ?>">
                                    <?php echo ucfirst($row['importance']); ?>
                                </span>
                            </div>
                            <div class="announcement-content">
                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                            </div>
                            <div class="announcement-meta">
                                <i class="fas fa-user"></i> Posted by: <?php echo htmlspecialchars($row['posted_by']); ?>
                                <br>
                                <i class="fas fa-clock"></i> Posted on: <?php echo date('F d, Y h:i A', strtotime($row['date_posted'])); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-announcements">
                        <i class="fas fa-bullhorn"></i>
                        <p>No announcements available</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function showLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'flex';
        }
    </script>
</body>
</html>
