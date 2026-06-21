<?php
session_start();   // 🔹 Add this at the very top
require 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // ✅ Store session in the same format as header.php expects
        $_SESSION['user'] = [
            'id'   => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
            exit;
            
        } elseif ($user['role'] === 'doctor') {
            header("Location: therapist_dashboard.php");
            exit;
        } else {
            header("Location: index.php"); // normal client
            exit;
        }
    } else {
        $message = "❌ Invalid email or password!";
    }
}
?>

<?php require 'header.php'; ?>

<h2 style="text-align:center">🔑 Login to Your Account</h2>

<?php if ($message): ?>
  <p style="text-align:center;color:red;"><?= $message ?></p>
<?php endif; ?>

<form method="post" style="max-width:400px;margin:20px auto;">
  <label>Email:</label>
  <input type="email" name="email" required><br><br>

  <label>Password:</label>
  <input type="password" name="password" required><br><br>

  <button type="submit">Login</button>
</form>

<p style="text-align:center">Don’t have an account? <a href="signup.php">Sign up here</a></p>

<?php require 'footer.php'; ?>
