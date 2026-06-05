<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

// --- Aksi POST (PRG) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
        $pdo->prepare("DELETE FROM articles WHERE id=?")->execute([$id]);
        set_flash('success', 'Artikel dihapus.');
    } elseif ($action === 'publish') {
        $pdo->prepare("UPDATE articles SET status='published' WHERE id=?")->execute([$id]);
        set_flash('success', 'Artikel diterbitkan.');
    } elseif ($action === 'unpublish') {
        $pdo->prepare("UPDATE articles SET status='draft' WHERE id=?")->execute([$id]);
        set_flash('success', 'Artikel dijadikan draft.');
    } elseif ($action === 'update') {
        $title  = trim($_POST['title'] ?? '');
        $catId  = (int)($_POST['category_id'] ?? 0);
        $excerpt= trim($_POST['excerpt'] ?? '');
        $content= trim($_POST['content'] ?? '');
        $status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';
        if ($title && $catId && $content) {
            $pdo->prepare("UPDATE articles SET title=?, category_id=?, excerpt=?, content=?, status=? WHERE id=?")
                ->execute([$title, $catId, $excerpt, $content, $status, $id]);
            set_flash('success', 'Artikel diperbarui.');
        } else {
            set_flash('error', 'Judul, kategori, dan konten wajib diisi.');
        }
    }
    redirect('/admin/articles.php');
}

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

// --- Mode edit ---
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id=?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

// --- Pencarian + daftar ---
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare(
      "SELECT a.*, u.name AS penulis, c.name AS kategori
       FROM articles a JOIN users u ON a.user_id=u.id JOIN categories c ON a.category_id=c.id
       WHERE a.title LIKE ? ORDER BY a.created_at DESC");
    $stmt->execute(['%' . $q . '%']);
    $articles = $stmt->fetchAll();
} else {
    $articles = $pdo->query(
      "SELECT a.*, u.name AS penulis, c.name AS kategori
       FROM articles a JOIN users u ON a.user_id=u.id JOIN categories c ON a.category_id=c.id
       ORDER BY a.created_at DESC")->fetchAll();
}

$active = 'articles';
$crumb = 'Kelola Artikel';
$page_title = 'Kelola Artikel - Admin';
require __DIR__ . '/../includes/admin-header.php';
?>

<?php if ($edit): ?>
<div class="panel" style="margin-bottom:24px;">
  <div class="panel-head"><h2>Edit Artikel</h2></div>
  <div style="padding:22px;">
    <form method="post" data-validate>
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= (int)$edit['id'] ?>">
      <div class="field"><label>Judul</label><input type="text" name="title" value="<?= e($edit['title']) ?>" required></div>
      <div class="field"><label>Kategori</label>
        <select name="category_id" required>
          <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= ((int)$edit['category_id']===(int)$c['id'])?'selected':'' ?>><?= e($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field"><label>Ringkasan</label><input type="text" name="excerpt" value="<?= e($edit['excerpt']) ?>"></div>
      <div class="field"><label>Konten</label><textarea name="content" rows="8" required><?= e($edit['content']) ?></textarea></div>
      <div class="field"><label>Status</label>
        <select name="status">
          <option value="published" <?= $edit['status']==='published'?'selected':'' ?>>Published</option>
          <option value="draft" <?= $edit['status']==='draft'?'selected':'' ?>>Draft</option>
        </select>
      </div>
      <button class="btn btn-primary" type="submit">Simpan</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>/admin/articles.php">Batal</a>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="panel">
  <div class="panel-head">
    <div><h2>Semua Artikel</h2><p>Kelola seluruh publikasi konten blog kampus.</p></div>
    <form class="toolbar" method="get">
      <input type="search" name="q" placeholder="Cari artikel..." value="<?= e($q) ?>">
      <button class="btn btn-outline btn-sm" type="submit">Cari</button>
    </form>
  </div>
  <?php if (!$articles): ?>
    <p class="empty">Tidak ada artikel.</p>
  <?php else: ?>
  <table class="data">
    <thead><tr><th>Judul</th><th>Penulis</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($articles as $a): ?>
      <tr>
        <td><div class="t-title"><?= e($a['title']) ?></div><div class="t-sub">Kategori: <?= e($a['kategori']) ?></div></td>
        <td><div class="row-author"><span class="avatar avatar-sm"><?= e(initials($a['penulis'])) ?></span><?= e($a['penulis']) ?></div></td>
        <td><span class="badge badge-<?= e($a['status']) ?>"><?= e(ucfirst($a['status'])) ?></span></td>
        <td><?= e(tanggal_id($a['created_at'])) ?></td>
        <td class="actions-cell">
          <?php if ($a['status'] === 'draft'): ?>
            <form method="post" style="display:inline">
              <?= csrf_field() ?><input type="hidden" name="action" value="publish"><input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
              <button class="btn btn-primary btn-sm" type="submit">Approve</button>
            </form>
          <?php endif; ?>
          <a class="icon-btn" title="Edit" href="<?= BASE_URL ?>/admin/articles.php?edit=<?= (int)$a['id'] ?>">&#9998;</a>
          <a class="icon-btn" title="Lihat" href="<?= BASE_URL ?>/article.php?slug=<?= e($a['slug']) ?>">&#128065;</a>
          <form method="post" data-confirm="Hapus artikel ini?" style="display:inline">
            <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
            <button class="icon-btn danger" title="Hapus" type="submit">&#128465;</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
