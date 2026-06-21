<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
$role = $_SESSION['user']['role'] ?? null;
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>GreenLife Wellness</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header">
  <div class="container">
    <div class="logo"><a href="index.php">GreenLife Wellness</a></div>

    <?php if ($role === 'client' || !$role): ?>
      <!-- Normal nav for clients/guests -->
      <nav class="main-nav">
        <a href="index.php">Home</a>
        <a href="services.php">Services</a>
        <a href="team.php">Our Team</a>
        <a href="book.php">Book Appointment</a>
        <a href="contact.php">Contact</a>
      </nav>
    <?php endif; ?>

    <div class="account">
      <?php if (!empty($_SESSION['user'])): ?>
        <div class="user-menu">
          <span class="user-name">
            <?= htmlspecialchars($_SESSION['user']['name']) ?> ▾
          </span>
          <div class="user-dropdown">
            <?php if ($role === 'admin'): ?>
              <a href="admin_dashboard.php">Dashboard</a>
            <?php elseif ($role === 'doctor'): ?>
              <a href="therapist_dashboard.php">Dashboard</a>
            <?php else: ?>
              <a href="profile.php">Profile</a>
              <a href="history.php">Booking History</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a class="btn small" href="login.php">Login</a>
        <a class="btn small primary" href="signup.php">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<main class="container">
