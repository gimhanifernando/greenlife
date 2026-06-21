<?php
require 'db.php';
header('Content-Type: application/json'); // must be first output

$service_id = $_GET['service_id'] ?? null;
$date = $_GET['date'] ?? null;
$doctor_id  = $_GET['doctor_id'] ?? null;

if (!$service_id || !$date){
  echo json_encode(['error'=>'missing']); exit;
}

// if doctor_id not passed → auto select doctor for this service
if (!$doctor_id) {
  $st = $pdo->prepare("SELECT id FROM doctors WHERE service_id=? LIMIT 1");
  $st->execute([$service_id]);
  $doc = $st->fetch();
  if (!$doc) { echo json_encode(['error'=>'no doctor']); exit; }
  $doctor_id = $doc['id'];
}

// get service info
$st = $pdo->prepare('SELECT * FROM services WHERE id=? LIMIT 1');
$st->execute([$service_id]); 
$service = $st->fetch();
if (!$service){ echo json_encode(['error'=>'invalid service']); exit; }

// all slots
$slots = $pdo->query('SELECT * FROM time_slots ORDER BY slot_time')->fetchAll();
$response = [];

foreach ($slots as $slot){
  $c = $pdo->prepare('SELECT COUNT(*) FROM bookings 
    WHERE service_id=? AND doctor_id=? AND booking_date=? AND slot_id=? AND status="booked"');
  $c->execute([$service_id,$doctor_id,$date,$slot['id']]);
  $cnt = (int)$c->fetchColumn();
  $available = true;
  $reason = '';

  if (!empty($service['is_group'])) {
    $allowed = ['14:00:00','15:30:00']; // example
    if (!in_array($slot['slot_time'],$allowed)){
      $available = false; $reason = 'not_group_slot';
    } else {
      if ($cnt >= 10){ $available = false; $reason='full'; }
    }
  } else {
    if ($cnt > 0){ $available = false; $reason='booked'; }
  }

  $response[] = [
    'id'=>$slot['id'],
    'label'=>$slot['slot_label'],
    'time'=>$slot['slot_time'],
    'available'=>$available,
    'reason'=>$reason
  ];
}

echo json_encode($response);
