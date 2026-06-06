<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$bulan_id = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$year  = (int)($_GET['year'] ?? 0);
$month = (int)($_GET['month'] ?? 0);

// Daftar bulan (sidebar arsip).
$months = $pdo->query(
  "SELECT YEAR(created_at) AS th, MONTH(created_at) AS bl, COUNT(*) AS jml
   FROM articles WHERE status='published'
   GROUP BY th, bl ORDER BY th DESC, bl DESC")->fetchAll();

// Artikel terfilter atau semua.
if ($year && $month) {
    $stmt = $pdo->prepare(
      "SELECT a.*, c.name AS kategori FROM articles a JOIN categories c ON a.category_id=c.id
       WHERE a.status='published' AND YEAR(a.created_at)=? AND MONTH(a.created_at)=?
       ORDER BY a.created_at DESC");
    $stmt->execute([$year, $month]);
    $articles = $stmt->fetchAll();
    $heading = 'Arsip: ' . ($bulan_id[$month] ?? '') . ' ' . $year;
} else {
    $articles = $pdo->query(
      "SELECT a.*, c.name AS kategori FROM articles a JOIN categories c ON a.category_id=c.id
       WHERE a.status='published' ORDER BY a.created_at DESC")->fetchAll();
    $heading = 'Arsip Artikel';
}

$page_title = 'Arsip - UNSRAT Community Blog';
require __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="page-head">
    <div class="breadcrumb"><a href="<?= BASE_URL ?>/index.php">Home</a> &rsaquo; Arsip</div>
    <h1><?= e($heading) ?></h1>
    <p><?= count($articles) ?> artikel.</p>
  </div>

  <div class="layout">
    <main>
      <?php if (!$articles): ?>
        <p class="empty">Tidak ada artikel untuk periode ini.</p>
      <?php else: ?>
        <div class="card-grid">
          <?php foreach ($articles as $a) require __DIR__ . '/includes/article-card.php'; ?>
        </div>
      <?php endif; ?>
    </main>
    <aside>
      <div class="widget">
        <h3>Arsip Bulanan</h3>
        <?php foreach ($months as $m): ?>
          <a class="archive-row" href="<?= BASE_URL ?>/archive.php?year=<?= (int)$m['th'] ?>&month=<?= (int)$m['bl'] ?>">
            <span><?= $bulan_id[(int)$m['bl']] ?> <?= (int)$m['th'] ?></span>
            <span class="count">(<?= (int)$m['jml'] ?>)</span>
          </a>
        <?php endforeach; ?>
        <?php if ($year && $month): ?>
          <a class="btn btn-outline btn-sm btn-block" style="margin-top:12px" href="<?= BASE_URL ?>/archive.php">Lihat Semua</a>
        <?php endif; ?>
      </div>
    </aside>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
