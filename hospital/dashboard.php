<?php
require_once '../config/db.php';
need('hospital');
$u = me();
$title = 'Dashboard';
include '../includes/header.php';
$s = [
  'Scheduled' => q("SELECT COUNT(*) FROM bookings WHERE hospital_id=? AND status='Approved'", [$u['id']])->fetchColumn(),
  'Vaccinated' => q("SELECT COUNT(*) FROM bookings WHERE hospital_id=? AND status='Vaccinated'", [$u['id']])->fetchColumn(),
];
echo "<div class='stats'>";
foreach ($s as $label => $n)
  echo "<div class='card stat'><small>$label</small><h2>$n</h2></div>";
echo "</div><div class='card'><h3>Scheduled appointments</h3><table><tr><th>Order</th><th>Child</th><th>Parent</th><th>Vaccine</th><th>Appointment date</th></tr>";
foreach (q(BOOKING_SQL . " WHERE b.hospital_id=? AND b.status='Approved' ORDER BY b.appointment_date", [$u['id']])->fetchAll() as $r)
  echo "<tr><td>" . order_no($r['id']) . "</td><td>" . e($r['child']) . "</td><td>" . e($r['parent']) . "</td><td>" . e($r['vaccine']) . "</td><td>{$r['appointment_date']}</td></tr>";
echo "</table><p><a class='btn' href='update-status.php'>Update status</a></p></div>";
include '../includes/footer.php';
