<?php
// ---------- database connection ----------
$host     = "localhost";
$username = "root";
$password = "";
$dbname   = "vaccination_db";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
// ---------- end of connection ----------

// everything below is the helper code every page uses
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   // SQL errors (like a duplicate email) become exceptions we can catch
$conn->set_charset('utf8mb4');
session_start();

// BASE = project folder URL, e.g. /vaccination-system (works wherever you put the folder in htdocs)
$root = str_replace('\\', '/', realpath(__DIR__ . '/..'));
$doc  = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
define('BASE', rtrim(substr($root, strlen($doc)), '/'));

// q("SELECT ... WHERE id=?", [5]) runs a safe query. ->fetch() one row, ->fetchAll() all rows, ->fetchColumn() one value
class Result {
  private $res;
  function __construct($stmt) { $this->res = $stmt->get_result() ?: null; }   // null for INSERT / UPDATE / DELETE
  function fetch()       { return $this->res ? ($this->res->fetch_assoc() ?: false) : false; }
  function fetchAll()    { return $this->res ? $this->res->fetch_all(MYSQLI_ASSOC) : []; }
  function fetchColumn() { $r = $this->res ? $this->res->fetch_row() : null; return $r ? $r[0] : false; }
}
function q($sql, $params = []) {
  global $conn;
  $stmt = $conn->prepare($sql);
  if ($params) $stmt->bind_param(str_repeat('s', count($params)), ...$params);
  $stmt->execute();
  return new Result($stmt);
}
function e($t) { return htmlspecialchars($t ?? ''); }
function me() { return $_SESSION['user'] ?? null; }
function go($path) { header('Location: ' . BASE . '/' . $path); exit; }

// the 3 tables have different column names, so we keep one simple user array in the session
function session_user($row, $role) {
  return ['id'   => $row[$role . '_id'],                       // admin_id / parent_id / hospital_id
          'name' => $row['name'] ?? $row['hospital_name'] ?? $row['username'],
          'email'=> $row['email'] ?? $row['username'],
          'role' => $role];
}
function logout() {                              // end the session completely
  $_SESSION = [];
  if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
  }
  session_destroy();
}
function need(...$roles) {                       // page protection
  // never let the browser keep a copy of a protected page (stops back / forward button from showing it)
  header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
  header('Pragma: no-cache');
  header('Expires: 0');
  $u = me();
  if (!$u || !in_array($u['role'], $roles)) go('login.php');
}
function order_no($id) { return '#' . str_pad($id, 3, '0', STR_PAD_LEFT); }
function badge($s) {
  $c = ['Pending'=>'warn','Approved'=>'info','Rejected'=>'bad','Vaccinated'=>'ok','Not Vaccinated'=>'bad'][$s] ?? 'info';
  return "<span class='badge $c'>$s</span>";
}
function status_badge($s) {                      // parent friendly: badge + short note
  $note = ['Pending'=>'Waiting for admin','Approved'=>'Visit hospital on the appointment date','Rejected'=>'Request rejected'][$s] ?? '';
  return badge($s) . ($note ? " <small>$note</small>" : '');
}

// one booking = child + parent + vaccine + hospital (columns renamed so pages stay simple)
const BOOKING_SQL = "SELECT b.*, b.booking_id AS id, c.child_name AS child, c.date_of_birth AS dob,
  p.name AS parent, p.email AS parent_email, v.vaccine_name AS vaccine, h.hospital_name AS hospital, h.address
  FROM bookings b JOIN children c ON c.child_id=b.child_id JOIN parents p ON p.parent_id=b.parent_id
  JOIN vaccines v ON v.vaccine_id=b.vaccine_id JOIN hospitals h ON h.hospital_id=b.hospital_id";

// PakVac logo: same shield + cross SVG as the landing page. $light = true for dark backgrounds (white shield)
function pakvac_logo($light = false) {
  $shield = $light ? '#fff' : '#01411c';
  $cross  = $light ? '#01411c' : '#fff';
  return "<svg viewBox='0 0 32 32' width='32' height='32' aria-hidden='true' style='vertical-align:middle;margin-right:8px'><path d='M16 2l12 4v9c0 7-5 12-12 15C9 27 4 22 4 15V6z' fill='$shield'/><path d='M16 9v10M11 14h10' stroke='$cross' stroke-width='3' stroke-linecap='round' fill='none'/></svg>PakVac";
}

function certificate_row($id) { return q(BOOKING_SQL . " WHERE b.booking_id=? AND b.status='Vaccinated'", [$id])->fetch(); }
function certificate_email_html($r) {
  return "<div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;border:6px double #01411c;padding:30px;text-align:center'>
  <h2 style='margin:0;color:#01411c'>PakVac</h2><h1>Vaccination Certificate</h1>
  <p>This is to certify that</p><h2 style='color:#01411c'>" . e($r['child']) . "</h2>
  <p>Child of <b>" . e($r['parent']) . "</b> &middot; Date of birth: " . e($r['dob']) . "</p>
  <p>has been vaccinated with</p><h2 style='color:#01411c'>" . e($r['vaccine']) . "</h2>
  <p>at <b>" . e($r['hospital']) . "</b><br>" . e($r['address']) . "<br>on <b>" . e($r['appointment_date']) . "</b></p>
  <p style='color:#71717a;font-size:12px'>Certificate no. " . order_no($r['id']) . "</p></div>";
}
// admin button: send certificate to the parent's email ($back = admin page to return to)
function send_form($r, $back = 'manage-requests.php') {
  if ($r['status'] != 'Vaccinated') return '';
  $sent = file_exists(dirname(__DIR__) . '/sent_emails/sent_' . $r['id'] . '.txt');   // marker written when the email went out
  $label = $sent ? 'Resend certificate' : 'Send certificate to parent';
  $tick = $sent ? "<span class='badge ok'>Emailed ✓</span> " : '';
  return "$tick<form method='post' action='" . BASE . "/admin/manage-requests.php' class='inline'>
  <input type='hidden' name='id' value='{$r['id']}'><input type='hidden' name='back' value='$back'>
  <button name='send' value='1'>$label</button></form>";
}
