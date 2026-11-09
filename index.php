<?php
require_once 'config/db.php';
$auth = true; $title = 'Welcome'; $u = me();
include 'includes/header.php';
?>
<div class="card auth-box wide">
  <p>Register your child, choose a hospital and never miss a vaccination.</p>
  <?php if ($u): ?>
    <a class="btn" href="<?= BASE ?>/<?= $u['role'] ?>/dashboard.php">Go to dashboard</a>
  <?php else: ?>
    <a class="btn" href="<?= BASE ?>/login.php">Login</a>
    <a class="btn" href="<?= BASE ?>/register.php">Register</a>
  <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
