<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Artikel unggulan (terbaru) untuk hero.
$featured = $pdo->query(
  "SELECT a.*, u.name AS penulis, c.name AS kategori, c.slug AS kategori_slug
   FROM articles a
   JOIN users u ON a.user_id = u.id
   JOIN categories c ON a.category_id = c.id
   WHERE a.status = 'published'
   ORDER BY a.created_at DESC LIMIT 1")->fetch();

// Artikel terbaru (6 setelah hero).
$stmt = $pdo->prepare(
  "SELECT a.*, u.name AS penulis, c.name AS kategori, c.slug AS kategori_slug
   FROM articles a
   JOIN users u ON a.user_id = u.id
   JOIN categories c ON a.category_id = c.id
   WHERE a.status = 'published' AND a.id <> ?
   ORDER BY a.created_at DESC LIMIT 4");
$stmt->execute([$featured['id'] ?? 0]);
$latest = $stmt->fetchAll();

// Populer (views terbanyak).
$popular = $pdo->query(
  "SELECT title, slug FROM articles WHERE status='published' ORDER BY views DESC LIMIT 3")->fetchAll();

// Kategori.
$categories = $pdo->query("SELECT name, slug FROM categories ORDER BY name")->fetchAll();

// Arsip bulanan.
$archives = $pdo->query(
  "SELECT YEAR(created_at) AS th, MONTH(created_at) AS bl, COUNT(*) AS jml
   FROM articles WHERE status='published'
   GROUP BY th, bl ORDER BY th DESC, bl DESC LIMIT 6")->fetchAll();
$bulan_id = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

$page_title = 'Beranda - UNSRAT Community Blog';
require __DIR__ . '/includes/header.php';
?>

<div class="container">
  <?php if ($featured): ?>
  <section class="hero">
    <?php if ($featured['cover_image']): ?>
      <img class="hero-bg" src="<?= UPLOAD_URL . '/' . e($featured['cover_image']) ?>" alt="">
    <?php endif; ?>
    <div class="hero-inner">
      <span class="chip chip-dark">PENGUMUMAN</span>
      <h1><?= e($featured['title']) ?></h1>
      <p><?= e($featured['excerpt']) ?></p>
      <a class="btn btn-light" href="<?= BASE_URL ?>/article.php?slug=<?= e($featured['slug']) ?>">Baca Selengkapnya</a>
      <div class="hero-meta"><?= e(tanggal_id($featured['created_at'])) ?> &bull; Oleh <?= e($featured['penulis']) ?></div>
    </div>
  </section>
  <?php endif; ?>

  <div class="layout">
    <main>
      <div class="section-head">
        <h2>Artikel Terbaru</h2>
        <a href="<?= BASE_URL ?>/archive.php">Lihat Semua</a>
      </div>

      <?php if (!$latest): ?>
        <p class="empty">Belum ada artikel.</p>
      <?php else: ?>
      <div class="card-grid">
        <?php foreach ($latest as $a): ?>
          <article class="acard">
            <?php if ($a['cover_image']): ?>
              <img class="acard-cover" src="<?= UPLOAD_URL . '/' . e($a['cover_image']) ?>" alt="">
            <?php else: ?>
              <div class="acard-cover"></div>
            <?php endif; ?>
            <div class="acard-body">
              <div><span class="chip"><?= e($a['kategori']) ?></span></div>
              <h3 class="acard-title">
                <a href="<?= BASE_URL ?>/article.php?slug=<?= e($a['slug']) ?>"><?= e($a['title']) ?></a>
              </h3>
              <p class="acard-excerpt"><?= e(excerpt_text($a['excerpt'] ?: $a['content'], 110)) ?></p>
              <div class="acard-meta"><span><?= e(time_ago($a['created_at'])) ?></span></div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </main>

    <aside>
      <div class="widget">
        <h3>&#128200; Populer Saat Ini</h3>
        <?php foreach ($popular as $i => $p): ?>
          <div class="popular-item">
            <span class="popular-rank"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
            <a href="<?= BASE_URL ?>/article.php?slug=<?= e($p['slug']) ?>"><?= e($p['title']) ?></a>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="widget">
        <h3>Kategori</h3>
        <div class="chip-cloud">
          <?php foreach ($categories as $c): ?>
            <a class="chip" href="<?= BASE_URL ?>/category.php?slug=<?= e($c['slug']) ?>"><?= e($c['name']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="widget">
        <h3>Arsip Bulanan</h3>
        <?php foreach ($archives as $ar): ?>
          <a class="archive-row" href="<?= BASE_URL ?>/archive.php?year=<?= (int)$ar['th'] ?>&month=<?= (int)$ar['bl'] ?>">
            <span><?= $bulan_id[(int)$ar['bl']] ?> <?= (int)$ar['th'] ?></span>
            <span class="count">(<?= (int)$ar['jml'] ?>)</span>
          </a>
        <?php endforeach; ?>
      </div>
    </aside>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
