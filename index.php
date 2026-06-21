
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'header.php';

require 'db.php';
?>
<link rel="stylesheet" href="style.css">

<!-- ================= HERO SECTION ================= -->
<section style="background:#2e8b57; color:white; text-align:center; padding:60px 20px;">
  <h1>🍀 Welcome to GreenLife Wellness Center</h1>
  <p style="font-size:18px; margin-top:10px;">
    Your journey to holistic wellness begins here. Discover natural healing
    through our comprehensive wellness programs in the heart of Colombo.
  </p>
  <a href="services.php" style="display:inline-block; margin-top:20px; padding:10px 20px; background:white; color:#2e8b57; border-radius:5px; text-decoration:none; font-weight:bold;">
    → Explore Our Services
  </a>
</section>

<!-- ================= SERVICES SECTION ================= -->
<h2 style="text-align:center; margin:40px 0;">✨ Our Services</h2>
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px,1fr)); gap:20px; margin:20px;">
  <?php
  $services = $pdo->query('SELECT * FROM services')->fetchAll(PDO::FETCH_ASSOC);
  foreach($services as $serv): ?>
    <div class="card" style="border:1px solid #ddd; border-radius:10px; padding:20px; text-align:center; background:#f9f9f9;">
      <h3><?= htmlspecialchars($serv['name']) ?></h3>
      <p><?= nl2br(htmlspecialchars($serv['description'])) ?></p>
      <p><strong><?= htmlspecialchars($serv['price']) ?></strong></p>
    </div>
  <?php endforeach; ?>
</div>

<!-- ================= WELLNESS CENTER SECTION ================= -->
<h2 style="text-align:center; margin:40px 0;">🏡 Our Wellness Center</h2>
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px,1fr)); gap:20px; margin:20px;">
  <div class="card" style="border:1px solid #ddd; border-radius:10px; padding:15px; background:#f9f9f9; text-align:center;">
    <img src="images/meditation_garden.jpeg" alt="Meditation Garden" style="width:100%; height:180px; object-fit:cover; border-radius:10px;">
    <h4 style="margin-top:10px;">Meditation Garden</h4>
    <p>Peaceful indoor space for yoga and meditation sessions</p>
  </div>

  <div class="card" style="border:1px solid #ddd; border-radius:10px; padding:15px; background:#f9f9f9; text-align:center;">
    <img src="images/treatment_rooms.jpeg" alt="Treatment Rooms" style="width:100%; height:180px; object-fit:cover; border-radius:10px;">
    <h4 style="margin-top:10px;">Treatment Rooms</h4>
    <p>Comfortable and serene spaces for therapy sessions</p>
  </div>

  <div class="card" style="border:1px solid #ddd; border-radius:10px; padding:15px; background:#f9f9f9; text-align:center;">
    <img src="images/herb_garden.jpeg" alt="Herb Garden" style="width:100%; height:180px; object-fit:cover; border-radius:10px;">
    <h4 style="margin-top:10px;">Herb Garden</h4>
    <p>Organic medicinal herbs grown on-site for Ayurvedic treatments</p>
  </div>
</div>
<!-- ================= REVIEWS SECTION ================= -->
<h2 style="text-align:center; margin:40px 0;">⭐ What Our Clients Say</h2>
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px,1fr)); gap:20px; margin:20px;">
  <?php
  $reviews = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
  foreach($reviews as $r): ?>
    <div class="card" style="border:1px solid #ddd; border-radius:10px; padding:20px; background:#fff;">
      <strong><?= htmlspecialchars($r['name']) ?></strong>
      <p>
        <?php for($i=0;$i<$r['rating'];$i++) echo "⭐"; ?>
      </p>
      <p>"<?= nl2br(htmlspecialchars($r['review'])) ?>"</p>
      <small><?= date("F j, Y", strtotime($r['created_at'])) ?></small>
    </div>
  <?php endforeach; ?>
</div>

<div style="text-align:center; margin:30px;">
  <button onclick="document.getElementById('reviewModal').style.display='block'"
          style="padding:10px 20px; background:#2e8b57; color:white; border:none; border-radius:5px; cursor:pointer;">
    + Add Your Review
  </button>
</div>

<!-- ================= REVIEW POPUP ================= -->
<div id="reviewModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
     background:rgba(0,0,0,0.6); text-align:center; padding-top:50px;">
  <div style="background:white; max-width:500px; margin:auto; padding:20px; border-radius:10px; position:relative;">
    <h3>✍ Share Your Experience</h3>
    <form method="post" action="submit_review.php">
      <label>Your Name:</label><br>
      <input type="text" name="name" required style="width:100%; padding:8px; margin:8px 0;"><br>
      <label>Rating:</label><br>
      <select name="rating" required style="width:100%; padding:8px; margin:8px 0;">
        <option value="">Select rating...</option>
        <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
        <option value="4">⭐⭐⭐⭐ Very Good</option>
        <option value="3">⭐⭐⭐ Good</option>
        <option value="2">⭐⭐ Fair</option>
        <option value="1">⭐ Poor</option>
      </select><br>
      <label>Your Review:</label><br>
      <textarea name="review" rows="4" required style="width:100%; padding:8px; margin:8px 0;"></textarea><br>
      <button type="submit" style="padding:10px 20px; background:#2e8b57; color:white; border:none; border-radius:5px;">✔ Submit Review</button>
      <button type="button" onclick="document.getElementById('reviewModal').style.display='none'"
              style="padding:10px 20px; background:#ccc; border:none; border-radius:5px;">✖ Cancel</button>
    </form>
  </div>
</div>

<script>
// Close modal when clicking outside
window.onclick = function(event) {
  let modal = document.getElementById('reviewModal');
  if (event.target == modal) modal.style.display = "none";
}
</script>

<?php require 'footer.php'; ?>
