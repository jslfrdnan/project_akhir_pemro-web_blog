<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id   = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');

    if ($action === 'delete') {
        $pdo->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
        set_flash('success', 'Kategori dihapus (artikel terkait ikut terhapus).');
    } elseif ($action === 'save' && $name !== '') {
        $slug = slugify($name);
        if ($id) {
            $pdo->prepare("UPDATE categories SET name=?, slug=? WHERE id=?")->execute([$name, $slug, $id]);
            set_flash('success', 'Kategori diperbarui.');
        } else {
            // Cegah slug duplikat.
            $chk = $pdo->prepare("SELECT id FROM categories WHERE slug=?");
            $chk->execute([$slug]);
            if ($chk->fetch()) {
                set_flash('error', 'Kategori dengan nama itu sudah ada.');
            } else {
                $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?,?)")->execute([$name, $slug]);
                set_flash('success', 'Kategori ditambahkan.');
            }
        }
    }
    redirect('/admin/categories.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

$cats = $pdo->query(
  "SELECT c.*, COUNT(a.id) AS jml
   FROM categories c LEFT JOIN articles a ON a.category_id=c.id
   GROUP BY c.id ORDER BY c.name")->fetchAll();

$active = 'categories';
$crumb = 'Kategori';
$page_title = 'Kategori - Admin';
require __DIR__ . '/../includes/admin-header.php';
?>

<div class="stat-grid" style="grid-template-columns:340px 1fr;">
  <div class="panel">
    <div class="panel-head"><h2><?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?></h2></div>
    <div style="padding:20px;">
      <form method="post" data-validate>
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= (int)$edit['id'] ?>"><?php endif; ?>
        <div class="field"><label>Nama Kategori</label><input type="text" name="name" value="<?= e($edit['name'] ?? '') ?>" required></div>
        <button class="btn btn-primary" type="submit"><?= $edit ? 'Simpan' : 'Tambah' ?></button>
        <?php if ($edit): ?><a class="btn btn-outline" href="<?= BASE_URL ?>/admin/categories.php">Batal</a><?php endif; ?>
      </form>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head"><h2>Daftar Kategori</h2></div>
    <table class="data">
      <thead><tr><th>Nama</th><th>Slug</th><th>Artikel</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach ($cats as $c): ?>
        <tr>
          <td class="t-title"><?= e($c['name']) ?></td>
          <td><?= e($c['slug']) ?></td>
          <td><?= (int)$c['jml'] ?></td>
          <td class="actions-cell">
            <a class="icon-btn" title="Edit" href="<?= BASE_URL ?>/admin/categories.php?edit=<?= (int)$c['id'] ?>">&#9998;</a>
            <form method="post" data-confirm="Hapus kategori ini? Artikel di dalamnya ikut terhapus." style="display:inline">
              <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
              <button class="icon-btn danger" title="Hapus" type="submit">&#128465;</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
