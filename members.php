<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// ====== EDIT DI SINI: data anggota kelompok / tim redaksi ======
// Ganti nama, NIM, dan peran sesuai anggota kelompokmu.
$members = [
  ['name' => 'Jasiel Wowiling', 'nim' => 'NIM 240211060114', 'role' => 'Backend' , 'photo' => 'WhatsApp Image 2026-06-04 at 08.54.57.jpeg'],
  ['name' => 'Gian Keintjem', 'nim' => 'NIM 240211060036', 'role' => 'Frontend / UI', 'photo' => 'WhatsApp Image 2026-06-04 at 08.49.39.jpeg'],
  ['name' => 'Brandon Sumarlie', 'nim' => 'NIM 240211060117', 'role' => 'Backend' , 'photo' => 'WhatsApp Image 2026-06-04 at 08.52.00.jpeg'],
  ['name' => 'Fabryo Oroh', 'nim' => 'NIM 240211060033', 'role' => 'Testing', 'photo' => 'WhatsApp Image 2026-06-04 at 08.50.37.jpeg'],
];
// ===============================================================

$page_title = 'Tim Redaksi - UNSRAT Community Blog';
require __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="page-head">
    <div class="breadcrumb"><a href="<?= BASE_URL ?>/index.php">Home</a> &rsaquo; Redaksi</div>
    <h1>Anggota Kelompok</h1>
    <p>Pengembang dan pengelola portal blog komunitas UNSRAT.</p>
  </div>

  <div class="member-grid" style="margin-bottom:50px;">
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
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
