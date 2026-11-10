<?php
// HEADER. Before including set $title.  On login/register/landing set $auth = true (plain layout, no sidebar).
$u = me();
if (empty($auth)) {
  $menus = [
    'admin'    => ['admin/dashboard.php'=>'Dashboard','admin/manage-requests.php'=>'Requests','admin/manage-vaccines.php'=>'Vaccines','admin/manage-hospitals.php'=>'Hospitals'],
    'parent'   => ['parent/dashboard.php'=>'Dashboard','parent/register-child.php'=>'Add Child','parent/book-hospital.php'=>'Book Hospital','parent/reports.php'=>'Reports'],
    'hospital' => ['hospital/dashboard.php'=>'Dashboard','hospital/update-status.php'=>'Update Status'],
  ][$u['role']];
  $here = basename(dirname($_SERVER['PHP_SELF'])) . '/' . basename($_SERVER['PHP_SELF']);
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?> - PakVac</title>
  <link rel="stylesheet" href="<?= BASE ?>/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>">
<?php if (!empty($auth)): ?>
  <style>
    /* LOGIN / REGISTER / WELCOME: same layout as landing-page/about.html (self-contained so it never depends on a cached stylesheet) */
    body.auth { display: block; background: #01411c; color: #fff; padding: 14px; min-height: 100vh; font-family: "Segoe UI", Tahoma, Arial, sans-serif; }
    body.auth a { text-decoration: none; }
    .about { position: relative; overflow: hidden; border-radius: 18px; min-height: calc(100vh - 28px); background: #01411c; padding: 24px 40px 48px; display: flex; flex-direction: column; }
    .about .flag-bg { position: absolute; inset: 0; z-index: 0; pointer-events: none; }
    .about .flag-bg canvas { width: 100%; height: 100%; display: block; }
    .about .flag-bg .dark { position: absolute; inset: 0; background: radial-gradient(ellipse at 60% 50%, rgba(0,18,7,.25), rgba(0,12,5,.6)); }
    .about nav { position: relative; z-index: 2; align-self: flex-start; display: inline-flex; align-items: center; gap: 32px; background: rgba(1,42,18,.6); backdrop-filter: blur(6px); padding: 12px 22px; border-radius: 12px; }
    .about nav .logo { display: flex; align-items: center; margin: 0; font-size: 1.4rem; font-weight: 700; color: #fff; }
    .about nav .links a { color: #fff; font-size: .95rem; margin-right: 18px; }
    .about nav .links a:last-child { margin-right: 0; }
    .about nav .links a:hover { color: #bfe3cc; }
    .about .center { position: relative; z-index: 2; flex: 1; display: flex; align-items: center; justify-content: center; padding-top: 32px; }
    .about .auth-footer { position: relative; z-index: 2; background: transparent; border: 0; color: #e3efe7; padding: 12px 0 0; text-align: center; font-size: 13px; }

    /* the form box: transparent glass, same as the About card */
    .about .auth-box { width: 380px; max-width: 100%; margin: 0; padding: 32px; background: rgba(1,42,18,.6); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 0; border-radius: 16px; box-shadow: none; color: #fff; overflow: visible; }
    .about .auth-box.wide { width: 460px; text-align: center; }
    .about .auth-box.wide p { color: #e3efe7; line-height: 1.6; }
    .about .auth-box .lbl { color: #e3efe7; }
    .about .auth-box input, .about .auth-box select { width: 100%; background-color: rgba(255,255,255,.1); color: #fff; border: 1px solid rgba(255,255,255,.45); border-radius: 8px; }
    .about .auth-box input::placeholder { color: rgba(255,255,255,.75); }
    .about .auth-box option { color: #000; }
    .about .auth-box input:focus, .about .auth-box select:focus { outline: 2px solid #fff; outline-offset: 1px; }
    .about .auth-box .tabs { background: rgba(255,255,255,.1); }
    .about .auth-box .tabs a { color: #fff; }
    .about .auth-box .tabs a:hover { background: rgba(255,255,255,.2); color: #fff; }
    .about .auth-box .tabs a.on, .about .auth-box .tabs a.on:hover { background: #fff; color: #01411c; box-shadow: none; }
    .about .auth-box .roles input { display: none; width: auto; }
    .about .auth-box .roles span { color: #fff; border: 1px solid rgba(255,255,255,.45); background: transparent; }
    .about .auth-box .roles input:checked + span { background: #fff; color: #01411c; border-color: #fff; }
    .about .auth-box .alert { background: rgba(255,255,255,.15); color: #fff; }

    /* buttons: white + flag green text, hover = flag green + white text */
    .about .auth-box button, .about .auth-box .btn { background: #fff; color: #01411c; border: 1.5px solid #fff; }
    .about .auth-box button:hover, .about .auth-box .btn:hover { background: #01411c !important; color: #fff !important; border-color: #fff !important; }
    @media (max-width: 800px) { .about { padding: 18px 16px 28px; } }
  </style>
<?php endif; ?>
</head>
<?php if (!empty($auth)): ?>
<body class="auth">
<section class="about">
  <div class="flag-bg" id="flagBg" aria-hidden="true"><canvas id="flagCanvas"></canvas><div class="dark"></div></div>
  <nav>
    <a class="logo" href="<?= BASE ?>/landing-page/index.html"><?= pakvac_logo(true) ?></a>
    <div class="links"><a href="<?= BASE ?>/landing-page/index.html">Home</a><a href="<?= BASE ?>/landing-page/about.html">About Us</a></div>
  </nav>
  <div class="center">
<?php else: ?>
<body>
<aside>
  <div class="logo"><?= pakvac_logo() ?></div>
  <?php foreach ($menus as $file => $label): ?>
    <a href="<?= BASE ?>/<?= $file ?>" class="<?= $here == $file ? 'active' : '' ?>"><?= $label ?></a>
  <?php endforeach; ?>
</aside>
<main>
<header>
  <b><?= e($title) ?></b>
  <div><?= e($u['name']) ?> <small>(<?= $u['role'] ?>)</small> &nbsp; <a href="<?= BASE ?>/login.php?logout=1">Logout</a></div>
</header>
<section>
<?php
  if (!empty($_SESSION['flash'])) { echo "<p class='alert'>" . e($_SESSION['flash']) . "</p>"; unset($_SESSION['flash']); }
endif; ?>
