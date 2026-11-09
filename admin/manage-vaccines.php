<?php
require_once '../config/db.php'; need('admin');
if (isset($_GET['toggle'])) {
  q("UPDATE vaccines SET status = IF(status='Available','Unavailable','Available') WHERE vaccine_id=?", [$_GET['toggle']]);
  go('admin/manage-vaccines.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  q("INSERT INTO vaccines (vaccine_name,description) VALUES (?,?)", [$_POST['vaccine_name'], $_POST['description']]);
  go('admin/manage-vaccines.php');
}
$title = 'Vaccines'; include '../includes/header.php';
echo "<div class='card'><form method='post' class='row'><input name='vaccine_name' placeholder='Vaccine name' required>
<input name='description' placeholder='Description' style='min-width:280px'><button>Add vaccine</button></form></div>";

// parent names shown near each vaccine they chose
$by = [];
foreach (q(BOOKING_SQL)->fetchAll() as $p)
  $by[$p['vaccine_id']][] = "<span class='badge info'>" . e($p['parent']) . " (" . e($p['child']) . ")</span>";

echo "<div class='card'><table><tr><th>Vaccine</th><th>Description</th><th>Availability</th><th>Chosen by</th><th></th></tr>";
foreach (q("SELECT * FROM vaccines")->fetchAll() as $v) {
  $mark = $v['status'] == 'Available' ? "<span class='badge ok'>✓ Available</span>" : "<span class='badge bad'>✗ Unavailable</span>";
  echo "<tr><td>" . e($v['vaccine_name']) . "</td><td>" . e($v['description']) . "</td><td>$mark</td>
  <td>" . (isset($by[$v['vaccine_id']]) ? implode(' ', $by[$v['vaccine_id']]) : '-') . "</td>
  <td><a class='btn' href='?toggle={$v['vaccine_id']}'>Toggle</a></td></tr>";
}
echo "</table></div>";
include '../includes/footer.php';
