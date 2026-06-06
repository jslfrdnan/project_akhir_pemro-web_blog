<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/user/articles.php');
csrf_check();

$id  = (int)($_POST['id'] ?? 0);
$uid = $_SESSION['user_id'];

// Hanya boleh hapus artikel milik sendiri.
$del = $pdo->prepare("DELETE FROM articles WHERE id = ? AND user_id = ?");
$del->execute([$id, $uid]);

set_flash($del->rowCount() ? 'success' : 'error',
          $del->rowCount() ? 'Artikel dihapus.' : 'Artikel tidak ditemukan.');
redirect('/user/articles.php');
