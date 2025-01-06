<?php
include 'config.php';

// Initialize search query variable
$search = '';

// Capture search term
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Function to highlight search terms
function highlightSearchTerm($text, $searchTerm) {
    if (empty($searchTerm)) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
    $searchTerm = preg_quote($searchTerm, '/');
    return preg_replace("/($searchTerm)/i", '<mark>$1</mark>', htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
}

// SQL query setup
$sql = "SELECT id, Fname, Lname, gender, date_birth, Address, position, salary, email, phone FROM employee_data";
if ($search !== '') {
    $search = $conn->real_escape_string($search);
    $sql .= " WHERE Fname LIKE '%$search%' 
              OR Lname LIKE '%$search%' 
              OR position LIKE '%$search%' 
              OR gender LIKE '%$search%' 
              OR Address LIKE '%$search%'
              OR email LIKE '%$search%' 
              OR phone LIKE '%$search%'";
}
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Data</title>
    <link rel="stylesheet" href="../CSS/table.css">
    <style>
        /* Highlight styling */
        mark {
            background-color: #fff3cd;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: bold;
        }
    </style>
</head>
<body id="top">
    <div class="back-btn-container">
        <a class="back-btn" href="../admin/adminPage.php">Back</a>
    </div>
    
    <h1>Employee Data</h1>
    
    <!-- Search Form -->
    <form method="post" action="">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by first name, last name, position, gender, email, or phone">
        <button type="submit">Search</button>
    </form>
    
    <div class="create-btn-container">
        <a class="create-btn" href="create.php">Add New Employee</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Date of Birth</th>
            <th>Address</th>
            <th>Position</th>
            <th>Salary</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo highlightSearchTerm($row['Fname'], $search); ?></td>
            <td><?php echo highlightSearchTerm($row['Lname'], $search); ?></td>
            <td><?php echo highlightSearchTerm($row['gender'], $search); ?></td>
            <td><?php echo htmlspecialchars($row['date_birth'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo highlightSearchTerm($row['Address'], $search); ?></td>
            <td><?php echo highlightSearchTerm($row['position'], $search); ?></td>
            <td><?php echo htmlspecialchars($row['salary'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo highlightSearchTerm($row['email'], $search); ?></td>
            <td><?php echo highlightSearchTerm($row['phone'], $search); ?></td>
            <td>
                <div class="actions">
                    <a href="edit.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="edit-btn">Edit</a>
                    <a href="delete.php?id=<?php echo htmlspecialchars($row['id']); ?>" onclick="return confirm('Are you sure?')" class="delete-btn">Delete</a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
