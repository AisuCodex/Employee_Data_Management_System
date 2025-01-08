<?php
include("adminAuth.php");
include("../Employee_Database/config.php");

// Get leave request counts by employee
$leave_count_sql = "SELECT 
    ed.email,
    ed.Fname,
    ed.Lname,
    COUNT(lr.id) as total_requests,
    SUM(CASE WHEN lr.status = 'approved' THEN 1 ELSE 0 END) as approved_count,
    SUM(CASE WHEN lr.status = 'pending' THEN 1 ELSE 0 END) as pending_count,
    SUM(CASE WHEN lr.status = 'rejected' THEN 1 ELSE 0 END) as rejected_count
FROM employee_data ed
LEFT JOIN leave_requests lr ON ed.email = lr.email
GROUP BY ed.email, ed.Fname, ed.Lname
ORDER BY total_requests DESC";
$leave_count_result = $conn->query($leave_count_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Statistics</title>
    <link rel="stylesheet" href="../CSS/leaveRequests.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-content {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .back-btn {
            background-color: #556B2F;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-right: 20px;
        }

        .back-btn:hover {
            background-color: #3d4d22;
        }

        .statistics-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-card i {
            font-size: 2em;
            color: #556B2F;
            margin-bottom: 10px;
        }

        .summary-card .count {
            font-size: 2em;
            font-weight: bold;
            color: #556B2F;
        }

        .statistics-table {
            overflow-x: auto;
        }

        .statistics-table table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .statistics-table th,
        .statistics-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .statistics-table th {
            background-color: #f8f9fa;
            color: #556B2F;
            font-weight: bold;
        }

        .statistics-table tr:hover {
            background-color: #f8f9fa;
        }

        .status-count {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: 500;
        }

        .approved {
            color: #28a745;
        }

        .pending {
            color: #ffc107;
        }

        .rejected {
            color: #dc3545;
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #c82333;
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
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 500px;
            text-align: center;
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .confirm-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancel-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Loading screen -->
    <div class="loading-overlay" id="loading-screen">
        <div class="loader"></div>
    </div>

    <div class="container">
        <div class="header-content">
            <a href="adminPage.php" class="back-btn" onclick="showLoadingScreen()">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h1>Employee Leave Statistics</h1>
        </div>

        <?php
        // Calculate totals
        $total_requests = 0;
        $total_approved = 0;
        $total_pending = 0;
        $total_rejected = 0;

        if ($leave_count_result && $leave_count_result->num_rows > 0) {
            while($row = $leave_count_result->fetch_assoc()) {
                $total_requests += $row['total_requests'];
                $total_approved += $row['approved_count'];
                $total_pending += $row['pending_count'];
                $total_rejected += $row['rejected_count'];
            }
            $leave_count_result->data_seek(0);
        }
        ?>

        <div class="statistics-container">
            <div class="summary-cards">
                <div class="summary-card">
                    <i class="fas fa-clipboard-list"></i>
                    <div class="count"><?php echo $total_requests; ?></div>
                    <div>Total Requests</div>
                </div>
                <div class="summary-card">
                    <i class="fas fa-check-circle"></i>
                    <div class="count approved"><?php echo $total_approved; ?></div>
                    <div>Approved</div>
                </div>
                <div class="summary-card">
                    <i class="fas fa-clock"></i>
                    <div class="count pending"><?php echo $total_pending; ?></div>
                    <div>Pending</div>
                </div>
                <div class="summary-card">
                    <i class="fas fa-times-circle"></i>
                    <div class="count rejected"><?php echo $total_rejected; ?></div>
                    <div>Rejected</div>
                </div>
            </div>

            <div class="search-container">
                <input type="text" id="searchInput" class="search-bar" placeholder="Search by name or email..." onkeyup="searchTable()">
            </div>

            <div class="statistics-table">
                <table id="statisticsTable">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Email</th>
                            <th>Total Requests</th>
                            <th>Approved</th>
                            <th>Pending</th>
                            <th>Rejected</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($leave_count_result && $leave_count_result->num_rows > 0): ?>
                            <?php while($row = $leave_count_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Fname'] . ' ' . $row['Lname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo $row['total_requests']; ?></td>
                                    <td class="approved"><?php echo $row['approved_count']; ?></td>
                                    <td class="pending"><?php echo $row['pending_count']; ?></td>
                                    <td class="rejected"><?php echo $row['rejected_count']; ?></td>
                                    <td>
                                        <button class="delete-btn" onclick="deleteStatistics('<?php echo htmlspecialchars($row['email']); ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">No leave requests found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <h2>Confirm Delete</h2>
            <p>Are you sure you want to delete all leave statistics for this employee?</p>
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

        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('statisticsTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const nameCell = rows[i].getElementsByTagName('td')[0];
                const emailCell = rows[i].getElementsByTagName('td')[1];
                if (nameCell && emailCell) {
                    const name = nameCell.textContent || nameCell.innerText;
                    const email = emailCell.textContent || emailCell.innerText;
                    if (name.toLowerCase().indexOf(filter) > -1 || email.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }

        let emailToDelete = '';

        function deleteStatistics(email) {
            emailToDelete = email;
            document.getElementById('delete-modal').style.display = 'block';
        }

        function hideDeleteModal() {
            document.getElementById('delete-modal').style.display = 'none';
        }

        function confirmDelete() {
            if (!emailToDelete) return;

            showLoadingScreen();
            
            const formData = new FormData();
            formData.append('email', emailToDelete);
            
            fetch('deleteLeaveStatistics.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to show updated statistics
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                    hideLoadingScreen();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the statistics');
                hideLoadingScreen();
            });

            hideDeleteModal();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('delete-modal')) {
                hideDeleteModal();
            }
        }
    </script>
</body>
</html>
