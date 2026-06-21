<link rel="stylesheet" href="style.css">
<?php
// ✅ Show errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$db   = "greenlife";   // your DB name
$user = "root";        // default XAMPP user
$pass = "";            // default XAMPP password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ DB Connection failed: " . $e->getMessage());
}

// === EDIT THESE VALUES ===
$adminEmail = "admin@greenlife.local"; 
$newPassword = "Admin@123";   // New password you want
// ==========================

// Hash new password
$hashed = password_hash($newPassword, PASSWORD_DEFAULT);

// Update query
$stmt = $pdo->prepare("UPDATE users SET password=? WHERE email=? AND role='admin'");
if ($stmt->execute([$hashed, $adminEmail])) {
    if ($stmt->rowCount() > 0) {
        echo "✅ Admin password reset successful!<br>";
        echo "👉 Email: <b>$adminEmail</b><br>";
        echo "👉 New Password: <b>$newPassword</b><br>";
    } else {
        echo "⚠️ No admin user found with email $adminEmail";
    }
} else {
    echo "❌ Error updating password.";
}

echo "<br><br><b>⚠️ IMPORTANT: Delete reset_admin.php after using it for security.</b>";
?>
