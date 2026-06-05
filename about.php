<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Data anggota kelompok / tim redaksi (statis). Sama dengan members.php.
$members = [
  ['name' => 'Jasiel Wowiling', 'nim' => 'NIM 240211060114', 'role' => 'Backend' , 'photo' => 'WhatsApp Image 2026-06-04 at 08.54.57.jpeg'],
  ['name' => 'Gian Keintjem', 'nim' => 'NIM 240211060036', 'role' => 'Frontend / UI', 'photo' => 'WhatsApp Image 2026-06-04 at 08.49.39.jpeg'],
  ['name' => 'Brandon Sumarlie', 'nim' => 'NIM 240211060117', 'role' => 'Backend' , 'photo' => 'WhatsApp Image 2026-06-04 at 08.52.00.jpeg'],
  ['name' => 'Fabryo Oroh', 'nim' => 'NIM 240211060033', 'role' => 'Testing', 'photo' => 'WhatsApp Image 2026-06-04 at 08.50.37.jpeg'],
];

$page_title = 'Tentang Portal - UNSRAT Community Blog';
require __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="page-head">
    <div class="breadcrumb"><a href="<?= BASE_URL ?>/index.php">Home</a> &rsaquo; Tentang</div>
    <h1>Tentang Portal</h1>
  </div>
  <div class="prose" style="margin-bottom:50px;">
    <p>UNSRAT Community Blog adalah platform blog komunitas resmi untuk civitas akademika
       Universitas Sam Ratulangi Manado. Portal ini menjadi wadah pertukaran informasi,
       hasil penelitian, opini, dan pemikiran akademis lintas fakultas.</p>

    <h2>Tujuan</h2>
    <p>Mendorong budaya menulis dan berbagi gagasan di kalangan dosen dan mahasiswa, sekaligus
       mempublikasikan kegiatan serta capaian akademik kepada masyarakat luas.</p>

    <h2>Siapa yang Dapat Berkontribusi?</h2>
    <p>Dosen dan mahasiswa UNSRAT yang telah mendaftar dapat menulis artikel dan berkomentar.
       Pengunjung umum dapat membaca seluruh artikel yang telah dipublikasikan. Tim admin
       bertugas memoderasi dan mengelola seluruh konten agar tetap relevan dan berkualitas.</p>

    <h2>Kebijakan Konten</h2>
    <p>Setiap tulisan menjadi tanggung jawab penulisnya. Konten yang melanggar etika akademik,
       mengandung SARA, atau plagiarisme dapat dihapus oleh tim moderasi.</p>
  </div>

  <section id="tim" style="margin-bottom:50px;">
    <h2>Anggota Kelompok</h2>
    <p style="color:var(--muted); margin-bottom:20px;">Pengembang dan pengelola portal blog komunitas UNSRAT.</p>
    <div class="member-grid">
      <?php foreach ($members as $m): ?>
        <div class="member-card">
          <span class="avatar avatar-lg">
            <?php if (!empty($m['photo'])): ?>
              <img src="<?= UPLOAD_URL . '/' . e($m['photo']) ?>" alt="<?= e($m['name']) ?>">
            <?php else: ?>
              <?= e(initials($m['name'])) ?>
            <?php endif; ?>
          </span>
          <h3><?= e($m['name']) ?></h3>
          <div class="role"><?= e($m['role']) ?></div>
          <div class="nim"><?= e($m['nim']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
