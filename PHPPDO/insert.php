<?php
require 'config.php';

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $product = $_POST['product'];
    $amount = $_POST['amount'];

    $stmt = $pdo->prepare("INSERT INTO users (name, email)
    VALUES (?, ?)");
    $stmt->execute([$name, $email]);

    $user_id = $pdo->lastInsertId();

    $stmt2 = $pdo->prepare("INSERT INTO orders (user_id, product, amount)
    VALUES (?, ?, ?");
    $stmt2->execute([$user_id, $product< $amount]);

    echo "User and Order added successfully!";

}