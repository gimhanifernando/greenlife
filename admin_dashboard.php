<?php
require 'db.php';
session_start();

// Restrict to admins only
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require 'header.php';
?>

<h2>⚙️ Admin Dashboard</h2>
<p>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</p>

<div style="display:grid; gap:20px; margin:30px;">
  <a href="admin_bookings.php" style="padding:15px; border:1px solid #ccc; border-radius:10px; text-decoration:none; display:block;">
    📅 Manage All Bookings
  </a>
  <a href="admin_therapists.php" style="padding:15px; border:1px solid #ccc; border-radius:10px; text-decoration:none; display:block;">
    👨‍⚕️ Go to Therapists
  </a>
  <a href="admin_clients.php" style="padding:15px; border:1px solid #ccc; border-radius:10px; text-decoration:none; display:block;">
    👥 View Client Profiles
  </a>
  <a href="admin_messages.php" style="padding:15px; border:1px solid #ccc; border-radius:10px; text-decoration:none; display:block;">
    📩 Respond to Contact Queries
  </a>
</div>

<?php require 'footer.php'; ?>
