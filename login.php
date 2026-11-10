<?php
require_once 'config/db.php';
// coming back to the login page while logged in = logged out, they must login again
if ($_SERVER['REQUEST_METHOD'] != 'POST' && me())
  logout();
if (isset($_GET['logout']))
  go('login.php');

$demo = true; // set to false before going live to hide the autofill buttons

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
<style>
  /* password field with Show/Hide inside */
  .auth-box .pw-wrap {
    position: relative;
    margin-bottom: 14px;
  }

  .auth-box .pw-wrap input {
    width: 100%;
    padding-right: 70px;
    margin-bottom: 0;
  }

  .auth-box .pw-wrap .pw-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: auto;
    height: auto;
    margin: 0;
    padding: 6px 8px;
    background: transparent;
    border: 0;
    box-shadow: none;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
  }

  .auth-box .pw-wrap .pw-toggle:hover {
    text-decoration: underline;
    background: transparent;
  }

  /* quick fill: label on its own line, buttons in one row */
  .auth-box .autofill {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    margin-top: 16px;
  }

  .auth-box .autofill small {
    flex: 0 0 100%;
    text-align: center;
    margin: 0 0 2px;
    opacity: .7;
  }

  .auth-box .autofill .fill-btn {
    flex: 1 1 0;
    min-width: 80px;
    width: auto;
    margin: 0;
    padding: 8px 10px;
    font-size: 13px;
  }

  /* mobile */
  @media (max-width: 480px) {
    .auth-box {
      width: 100%;
      max-width: 100%;
      margin: 0 auto;
      padding: 18px 14px;
      border-radius: 14px;
    }

    .auth-box input {
      font-size: 16px;
    }

    /* stops iOS zooming on focus */
    .auth-box .pw-wrap input {
      padding-right: 64px;
    }

    .auth-box .roles {
      gap: 6px;
    }

    .auth-box .roles span {
      padding: 10px 6px;
      font-size: 13px;
    }

    .auth-box button {
      min-height: 44px;
    }

    /* comfortable tap size */
    .auth-box .autofill .fill-btn {
      min-height: 40px;
    }
  }

  @media (max-width: 340px) {
    .auth-box .autofill .fill-btn {
      flex: 1 1 100%;
    }

    /* stack on very small screens */
  }
</style>
<div class="card auth-box">
  <div class="tabs"><a class="on" href="login.php">Login</a><a href="register.php">Register</a></div>
  <?php if ($error)
    echo "<p class='alert'>" . e($error) . "</p>"; ?>
  <form method="post" id="loginForm">
    <label class="lbl">I am a</label>
    <div class="roles">
      <label><input type="radio" name="role" value="parent" <?= chk('parent') ?>><span>Parent</span></label>
      <label><input type="radio" name="role" value="hospital" <?= chk('hospital') ?>><span>Hospital</span></label>
      <label><input type="radio" name="role" value="admin" <?= chk('admin') ?>><span>Admin</span></label>
    </div>
    <input id="loginInput" name="login" placeholder="Email" required autocomplete="username">

    <div class="pw-wrap">
      <input id="passInput" name="password" type="password" placeholder="Password" required autocomplete="current-password">
      <button type="button" class="pw-toggle" id="pwToggle" aria-label="Show password">Show</button>
    </div>

    <button>Login</button>

    <?php if ($demo): ?>
      <div class="autofill">
        <small>Quick fill</small>
        <button type="button" class="fill-btn" data-fill="parent">Parent</button>
        <button type="button" class="fill-btn" data-fill="hospital">Hospital</button>
        <button type="button" class="fill-btn" data-fill="admin">Admin</button>
      </div>
    <?php endif; ?>
  </form>
</div>

<script>
  (function() {
    // Hard coded details
    var accounts = {
      parent: {
        login: 'fatima@demo.com',
        password: 'fatima123'
      },
      hospital: {
        login: 'hospital@hospital.com',
        password: 'hospital1234'
      },
      admin: {
        login: 'admin@admin.com',
        password: 'admin1234'
      }
    };

    var form = document.getElementById('loginForm');
    var login = document.getElementById('loginInput');
    var pass = document.getElementById('passInput');
    var tog = document.getElementById('pwToggle');

    // show / hide password
    tog.addEventListener('click', function() {
      var show = pass.type === 'password';
      pass.type = show ? 'text' : 'password';
      tog.textContent = show ? 'Hide' : 'Show';
      tog.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });

    // admin uses a username, the others use email
    function updatePlaceholder(role) {
      login.placeholder = role === 'admin' ? 'Username' : 'Email';
    }

    function selectRole(role) {
      var radio = form.querySelector('input[name="role"][value="' + role + '"]');
      if (radio) radio.checked = true;
      updatePlaceholder(role);
    }

    // keep placeholder in sync when the user clicks a role manually
    form.querySelectorAll('input[name="role"]').forEach(function(r) {
      r.addEventListener('change', function() {
        updatePlaceholder(r.value);
      });
    });
    var checked = form.querySelector('input[name="role"]:checked');
    if (checked) updatePlaceholder(checked.value);

    // autofill buttons
    document.querySelectorAll('.fill-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var role = btn.getAttribute('data-fill');
        var acc = accounts[role];
        if (!acc) return;
        selectRole(role);
        login.value = acc.login;
        pass.value = acc.password;
      });
    });
  })();
</script>
<?php include 'includes/footer.php'; ?>