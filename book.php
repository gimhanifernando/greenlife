<?php
require 'db.php';
session_start();
$message = "";
$messageClass = "green"; // default success color

// --- Fetch all services ---
$services = $pdo->query("SELECT id, name FROM services")->fetchAll(PDO::FETCH_ASSOC);

$selected_service = isset($_POST['service_id']) ? (int)$_POST['service_id'] : 0;
$selected_slot    = isset($_POST['slot_id']) ? (int)$_POST['slot_id'] : 0;
$selected_date    = isset($_POST['booking_date']) ? $_POST['booking_date'] : "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $service_id = $selected_service;
    $slot_id    = $selected_slot;
    $date       = $selected_date;

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // --- Auto-fetch doctor for this service ---
    $stmt = $pdo->prepare("SELECT id, name FROM doctors WHERE service_id=? LIMIT 1");
    $stmt->execute([$service_id]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$doctor) {
        $message = "❌ No doctor assigned for this service.";
        $messageClass = "red";
    } else {
        $doctor_id = $doctor['id'];
    }

    // --- Group booking rules (Yoga=2, Meditation=3) ---
    if (in_array($service_id, [2, 3]) && !$message) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings 
            WHERE service_id=? AND slot_id=? AND booking_date=? AND status='booked'");
        $stmt->execute([$service_id, $slot_id, $date]);
        $count = $stmt->fetchColumn();

        if ($count >= 10) {
            $message = "❌ This group class is already full (max 10 people).";
            $messageClass = "red";
        }
    }

    // --- Save booking if no error ---
    if (!$message) {
        $stmt = $pdo->prepare("INSERT INTO bookings 
            (user_id, guest_name, guest_email, guest_phone, service_id, doctor_id, slot_id, booking_date) 
            VALUES (?,?,?,?,?,?,?,?)");

        if ($stmt->execute([$user_id, $name, $email, $phone, $service_id, $doctor_id, $slot_id, $date])) {
            $message = "✅ Booking confirmed with <b>" . htmlspecialchars($doctor['name']) . "</b>!<br>
            Please transfer Rs.2,000.00 to account number 
            <b>123-456-789</b> <b>ABC Bank PLC</b> <b>Colombo 03 Branch</b> 
            and bring the receipt on your visit.";
            $messageClass = "green";
        } else {
            $message = "❌ Something went wrong.";
            $messageClass = "red";
        }
    }
}
?>

<?php require 'header.php'; ?>

<h2 style="text-align:center">📅 Book an Appointment</h2>

<?php if ($message): ?>
  <p style="text-align:center; color:<?= $messageClass ?>;"><?= $message ?></p>
<?php endif; ?>

<form method="post" style="max-width:500px; margin:20px auto;" id="bookingForm">
  <label>Service:</label>
  <select name="service_id" required>
    <option value="">-- Select Service --</option>
    <?php foreach ($services as $s): ?>
      <option value="<?= $s['id'] ?>" <?= $s['id']==$selected_service?'selected':'' ?>>
        <?= htmlspecialchars($s['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <br><br>

  <label>Date:</label>
  <input type="date" name="booking_date" 
         value="<?= htmlspecialchars($selected_date) ?>" 
         min="<?= date('Y-m-d') ?>" required>
  <br><br>

  <label>Time Slot:</label>
  <select name="slot_id" required>
    <option value="">-- Select Time --</option>
  </select>
  <br><br>

  <?php if (isset($_SESSION['user_id'])): ?>
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required><br><br>
    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" required><br><br>
    <label>Phone:</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($_SESSION['user_phone'] ?? '') ?>"><br><br>
  <?php else: ?>
    <label>Name:</label>
    <input type="text" name="name" required><br><br>
    <label>Email:</label>
    <input type="email" name="email" required><br><br>
    <label>Phone:</label>
    <input type="text" name="phone"><br><br>
  <?php endif; ?>

  <button type="submit" name="submit">Book Now</button>
</form>

<script>
const serviceSelect = document.querySelector("select[name='service_id']");
const dateInput     = document.querySelector("input[name='booking_date']");
const slotSelect    = document.querySelector("select[name='slot_id']");

function loadSlots(){
  let serviceId = serviceSelect.value;
  let date = dateInput.value;

  if(!serviceId || !date){
    slotSelect.innerHTML = "<option value=''>-- Select Time --</option>";
    return;
  }

  fetch("check_slots.php?service_id=" + serviceId + "&date=" + date)
    .then(res => res.json())
    .then(data => {
      console.log("Slots received:", data); // ✅ Debug
      slotSelect.innerHTML = "<option value=''>-- Select Time --</option>";
      if(data.error){
        slotSelect.innerHTML = "<option value=''>No slots available</option>";
        return;
      }
      data.forEach(s => {
        let opt = document.createElement("option");
        opt.value = s.id;
        opt.textContent = s.label;

        if (!s.available) {
          opt.disabled = true;
          if (s.reason === "booked") opt.textContent += " (Booked)";
          if (s.reason === "full") opt.textContent += " (Full)";
        }

        slotSelect.appendChild(opt);
      });
    })
    .catch(err => {
      console.error("Error loading slots:", err);
      slotSelect.innerHTML = "<option value=''>Error loading slots</option>";
    });
}

serviceSelect.addEventListener("change", loadSlots);
dateInput.addEventListener("change", loadSlots);
</script>

<?php require 'footer.php'; ?>
