<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    if ($action === 'approve') {
        $pdo->prepare("UPDATE comments SET status='approved' WHERE id=?")->execute([$id]);
        set_flash('success', 'Komentar disetujui.');
    } elseif ($action === 'delete') {
        $pdo->prepare("DELETE FROM comments WHERE id=?")->execute([$id]);
        set_flash('success', 'Komentar dihapus.');
    }
    redirect('/admin/comments.php');
}

$comments = $pdo->query(
  "SELECT cm.*, u.name AS penulis, a.title AS artikel, a.slug
   FROM comments cm JOIN users u ON cm.user_id=u.id JOIN articles a ON cm.article_id=a.id
   ORDER BY cm.status='pending' DESC, cm.created_at DESC")->fetchAll();

$active = 'comments';
$crumb = 'Komentar';
$page_title = 'Moderasi Komentar - Admin';
require __DIR__ . '/../includes/admin-header.php';
?>

<div class="panel">
  <div class="panel-head"><div><h2>Moderasi Komentar</h2><p>Setujui atau hapus komentar pengguna.</p></div></div>
  <?php if (!$comments): ?>
    <p class="empty">Belum ada komentar.</p>
  <?php else: ?>
  <table class="data">
    <thead><tr><th>Komentar</th><th>Penulis</th><th>Artikel</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($comments as $c): ?>
      <tr>
        <td style="max-width:340px;"><?= e(excerpt_text($c['content'], 140)) ?><div class="t-sub"><?= e(time_ago($c['created_at'])) ?></div></td>
        <td><?= e($c['penulis']) ?></td>
        <td><a href="<?= BASE_URL ?>/article.php?slug=<?= e($c['slug']) ?>"><?= e(excerpt_text($c['artikel'], 40)) ?></a></td>
        <td><span class="badge badge-<?= $c['status']==='approved'?'published':'pending' ?>"><?= e(ucfirst($c['status'])) ?></span></td>
        <td class="actions-cell">
          <?php if ($c['status'] === 'pending'): ?>
            <form method="post" style="display:inline">
              <?= csrf_field() ?><input type="hidden" name="action" value="approve"><input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
              <button class="btn btn-primary btn-sm" type="submit">Approve</button>
            </form>
          <?php endif; ?>
          <form method="post" data-confirm="Hapus komentar ini?" style="display:inline">
            <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
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
