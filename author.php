<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$tab = ($_GET['tab'] ?? 'artikel') === 'tentang' ? 'tentang' : 'artikel';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$id]);
$author = $stmt->fetch();

if (!$author) {
    http_response_code(404);
    $page_title = 'Pengguna tidak ditemukan';
    require __DIR__ . '/includes/header.php';
    echo '<div class="container"><p class="empty">Pengguna tidak ditemukan.</p></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

// Artikel publik milik penulis (+ jumlah komentar).
$astmt = $pdo->prepare(
  "SELECT a.*, c.name AS kategori,
          (SELECT COUNT(*) FROM comments cm WHERE cm.article_id=a.id AND cm.status='approved') AS jml_komentar
   FROM articles a JOIN categories c ON a.category_id=c.id
   WHERE a.user_id=? AND a.status='published'
   ORDER BY a.created_at DESC");
$astmt->execute([$id]);
$articles = $astmt->fetchAll();

$role_label = ['admin' => 'Admin', 'dosen' => 'Dosen', 'mahasiswa' => 'Mahasiswa'][$author['role']] ?? '';

$page_title = $author['name'] . ' - UNSRAT Community Blog';
require __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="profile-card">
    <div class="profile-banner"></div>
    <div class="profile-head">
      <span class="avatar avatar-lg">
        <?php if ($author['avatar']): ?><img src="<?= UPLOAD_URL . '/' . e($author['avatar']) ?>" alt=""><?php else: ?><?= e(initials($author['name'])) ?><?php endif; ?>
      </span>
      <div class="info">
        <h1><?= e($author['name']) ?></h1>
        <span class="chip"><?= e($role_label) ?></span>
        <div class="fac"><?= e($author['faculty'] ?: 'Universitas Sam Ratulangi') ?></div>
      </div>
    </div>
    <div class="profile-bio">
      <h4>Bio Singkat</h4>
      <p><?= e($author['bio'] ?: 'Belum ada bio.') ?></p>
    </div>
  </div>

  <div class="tabs">
    <a href="<?= BASE_URL ?>/author.php?id=<?= $id ?>&tab=artikel" class="<?= $tab==='artikel'?'active':'' ?>">Artikel (<?= count($articles) ?>)</a>
    <a href="<?= BASE_URL ?>/author.php?id=<?= $id ?>&tab=tentang" class="<?= $tab==='tentang'?'active':'' ?>">Tentang</a>
  </div>

  <?php if ($tab === 'tentang'): ?>
    <div class="prose" style="margin-bottom:50px;">
      <h2>Tentang <?= e($author['name']) ?></h2>
      <p><?= nl2br(e($author['bio'] ?: 'Belum ada informasi.')) ?></p>
      <p><strong>Peran:</strong> <?= e($role_label) ?></p>
      <p><strong>Fakultas:</strong> <?= e($author['faculty'] ?: '-') ?></p>
      <p><strong>Bergabung:</strong> <?= e(tanggal_id($author['created_at'])) ?></p>
    </div>
  <?php else: ?>
    <?php if (!$articles): ?>
      <p class="empty">Belum ada artikel yang dipublikasikan.</p>
    <?php else: ?>
      <div class="card-grid" style="margin-bottom:50px;">
        <?php foreach ($articles as $a): ?>
          <article class="acard">
            <?php if ($a['cover_image']): ?>
              <img class="acard-cover" src="<?= UPLOAD_URL . '/' . e($a['cover_image']) ?>" alt="">
            <?php else: ?><div class="acard-cover"></div><?php endif; ?>
            <div class="acard-body">
              <div><span class="chip"><?= e($a['kategori']) ?></span></div>
              <h3 class="acard-title"><a href="<?= BASE_URL ?>/article.php?slug=<?= e($a['slug']) ?>"><?= e($a['title']) ?></a></h3>
              <p class="acard-excerpt"><?= e(excerpt_text($a['excerpt'] ?: $a['content'], 100)) ?></p>
              <div class="acard-meta">
                <span><?= e(tanggal_id($a['created_at'])) ?></span>
                <span>&#128065; <?= (int)$a['views'] ?></span>
                <span>&#128172; <?= (int)$a['jml_komentar'] ?></span>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
