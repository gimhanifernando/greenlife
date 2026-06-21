<link rel="stylesheet" href="style.css">
<?php
require 'db.php';


$service_id = $_GET['service_id'] ?? 0;

if ($service_id) {
    $stmt = $pdo->prepare("SELECT id, name FROM doctors WHERE service_id=?");
    $stmt->execute([$service_id]);
    $doctors = $stmt->fetchAll();

    if ($doctors) {
        foreach ($doctors as $doc) {
            echo "<option value='{$doc['id']}'>" . htmlspecialchars($doc['name']) . "</option>";
        }
    } else {
        echo "<option value=''>No doctors available</option>";
    }
} else {
    echo "<option value=''>Select a service first</option>";
}
