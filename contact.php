<link rel="stylesheet" href="style.css">
<?php
require 'db.php';
$message = "";
$messageClass = "green";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $phone   = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $msg     = trim($_POST['message']);

    if (!$name || !$email || !$phone || !$subject || !$msg) {
        $message = "❌ All fields are required!";
        $messageClass = "red";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Please enter a valid email address.";
        $messageClass = "red";
    } elseif (!preg_match("/^07\d{8}$/", $phone)) {
        $message = "❌ Please enter a valid Sri Lankan mobile number (07XXXXXXXX).";
        $messageClass = "red";
    } else {
        $stmt = $pdo->prepare("INSERT INTO messages (name,email,phone,subject,message) VALUES (?,?,?,?,?)");
        if ($stmt->execute([$name,$email,$phone,$subject,$msg])) {
            $message = "✅ Thank you! Your message has been sent successfully.";
            $messageClass = "green";
        } else {
            $message = "❌ Failed to send your message. Please try again later.";
            $messageClass = "red";
        }
    }
}
?>

<?php require 'header.php'; ?>

<h2 style="text-align:center">📩 Contact Us</h2>

<?php if ($message): ?>
  <p style="text-align:center; color:<?= $messageClass ?>;"><?= $message ?></p>
<?php endif; ?>

<div style="max-width:600px; margin:20px auto; padding:15px; border-radius:10px; background:#f3fef3;">
  <h3>📍 Visit Our Center</h3>
  <p><strong>Address:</strong> 123 Wellness Avenue, Colombo 03, Sri Lanka</p>
  <p><strong>Phone:</strong> +94 11 234 5678</p>
  <p><strong>Email:</strong> info@greenlifewellness.lk</p>
  <p><strong>Hours:</strong> Monday - Saturday, 9:00 AM - 5:00 PM</p>
</div>

<form method="post" style="max-width:600px; margin:20px auto;">
  <label>Your Name:</label>
  <input type="text" name="name" required><br><br>

  <label>Your Email:</label>
  <input type="email" name="email" required><br><br>

  <label>Contact Number:</label>
  <input type="text" name="phone" placeholder="07XXXXXXXX" required><br><br>

  <label>Subject:</label>
  <input type="text" name="subject" required><br><br>

  <label>Message:</label>
  <textarea name="message" rows="4" required></textarea><br><br>

  <button type="submit">📨 Send Message</button>
  <button type="reset">❌ Cancel</button>
</form>

<?php require 'footer.php'; ?>
