<?php
include("adminAuth.php");
include("../Employee_Database/config.php");

if(isset($_POST['id']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Update leave request status
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        $update_sql = "UPDATE leave_requests SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode(['success' => true, 'message' => 'Leave request ' . $status . ' successfully']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error updating leave request: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
}
?>
