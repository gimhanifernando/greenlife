<?php
require 'db.php';
session_start();

// Restrict to doctors only
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    header('Location: login.php');
    exit;
}


// Get doctor info (map user_id to doctor_id)
$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT id, name FROM doctors WHERE user_id = ? LIMIT 1");
$stmt->execute([$user_id]);
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
    echo "<p style='color:red'>❌ No doctor profile linked to this account.</p>";
    require 'footer.php';
    exit;
}

$doctor_id = $doctor['id']; // use this in bookings query


// Fetch only this doctor’s bookings
$stmt = $pdo->prepare("
    SELECT b.*, u.name AS client_name, u.email AS client_email, ts.slot_label, s.name AS service_name
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    JOIN time_slots ts ON b.slot_id = ts.id
    LEFT JOIN users u ON b.user_id = u.id
    WHERE b.doctor_id = ?
    ORDER BY b.booking_date DESC
");
$stmt->execute([$doctor_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>

<h2>👨‍⚕️ Therapist Dashboard</h2>
<p>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</p>

<table border="1" cellpadding="8" style="width:100%; border-collapse:collapse;">
  <tr>
    <th>Date</th>
    <th>Time</th>
    <th>Service</th>
    <th>Client</th>
    <th>Status</th>
  </tr>
  <?php foreach ($bookings as $b): ?>
    <tr>
      <td><?= htmlspecialchars($b['booking_date']) ?></td>
      <td><?= htmlspecialchars($b['slot_label']) ?></td>
      <td><?= htmlspecialchars($b['service_name']) ?></td>
      <td><?= htmlspecialchars($b['client_name'] ?? $b['guest_name']) ?> (<?= htmlspecialchars($b['client_email'] ?? $b['guest_email']) ?>)</td>
      <td><?= htmlspecialchars($b['status']) ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<?php require 'footer.php'; ?>
