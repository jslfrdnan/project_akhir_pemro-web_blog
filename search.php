<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$q = trim($_GET['q'] ?? '');
$articles = [];
if ($q !== '') {
    $stmt = $pdo->prepare(
      "SELECT a.*, c.name AS kategori FROM articles a
       JOIN categories c ON a.category_id=c.id
       WHERE a.status='published' AND (a.title LIKE ? OR a.content LIKE ?)
       ORDER BY a.created_at DESC");
    $like = '%' . $q . '%';
    $stmt->execute([$like, $like]);
    $articles = $stmt->fetchAll();
}

$page_title = 'Pencarian - UNSRAT Community Blog';
require __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="page-head">
    <h1>Hasil Pencarian</h1>
    <?php if ($q !== ''): ?>
      <p><?= count($articles) ?> artikel untuk &ldquo;<?= e($q) ?>&rdquo;.</p>
    <?php else: ?>
      <p>Masukkan kata kunci di kotak pencarian untuk mencari artikel.</p>
    <?php endif; ?>
  </div>

  <?php if ($q !== '' && !$articles): ?>
    <p class="empty">Tidak ada artikel yang cocok.</p>
  <?php elseif ($articles): ?>
    <div class="card-grid" style="margin-bottom:50px;">
      <?php foreach ($articles as $a) require __DIR__ . '/includes/article-card.php'; ?>
    </div>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
