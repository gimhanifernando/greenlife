<link rel="stylesheet" href="style.css">
<?php
require 'db.php';
<link rel="stylesheet" href="style.css">
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
  header('Location: book.php'); exit;
}

// get fields
$user_id = $_SESSION['user']['id'] ?? null;
$guest_name = trim($_POST['guest_name'] ?? '');
$guest_email = trim($_POST['guest_email'] ?? '');
$guest_phone = trim($_POST['guest_phone'] ?? '');
$service_id = $_POST['service_id'] ?? null;
$doctor_id = $_POST['doctor_id'] ?? null;
$slot_id = $_POST['slot_id'] ?? null;
$date = $_POST['booking_date'] ?? null;

if (!$service_id || !$doctor_id || !$slot_id || !$date){
  echo 'Missing data'; exit;
}

// re-check availability
$st = $pdo->prepare('SELECT is_group FROM services WHERE id=? LIMIT 1');
$st->execute([$service_id]); $service = $st->fetch();

$countStmt = $pdo->prepare('SELECT COUNT(*) FROM bookings WHERE service_id=? AND doctor_id=? AND booking_date=? AND slot_id=? AND status="booked"');
$countStmt->execute([$service_id,$doctor_id,$date,$slot_id]);
$cnt = (int)$countStmt->fetchColumn();

if ($service['is_group']){
  if ($cnt >= 10){ echo 'Slot full'; exit; }
} else {
  if ($cnt > 0){ echo 'Slot already taken'; exit; }
}

// insert booking (user_id nullable)
$ins = $pdo->prepare('INSERT INTO bookings (user_id,guest_name,guest_email,guest_phone,service_id,doctor_id,slot_id,booking_date) VALUES (?,?,?,?,?,?,?,?)');
$ins->execute([$user_id,$guest_name,$guest_email,$guest_phone,$service_id,$doctor_id,$slot_id,$date]);

// return success (in production redirect to confirmation page)
if (isset($_SESSION['user'])) header('Location: history.php');
else {
  echo "<script>alert('Booking confirmed! Please complete payment as shown.'); window.location='index.php';</script>";
}
