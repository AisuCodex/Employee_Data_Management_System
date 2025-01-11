<?php
include 'adminAuth.php';
include("../database/adminAcc_database.php");

// Handle approve/deny actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    
    if ($action === 'approve') {
        $sql = "UPDATE leave_requests SET status = 'approved' WHERE id = ?";
        $success_message = "Leave request approved successfully!";
    } else {
        $sql = "UPDATE leave_requests SET status = 'rejected' WHERE id = ?";
        $success_message = "Leave request denied successfully!";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    
    if ($stmt->execute()) {
        $success_message = "Request has been " . (($action === 'approve') ? 'approved' : 'rejected');
    } else {
        $error_message = "Error updating request: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all leave requests
$sql = "SELECT lr.*, ed.Fname, ed.Lname 
        FROM leave_requests lr
        LEFT JOIN employee_data ed ON lr.email = ed.email
        ORDER BY lr.created_at DESC";
$result = $conn->query($sql);

// Check if table exists
if ($result === false) {
    // Table doesn't exist, create it
    $create_table_sql = file_get_contents("../database/create_leave_table.sql");
    if ($conn->multi_query($create_table_sql)) {
        do {
            // Clear out the results
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        
        // Now fetch the requests again
        $sql = "SELECT lr.*, ed.Fname, ed.Lname 
                FROM leave_requests lr
                LEFT JOIN employee_data ed ON lr.email = ed.email
                ORDER BY lr.created_at DESC";
        $result = $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests Management</title>
    <link rel="stylesheet" href="../CSS/leaveRequests.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styles */
        header {
            background-color: #556B2F;
            color: white;
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 5px;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .back-btn {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .back-btn:hover {
            opacity: 0.8;
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
            margin-left: auto;
        }

        .print-btn:hover {
            background-color: #3d4d22;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            background-color: white;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #556B2F;
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        /* Status Styles */
        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .approved {
            background-color: #28a745;
            color: white;
        }

        .pending {
            background-color: #ffc107;
            color: #000;
        }

        .rejected {
            background-color: #dc3545;
            color: white;
        }

        /* Button Styles */
        .actions {
            display: flex;
            gap: 5px;
            justify-content: flex-start;
        }

        .approve-btn, .reject-btn, .delete-btn {
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
            color: white;
        }

        .approve-btn {
            background-color: #28a745;
        }

        .approve-btn:hover {
            background-color: #218838;
        }

        .reject-btn {
            background-color: #dc3545;
        }

        .reject-btn:hover {
            background-color: #c82333;
        }

        .delete-btn {
            background-color: #6c757d;
        }

        .delete-btn:hover {
            background-color: #5a6268;
        }

        /* Search Bar Styles */
        .search-container {
            margin-bottom: 20px;
        }

        .search-bar {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .search-bar:focus {
            outline: none;
            border-color: #556B2F;
        }

        /* Loading Screen */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #556B2F;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .confirm-btn, .cancel-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .confirm-btn {
            background-color: #dc3545;
            color: white;
        }

        .confirm-btn:hover {
            background-color: #c82333;
        }

        .cancel-btn {
            background-color: #6c757d;
            color: white;
        }

        .cancel-btn:hover {
            background-color: #5a6268;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            th, td {
                padding: 8px 10px;
            }

            .actions {
                flex-direction: column;
            }

            .modal-content {
                width: 95%;
                margin: 10% auto;
            }
        }

        /* Print-specific styles */
        @media print {
            .back-btn, .print-btn, .loading-overlay, .modal, .action-buttons, .search-bar {
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
                background-color: white;
                color: black;
                padding: 0;
                margin-bottom: 20px;
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
            .status {
                color: black !important;
                background: none !important;
                padding: 0 !important;
            }
            .status::before {
                content: attr(data-status);
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
                <h1>Leave Requests Management</h1>
                <button onclick="window.print()" class="print-btn">
                    <i class="fas fa-print"></i> Print Report
                </button>
            </div>
        </header>

        <main>
            <div class="search-container">
                <input type="text" id="searchBar" class="search-bar" placeholder="Search leave requests..." onkeyup="searchRequests()">
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

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Email</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $statusClass = '';
                                switch ($row['status']) {
                                    case 'approved':
                                        $statusClass = 'approved';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'rejected';
                                        break;
                                    default:
                                        $statusClass = 'pending';
                                }
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Fname'] . ' ' . $row['Lname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                    <td>
                                        <span class="status <?php echo $row['status']; ?>" 
                                              data-status="<?php echo ucfirst($row['status']); ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <?php if ($row['status'] === 'pending'): ?>
                                            <button onclick="approveRequest(<?php echo $row['id']; ?>)" class="approve-btn">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button onclick="rejectRequest(<?php echo $row['id']; ?>)" class="reject-btn">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        <?php endif; ?>
                                        <button class="delete-btn" onclick="deleteRequest(<?php echo $row['id']; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align: center;'>No leave requests found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <h2>Confirm Delete</h2>
            <p>Are you sure you want to delete this leave request?</p>
            <div class="modal-buttons">
                <button onclick="confirmDelete()" class="confirm-btn">Yes, Delete</button>
                <button onclick="hideDeleteModal()" class="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        function showLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'flex';
        }

        function hideLoadingScreen() {
            document.getElementById('loading-screen').style.display = 'none';
        }

        function searchRequests() {
            const searchText = document.getElementById('searchBar').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                let found = false;

                Array.from(cells).forEach(cell => {
                    const text = cell.textContent.toLowerCase();
                    if (text.includes(searchText)) {
                        found = true;
                    }
                });

                row.style.display = found ? '' : 'none';
            });
        }

        function approveRequest(id) {
            handleRequest(id, 'approve');
        }

        function rejectRequest(id) {
            handleRequest(id, 'reject');
        }

        function handleRequest(id, action) {
            showLoadingScreen();
            
            fetch('handleLeaveRequest.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + encodeURIComponent(id) + '&action=' + encodeURIComponent(action)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to show updated status
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                    hideLoadingScreen();
                }
            })
            .catch(error => {
                alert('Error: ' + error);
                hideLoadingScreen();
            });
        }

        let requestIdToDelete = '';

        function deleteRequest(id) {
            requestIdToDelete = id;
            document.getElementById('delete-modal').style.display = 'block';
        }

        function hideDeleteModal() {
            document.getElementById('delete-modal').style.display = 'none';
        }

        function confirmDelete() {
            if (!requestIdToDelete) return;

            showLoadingScreen();
            
            fetch('deleteLeaveRequest.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + encodeURIComponent(requestIdToDelete)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to show updated list
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                    hideLoadingScreen();
                }
            })
            .catch(error => {
                alert('Error: ' + error);
                hideLoadingScreen();
            });

            hideDeleteModal();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            var modal = document.getElementById('delete-modal');
            if (event.target == modal) {
                hideDeleteModal();
            }
        }
    </script>
</body>
</html>
