<?php
// Footer publik 3 kolom (kiri: navigasi+fakultas, tengah: kontak, kanan: tentang+gambar).
$foot_categories = $foot_categories ?? ($pdo->query('SELECT name, slug FROM categories ORDER BY name LIMIT 5')->fetchAll());
?>
<footer class="site-footer">
  <div class="container footer-grid">
    <!-- Kolom kiri: Navigasi + Fakultas -->
    <div class="footer-col footer-links-group">
      <div>
        <h4>Navigasi</h4>
        <a href="<?= BASE_URL ?>/index.php">Home</a>
        <a href="<?= BASE_URL ?>/archive.php">Arsip</a>
        <a href="<?= BASE_URL ?>/about.php">Tentang Kami</a>
        <a href="<?= BASE_URL ?>/members.php">Redaksi</a>
        <a href="<?= BASE_URL ?>/contact.php">Kontak</a>
      </div>
      <div>
        <h4>Fakultas</h4>
        <?php foreach ($foot_categories as $c): ?>
          <a href="<?= BASE_URL ?>/category.php?slug=<?= e($c['slug']) ?>"><?= e($c['name']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Kolom tengah: Contact Us -->
    <div class="footer-col">
      <h4>Contact Us</h4>
      <p class="footer-contact">
        <strong>Alamat</strong>
        Universitas Sam Ratulangi, Jl. Kampus Unsrat, Bahu, Kec. Malalayang, Kota Manado, Sulawesi Utara 95115.
      </p>
      <p class="footer-contact">
        <strong>Email</strong>
        redaksi@unsrat.ac.id
      </p>
      <p class="footer-contact">
        <strong>Jam Operasional</strong>
        Senin &ndash; Jumat, 08.00 &ndash; 16.00 WITA.
      </p>
    </div>

    <!-- Kolom kanan: teks tentang UNSRAT (atas) + gambar (bawah) -->
    <div class="footer-col footer-brand-col">
      <h4>Tentang UNSRAT</h4>
      <p>Wadah pertukaran intelektual, penelitian, dan opini civitas akademika Universitas Sam Ratulangi Manado.</p>
      <div class="footer-logo">
        <img src="<?= UPLOAD_URL ?>/footer_unsrat.png" alt="Universitas Sam Ratulangi">
        <span>Universitas Sam Ratulangi</span>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      &copy; <?= date('Y') ?> UNSRAT Community Blog. All rights reserved.
    </div>
  </div>
</footer>

<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
