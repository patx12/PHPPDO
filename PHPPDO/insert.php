<?php
require 'config.php';

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $product = $_POST['product'];
    $amount = $_POST['amount'];

    $check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $check->execute([$email]);
    
    if ($check->rowCount() > 0) {
        echo "Error: Email already exists! Please use a different email.";
    } else {
        $pdo->beginTransaction();
        
        try {

            $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
            $stmt->execute([$name, $email]);

            $user_id = $pdo->lastInsertId();

            $stmt2 = $pdo->prepare("INSERT INTO orders (user_id, product, amount) VALUES (?, ?, ?)");
            $stmt2->execute([$user_id, $product, $amount]);
            
            $pdo->commit();
            
            header("Location: landing.php");
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
