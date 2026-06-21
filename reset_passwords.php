<?php
require 'db.php';

$users = [
    ['email' => 'admin@greenlife.local', 'password' => 'Admin@123'],
    ['email' => 'doc1@greenlife.local', 'password' => 'Doc1@111'],
    ['email' => 'doc2@greenlife.local', 'password' => 'Doc2@111'],
    ['email' => 'doc3@greenlife.local', 'password' => 'Doc3@111'],
    ['email' => 'doc4@greenlife.local', 'password' => 'Doc4@111'],
    ['email' => 'doc5@greenlife.local', 'password' => 'Doc5@111'],
    ['email' => 'doc6@greenlife.local', 'password' => 'Doc6@111'],
    ['email' => 'doc7@greenlife.local', 'password' => 'Doc7@111'],
];

foreach ($users as $u) {
    $hashed = password_hash($u['password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->execute([$hashed, $u['email']]);
    echo "✅ Updated password for " . $u['email'] . "<br>";
}

echo "<br>All passwords reset successfully!";
