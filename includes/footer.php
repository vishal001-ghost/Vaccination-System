<?php // FOOTER. Closes the layout opened by header.php (same $auth switch) ?>
<?php if (!empty($auth)): ?>
  </div>
  <footer class="auth-footer">© <?= date('Y') ?> PakVac &middot; Vaccination Management System &middot; Karachi, Pakistan</footer>
</section>
<?php else: ?>
</section>
<footer>
  © <?= date('Y') ?> PakVac &middot; Vaccination Management System &middot; Karachi, Pakistan
</footer>
</main>
<?php endif; ?>
<?php if (!empty($auth)): ?><script src="<?= BASE ?>/assets/js/flag.js?v=<?= filemtime(__DIR__ . '/../assets/js/flag.js') ?>"></script><?php endif; ?>
<script src="<?= BASE ?>/assets/js/main.js"></script>
</body>
</html>
