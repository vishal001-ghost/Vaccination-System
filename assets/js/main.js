// register page: parent and hospital have different fields
function hospFields() {
  const form = document.getElementById('regForm');
  if (!form) return;
  const hospital = form.querySelector('input[name=role]:checked').value == 'hospital';
  document.getElementById('hospFields').style.display = hospital ? 'block' : 'none';
  document.getElementById('parentFields').style.display = hospital ? 'none' : 'block';
  form.querySelectorAll('#hospFields input').forEach(i => i.required = hospital);   // hospital address + location are required
  document.getElementById('regName').placeholder = hospital ? 'Hospital name' : 'Parent name';
}
// back / forward button: if the browser restores an old page from its cache, reload it so the server checks the login again
window.addEventListener('pageshow', function (e) { if (e.persisted) location.reload(); });
