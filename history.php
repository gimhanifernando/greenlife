<?php
require 'db.php';
session_start();

// Restrict to logged-in clients
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
// Fetch all bookings for this client
$stmt = $pdo->prepare("
    SELECT b.*, s.name AS service_name, d.name AS doctor_name, ts.slot_label
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    JOIN doctors d ON b.doctor_id = d.id
    JOIN time_slots ts ON b.slot_id = ts.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC, ts.slot_time
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>

<h2 style="text-align:center; margin:20px 0;">📜 My Booking History</h2>

<div class="table-container">
  <table class="styled-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Date</th>
        <th>Time</th>
        <th>Service</th>
        <th>Doctor</th>
        <th>Status</th>
        <th>Payment</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($bookings): ?>
        <?php foreach ($bookings as $b): ?>
          <tr>
            <td><?= htmlspecialchars($b['id']) ?></td>
            <td><?= htmlspecialchars($b['booking_date']) ?></td>
            <td><?= htmlspecialchars($b['slot_label']) ?></td>
            <td><?= htmlspecialchars($b['service_name']) ?></td>
            <td><?= htmlspecialchars($b['doctor_name']) ?></td>
            <td><?= htmlspecialchars($b['status']) ?></td>
            <td><?= htmlspecialchars($b['payment_status']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" style="text-align:center;">No bookings found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require 'footer.php'; ?>
