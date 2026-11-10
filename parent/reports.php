<?php
require_once '../config/db.php'; need('parent');
$u = me();

if (isset($_GET['certificate'])) {                             // downloadable certificate (Approved = approval, Vaccinated = vaccination)
  $r = certificate_row($_GET['certificate']);
  if (!$r || $r['parent_id'] != $u['id']) { echo 'Certificate not found.'; exit; }
  $done  = $r['status'] == 'Vaccinated';
  $head  = $done ? 'Vaccination Certificate' : 'Vaccination Approval Certificate';
  $intro = $done ? 'has been vaccinated with' : 'has been approved to receive';
  $fmt   = function ($d) { return date('d F Y', strtotime($d)); };
  // flag crescent + star (white or green), same shapes as the landing page flag
  $moon = function ($id, $fill) { return "<svg viewBox='0 0 200 200' aria-hidden='true'><defs><mask id='$id'><rect width='200' height='200' fill='#000'/><circle cx='95' cy='100' r='75' fill='#fff'/><circle cx='125' cy='90' r='62' fill='#000'/></mask></defs><rect width='200' height='200' fill='$fill' mask='url(#$id)'/><polygon fill='$fill' transform='translate(146 80) rotate(28) scale(.38)' points='0,-50 11.2,-15.5 47.6,-15.5 18.2,5.9 29.4,40.5 0,19.1 -29.4,40.5 -18.2,5.9 -47.6,-15.5 -11.2,-15.5'/></svg>"; };
  ?>
<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Certificate - PakVac</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0 }
  body { font-family: "Segoe UI", Tahoma, Arial, sans-serif; background: #e4e4e7; color: #18181b; min-height: 100vh; padding: 24px 12px; -webkit-print-color-adjust: exact; print-color-adjust: exact }
  .bar { max-width: 1000px; margin: 0 auto 16px; display: flex; justify-content: space-between; align-items: center }
  .bar a, .bar button { background: #fff; color: #01411c; border: 1.5px solid #01411c; border-radius: 8px; padding: 9px 16px; font: 600 13px "Segoe UI", Tahoma, Arial, sans-serif; text-decoration: none; cursor: pointer; transition: background .15s, color .15s }
  .bar a:hover, .bar button:hover { background: #01411c; color: #fff }
  .wrap { overflow-x: auto }
  .sheet { position: relative; width: 1000px; height: 707px; margin: 0 auto; background: #fff; padding: 20px; box-shadow: 0 8px 30px rgba(0, 0, 0, .2) }
  .frame { position: relative; height: 100%; border: 10px solid #01411c; display: flex; flex-direction: column; overflow: hidden }
  .frame::before { content: ""; position: absolute; inset: 6px; border: 2px solid #01411c; pointer-events: none; z-index: 3 }
  .mark { position: absolute; left: 50%; top: 54%; width: 430px; height: 430px; transform: translate(-50%, -50%); opacity: .07; z-index: 0 }
  .band { position: relative; z-index: 1; background: #01411c; color: #fff; display: flex; justify-content: space-between; align-items: center; padding: 14px 40px }
  .band .brand { display: flex; align-items: center; font-size: 26px; font-weight: 800 }
  .band .no { font-size: 13px; text-align: right; line-height: 1.5; color: #e3efe7 }
  .body { position: relative; z-index: 1; flex: 1; text-align: center; padding: 18px 70px 0; display: flex; flex-direction: column; justify-content: center }
  .kicker { font-size: 12px; letter-spacing: .25em; text-transform: uppercase; color: #52525b }
  h1 { font-family: Georgia, "Times New Roman", serif; font-size: 42px; color: #01411c; margin: 6px 0 4px }
  .rule { width: 220px; height: 2px; background: #01411c; margin: 8px auto 14px; position: relative }
  .rule::after { content: ""; position: absolute; left: 50%; top: -4px; width: 9px; height: 9px; background: #01411c; transform: translateX(-50%) rotate(45deg) }
  .small { font-size: 15px; color: #3f3f46 }
  .name { font-family: Georgia, "Times New Roman", serif; font-style: italic; font-size: 46px; color: #01411c; display: inline-block; margin: 6px auto 4px; padding: 0 30px 4px; border-bottom: 2px solid #01411c }
  .vac { font-family: Georgia, "Times New Roman", serif; font-size: 30px; font-weight: 700; color: #01411c; margin: 6px 0 }
  .foot { position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: flex-end; padding: 0 60px 16px }
  .sig { width: 260px; text-align: center; font-size: 13px; color: #3f3f46 }
  .sig .line { border-top: 1.5px solid #18181b; margin-bottom: 6px }
  .sig b { display: block; color: #18181b; font-size: 14px }
  .seal { width: 104px; height: 104px; border-radius: 50%; background: #01411c; box-shadow: 0 0 0 4px #fff, 0 0 0 6px #01411c; display: flex; align-items: center; justify-content: center; margin-bottom: 4px }
  .seal svg { width: 70px; height: 70px }
  .note { position: relative; z-index: 1; text-align: center; font-size: 11px; color: #71717a; padding: 0 40px 12px }
  @page { size: A4 landscape; margin: 0 }
  @media print {
    body { background: #fff; padding: 0 }
    .noprint { display: none }
    .wrap { overflow: visible }
    .sheet { width: 297mm; height: 210mm; box-shadow: none; margin: 0; padding: 6mm; transform: none }
    .frame { transform-origin: top left }
  }
</style></head>
<body>
<div class="bar noprint"><a href="<?= BASE ?>/parent/dashboard.php">&larr; Back to dashboard</a><button onclick="window.print()">Download PDF</button></div>
<div class="wrap"><div class="sheet"><div class="frame">
  <div class="mark"><?= $moon('mk', '#01411c') ?></div>
  <div class="band">
    <div class="brand"><?= pakvac_logo(true) ?></div>
    <div class="no">Certificate no. <b><?= order_no($r['id']) ?></b><br>Issued <?= date('d F Y') ?></div>
  </div>
  <div class="body">
    <div class="kicker">PakVac &middot; Vaccination Management System</div>
    <h1><?= $head ?></h1>
    <div class="rule"></div>
    <p class="small">This is to certify that</p>
    <div><span class="name"><?= e($r['child']) ?></span></div>
    <p class="small">Date of birth: <b><?= e($fmt($r['dob'])) ?></b> &nbsp;&middot;&nbsp; Child of <b><?= e($r['parent']) ?></b></p>
    <p class="small" style="margin-top:12px"><?= $intro ?></p>
    <div class="vac"><?= e($r['vaccine']) ?></div>
    <p class="small">at <b><?= e($r['hospital']) ?></b>, <?= e($r['address']) ?><br><?= $done ? 'on' : 'appointment date:' ?> <b><?= e($fmt($r['appointment_date'])) ?></b></p>
  </div>
  <div class="foot">
    <div class="sig"><div class="line"></div><b>PakVac Admin</b>Approved the request</div>
    <div class="seal"><?= $moon('sl', '#fff') ?></div>
    <div class="sig"><div class="line"></div><b><?= e($r['hospital']) ?></b>Vaccinating hospital</div>
  </div>
  <?php if (!$done): ?><div class="note">This is an approval certificate. The final Vaccination Certificate appears here after the hospital confirms the vaccination.</div><?php endif; ?>
</div></div></div>
<?php if (isset($_GET['download'])): ?><script>window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 400); });</script><?php endif; ?>
</body></html>
<?php exit; }

$title = 'Reports'; include '../includes/header.php';
echo "<div class='card'><h3>Vaccination history</h3><table><tr><th>Child</th><th>Vaccine</th><th>Hospital</th><th>Appointment date</th><th>Status</th><th></th></tr>";
foreach (q(BOOKING_SQL . " WHERE b.parent_id=? ORDER BY b.booking_id DESC", [$u['id']])->fetchAll() as $r) {
  echo "<tr><td>" . e($r['child']) . "</td><td>" . e($r['vaccine']) . "</td><td>" . e($r['hospital']) . "</td><td>{$r['appointment_date']}</td><td>" . status_badge($r['status']) . "</td><td>";
  if (in_array($r['status'], ['Approved', 'Vaccinated'])) echo "<a class='btn' target='_blank' href='?certificate={$r['id']}&download=1'>Download Certificate</a>";
  echo "</td></tr>";
}
echo "</table></div>";
include '../includes/footer.php';
