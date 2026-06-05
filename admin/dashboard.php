<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$totalArticles = (int)$pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$pendingComments = (int)$pdo->query("SELECT COUNT(*) FROM comments WHERE status='pending'")->fetchColumn();
$todayComments = (int)$pdo->query("SELECT COUNT(*) FROM comments WHERE DATE(created_at)=CURDATE()")->fetchColumn();
$activeUsers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role IN ('dosen','mahasiswa')")->fetchColumn();

$recent = $pdo->query(
  "SELECT a.*, u.name AS penulis, c.name AS kategori
   FROM articles a JOIN users u ON a.user_id=u.id JOIN categories c ON a.category_id=c.id
   ORDER BY a.created_at DESC LIMIT 5")->fetchAll();

$active = 'dashboard';
$crumb = 'Ringkasan';
$page_title = 'Dashboard Manajemen - UNSRAT Community Blog';
require __DIR__ . '/../includes/admin-header.php';
?>

<div class="stat-grid">
  <div class="stat-card">
    <div class="top"><span class="ico">&#128196;</span></div>
    <div class="num"><?= number_format($totalArticles, 0, ',', '.') ?></div>
    <div class="lbl">Total Artikel</div>
    <div class="note">Diterbitkan sejak Januari 2024</div>
  </div>
  <div class="stat-card">
    <div class="top"><span class="ico">&#128172;</span><span class="badge badge-pending">Perlu Review</span></div>
    <div class="num"><?= $pendingComments ?></div>
    <div class="lbl">Komentar Menunggu</div>
    <div class="note"><?= $todayComments ?> komentar baru hari ini</div>
  </div>
  <div class="stat-card">
    <div class="top"><span class="ico">&#128101;</span></div>
    <div class="num"><?= number_format($activeUsers, 0, ',', '.') ?></div>
    <div class="lbl">Pengguna Aktif</div>
    <div class="note">Mahasiswa &amp; Dosen UNSRAT</div>
  </div>
</div>

<div class="panel">
  <div class="panel-head">
    <div>
      <h2>Artikel Terbaru</h2>
      <p>Kelola dan monitor publikasi konten blog kampus.</p>
    </div>
    <a class="btn btn-outline btn-sm" href="<?= BASE_URL ?>/admin/articles.php">Kelola Semua</a>
  </div>
  <table class="data">
    <thead><tr><th>Judul Artikel</th><th>Penulis</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($recent as $a): ?>
      <tr>
        <td>
          <div class="t-title"><?= e($a['title']) ?></div>
          <div class="t-sub">Kategori: <?= e($a['kategori']) ?></div>
        </td>
        <td><div class="row-author"><span class="avatar avatar-sm"><?= e(initials($a['penulis'])) ?></span><?= e($a['penulis']) ?></div></td>
        <td><span class="badge badge-<?= e($a['status']) ?>"><?= e(ucfirst($a['status'])) ?></span></td>
        <td><?= e(tanggal_id($a['created_at'])) ?></td>
        <td class="actions-cell">
          <a class="icon-btn" title="Edit" href="<?= BASE_URL ?>/admin/articles.php?edit=<?= (int)$a['id'] ?>">&#9998;</a>
          <a class="icon-btn" title="Lihat" href="<?= BASE_URL ?>/article.php?slug=<?= e($a['slug']) ?>">&#128065;</a>
          <form method="post" action="<?= BASE_URL ?>/admin/articles.php" data-confirm="Hapus artikel ini?" style="display:inline">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
            <button class="icon-btn danger" title="Hapus" type="submit">&#128465;</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
