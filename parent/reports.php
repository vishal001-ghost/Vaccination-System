<?php
require_once '../config/db.php'; need('parent');
$u = me();

if (isset($_GET['certificate'])) {                             // printable certificate
  $r = certificate_row($_GET['certificate']);
  if (!$r || $r['parent_id'] != $u['id']) { echo 'Certificate not found.'; exit; }
  ?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Certificate - PakVac</title><link rel="stylesheet" href="<?= BASE ?>/assets/css/style.css"></head>
<body class="cert-page"><div class="cert">
  <div class="logo dark"><?= pakvac_logo() ?></div>
  <h1>Vaccination Certificate</h1>
  <p>This is to certify that</p>
  <h2><?= e($r['child']) ?></h2>
  <p>Date of birth: <?= e($r['dob']) ?><br>Child of <b><?= e($r['parent']) ?></b></p>
  <p>has been vaccinated with</p>
  <h2><?= e($r['vaccine']) ?></h2>
  <p>at <b><?= e($r['hospital']) ?></b><br><?= e($r['address']) ?><br>on <b><?= e($r['appointment_date']) ?></b></p>
  <p><small>Certificate no. <?= order_no($r['id']) ?></small></p>
  <button class="noprint" onclick="window.print()">Print / Save as PDF</button>
</div></body></html>
<?php exit; }

$title = 'Reports'; include '../includes/header.php';
echo "<div class='card'><h3>Vaccination history</h3><table><tr><th>Child</th><th>Vaccine</th><th>Hospital</th><th>Appointment date</th><th>Status</th><th></th></tr>";
foreach (q(BOOKING_SQL . " WHERE b.parent_id=? ORDER BY b.booking_id DESC", [$u['id']])->fetchAll() as $r) {
  echo "<tr><td>" . e($r['child']) . "</td><td>" . e($r['vaccine']) . "</td><td>" . e($r['hospital']) . "</td><td>{$r['appointment_date']}</td><td>" . status_badge($r['status']) . "</td><td>";
  if ($r['status'] == 'Vaccinated') echo "<a class='btn' target='_blank' href='?certificate={$r['id']}'>Certificate</a>";
  echo "</td></tr>";
}
echo "</table></div>";
include '../includes/footer.php';
