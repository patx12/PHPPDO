<?php
require 'config.php';

if (isset($_POST['update'])) {
    $users_id = $_POST['users_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $product = $_POST['product'];
    $amount = $_POST['amount'];


    $pdo->beginTransaction();
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE users_id = ?");
        $stmt->execute([$name, $email, $users_id]);
        
        $check = $pdo->prepare("SELECT * FROM orders WHERE user_id = ?");
        $check->execute([$users_id]);
        
        if ($check->rowCount() > 0) {
            $stmt2 = $pdo->prepare("UPDATE orders SET product = ?, amount = ? WHERE user_id = ?");
            $stmt2->execute([$product, $amount, $users_id]);
        } else {
            $stmt2 = $pdo->prepare("INSERT INTO orders (user_id, product, amount) VALUES (?, ?, ?)");
            $stmt2->execute([$users_id, $product, $amount]);
        }
        
        $pdo->commit();
        header("Location: landing.php");
        exit;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
