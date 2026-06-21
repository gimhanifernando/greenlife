<?php
require 'db.php';
session_start();

// ✅ Restrict to admin only
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ✅ Fetch all doctors with their service names
$stmt = $pdo->query("
    SELECT d.id, d.name, d.bio, s.name AS service_name
    FROM doctors d
    JOIN services s ON d.service_id = s.id
    ORDER BY d.id ASC
");
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>

<h2 style="text-align:center;">👩‍⚕️ Manage Therapists</h2>
<div style="text-align:center; margin:15px;">
    <a href="therapist_add.php" class="btn">➕ Add New Therapist</a>
</div>

<table class="styled-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Service</th>
      <th>Bio</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($doctors)): ?>
        <?php foreach ($doctors as $doc): ?>
            <tr>
                <td><?= $doc['id'] ?></td>
                <td><?= htmlspecialchars($doc['name']) ?></td>
                <td><?= htmlspecialchars($doc['service_name']) ?></td>
                <td><?= htmlspecialchars($doc['bio']) ?></td>
                <td>
                   <a href="therapist_edit.php?id=<?= $doc['id'] ?>" class="btn-edit">✏️ Edit</a>
                    <a href="therapist_delete.php?id=<?= $doc['id'] ?>" class="btn-delete"
                       onclick="return confirm('Are you sure you want to delete this therapist?');">🗑 Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" style="text-align:center;">⚠️ No therapists found</td>
        </tr>
    <?php endif; ?>
  </tbody>
</table>

<?php require 'footer.php'; ?>
