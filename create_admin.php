<link rel="stylesheet" href="style.css">
<?php
// ✅ Show all PHP errors (so you never get a blank page)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";   // usually localhost for XAMPP
$user = "root";        // default MySQL user in XAMPP
$pass = "";            // default MySQL password is empty
$db   = "greenlife";   // ✅ your database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// === EDIT THESE VALUES TO YOUR NEED ===
$adminName  = "Admin";
$adminEmail = "admin@greenlife.local";
$adminPass  = "Admin@123";  // plain password (will be hashed)
// ======================================

// Hash the password securely (bcrypt)
$hashedPass = password_hash($adminPass, PASSWORD_DEFAULT);

// Check if admin already exists
$check = $conn->prepare("SELECT * FROM users WHERE email=?");
$check->bind_param("s", $adminEmail);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "⚠️ Admin with email <b>$adminEmail</b> already exists!";
} else {
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
    $stmt->bind_param("sss", $adminName, $adminEmail, $hashedPass);

    if ($stmt->execute()) {
        echo "✅ Admin account created successfully!<br><br>";
        echo "👉 Email: <b>$adminEmail</b><br>";
        echo "👉 Password: <b>$adminPass</b><br><br>";
        echo "⚠️ Please <b>delete create_admin.php</b> after running it once for security.";
    } else {
        echo "❌ Error creating admin: " . $stmt->error;
    }
}

$conn->close();
?>

