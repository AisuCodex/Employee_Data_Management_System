<?php
include("adminAuth.php");
include("../Employee_Database/config.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_POST['email'])) {
    echo json_encode(['success' => false, 'message' => 'Email not provided']);
    exit;
}

$email = $_POST['email'];

try {
    // Start transaction
    $conn->begin_transaction();

    // Delete from leave_requests table
    $delete_leave_sql = "DELETE FROM leave_requests WHERE email = ?";
    $stmt = $conn->prepare($delete_leave_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Commit the transaction
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Leave statistics deleted successfully']);
} catch (Exception $e) {
    // Rollback the transaction on error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>