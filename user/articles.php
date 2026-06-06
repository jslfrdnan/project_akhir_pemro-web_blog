<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$uid = $_SESSION['user_id'];
$arts = $pdo->prepare(
  "SELECT a.*, c.name AS kategori FROM articles a
   JOIN categories c ON a.category_id = c.id
   WHERE a.user_id = ? ORDER BY a.created_at DESC");
$arts->execute([$uid]);
$articles = $arts->fetchAll();

$page_title = 'Artikel Saya - UNSRAT Community Blog';
require __DIR__ . '/../includes/header.php';
?>
<div class="container">
  <div class="page-head" style="display:flex;justify-content:space-between;align-items:center;">
    <div><h1>Artikel Saya</h1><p>Semua tulisan yang pernah kamu buat.</p></div>
    <a class="btn btn-primary" href="<?= BASE_URL ?>/user/article-form.php">+ Tulis Artikel Baru</a>
  </div>

  <div class="panel">
    <?php if (!$articles): ?>
      <p class="empty">Belum ada artikel.</p>
    <?php else: ?>
      <table class="data">
        <thead><tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Dibaca</th><th>Tanggal</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($articles as $a): ?>
          <tr>
            <td class="t-title"><?= e($a['title']) ?></td>
            <td><span class="chip"><?= e($a['kategori']) ?></span></td>
            <td><span class="badge badge-<?= e($a['status']) ?>"><?= e(ucfirst($a['status'])) ?></span></td>
            <td><?= (int)$a['views'] ?></td>
            <td><?= e(tanggal_id($a['created_at'])) ?></td>
            <td class="actions-cell">
              <a class="icon-btn" title="Edit" href="<?= BASE_URL ?>/user/article-form.php?id=<?= (int)$a['id'] ?>">&#9998;</a>
              <a class="icon-btn" title="Lihat" href="<?= BASE_URL ?>/article.php?slug=<?= e($a['slug']) ?>">&#128065;</a>
              <form method="post" action="<?= BASE_URL ?>/user/article-delete.php" data-confirm="Yakin hapus artikel ini?" style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                <button class="icon-btn danger" title="Hapus" type="submit">&#128465;</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
