<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("adminAuth.php");
include("../database/adminAcc_database.php");

// Debug database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';
$error_message = '';

// Debug - Check if table exists
$table_check = $conn->query("SHOW TABLES LIKE 'employee_logs'");
if ($table_check->num_rows == 0) {
    $error_message = "employee_logs table does not exist";
    error_log($error_message);
} else {
    error_log("employee_logs table exists");
}

// Handle log deletion
if (isset($_POST['delete_log'])) {
    $log_id = $_POST['log_id'];
    $delete_stmt = $conn->prepare("DELETE FROM employee_logs WHERE id = ?");
    if ($delete_stmt) {
        $delete_stmt->bind_param("i", $log_id);
        if ($delete_stmt->execute()) {
            $_SESSION['success_message'] = "Log entry deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Error deleting log entry: " . $delete_stmt->error;
            error_log("Error deleting log entry: " . $delete_stmt->error);
        }
        $delete_stmt->close();
    } else {
        $_SESSION['error_message'] = "Error preparing delete statement: " . $conn->error;
        error_log("Error preparing delete statement: " . $conn->error);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Build query
$sql = "SELECT * FROM employee_logs WHERE 1=1";
$params = array();
$types = "";

if (!empty($search)) {
    $sql .= " AND email LIKE ?";
    $search = "%$search%";
    $params[] = $search;
    $types .= "s";
}

if (!empty($status_filter)) {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

$sql .= " ORDER BY login_time DESC";

// Debug - Log the final SQL query
error_log("Executing SQL: " . $sql);

// Execute query
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $error_message = "Error preparing statement: " . $conn->error;
    error_log($error_message);
    $result = false;
} else {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    if (!$stmt->execute()) {
        $error_message = "Error executing query: " . $stmt->error;
        error_log($error_message);
        $result = false;
    } else {
        $result = $stmt->get_result();
        error_log("Query successful. Found " . $result->num_rows . " records");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Logs</title>
    <link rel="stylesheet" href="../CSS/employeeLogs.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
            .loading-overlay, .back-btn, .search-filter-container, .print-btn, .delete-btn,
            .alert, form, .actions {
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
            .header {
                margin-bottom: 20px;
            }
            .table-container {
                box-shadow: none;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
            .status-badge {
                background: none !important;
                color: black !important;
                padding: 0 !important;
                border-radius: 0 !important;
            }
            .no-records {
                text-align: center;
            }
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <!-- Loading screen -->
    <div class="loading-overlay" id="loading-screen">
        <div class="loader"></div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Employee Logs</h1>
            <a href="adminPage.php" class="back-btn" onclick="showLoadingScreen()">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Search Form -->
        <div class="search-filter-container">
            <form method="GET" action="" class="search-form">
                <div class="search-box">
                    <input type="text" name="search" placeholder="Search by email..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="filter-box">
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="logged_out" <?php echo $status_filter === 'logged_out' ? 'selected' : ''; ?>>Logged Out</option>
                    </select>
                </div>
                <button type="submit" class="search-btn">Search</button>
                <a href="employeeLogs.php" class="reset-btn">Reset</a>
                <button type="button" onclick="window.print()" class="print-btn">
                    <i class="fas fa-print"></i> Print Report
                </button>
            </form>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert success">
                <?php 
                    echo htmlspecialchars($_SESSION['success_message']);
                    unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert error">
                <?php 
                    echo htmlspecialchars($_SESSION['error_message']);
                    unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Logs Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Login Time</th>
                        <th>Logout Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($row['login_time'])); ?></td>
                                <td><?php echo $row['logout_time'] ? date('Y-m-d H:i:s', strtotime($row['logout_time'])) : 'N/A'; ?></td>
                                <td>
                                    <span class="status-badge <?php echo htmlspecialchars($row['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="" style="display: inline;" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="log_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete_log" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-records">No log records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'flex';
        }

        function confirmDelete() {
            return confirm('Are you sure you want to delete this log entry?');
        }
    </script>
</body>
</html>

<?php
if (isset($stmt) && $stmt) {
    $stmt->close();
}
$conn->close();
?>
