<?php
require 'config.php';

// Check if delete parameter exists in URL
if (isset($_GET['delete'])) {
    $users_id = $_GET['delete'];
    
    try {
        // Begin transaction
        $pdo->beginTransaction();
        
        // First delete related orders (foreign key constraint)
        $stmt1 = $pdo->prepare("DELETE FROM orders WHERE user_id = ?");
        $stmt1->execute([$users_id]);
        
        // Then delete the user
        $stmt2 = $pdo->prepare("DELETE FROM users WHERE users_id = ?");
        $stmt2->execute([$users_id]);
        
        // Commit transaction
        $pdo->commit();
        
        // Redirect to landing page
        header("Location: landing.php");
        exit;
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo "Error deleting user: " . $e->getMessage();
    }
}
?>