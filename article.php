<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$slug = $_GET['slug'] ?? '';

// Ambil artikel.
$stmt = $pdo->prepare(
  "SELECT a.*, u.id AS author_id, u.name AS penulis, u.faculty, u.avatar AS author_avatar,
          c.name AS kategori, c.slug AS kategori_slug
   FROM articles a
   JOIN users u ON a.user_id = u.id
   JOIN categories c ON a.category_id = c.id
   WHERE a.slug = ? AND a.status = 'published'");
$stmt->execute([$slug]);
$article = $stmt->fetch();

if (!$article) {
    http_response_code(404);
    $page_title = 'Artikel tidak ditemukan';
    require __DIR__ . '/includes/header.php';
    echo '<div class="container"><p class="empty">Artikel tidak ditemukan.</p></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

// Proses komentar (POST) — pola PRG.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_login();
    csrf_check();
    $content = trim($_POST['content'] ?? '');
    if ($content !== '') {
        $ins = $pdo->prepare(
          "INSERT INTO comments (article_id, user_id, content, status) VALUES (?, ?, ?, 'approved')");
        $ins->execute([$article['id'], $_SESSION['user_id'], $content]);
        set_flash('success', 'Komentar berhasil dikirim.');
    }
    redirect('/article.php?slug=' . urlencode($slug) . '#komentar');
}

// Hitung views (+1).
$pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?")->execute([$article['id']]);

// Komentar approved.
$cstmt = $pdo->prepare(
  "SELECT cm.*, u.name AS penulis, u.avatar
   FROM comments cm JOIN users u ON cm.user_id = u.id
   WHERE cm.article_id = ? AND cm.status = 'approved'
   ORDER BY cm.created_at ASC");
$cstmt->execute([$article['id']]);
$comments = $cstmt->fetchAll();

// Artikel terkait (kategori sama).
$rstmt = $pdo->prepare(
  "SELECT a.title, a.slug, a.cover_image, c.name AS kategori
   FROM articles a JOIN categories c ON a.category_id = c.id
   WHERE a.category_id = ? AND a.id <> ? AND a.status='published'
   ORDER BY a.created_at DESC LIMIT 3");
$rstmt->execute([$article['category_id'], $article['id']]);
$related = $rstmt->fetchAll();

$me = current_user($pdo);
$page_title = $article['title'] . ' - UNSRAT Community Blog';
require __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div class="breadcrumb">
    <a href="<?= BASE_URL ?>/index.php">Home</a> &rsaquo;
    <a href="<?= BASE_URL ?>/category.php?slug=<?= e($article['kategori_slug']) ?>"><?= e($article['kategori']) ?></a>
  </div>

  <div class="layout">
    <main>
      <article class="article-detail">
        <?php if ($article['cover_image']): ?>
          <img class="article-cover" src="<?= UPLOAD_URL . '/' . e($article['cover_image']) ?>" alt="">
        <?php else: ?>
          <div class="article-cover"></div>
        <?php endif; ?>
        <div class="article-inner">
          <span class="chip"><?= e($article['kategori']) ?></span>
          <h1><?= e($article['title']) ?></h1>

          <div class="article-byline">
            <span class="avatar avatar-md">
              <?php if ($article['author_avatar']): ?>
                <img src="<?= UPLOAD_URL . '/' . e($article['author_avatar']) ?>" alt="">
              <?php else: ?><?= e(initials($article['penulis'])) ?><?php endif; ?>
            </span>
            <div>
              <div class="who"><a href="<?= BASE_URL ?>/author.php?id=<?= (int)$article['author_id'] ?>"><?= e($article['penulis']) ?></a></div>
              <div class="sub"><?= e($article['faculty'] ?: 'Civitas UNSRAT') ?> &bull; <?= e(tanggal_id($article['created_at'])) ?> &bull; <?= (int)$article['views'] ?> kali dibaca</div>
            </div>
          </div>

          <div class="article-content">
            <?php
              // Konten disimpan sebagai teks dengan baris baru; tampilkan per paragraf (aman: di-escape).
              foreach (preg_split('/\n\s*\n/', trim($article['content'])) as $i => $para) {
                  $para = trim($para);
                  if ($para === '') continue;
                  $cls = ($i === 0) ? ' class="lead"' : '';
                  // Paragraf yang diapit tanda kutip → tampilkan sebagai blockquote.
                  if (preg_match('/^".*"$/s', $para)) {
                      echo '<blockquote>' . e($para) . '</blockquote>';
                  } else {
                      echo '<p' . $cls . '>' . nl2br(e($para)) . '</p>';
                  }
              }
            ?>
          </div>

          <div class="tag-row">
            <a class="chip" href="<?= BASE_URL ?>/category.php?slug=<?= e($article['kategori_slug']) ?>">#<?= e($article['kategori']) ?></a>
            <span class="chip">#UNSRAT</span>
          </div>
        </div>
      </article>

      <!-- Komentar -->
      <section class="comments" id="komentar">
        <h2>Komentar <span class="comment-count"><?= count($comments) ?></span></h2>

        <div class="form-card comment-form" style="margin-bottom:24px;">
          <?php if ($me): ?>
            <form method="post" action="<?= BASE_URL ?>/article.php?slug=<?= e($slug) ?>" data-validate>
              <?= csrf_field() ?>
              <textarea name="content" placeholder="Tulis komentar Anda di sini..." required></textarea>
              <div class="comment-form-footer">
                <span class="hint">Berkomentar sebagai <strong><?= e($me['name']) ?></strong></span>
                <button class="btn btn-primary" type="submit">Kirim</button>
              </div>
            </form>
          <?php else: ?>
            <p class="hint">Silakan <a href="<?= BASE_URL ?>/login.php">login</a> untuk berkomentar.</p>
          <?php endif; ?>
        </div>

        <?php if (!$comments): ?>
          <p class="empty">Belum ada komentar. Jadilah yang pertama!</p>
        <?php else: ?>
          <?php foreach ($comments as $cm): ?>
            <div class="comment">
              <span class="avatar avatar-md">
                <?php if ($cm['avatar']): ?>
                  <img src="<?= UPLOAD_URL . '/' . e($cm['avatar']) ?>" alt="">
                <?php else: ?><?= e(initials($cm['penulis'])) ?><?php endif; ?>
              </span>
              <div>
                <span class="who"><?= e($cm['penulis']) ?></span>
                <span class="when"><?= e(time_ago($cm['created_at'])) ?></span>
                <p><?= nl2br(e($cm['content'])) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </main>

    <aside>
      <div class="widget">
        <h3>Artikel Terkait</h3>
        <?php if (!$related): ?>
          <p class="hint">Belum ada artikel terkait.</p>
        <?php else: ?>
          <?php foreach ($related as $r): ?>
            <a class="related-item" href="<?= BASE_URL ?>/article.php?slug=<?= e($r['slug']) ?>">
              <?php if ($r['cover_image']): ?>
                <img src="<?= UPLOAD_URL . '/' . e($r['cover_image']) ?>" alt="">
              <?php else: ?><span class="related-thumb"></span><?php endif; ?>
              <div>
                <div class="k"><?= e($r['kategori']) ?></div>
                <div class="t"><?= e($r['title']) ?></div>
              </div>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="widget widget-cta">
        <h3>Jangan Lewatkan Update!</h3>
        <p>Dapatkan berita akademik dan komunitas terbaru langsung di email Anda.</p>
        <input type="email" placeholder="Email Anda">
        <button type="button" class="btn btn-light btn-block">Berlangganan</button>
      </div>
    </aside>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
