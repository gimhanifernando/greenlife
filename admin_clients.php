<?php
require 'db.php';
session_start();

// Restrict to admins only
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch all clients
$clients = $pdo->query("
    SELECT id, name, email, phone, created_at 
    FROM users 
    WHERE role='client'
    ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>

<h2>👥 Client Profiles</h2>
<table border="1" cellpadding="8" style="width:100%; border-collapse:collapse;">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Joined</th>
    <th>Action</th>
  </tr>
  <?php foreach ($clients as $c): ?>
    <tr>
      <td><?= $c['id'] ?></td>
      <td><?= htmlspecialchars($c['name']) ?></td>
      <td><?= htmlspecialchars($c['email']) ?></td>
      <td><?= htmlspecialchars($c['phone']) ?></td>
      <td><?= $c['created_at'] ?></td>
      <td>
        <a href="client_history.php?id=<?= $c['id'] ?>">📜 View History</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php require 'footer.php'; ?>
