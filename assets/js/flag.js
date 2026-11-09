// Still Pakistan flag background (same as the landing / about page)
function makeFlag(hero, canvas) {
  var ctx = canvas.getContext("2d");
  var AMP = 30;                 // wave height in px: lower = calmer
  var off, W, H, scale;

  function buildFlag() {
    scale = Math.min(window.devicePixelRatio || 1, 1.5);
    W = Math.round(hero.clientWidth * scale);
    H = Math.round(hero.clientHeight * scale);
    canvas.width = W; canvas.height = H;

    off = document.createElement("canvas");
    off.width = W; off.height = H + 2 * AMP * scale;
    var c = off.getContext("2d");
    var s = Math.max(W / 900, off.height / 600);          // fit the 900x600 flag design
    c.translate((W - 900 * s) / 2, (off.height - 600 * s) / 2);
    c.scale(s, s);

    c.fillStyle = "#01411c"; c.fillRect(-200, -200, 1400, 1000);   // green field
    c.fillStyle = "#fff";                                          // crescent, one clean path (no overlay seam)
    var x1 = 555, y1 = 300, r1 = 150, x2 = 605, y2 = 275, r2 = 125;
    var d = Math.hypot(x2 - x1, y2 - y1), a = (r1 * r1 - r2 * r2 + d * d) / (2 * d), h = Math.sqrt(r1 * r1 - a * a);
    var ux = (x2 - x1) / d, uy = (y2 - y1) / d;
    var ix1 = x1 + a * ux + h * -uy, iy1 = y1 + a * uy + h * ux;   // lower tip
    var ix2 = x1 + a * ux - h * -uy, iy2 = y1 + a * uy - h * ux;   // upper tip
    c.beginPath();
    c.arc(x1, y1, r1, Math.atan2(iy2 - y1, ix2 - x1), Math.atan2(iy1 - y1, ix1 - x1), true);
    c.arc(x2, y2, r2, Math.atan2(iy1 - y2, ix1 - x2), Math.atan2(iy2 - y2, ix2 - x2), false);
    c.closePath(); c.fill();
    c.fillStyle = "#fff";                                          // star
    c.translate(648, 250); c.rotate(28 * Math.PI / 180); c.scale(.9, .9);
    var pts = [[0, -50], [11.2, -15.5], [47.6, -15.5], [18.2, 5.9], [29.4, 40.5], [0, 19.1], [-29.4, 40.5], [-18.2, 5.9], [-47.6, -15.5], [-11.2, -15.5]];
    c.beginPath(); pts.forEach(function (p, i) { i ? c.lineTo(p[0], p[1]) : c.moveTo(p[0], p[1]); });
    c.closePath(); c.fill();
  }

  function draw(t) {
    var amp = AMP * scale, step = Math.max(2, Math.round(3 * scale)), k = (2 * Math.PI) / (W / 1.6);
    ctx.clearRect(0, 0, W, H);
    for (var x = 0; x < W; x += step) {
      var ph = x * k - t * 1.4;
      var f = 0.25 + 0.75 * (x / W);               // calm near the pole (left), bigger waves on the right
      var dy = amp * f * Math.sin(ph);
      ctx.drawImage(off, x, amp - dy, step + 1, H, x, 0, step + 1, H);
      var n = Math.cos(ph);                         // slope of the wave
      ctx.fillStyle = "rgba(0,14,5," + (0.30 * (1 + n)) * f + ")";   // dark layer in the troughs
      ctx.fillRect(x, 0, step, H);
      if (n < 0) { ctx.fillStyle = "rgba(255,255,255," + (-0.07 * n * f) + ")"; ctx.fillRect(x, 0, step, H); }
    }
  }

  buildFlag(); draw(2);
  window.addEventListener("resize", function () { buildFlag(); draw(2); });
}
makeFlag(document.getElementById('flagBg'), document.getElementById('flagCanvas'));
