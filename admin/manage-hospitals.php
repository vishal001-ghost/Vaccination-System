<?php
require_once '../config/db.php'; need('admin');
if (isset($_GET['delete'])) {
  q("DELETE FROM hospitals WHERE hospital_id=?", [$_GET['delete']]);    // its bookings are deleted with it (ON DELETE CASCADE)
  go('admin/manage-hospitals.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  try {
    if (!empty($_POST['id'])) {                                  // edit
      q("UPDATE hospitals SET hospital_name=?, email=?, address=?, location_details=? WHERE hospital_id=?", [$_POST['name'], $_POST['email'], $_POST['address'], $_POST['location'], $_POST['id']]);
      if ($_POST['password'] !== '') q("UPDATE hospitals SET password=? WHERE hospital_id=?", [password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['id']]);
    } else                                                       // add
      q("INSERT INTO hospitals (hospital_name,email,password,address,location_details) VALUES (?,?,?,?,?)",
        [$_POST['name'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['address'], $_POST['location']]);
  } catch (mysqli_sql_exception $ex) { $_SESSION['flash'] = 'That email is already used by another hospital.'; }
  go('admin/manage-hospitals.php');
}
$edit = isset($_GET['edit']) ? q("SELECT * FROM hospitals WHERE hospital_id=?", [$_GET['edit']])->fetch() : null;
$title = 'Hospitals'; include '../includes/header.php';
?>
<div class="card"><h3><?= $edit ? 'Edit hospital' : 'Add hospital' ?></h3>
<form method="post" class="row">
  <input type="hidden" name="id" value="<?= $edit['hospital_id'] ?? '' ?>">
  <input name="name" placeholder="Hospital name" value="<?= e($edit['hospital_name'] ?? '') ?>" required>
  <input name="address" placeholder="Address" value="<?= e($edit['address'] ?? '') ?>" required>
  <input name="location" placeholder="Location details" value="<?= e($edit['location_details'] ?? '') ?>" required>
  <input name="email" type="email" placeholder="Login email" value="<?= e($edit['email'] ?? '') ?>" required>
  <input name="password" type="password" placeholder="<?= $edit ? 'New password (empty = keep)' : 'Password' ?>" <?= $edit ? '' : 'required' ?>>
  <button><?= $edit ? 'Save changes' : 'Add hospital' ?></button>
  <?php if ($edit): ?><a class="btn" href="manage-hospitals.php">Cancel</a><?php endif; ?>
</form></div>
<div class="card"><table><tr><th>Name</th><th>Address</th><th>Location</th><th>Login email</th><th></th></tr>
<?php foreach (q("SELECT * FROM hospitals")->fetchAll() as $h): ?>
<tr><td><?= e($h['hospital_name']) ?></td><td><?= e($h['address']) ?></td><td><?= e($h['location_details']) ?></td><td><?= e($h['email']) ?></td>
<td><a class="btn" href="?edit=<?= $h['hospital_id'] ?>">Edit</a> <a class="btn danger" href="?delete=<?= $h['hospital_id'] ?>" onclick="return confirm('Delete this hospital and its bookings?')">Delete</a></td></tr>
<?php endforeach; ?>
</table></div>
<?php include '../includes/footer.php'; ?>
