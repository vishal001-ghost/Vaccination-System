<?php
require_once 'config/db.php';
if ($_SERVER['REQUEST_METHOD'] != 'POST' && me()) logout();   // back on register page = logged out
$error = '';
$pr = $_POST['role'] ?? 'parent';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {            // parents and hospitals can register
  $role = $pr == 'hospital' ? 'hospital' : 'parent';
  $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $email = trim($_POST['email']);
  try {
    if ($role == 'hospital') {
      if (trim($_POST['h_address']) == '' || trim($_POST['h_location']) == '') throw new Exception('Hospital address and location are required.');
      q(
        "INSERT INTO hospitals (hospital_name,email,password,address,location_details) VALUES (?,?,?,?,?)",
        [$_POST['name'], $email, $pass, $_POST['h_address'], $_POST['h_location']]
      );
    } else
      q(
        "INSERT INTO parents (name,email,password,phone,address) VALUES (?,?,?,?,?)",
        [$_POST['name'], $email, $pass, $_POST['p_phone'], $_POST['p_address']]
      );
    $row = q("SELECT * FROM " . ($role == 'hospital' ? 'hospitals' : 'parents') . " WHERE {$role}_id=?", [$conn->insert_id])->fetch();
    session_regenerate_id(true);
    $_SESSION['user'] = session_user($row, $role);     // registered = logged in
    go($role . '/dashboard.php');
  } catch (mysqli_sql_exception $ex) {
    $error = 'Email already used.';
  } catch (Exception $ex) {
    $error = $ex->getMessage();
  }
}
function chk($v)
{
  global $pr;
  return $pr == $v ? 'checked' : '';
}

$auth = true;
$title = 'Register';
include 'includes/header.php';
?>
<div class="card auth-box">
  <div class="tabs"><a href="login.php">Login</a><a class="on" href="register.php">Register</a></div>
  <?php if ($error) echo "<p class='alert'>" . e($error) . "</p>"; ?>
  <form method="post" id="regForm">
    <label class="lbl">Register as</label>
    <div class="roles">
      <label><input type="radio" name="role" value="parent" <?= chk('parent') ?> onchange="hospFields()"><span>Parent</span></label>
      <label><input type="radio" name="role" value="hospital" <?= chk('hospital') ?> onchange="hospFields()"><span>Hospital</span></label>
    </div>
    <input name="name" id="regName" placeholder="Parent name" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <div id="parentFields">
      <input name="p_phone" placeholder="Phone (optional)">
      <input name="p_address" placeholder="Address (optional)">
    </div>
    <div id="hospFields" style="display:none">
      <input name="h_address" placeholder="Hospital address">
      <input name="h_location" placeholder="Location details (area in Karachi)">
    </div>
    <button>Register &amp; Login</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
<script>
  hospFields()
</script>