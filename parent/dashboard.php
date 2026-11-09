<?php
require_once '../config/db.php';
need('parent');
$u = me();
$title = 'Dashboard';
include '../includes/header.php';
$rows = q(BOOKING_SQL . " WHERE b.parent_id=? ORDER BY b.booking_id DESC", [$u['id']])->fetchAll();

if (!$rows) {
  echo "<div class='card'><h3>Welcome, " . e($u['name']) . "!</h3><p>You have not added a child yet.</p><a class='btn' href='register-child.php'>+ Add your child</a></div>";
} else {
  echo "<p><a class='btn' href='register-child.php'>+ Add child</a> <a class='btn' href='book-hospital.php'>Book another vaccine</a></p>";
  // notifications
  foreach ($rows as $r) {
    if ($r['status'] == 'Approved')
      echo "<p class='alert good'>🔔 " . e($r['child']) . ": " . e($r['vaccine']) . " is approved. Visit " . e($r['hospital']) . " on {$r['appointment_date']}.</p>";
    if ($r['status'] == 'Vaccinated')
      echo "<p class='alert good'>✓ " . e($r['child']) . " is vaccinated (" . e($r['vaccine']) . "). Certificate is ready in Reports.</p>";
  }
  echo "<div class='card'><table><tr><th>Child</th><th>Parent</th><th>Vaccine</th><th>Hospital</th><th>Date</th><th>Status</th><th></th></tr>";
  foreach ($rows as $r) {
    echo "<tr><td>" . e($r['child']) . "</td><td>" . e($r['parent']) . "</td><td>" . e($r['vaccine']) . "</td><td>" . e($r['hospital']) . "</td><td>{$r['appointment_date']}</td><td>" . status_badge($r['status']) . "</td><td>";
    if ($r['status'] == 'Vaccinated')
      echo "<a class='btn' target='_blank' href='reports.php?certificate={$r['id']}'>Certificate</a>";
    echo "</td></tr>";
  }
  echo "</table></div>";
}
include '../includes/footer.php';
