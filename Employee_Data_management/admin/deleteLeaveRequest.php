<?php
include("adminAuth.php");
include("../Employee_Database/config.php");

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete the specific leave request
        $delete_sql = "DELETE FROM leave_requests WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode(['success' => true, 'message' => 'Leave request deleted successfully']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error deleting leave request: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Request ID not provided']);
}
?>
