<?php
require 'config.php';
require 'insert.php';
require 'update.php';
require 'delete.php';
require 'select.php';
?>

<h2>Simple PDO CRUD</h2>

<?php
// CHECK IF EDIT MODE
$editUser = null;

if (isset($_GET['edit'])) {
  $users_id = $_GET['edit'];
  $stmt = $pdo->prepare("SELECT * FROM users WHERE users_id = ?");
  $stmt->execute([$users_id]);
  $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!-- ADD / UPDATE FORM -->
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
  <input type="number" step="0.01" name="amount" placeholder="Amount" required><br>

  <!-- Submit buttons -->
  <?php if (!empty($editUser)): ?>
    <button type="submit" name="update">Update</button>
    <a href="landing.php">Cancel</a>
  <?php else: ?>
    <button type="submit" name="add">Add</button>
  <?php endif; ?>
</form>

<hr>

<!-- USER TABLE -->
<h3>User List</h3>

<table border="1" cellpadding="10">
  <tr>
    <th>users_id</th>
    <th>Name</th>
    <th>Email</th>
    <th>Action</th>
  </tr>

  <?php foreach ($users as $user): ?>
  <tr>
    <td><?= $user['users_id'] ?></td>
    <td><?= $user['name'] ?></td>
    <td><?= $user['email'] ?></td>
    <td>
      <a href="?edit=<?= $user['users_id'] ?>">Edit</a> |
      <a href="?delete=<?= $user['users_id'] ?>">Delete</a>
    </td>
  </tr>
  <?php endforeach; ?>
</table>