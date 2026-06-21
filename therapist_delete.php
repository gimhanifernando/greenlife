<?php
// therapist_delete.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: admin_therapists.php?msg=invalid_id');
    exit;
}

// NOTE: If bookings reference doctor_id and delete is blocked by FK, consider first reassigning bookings
// or deleting them. If your FK has ON DELETE CASCADE, this will delete bookings automatically.
try {
    $stmt = $pdo->prepare("DELETE FROM doctors WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin_therapists.php?msg=deleted');
    exit;
} catch (PDOException $e) {
    // foreign key constraint or other DB error
    header('Location: admin_therapists.php?msg=error&dberr=' . urlencode($e->getMessage()));
    exit;
}
