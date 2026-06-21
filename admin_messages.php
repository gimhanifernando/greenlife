<?php
require 'db.php';
session_start();

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require 'header.php';

$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>📩 Client Messages</h2>
<table border="1" cellpadding="8" style="width:100%;border-collapse:collapse">
  <tr>
    <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Subject</th><th>Message</th><th>Date</th>
  </tr>
  <?php foreach($messages as $m): ?>
    <tr>
      <td><?= $m['id'] ?></td>
      <td><?= htmlspecialchars($m['name']) ?></td>
      <td><?= htmlspecialchars($m['email']) ?></td>
      <td><?= htmlspecialchars($m['phone']) ?></td>
      <td><?= htmlspecialchars($m['subject']) ?></td>
      <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
      <td><?= $m['created_at'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<?php require 'footer.php'; ?>
