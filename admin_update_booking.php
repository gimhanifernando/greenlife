<?php
// admin_update_booking.php
require 'db.php';
session_start();

// ✅ Ensure only admin can update
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $payment_status = $_POST['payment_status'] ?? 'pending';

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE bookings SET payment_status = ? WHERE id = ?");
        $stmt->execute([$payment_status, $id]);
    }
}

// Redirect back to bookings page
header("Location: admin_bookings.php");
exit;
