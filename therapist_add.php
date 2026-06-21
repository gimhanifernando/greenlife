<?php
require 'db.php';
session_start();

// ✅ Only allow admin access
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $service_id = $_POST['service_id'];
    $bio = trim($_POST['bio']);

    if (!empty($name) && !empty($service_id) && !empty($bio)) {
        $stmt = $pdo->prepare("INSERT INTO doctors (name, service_id, bio) VALUES (?, ?, ?)");
        $stmt->execute([$name, $service_id, $bio]);

        header("Location: admin_therapists.php?success=1");
        exit;
    }
}

// ✅ Fetch available services
$services = $pdo->query("SELECT * FROM services")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require 'header.php'; ?>
<link rel="stylesheet" href="style.css">

<h2 style="text-align:center; margin:20px 0;">➕ Add Therapist</h2>

<div class="form-container">
  <form method="post">
    <label for="name">👤 Name:</label>
    <input type="text" id="name" name="name" placeholder="Enter therapist name" required>

    <label for="service">💆 Service:</label>
    <select id="service" name="service_id" required>
      <option value="">-- Select Service --</option>
      <?php foreach($services as $s): ?>
        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="bio">📝 Bio:</label>
    <textarea id="bio" name="bio" rows="4" placeholder="Short description" required></textarea>

    <div style="margin-top:15px;">
      <button type="submit" class="btn">💾 Save</button>
      <a href="admin_therapists.php" class="btn cancel-btn">❌ Cancel</a>
    </div>
  </form>
</div>

<?php require 'footer.php'; ?>
