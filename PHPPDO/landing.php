<?php
require 'config.php';
require 'insert.php';
require 'update.php';
require 'delete.php';
require 'select.php';
?>

<?php
$editUser = null;

if (isset($_GET['edit'])) {
  $users_id = $_GET['edit'];
  $stmt = $pdo->prepare("SELECT * FROM users WHERE users_id = ?");
  $stmt->execute([$users_id]);
  $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<h2>Simple PDO CRUD</h2>

<div class="container">
    <div class="form-side">
        <h3><?= $editUser ? 'Update User' : 'Add User' ?></h3>
        
        <form method="POST">
            <?php if (!empty($editUser)): ?>
                <input type="hidden" name="users_id" value="<?= $editUser['users_id'] ?>">
            <?php endif; ?>

            <label>Name:</label>
            <input type="text" name="name" value="<?= !empty($editUser) ? $editUser['name'] : '' ?>" required><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?= !empty($editUser) ? $editUser['email'] : '' ?>" required><br>

            <label>Product:</label>
            <input type="text" name="product" placeholder="Product" required><br>

            <label>Amount:</label>
<div class="amount-container">
    <input type="number" step="0.01" name="amount" placeholder="$0.00" required>
</div><br>

            <?php if (!empty($editUser)): ?>
                <button type="submit" name="update"><i class="fa-solid fa-pen-to-square"></i> Update</button>
                <a href="landing.php"><i class="fa-solid fa-ban"></i> Cancel</a>
            <?php else: ?>
                <button type="submit" name="add"><i class="fa-solid fa-plus"></i> Add</button>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-side">
        <h3>User List</h3>
        
        <table>
            <tr>
                <th>users_id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Product</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>

            <?php foreach ($users as $user): ?>
            <?php
            $orders = $pdo->prepare("SELECT product, amount FROM orders WHERE user_id = ?");
            $orders->execute([$user['users_id']]);
            $user_orders = $orders->fetchAll(PDO::FETCH_ASSOC);
            ?>
                <?php if (count($user_orders) > 0): ?>
                    <?php foreach ($user_orders as $order): ?>
                    <tr>
                        <td><?= $user['users_id'] ?></td>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $order['product'] ?></td>
                        <td><span class="dollar-sign">$</span><?= number_format($order['amount'], 2) ?></td>
                        <td class="action-icons">
                            <a href="?edit=<?= $user['users_id'] ?>" class="edit-icon"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="?delete=<?= $user['users_id'] ?>" class="delete-icon" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td><?= $user['users_id'] ?></td>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td>-</td>
                        <td>-</td>
                        <td class="action-icons">
                            <a href="?edit=<?= $user['users_id'] ?>" class="edit-icon"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="?delete=<?= $user['users_id'] ?>" class="delete-icon" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    </div>
</div>

</body>
</html>
