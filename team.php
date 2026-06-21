<link rel="stylesheet" href="style.css">
<?php
require 'header.php';
require 'db.php';

// Fetch doctors with their service (added d.service_id)
$stmt = $pdo->query("
    SELECT d.id, d.name AS doctor_name, d.bio, d.service_id, s.name AS service_name 
    FROM doctors d
    JOIN services s ON d.service_id = s.id
    ORDER BY s.id
");
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 style="text-align:center; margin:30px 0;">👨🏻‍⚕️👩🏻‍⚕️ Meet Our Team</h2>

<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px,1fr)); gap:25px; padding:20px;">
  <?php foreach ($doctors as $doc): ?>
    <div style="background:#fff; border-radius:15px; padding:25px; box-shadow:0 4px 10px rgba(0,0,0,0.1); text-align:center;">
      
      <!-- Circle Icon -->
           <div style="width:100px; height:100px; border-radius:50%; background:#4CAF50; display:flex; align-items:center; justify-content:center; margin:0 auto 15px auto;">
        <?php if (strpos(strtolower($doc['service_name']), 'yoga') !== false): ?>
          <span style="font-size:50px; color:#fff;">🧘</span>
        <?php elseif (strpos(strtolower($doc['service_name']), 'meditation') !== false): ?>
          <span style="font-size:50px; color:#fff;">🙏</span>
        <?php elseif (strpos(strtolower($doc['service_name']), 'massage') !== false): ?>
          <span style="font-size:50px; color:#fff;">💆</span>
        <?php elseif (strpos(strtolower($doc['service_name']), 'nutrition') !== false): ?>
          <span style="font-size:50px; color:#fff;">🍏</span>
        <?php elseif (strpos(strtolower($doc['service_name']), 'physio') !== false): ?>
          <span style="font-size:50px; color:#fff;">🏋️</span>
        <?php elseif (strpos(strtolower($doc['service_name']), 'mental') !== false): ?>
          <span style="font-size:50px; color:#fff;">🧠</span>
        <?php else: ?>
          <span style="font-size:50px; color:#fff;">🌿</span>
        <?php endif; ?>
       </div>

      <!-- Doctor Info -->
      <h3 style="margin:10px 0;"><?= htmlspecialchars($doc['doctor_name']) ?></h3>
      <p style="font-weight:bold; color:#444;"><?= htmlspecialchars($doc['service_name']) ?></p>
      <p style="font-size:14px; color:#666; margin:10px 0;"><?= nl2br(htmlspecialchars($doc['bio'])) ?></p>
      
      <!-- Book button -->
      <a href="book.php?service_id=<?= $doc['service_id'] ?>" 
         style="display:inline-block; margin-top:10px; padding:8px 15px; background:#4CAF50; color:#fff; text-decoration:none; border-radius:5px;">
        📅 Book Appointment
      </a>
    </div>
  <?php endforeach; ?>
</div>

<?php require 'footer.php'; ?>
