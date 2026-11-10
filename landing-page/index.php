<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PakVac | Vaccinate Now, Save Pakistan</title>
  <style>
    :root {
      --green: #01411c;
      --green-dark: #012a12;
      --green-mid: #0b6a35;
      --mist: #e3efe7;
      --white: #ffffff;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: "Segoe UI", Tahoma, Arial, sans-serif;
      background: var(--green);
      color: var(--white);
      padding: 14px;
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    /* ---------- HERO = WAVING PAKISTAN FLAG ---------- */
    .hero {
      position: relative;
      overflow: hidden;
      border-radius: 18px;
      background: var(--green);
      min-height: 560px;
      padding: 24px 40px 48px;
    }

    .flag-bg {
      position: absolute;
      inset: 0;
      z-index: 0;
      pointer-events: none;
    }

    .flag-bg canvas {
      width: 100%;
      height: 100%;
      display: block;
    }

    .flag-bg .dark {
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse at 60% 50%, rgba(0, 18, 7, .25), rgba(0, 12, 5, .6));
    }

    nav {
      position: relative;
      z-index: 2;
      display: inline-flex;
      align-items: center;
      gap: 32px;
      background: rgba(1, 42, 18, .6);
      backdrop-filter: blur(6px);
      padding: 12px 22px;
      border-radius: 12px;
      margin-bottom: 36px;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 1.4rem;
      font-weight: 700;
    }

    .logo svg {
      width: 32px;
      height: 32px;
    }

    nav .links a {
      font-size: .95rem;
    }

    nav .links a:hover {
      color: #bfe3cc;
    }

    .hero-card {
      position: relative;
      z-index: 2;
      max-width: 500px;
      background: rgba(1, 42, 18, .6);
      backdrop-filter: blur(6px);
      border-radius: 16px;
      padding: 32px;
    }

    .hero-card h1 {
      font-size: clamp(2.1rem, 4.5vw, 3.2rem);
      line-height: 1.1;
      margin-bottom: 16px;
    }

    .hero-card p {
      line-height: 1.6;
      color: #e3efe7;
      margin-bottom: 26px;
    }

    .btn {
      border: none;
      border-radius: 8px;
      padding: 13px 26px;
      font-size: .95rem;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
    }

    .btn-white {
      background: var(--white);
      color: var(--green);
    }

    .btn-white:hover {
      background: var(--mist);
    }

    .btn:focus-visible,
    input:focus-visible {
      outline: 3px solid #fff;
      outline-offset: 3px;
    }

    /* ---------- THREE IMAGES ---------- */
    .gallery {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
      margin-top: 14px;
    }

    .photo {
      position: relative;
      overflow: hidden;
      height: 280px;
      border-radius: 16px;
      background: linear-gradient(160deg, #2f7d4c, #01411c);
    }

    .photo img {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0;
      transition: opacity 1s ease;
    }

    .photo img.show {
      opacity: 1;
    }

    .photo .hint {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 10px;
      font-size: .9rem;
      color: rgba(255, 255, 255, .7);
    }

    .photo.has-image .hint {
      display: none;
    }

    /* ---------- LET'S VACCINATE NOW ---------- */
    .cta {
      display: grid;
      grid-template-columns: 1fr 1fr;
      margin-top: 14px;
      border-radius: 18px;
      overflow: hidden;
    }

    .cta-left {
      background: var(--green-dark);
      padding: 3.5rem 3rem;
      display: flex;
      align-items: center;
    }

    .cta-left h2 {
      font-size: clamp(1.8rem, 4vw, 3rem);
      line-height: 1.15;
    }

    .cta-left h2 span {
      display: block;
      color: #bfe3cc;
    }

    .cta-right {
      background: var(--white);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem;
    }

    .btn-big {
      background: var(--green);
      color: var(--white);
      padding: 1.2rem 2.8rem;
      font-size: 1.25rem;
      letter-spacing: .04em;
      border-radius: 999px;
      font-weight: 800;
    }

    .btn-big:hover {
      background: var(--green-mid);
    }

    #thanks {
      display: none;
      margin-top: 14px;
      text-align: center;
      background: var(--green-dark);
      border-radius: 12px;
      padding: 1rem;
    }

    #thanks.open {
      display: block;
    }

    /* ---------- FOOTER ---------- */
    .site-footer { margin-top: 14px; background: #012a12; border: 1px solid rgba(255, 255, 255, .2); border-radius: 18px; padding: 36px 40px 20px; color: #fff; }
    .site-footer .cols { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 32px; }
    .site-footer .brand { display: flex; align-items: center; gap: 10px; font-size: 1.4rem; font-weight: 700; margin-bottom: 12px; }
    .site-footer .brand svg { width: 32px; height: 32px; }
    .site-footer p { color: #e3efe7; line-height: 1.6; max-width: 360px; }
    .site-footer h3 { font-size: .8rem; letter-spacing: .12em; text-transform: uppercase; color: #bfe3cc; margin-bottom: 12px; }
    .site-footer ul { list-style: none; margin: 0; padding: 0; }
    .site-footer li { margin-bottom: 8px; }
    .site-footer a { color: #fff; text-decoration: none; font-size: .95rem; }
    .site-footer a:hover { color: #bfe3cc; }
    .site-footer .copy { margin-top: 28px; padding-top: 16px; border-top: 1px solid rgba(255, 255, 255, .2); text-align: center; font-size: .85rem; color: #cfe3d6; }
    @media (max-width: 800px) {
      .site-footer { padding: 28px 20px 18px; }
      .site-footer .cols { grid-template-columns: 1fr; gap: 22px; }
    }

    /* ---------- MOBILE ---------- */
    @media (max-width: 800px) {
      .hero {
        padding: 18px 16px 32px;
        min-height: 0;
      }

      nav {
        gap: 18px;
        margin-bottom: 24px;
      }

      .hero-card {
        padding: 24px;
      }

      .gallery {
        grid-template-columns: 1fr;
      }

      .cta {
        grid-template-columns: 1fr;
      }

      .cta-left,
      .cta-right {
        padding: 2.2rem 1.5rem;
      }


    }

    @media (prefers-reduced-motion: reduce) {
      .photo img {
        transition: none;
      }
    }
  </style>
</head>

<body>

  <section class="hero">
    <!-- Waving Pakistan flag: green field with crescent and star only -->
    <div class="flag-bg" aria-hidden="true">
      <canvas id="flagCanvas"></canvas>
      <div class="dark"></div>
    </div>

    <nav>
      <a class="logo" href="#">
        <svg viewBox="0 0 32 32" aria-hidden="true">
          <path d="M16 2l12 4v9c0 7-5 12-12 15C9 27 4 22 4 15V6z" fill="#fff" />
          <path d="M16 9v10M11 14h10" stroke="#01411c" stroke-width="3" stroke-linecap="round" />
        </svg>
        PakVac
      </a>
      <div class="links"><a href="../landing-page/about.html">About US</a></div>
    </nav>

    <div class="hero-card">
      <h1>Find a vaccine near you</h1>
      <p>Our vaccination programme protects families in every neighbourhood of Karachi and across Pakistan, so children,
        parents and workers stay healthy and safe.</p>
      <a class="btn btn-white" id="getBtn"  href="../login.php">Get Vaccine Now</a>
    </div>
  </section>

  <!-- THREE IMAGES -->
  <section class="gallery">
    <div class="photo">
      <div class="hint">Add photo: images/karachi1.jpg</div>
    </div>
    <div class="photo">
      <div class="hint">Add photo: images/karachi2.jpg</div>
    </div>
    <div class="photo">
      <div class="hint">Add photo: images/karachi3.jpg</div>
    </div>
  </section>

  <!-- LET'S VACCINATE NOW -->
  <section class="cta">
    <div class="cta-left">
      <h2>Let's vaccinate now.<span>Let's save Pakistan.</span></h2>
    </div>
    <div class="cta-right">
      <a class="btn btn-big" id="vaccinateBtn" href="../login.php">VACCINATE NOW</a>
    </div>
  </section>
  <div id="thanks">Thank you! Registration page coming soon.</div>

  <!-- FOOTER -->
  <footer class="site-footer">
    <div class="cols">
      <div>
        <div class="brand">
          <svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 2l12 4v9c0 7-5 12-12 15C9 27 4 22 4 15V6z" fill="#fff" /><path d="M16 9v10M11 14h10" stroke="#01411c" stroke-width="3" stroke-linecap="round" /></svg>
          PakVac
        </div>
        <p>Let's vaccinate now. Let's save Pakistan. A vaccination system that helps every family in Karachi and across Pakistan.</p>
      </div>
      <div>
        <h3>Quick links</h3>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="about.html">About Us</a></li>
          <li><a href="../login.php">Login</a></li>
          <li><a href="../register.php">Register</a></li>
        </ul>
      </div>
      <div>
        <h3>Location</h3>
        <ul>
          <li>Karachi, Pakistan</li>
        </ul>
      </div>
    </div>
    <div class="copy">&copy; <span id="yr">2026</span> PakVac &middot; Vaccination Management System &middot; Karachi, Pakistan</div>
  </footer>

  <script>
    // Your photo paths. One list per image box; add as many as you like (they fade in turn).
    var photos = [
      ["images/images01.jpg"],   // image box 1
      ["images/images02.jpg"],   // image box 2
      ["images/images03.jpg"]    // image box 3
    ];

    var frames = document.querySelectorAll(".gallery .photo");
    frames.forEach(function (frame, i) {
      photos[i].forEach(function (src) {
        var img = document.createElement("img");
        img.alt = "People in Karachi";
        img.onload = function () {
          frame.classList.add("has-image");
          if (!frame.querySelector("img.show")) img.classList.add("show");
        };
        img.onerror = function () { img.remove(); };
        img.src = src;
        frame.appendChild(img);
      });
    });

    var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (!reduce) {
      setInterval(function () {
        frames.forEach(function (frame) {
          var imgs = frame.querySelectorAll("img");
          if (imgs.length < 2) return;
          var cur = frame.querySelector("img.show");
          var next = cur.nextElementSibling || imgs[0];
          cur.classList.remove("show");
          next.classList.add("show");
        });
      }, 4000);
    }

    // Smooth waving Pakistan flag (green field, crescent and star) drawn on a canvas
    (function () {
      var hero = document.querySelector(".hero");
      var canvas = document.getElementById("flagCanvas");
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

      buildFlag(); draw(0);
      if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        (function loop(ms) { draw(ms / 1000); requestAnimationFrame(loop); })(0);
      }
      window.addEventListener("resize", function () { buildFlag(); draw(0); });
    })();

    document.getElementById("yr").textContent = new Date().getFullYear();

    // Buttons: replace with window.location.href = "register.html"
    function showThanks() { document.getElementById("thanks").classList.add("open"); }
    document.getElementById("vaccinateBtn").addEventListener("click", showThanks);
    document.getElementById("getBtn").addEventListener("click", showThanks);
  </script>
</body>

</html>