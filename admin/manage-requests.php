<?php
require_once '../config/db.php'; need('admin');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['status']) && in_array($_POST['status'], ['Approved','Rejected']))
    q("UPDATE bookings SET status=? WHERE booking_id=?", [$_POST['status'], $_POST['id']]);
  if (isset($_POST['send'])) {                               // email the certificate to the parent
    $r = certificate_row($_POST['id']);
    if ($r) {
      $html = certificate_email_html($r);
      $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\nFrom: PakVac <no-reply@pakvac.local>\r\n";
      $sent = @mail($r['parent_email'], 'Vaccination certificate for ' . $r['child'], $html, $headers);
      $dir = __DIR__ . '/../sent_emails'; @mkdir($dir);        // keep a copy so you can test on localhost
      file_put_contents("$dir/certificate_{$r['id']}.html", $html);
      if ($sent) { file_put_contents("$dir/sent_{$r['id']}.txt", date('c')); $_SESSION['flash'] = 'Certificate emailed to ' . $r['parent_email']; }
      else $_SESSION['flash'] = 'Mail server is not set up on this computer, so the email was not sent. A copy was saved in the sent_emails folder.';
    }
    $back = in_array($_POST['back'] ?? '', ['dashboard.php','manage-requests.php']) ? $_POST['back'] : 'manage-requests.php';
    go('admin/' . $back);
  }
  go('admin/manage-requests.php');
}
$title = 'Requests'; include '../includes/header.php';

echo "<div class='card'><table><tr><th>Order</th><th>Child</th><th>Parent</th><th>Vaccine</th><th>Hospital</th><th>Date</th><th>Status</th><th></th></tr>";
foreach (q(BOOKING_SQL . " ORDER BY b.status='Pending' DESC, b.booking_id DESC")->fetchAll() as $r) {
  echo "<tr><td>" . order_no($r['id']) . "</td><td>" . e($r['child']) . "</td><td>" . e($r['parent']) . "</td><td>" . e($r['vaccine']) . "</td><td>" . e($r['hospital']) . "</td><td>{$r['appointment_date']}</td><td>" . badge($r['status']) . "</td><td>";
  if ($r['status'] == 'Pending')
    echo "<form method='post' class='inline'><input type='hidden' name='id' value='{$r['id']}'>
    <button name='status' value='Approved'>Approve</button> <button class='danger' name='status' value='Rejected'>Reject</button></form>";
  echo send_form($r) . "</td></tr>";
}
echo "</table></div>";
include '../includes/footer.php';
