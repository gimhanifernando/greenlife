<link rel="stylesheet" href="style.css">
<?php
session_start();  
require 'db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require 'header.php';

$sql = "SELECT b.*, 
               s.name as service_name, 
               d.name as doctor_name, 
               ts.slot_label
        FROM bookings b
        JOIN services s ON b.service_id = s.id
        JOIN doctors d ON b.doctor_id = d.id
        JOIN time_slots ts ON b.slot_id = ts.id
        ORDER BY b.booking_date DESC, b.slot_id";

$bookings = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>All Bookings</h2>
<table border="1" cellpadding="8" style="width:100%;border-collapse:collapse">
<tr>
  <th>ID</th><th>Client</th><th>Service</th><th>Doctor</th><th>Date</th><th>Slot</th><th>Status</th><th>Payment</th><th>Action</th>
</tr>
<?php foreach($bookings as $b): ?>
  <tr>
    <td><?= $b['id'] ?></td>
    
    <td>
      <?= htmlspecialchars($b['guest_name'] ?: ($b['user_id'] ? 'User#'.$b['user_id'] : 'Guest')) ?><br>
      <?= htmlspecialchars($b['guest_email']) ?>
    </td>
    <td><?= htmlspecialchars($b['service_name']) ?></td>
    <td><?= htmlspecialchars($b['doctor_name']) ?></td>
    <td><?= htmlspecialchars($b['booking_date']) ?></td>
    <td><?= htmlspecialchars($b['slot_label']) ?></td>
    <td><?= htmlspecialchars($b['status']) ?></td>
    <td id="pay-<?=$b['id']?>"><?= htmlspecialchars($b['payment_status']) ?></td>
    <td style="white-space:nowrap; text-align:center;">
        <form method="post" action="admin_update_booking.php" style="display:flex; align-items:center; gap:5px; margin:0;">
       <input type="hidden" name="id" value="<?= $b['id'] ?>">
        <select name="payment_status" style="padding:4px; font-size:14px;">
        <option value="pending" <?= $b['payment_status']=='pending'? 'selected':'' ?>>pending</option>
        <option value="received" <?= $b['payment_status']=='received'? 'selected':'' ?>>received</option>
     </select>
    <button type="submit" style="padding:4px 10px; background:#28a745; color:white; border:none; border-radius:4px; cursor:pointer;">
      Update
    </button>
  </form>
</td>
  </tr>
<?php endforeach; ?>
</table>

<?php require 'footer.php'; ?>
