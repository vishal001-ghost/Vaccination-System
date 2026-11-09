<?php
require_once 'config/db.php';
// coming back to the login page while logged in = logged out, they must login again
if ($_SERVER['REQUEST_METHOD'] != 'POST' && me())
  logout();
if (isset($_GET['logout']))
  go('login.php');

$error = '';
$pr = $_POST['role'] ?? 'parent';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // role => [table, login column]   (admin logs in with a username, the others with email)
  $tables = ['parent' => ['parents', 'email'], 'admin' => ['admin', 'username'], 'hospital' => ['hospitals', 'email']];
  $role = isset($tables[$pr]) ? $pr : 'parent';
  [$table, $col] = $tables[$role];
  $row = q("SELECT * FROM $table WHERE $col=?", [trim($_POST['login'])])->fetch();
  if ($row && password_verify($_POST['password'], $row['password'])) {
    session_regenerate_id(true);                // new session id on every login
    $u = session_user($row, $role);
    $_SESSION['user'] = $u;
    // new parent with no child yet -> show the "register child" page first
    if ($role == 'parent' && !q("SELECT child_id FROM children WHERE parent_id=?", [$u['id']])->fetch())
      go('parent/register-child.php');
    go($role . '/dashboard.php');
  }
  $error = 'Wrong email/username or password for ' . $role . '. Check the role tick (Parent / Hospital / Admin).';
}
function chk($v)
{
  global $pr;
  return $pr == $v ? 'checked' : '';
}

$auth = true;
$title = 'Login';
include 'includes/header.php';
?>
<div class="card auth-box">
  <div class="tabs"><a class="on" href="login.php">Login</a><a href="register.php">Register</a></div>
  <?php if ($error)
    echo "<p class='alert'>" . e($error) . "</p>"; ?>
  <form method="post">
    <label class="lbl">I am a</label>
    <div class="roles">
      <label><input type="radio" name="role" value="parent" <?= chk('parent') ?>><span>Parent</span></label>
      <label><input type="radio" name="role" value="hospital" <?= chk('hospital') ?>><span>Hospital</span></label>
      <label><input type="radio" name="role" value="admin" <?= chk('admin') ?>><span>Admin</span></label>
    </div>
    <input name="login" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button>Login</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>