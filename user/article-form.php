<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$uid = $_SESSION['user_id'];
$id  = (int)($_GET['id'] ?? 0);
$editing = $id > 0;
$errors = [];

// Saat edit: pastikan artikel milik user ini.
$article = ['title' => '', 'category_id' => '', 'excerpt' => '', 'content' => '', 'status' => 'published', 'cover_image' => null];
if ($editing) {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $uid]);
    $article = $stmt->fetch();
    if (!$article) { http_response_code(403); exit('Artikel tidak ditemukan atau bukan milik Anda.'); }
}

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $title  = trim($_POST['title'] ?? '');
    $catId  = (int)($_POST['category_id'] ?? 0);
    $excerpt= trim($_POST['excerpt'] ?? '');
    $content= trim($_POST['content'] ?? '');
    $status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';

    if ($title === '')   $errors[] = 'Judul wajib diisi.';
    if ($catId <= 0)     $errors[] = 'Kategori wajib dipilih.';
    if ($content === '') $errors[] = 'Konten wajib diisi.';

    // Upload cover (opsional).
    $cover = $article['cover_image'];
    if (!empty($_FILES['cover']['name']) && $_FILES['cover']['error'] === 0) {
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $type = mime_content_type($_FILES['cover']['tmp_name']);
        if (!isset($allowed[$type])) {
            $errors[] = 'Format gambar harus JPG, PNG, atau WEBP.';
        } elseif ($_FILES['cover']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Ukuran gambar maksimal 2 MB.';
        } else {
            $fname = 'cover_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$type];
            move_uploaded_file($_FILES['cover']['tmp_name'], UPLOAD_DIR . '/' . $fname);
            $cover = $fname;
        }
    }

    if (!$errors) {
        // Slug unik.
        $base = slugify($title);
        $slug = $base;
        $chk = $pdo->prepare("SELECT id FROM articles WHERE slug = ? AND id <> ?");
        $chk->execute([$slug, $id]);
        if ($chk->fetch()) $slug = $base . '-' . time();

        $auto_excerpt = $excerpt !== '' ? $excerpt : excerpt_text($content, 200);

        if ($editing) {
            $up = $pdo->prepare(
              "UPDATE articles SET title=?, slug=?, category_id=?, excerpt=?, content=?, status=?, cover_image=?
               WHERE id=? AND user_id=?");
            $up->execute([$title, $slug, $catId, $auto_excerpt, $content, $status, $cover, $id, $uid]);
            set_flash('success', 'Artikel diperbarui.');
        } else {
            $ins = $pdo->prepare(
              "INSERT INTO articles (user_id, category_id, title, slug, excerpt, content, status, cover_image)
               VALUES (?,?,?,?,?,?,?,?)");
            $ins->execute([$uid, $catId, $title, $slug, $auto_excerpt, $content, $status, $cover]);
            set_flash('success', 'Artikel berhasil dibuat.');
        }
        redirect('/user/articles.php');
    }

    // Pertahankan input bila ada error.
    $article = compact('title', 'excerpt', 'content', 'status') + ['category_id' => $catId, 'cover_image' => $cover];
}

$page_title = ($editing ? 'Edit' : 'Tulis') . ' Artikel - UNSRAT Community Blog';
require __DIR__ . '/../includes/header.php';
?>
<div class="container">
  <div class="page-head"><h1><?= $editing ? 'Edit Artikel' : 'Tulis Artikel Baru' ?></h1></div>

  <?php if ($errors): ?><div class="flash flash-error"><?= e(implode(' ', $errors)) ?></div><?php endif; ?>

  <div class="form-card">
    <form method="post" enctype="multipart/form-data" data-validate>
      <?= csrf_field() ?>
      <div class="field">
        <label>Judul</label>
        <input type="text" name="title" value="<?= e($article['title']) ?>" required>
      </div>
      <div class="field">
        <label>Kategori</label>
        <select name="category_id" required>
          <option value="">— Pilih kategori —</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= ((int)$article['category_id']===(int)$c['id'])?'selected':'' ?>><?= e($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label>Ringkasan <span style="color:var(--muted-light)">(opsional)</span></label>
        <input type="text" name="excerpt" value="<?= e($article['excerpt']) ?>" placeholder="Ringkasan singkat untuk daftar artikel">
      </div>
      <div class="field">
        <label>Konten</label>
        <textarea name="content" rows="12" required><?= e($article['content']) ?></textarea>
      </div>
      <div class="field">
        <label>Cover (JPG/PNG/WEBP, maks 2 MB)</label>
        <input type="file" name="cover" accept="image/*">
        <?php if ($article['cover_image']): ?>
          <p class="hint" style="margin-top:8px">Cover saat ini: <?= e($article['cover_image']) ?></p>
        <?php endif; ?>
      </div>
      <div class="field">
        <label>Status</label>
        <select name="status">
          <option value="published" <?= $article['status']==='published'?'selected':'' ?>>Published</option>
          <option value="draft" <?= $article['status']==='draft'?'selected':'' ?>>Draft</option>
        </select>
      </div>
      <button class="btn btn-primary" type="submit"><?= $editing ? 'Simpan Perubahan' : 'Terbitkan' ?></button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>/user/articles.php">Batal</a>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
