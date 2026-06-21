<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name   = trim($_POST['name']);
    $rating = (int)$_POST['rating'];
    $review = trim($_POST['review']);

    if ($name && $rating && $review) {
        $stmt = $pdo->prepare("INSERT INTO reviews (name, rating, review, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $rating, $review]);
    }
}

// ✅ Always go back to homepage after submitting
header("Location: index.php");
exit;
?>