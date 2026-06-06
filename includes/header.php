<?php
// Header + navbar publik. Diasumsikan $pdo, functions.php, auth.php sudah dimuat.
$me = current_user($pdo);
// Kategori untuk dropdown navbar.
$nav_categories = $pdo->query('SELECT name, slug FROM categories ORDER BY name')->fetchAll();
$page_title = $page_title ?? 'UNSRAT Community Blog';
$flash = get_flash();
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
<header class="site-header">
  <div class="container nav-wrap">
    <a class="brand" href="<?= BASE_URL ?>/index.php">
      <img class="brand-logo" src="<?= UPLOAD_URL ?>/footer_unsrat.png" alt="UNSRAT">
      <span class="brand-text">UNSRAT Blog</span>
    </a>

    <button class="nav-toggle" aria-label="Menu" onclick="toggleMenu()">&#9776;</button>

    <nav class="main-nav" id="mainNav">
      <a href="<?= BASE_URL ?>/index.php">Home</a>
      <div class="nav-dropdown">
        <a href="#" class="nav-dropdown-toggle">Kategori &#9662;</a>
        <div class="nav-dropdown-menu">
          <?php foreach ($nav_categories as $c): ?>
            <a href="<?= BASE_URL ?>/category.php?slug=<?= e($c['slug']) ?>"><?= e($c['name']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <a href="<?= BASE_URL ?>/archive.php">Arsip</a>
      <a href="<?= BASE_URL ?>/about.php">Tentang</a>
    </nav>

    <div class="nav-actions">
      <form class="nav-search" action="<?= BASE_URL ?>/search.php" method="get">
        <input type="search" name="q" placeholder="Cari artikel..." value="<?= e($_GET['q'] ?? '') ?>">
        <button type="submit" aria-label="Cari">&#128269;</button>
      </form>

      <?php if ($me): ?>
        <div class="nav-dropdown user-menu">
          <a href="#" class="nav-dropdown-toggle user-chip">
            <span class="avatar avatar-sm"><?= e(initials($me['name'])) ?></span>
            <span class="user-name"><?= e($me['name']) ?> &#9662;</span>
          </a>
          <div class="nav-dropdown-menu align-right">
            <a href="<?= BASE_URL ?>/author.php?id=<?= (int)$me['id'] ?>">Profil Publik</a>
            <?php if (is_admin()): ?>
              <a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard Admin</a>
            <?php else: ?>
              <a href="<?= BASE_URL ?>/user/dashboard.php">Dashboard Saya</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/user/profile.php">Pengaturan</a>
            <a href="<?= BASE_URL ?>/logout.php">Keluar</a>
          </div>
        </div>
      <?php else: ?>
        <a class="btn btn-light" href="<?= BASE_URL ?>/login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<?php if ($flash): ?>
  <div class="container">
    <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['msg']) ?></div>
  </div>
<?php endif; ?>
