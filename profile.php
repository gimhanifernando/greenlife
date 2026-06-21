<link rel="stylesheet" href="style.css">
<?php
require 'db.php';
require 'header.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info (make sure these match your DB column names)
$st = $pdo->prepare("SELECT name, email, phone FROM users WHERE id=?");
$st->execute([$user_id]);
$user = $st->fetch(PDO::FETCH_ASSOC);

// Fetch booking history
$st = $pdo->prepare("
    SELECT b.id, b.booking_date, t.slot_label, s.name AS service_name, d.name AS doctor_name, b.status
    FROM bookings b
    JOIN time_slots t ON b.slot_id = t.id
    JOIN services s ON b.service_id = s.id
    JOIN doctors d ON b.doctor_id = d.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC
");
$st->execute([$user_id]);
$bookings = $st->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container" style="max-width:900px; margin:20px auto;">
    <h2 style="text-align:center; margin-bottom:20px;">👤 My Profile</h2>

    <!-- Personal Info -->
    <div class="card" style="margin-bottom:20px; padding:20px;">
        <h3>Personal Info</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($user['phone']) ?></p>
    </div>

    <!-- Booking History -->
    <div class="card" style="padding:20px;">
        <h3>📅 Booking History</h3>
        <?php if ($bookings): ?>
            <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f0f0f0;">
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Service</th>
                        <th>Doctor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['booking_date']) ?></td>
                        <td><?= htmlspecialchars($b['slot_label']) ?></td>
                        <td><?= htmlspecialchars($b['service_name']) ?></td>
                        <td><?= htmlspecialchars($b['doctor_name']) ?></td>
                        <td><?= htmlspecialchars($b['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </div>
</div>

<?php require 'footer.php'; ?>
