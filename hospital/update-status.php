<?php
require_once '../config/db.php'; need('hospital');
$u = me();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && in_array($_POST['state'], ['Vaccinated', 'Not Vaccinated']))
  q("UPDATE bookings SET status=? WHERE booking_id=? AND hospital_id=?", [$_POST['state'], $_POST['id'], $u['id']]);   // Vaccinated -> certificate becomes available
if ($_SERVER['REQUEST_METHOD'] == 'POST') go('hospital/update-status.php');

$title = 'Update Status'; include '../includes/header.php';
echo "<div class='card'><table><tr><th>Order</th><th>Child</th><th>Parent</th><th>Vaccine</th><th>Appointment date</th><th>Status</th><th>Update</th></tr>";
foreach (q(BOOKING_SQL . " WHERE b.hospital_id=? AND b.status IN ('Approved','Vaccinated','Not Vaccinated') ORDER BY b.appointment_date", [$u['id']])->fetchAll() as $r) {
  echo "<tr><td>" . order_no($r['id']) . "</td><td>" . e($r['child']) . "</td><td>" . e($r['parent']) . "</td><td>" . e($r['vaccine']) . "</td><td>{$r['appointment_date']}</td><td>" . badge($r['status']) . "</td><td>
  <form method='post' class='inline'><input type='hidden' name='id' value='{$r['id']}'>
  <button name='state' value='Vaccinated'>Vaccinated</button> <button class='danger' name='state' value='Not Vaccinated'>Not Vaccinated</button></form></td></tr>";
}
echo "</table></div>";
include '../includes/footer.php';
