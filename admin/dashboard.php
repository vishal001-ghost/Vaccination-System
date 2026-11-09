<?php
require_once '../config/db.php'; need('admin');
$title = 'Dashboard'; include '../includes/header.php';

$s = [
  'Parents'      => q("SELECT COUNT(*) FROM parents")->fetchColumn(),
  'Children'     => q("SELECT COUNT(*) FROM children")->fetchColumn(),
  'New requests' => q("SELECT COUNT(*) FROM bookings WHERE status='Pending'")->fetchColumn(),
  'Vaccinated'   => q("SELECT COUNT(*) FROM bookings WHERE status='Vaccinated'")->fetchColumn(),
];
echo "<div class='stats'>";
foreach ($s as $label => $n) echo "<div class='card stat'><small>$label</small><h2>$n</h2></div>";
echo "</div><div class='card'><h3>Children added</h3><table><tr><th>Child</th><th>Parent</th><th>Parent email</th><th>Vaccine</th><th>Hospital</th><th>Date</th><th>Status</th><th>Certificate</th></tr>";
foreach (q(BOOKING_SQL . " ORDER BY b.booking_id DESC")->fetchAll() as $r)
  echo "<tr><td>" . e($r['child']) . "</td><td>" . e($r['parent']) . "</td><td>" . e($r['parent_email']) . "</td><td>" . e($r['vaccine']) . "</td><td>" . e($r['hospital']) . "</td><td>{$r['appointment_date']}</td><td>" . badge($r['status']) . "</td><td>" . send_form($r, 'dashboard.php') . "</td></tr>";
echo "</table></div>";
include '../includes/footer.php';
