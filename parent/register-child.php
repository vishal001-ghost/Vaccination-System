<?php
require_once '../config/db.php'; need('parent');
$u = me();
$edit = isset($_GET['edit']) ? q("SELECT * FROM children WHERE child_id=? AND parent_id=?", [$_GET['edit'], $u['id']])->fetch() : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['id']))                                    // update child profile
    q("UPDATE children SET child_name=?, date_of_birth=?, gender=? WHERE child_id=? AND parent_id=?", [$_POST['name'], $_POST['dob'], $_POST['gender'], $_POST['id'], $u['id']]);
  else {                                                       // new child + vaccine + hospital + date
    q("INSERT INTO children (parent_id,child_name,date_of_birth,gender) VALUES (?,?,?,?)", [$u['id'], $_POST['name'], $_POST['dob'], $_POST['gender']]);
    q("INSERT INTO bookings (parent_id,child_id,hospital_id,vaccine_id,appointment_date) VALUES (?,?,?,?,?)",
      [$u['id'], $conn->insert_id, $_POST['hospital_id'], $_POST['vaccine_id'], $_POST['appointment_date']]);
  }
  go('parent/dashboard.php');
}
$title = $edit ? 'Update Child' : 'Register Child'; include '../includes/header.php';
?>
<div class="card" style="max-width:520px"><h3>Child details</h3>
<form method="post" class="col">
  <input type="hidden" name="id" value="<?= $edit['child_id'] ?? '' ?>">
  <input name="name" placeholder="Child name" value="<?= e($edit['child_name'] ?? '') ?>" required>
  <label>Date of birth</label><input name="dob" type="date" value="<?= e($edit['date_of_birth'] ?? '') ?>" required>
  <label>Gender</label>
  <select name="gender" required>
  <?php foreach (['Male','Female','Other'] as $g): ?>
    <option <?= ($edit['gender'] ?? '') == $g ? 'selected' : '' ?>><?= $g ?></option>
  <?php endforeach; ?></select>
  <?php if (!$edit): ?>
  <label>Vaccine you want</label>
  <select name="vaccine_id" required><option value="">Choose vaccine</option>
  <?php foreach (q("SELECT * FROM vaccines WHERE status='Available'")->fetchAll() as $v): ?>
    <option value="<?= $v['vaccine_id'] ?>"><?= e($v['vaccine_name']) ?></option>
  <?php endforeach; ?></select>
  <label>Hospital</label>
  <select name="hospital_id" required><option value="">Choose hospital</option>
  <?php foreach (q("SELECT * FROM hospitals")->fetchAll() as $h): ?>
    <option value="<?= $h['hospital_id'] ?>"><?= e($h['hospital_name']) ?> (<?= e($h['location_details']) ?>)</option>
  <?php endforeach; ?></select>
  <label>Appointment date</label><input name="appointment_date" type="date" min="<?= date('Y-m-d') ?>" required>
  <?php endif; ?>
  <button><?= $edit ? 'Save changes' : 'Save &amp; go to dashboard' ?></button>
</form></div>

<div class="card"><h3>My children</h3><table><tr><th>Name</th><th>Date of birth</th><th>Gender</th><th></th></tr>
<?php foreach (q("SELECT * FROM children WHERE parent_id=?", [$u['id']])->fetchAll() as $c): ?>
<tr><td><?= e($c['child_name']) ?></td><td><?= e($c['date_of_birth']) ?></td><td><?= e($c['gender']) ?></td><td><a class="btn" href="?edit=<?= $c['child_id'] ?>">Edit</a></td></tr>
<?php endforeach; ?></table></div>
<?php include '../includes/footer.php'; ?>
