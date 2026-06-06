<?php
// Koneksi PDO ke MySQL. Satu file koneksi dipakai semua halaman.
require_once __DIR__ . '/config.php';

$dsn = 'mysql:host=127.0.0.1;dbname=blog_unsrat;charset=utf8mb4';
try {
    $pdo = new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage()
        . '<br>Pastikan MySQL di XAMPP sudah jalan dan database "blog_unsrat" sudah diimpor.');
}
