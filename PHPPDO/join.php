<?php
require 'config.php';

$stmt = $pdo->query("
    SELECT users.users_id, users.name, users.email,
           COUNT(orders.order_id) as number_of_orders,
           COALESCE(SUM(orders.amount), 0) as total_paid
    FROM users
    LEFT JOIN orders ON users.users_id = orders.user_id
    GROUP BY users.users_id
    ORDER BY total_paid DESC
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
