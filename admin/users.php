<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    // Cegah admin mengubah/menghapus dirinya sendiri.
    if ($id === (int)$_SESSION['user_id']) {
        set_flash('error', 'Tidak bisa mengubah akun sendiri dari sini.');
        redirect('/admin/users.php');
    }
    if ($action === 'role') {
        $role = $_POST['role'] ?? '';
        if (in_array($role, ['admin', 'dosen', 'mahasiswa'])) {
            $pdo->prepare("UPDATE users SET role=? WHERE id=?")->execute([$role, $id]);
            set_flash('success', 'Peran pengguna diubah.');
        }
    } elseif ($action === 'delete') {
        $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
        set_flash('success', 'Pengguna dihapus (beserta artikel & komentarnya).');
    }
    redirect('/admin/users.php');
}

$users = $pdo->query(
  "SELECT u.*, COUNT(a.id) AS jml_artikel
   FROM users u LEFT JOIN articles a ON a.user_id=u.id
   GROUP BY u.id ORDER BY u.created_at ASC")->fetchAll();

$active = 'users';
$crumb = 'Pengguna';
$page_title = 'Kelola Pengguna - Admin';
require __DIR__ . '/../includes/admin-header.php';
?>

<div class="panel">
  <div class="panel-head"><div><h2>Kelola Pengguna</h2><p>Ubah peran atau hapus akun civitas akademika.</p></div></div>
  <table class="data">
    <thead><tr><th>Nama</th><th>Email</th><th>Fakultas</th><th>Artikel</th><th>Peran</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><div class="row-author"><span class="avatar avatar-sm"><?= e(initials($u['name'])) ?></span><span class="t-title"><?= e($u['name']) ?></span></div></td>
        <td><?= e($u['email']) ?></td>
        <td><?= e($u['faculty'] ?: '-') ?></td>
        <td><?= (int)$u['jml_artikel'] ?></td>
        <td>
          <?php if ($u['id'] === (int)$_SESSION['user_id']): ?>
            <span class="badge badge-published">Admin (Anda)</span>
          <?php else: ?>
            <form method="post" style="display:inline">
              <?= csrf_field() ?><input type="hidden" name="action" value="role"><input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
              <select name="role" onchange="this.form.submit()">
                <option value="mahasiswa" <?= $u['role']==='mahasiswa'?'selected':'' ?>>Mahasiswa</option>
                <option value="dosen" <?= $u['role']==='dosen'?'selected':'' ?>>Dosen</option>
                <option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>Admin</option>
              </select>
            </form>
          <?php endif; ?>
        </td>
        <td class="actions-cell">
          <a class="icon-btn" title="Profil" href="<?= BASE_URL ?>/author.php?id=<?= (int)$u['id'] ?>">&#128065;</a>
          <?php if ($u['id'] !== (int)$_SESSION['user_id']): ?>
            <form method="post" data-confirm="Hapus pengguna ini beserta semua artikel & komentarnya?" style="display:inline">
              <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
              <button class="icon-btn danger" title="Hapus" type="submit">&#128465;</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
