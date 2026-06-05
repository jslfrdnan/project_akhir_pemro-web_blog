<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$slug = $_GET['slug'] ?? '';
$cat = $pdo->prepare("SELECT * FROM categories WHERE slug=?");
$cat->execute([$slug]);
$category = $cat->fetch();

if (!$category) {
    http_response_code(404);
    $page_title = 'Kategori tidak ditemukan';
    require __DIR__ . '/includes/header.php';
    echo '<div class="container"><p class="empty">Kategori tidak ditemukan.</p></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = $pdo->prepare(
  "SELECT a.*, c.name AS kategori FROM articles a
   JOIN categories c ON a.category_id=c.id
   WHERE a.category_id=? AND a.status='published'
   ORDER BY a.created_at DESC");
$stmt->execute([$category['id']]);
$articles = $stmt->fetchAll();

$page_title = 'Kategori: ' . $category['name'] . ' - UNSRAT Community Blog';
require __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="page-head">
    <div class="breadcrumb"><a href="<?= BASE_URL ?>/index.php">Home</a> &rsaquo; Kategori</div>
    <h1>Kategori: <?= e($category['name']) ?></h1>
    <p><?= count($articles) ?> artikel ditemukan.</p>
  </div>

  <?php if (!$articles): ?>
    <p class="empty">Belum ada artikel di kategori ini.</p>
  <?php else: ?>
    <div class="card-grid" style="margin-bottom:50px;">
      <?php foreach ($articles as $a) require __DIR__ . '/includes/article-card.php'; ?>
    </div>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
