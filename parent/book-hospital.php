<?php
require_once '../config/db.php'; need('parent');
$u = me();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {                    // book another vaccine for an existing child
  if (q("SELECT child_id FROM children WHERE child_id=? AND parent_id=?", [$_POST['child_id'], $u['id']])->fetch())
    q("INSERT INTO bookings (parent_id,child_id,hospital_id,vaccine_id,appointment_date) VALUES (?,?,?,?,?)",
      [$u['id'], $_POST['child_id'], $_POST['hospital_id'], $_POST['vaccine_id'], $_POST['appointment_date']]);
  go('parent/dashboard.php');
}
$search = trim($_GET['search'] ?? '');
$hospitals = q("SELECT * FROM hospitals WHERE hospital_name LIKE ? OR location_details LIKE ?", ["%$search%", "%$search%"])->fetchAll();
$title = 'Book Hospital'; include '../includes/header.php';
?>
<div class="card"><form method="get" class="row">
  <input name="search" placeholder="Search hospital or area (e.g. Clifton)" value="<?= e($search) ?>">
  <button>Search</button>
</form></div>

<div class="card" style="max-width:520px"><h3>Book vaccine</h3>
<form method="post" class="col">
  <label>Child</label>
  <select name="child_id" required><option value="">Choose child</option>
  <?php foreach (q("SELECT * FROM children WHERE parent_id=?", [$u['id']])->fetchAll() as $c): ?>
    <option value="<?= $c['child_id'] ?>"><?= e($c['child_name']) ?></option>
  <?php endforeach; ?></select>
  <label>Vaccine</label>
  <select name="vaccine_id" required><option value="">Choose vaccine</option>
  <?php foreach (q("SELECT * FROM vaccines WHERE status='Available'")->fetchAll() as $v): ?>
    <option value="<?= $v['vaccine_id'] ?>"><?= e($v['vaccine_name']) ?></option>
  <?php endforeach; ?></select>
  <label>Hospital (<?= count($hospitals) ?> found)</label>
  <select name="hospital_id" required><option value="">Choose hospital</option>
  <?php foreach ($hospitals as $h): ?>
    <option value="<?= $h['hospital_id'] ?>"><?= e($h['hospital_name']) ?> (<?= e($h['location_details']) ?>)</option>
  <?php endforeach; ?></select>
  <label>Appointment date</label><input name="appointment_date" type="date" min="<?= date('Y-m-d') ?>" required>
  <button>Book</button>
</form></div>
<?php include '../includes/footer.php'; ?>
