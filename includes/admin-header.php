<?php
// Layout admin (sidebar). Diasumsikan require_admin() sudah dipanggil & $pdo, helper dimuat.
// Halaman set: $active (kunci menu), $page_title, opsional $crumb.
$me = current_user($pdo);
$flash = get_flash();
$menu = [
  'dashboard'  => ['Dashboard',     'dashboard.php',  '&#9783;'],
  'articles'   => ['Kelola Artikel', 'articles.php',   '&#9776;'],
  'categories' => ['Kategori',       'categories.php', '&#127991;'],
  'comments'   => ['Komentar',       'comments.php',   '&#128172;'],
  'users'      => ['Pengguna',       'users.php',      '&#128101;'],
];
$active = $active ?? 'dashboard';
$page_title = $page_title ?? 'Admin - UNSRAT Community Blog';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($page_title) ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="admin-body">
  <aside class="admin-sidebar">
    <div class="admin-brand">Admin Panel<small>UNIVERSITY MANAGEMENT</small></div>

    <div class="admin-user">
      <span class="avatar avatar-md">
        <?php if ($me['avatar']): ?><img src="<?= UPLOAD_URL . '/' . e($me['avatar']) ?>" alt=""><?php else: ?><?= e(initials($me['name'])) ?><?php endif; ?>
      </span>
      <div>
        <div class="nm"><?= e($me['name']) ?></div>
        <div class="rl">Administrator</div>
      </div>
    </div>

    <nav class="admin-menu">
      <?php foreach ($menu as $key => $m): ?>
        <a href="<?= BASE_URL ?>/admin/<?= $m[1] ?>" class="<?= $active===$key?'active':'' ?>">
          <?= $m[2] ?> <?= e($m[0]) ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="admin-sidebar-foot">
      <a class="btn btn-primary btn-block" href="<?= BASE_URL ?>/user/article-form.php">+ Tambah Artikel Baru</a>
      <a class="admin-menu" style="padding:10px 12px" href="<?= BASE_URL ?>/index.php">&#8592; Lihat Situs</a>
      <a class="admin-menu" style="padding:10px 12px" href="<?= BASE_URL ?>/logout.php">&#8634; Keluar</a>
    </div>
  </aside>

  <main class="admin-main">
    <div class="admin-topbar">
      <div class="breadcrumb">Dashboard <?= isset($crumb) ? '&rsaquo; ' . e($crumb) : '' ?></div>
      <div class="user-chip"><span class="avatar avatar-sm"><?= e(initials($me['name'])) ?></span> <?= e($me['name']) ?></div>
    </div>

    <?php if ($flash): ?><div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['msg']) ?></div><?php endif; ?>
