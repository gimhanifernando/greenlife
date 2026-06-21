<link rel="stylesheet" href="style.css">
<?php
require 'header.php';
require 'db.php';
// Fetch all services
$stmt = $pdo->query("SELECT * FROM services ORDER BY id");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 style="text-align:center; margin:30px 0;">✨ Our Services</h2>

<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px,1fr)); gap:25px; padding:20px;">
  <?php foreach ($services as $s) { ?>
    <div style="background:#fff; border-radius:15px; padding:25px; box-shadow:0 4px 10px rgba(0,0,0,0.1); text-align:center;">
      
      <!-- Service Icon -->
      <div style="width:80px; height:80px; border-radius:50%; background:#4CAF50; display:flex; align-items:center; justify-content:center; margin:0 auto 15px auto; font-size:40px; color:#fff;">
        <?php 
        $name = strtolower($s['name']);
        if (strpos(strtolower($name), 'yoga') !== false) {
    echo "🧘";
        } elseif (strpos(strtolower($name), 'meditation') !== false) {
    echo "🙏";
        } elseif (strpos(strtolower($name), 'massage') !== false) {
    echo "💆";
        } elseif (strpos(strtolower($name), 'nutrition') !== false) {
    echo "🍏";
            } elseif (strpos(strtolower($name), 'physio') !== false) {
    echo "🏋️";
        } elseif (strpos(strtolower($name), 'mental') !== false) {
    echo "🧠";
        } else {
    echo "🌿";
        }
        ?>
      </div>

      <!-- Service Info -->
      <h3><?= htmlspecialchars($s['name']) ?></h3>
      <p style="font-size:14px; color:#555;"><?= nl2br(htmlspecialchars($s['description'])) ?></p>
      <p style="font-weight:bold; margin:10px 0; color:#333;"><?= htmlspecialchars($s['price']) ?></p>

      <!-- Book Now Button -->
      <a href="book.php?service_id=<?= $s['id'] ?>" 
         style="display:inline-block; margin-top:10px; padding:8px 15px; background:#4CAF50; color:#fff; text-decoration:none; border-radius:5px;">
        📅 Book Now
      </a>
    </div>
  <?php } ?>
</div>

<?php require 'footer.php'; ?>
