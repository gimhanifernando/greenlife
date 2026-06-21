<?php
require 'db.php';
$message = "";
$messageClass = "green";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // --- Validation ---
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Invalid email format.";
        $messageClass = "red";
    } elseif (!preg_match('/^07[0-9]{8}$/', $phone)) {
        $message = "❌ Phone must be in format 07XXXXXXXX.";
        $messageClass = "red";
    } elseif ($password !== $confirm) {
        $message = "❌ Passwords do not match.";
        $messageClass = "red";
    } else {
        // Check duplicate email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = "❌ This email is already registered.";
            $messageClass = "red";
        } else {
            // Hash password
            $hashed = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO users (name,email,password,phone,role) VALUES (?,?,?,?, 'client')");
            if ($stmt->execute([$name,$email,$hashed,$phone])) {
                $user_id = $pdo->lastInsertId();

                // ✅ Store session in SAME format as login.php
                $_SESSION['user'] = [
                    'id'   => $user_id,
                    'name' => $name,
                    'role' => 'client'
                ];

                $message = "✅ Account created successfully! Redirecting to home...";
                $messageClass = "green";

                // Redirect after 2s
                echo "<meta http-equiv='refresh' content='2;url=index.php'>";
            } else {
                $message = "❌ Something went wrong.";
                $messageClass = "red";
            }
        }
    }
}
?>

<?php require 'header.php'; ?>

<h2 style="text-align:center">📝 Create Your Account</h2>

<?php if ($message): ?>
  <p style="text-align:center; color:<?= $messageClass ?>; font-weight:bold;"><?= $message ?></p>
<?php endif; ?>

<form method="post" style="max-width:400px; margin:20px auto;" id="signupForm">
  <label>Full Name:</label>
  <input type="text" name="name" required><br><br>

  <label>Email:</label>
  <input type="email" name="email" required><br><br>

  <label>Contact Number:</label>
  <input type="text" name="phone" placeholder="07XXXXXXXX" required><br><br>

  <label>Password:</label>
  <input type="password" name="password" required><br><br>

  <label>Confirm Password:</label>
  <input type="password" name="confirm_password" required><br><br>

  <button type="submit">✅ Create Account</button>
  <a href="login.php"><button type="button">Cancel</button></a>
</form>

<p style="text-align:center">
  Already have an account? <a href="login.php">Login here</a>
</p>

<?php require 'footer.php'; ?>
