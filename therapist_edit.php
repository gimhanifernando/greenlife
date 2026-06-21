<?php
// therapist_edit.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';


// ensure admin only
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// id comes from GET (when clicking edit) or POST (after submitting)
$id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);

if ($id <= 0) {
    header('Location: admin_therapists.php?msg=invalid_id');
    exit;
}

$error = '';

// handle POST update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $service_id = (int)$_POST['service_id'];
    $bio = trim($_POST['bio']);

    if ($name === '' || $service_id <= 0) {
        $error = 'Name and Service are required.';
    } else {
        $update = $pdo->prepare("UPDATE doctors SET name=?, service_id=?, bio=? WHERE id=?");
        if ($update->execute([$name, $service_id, $bio, $id])) {
            header('Location: admin_therapists.php?msg=updated');
            exit;
        } else {
            $error = 'Database update failed.';
        }
    }
}

// fetch services
$services = $pdo->query("SELECT id, name FROM services ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// fetch doctor record
$stmt = $pdo->prepare("SELECT * FROM doctors WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
    header('Location: admin_therapists.php?msg=notfound');
    exit;
}

require 'header.php';
?>

<link rel="stylesheet" href="style.css">

<div class="form-container">
  <h2>✏️ Edit Therapist</h2>

  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="post">
    <input type="hidden" name="id" value="<?= (int)$doctor['id'] ?>">

    <label>Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($doctor['name']) ?>" required>

    <label>Service</label>
    <select name="service_id" required>
      <option value="">-- select service --</option>
      <?php foreach ($services as $s): ?>
        <option value="<?= $s['id'] ?>" <?= $s['id']==$doctor['service_id'] ? 'selected':'' ?>>
          <?= htmlspecialchars($s['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Bio</label>
    <textarea name="bio" rows="5"><?= htmlspecialchars($doctor['bio']) ?></textarea>

    <button type="submit">💾 Save Changes</button>
    <a href="admin_therapists.php" class="btn-cancel">Cancel</a>
  </form>
</div>

<?php require 'footer.php'; ?>