<?php
// Koneksi PDO ke MySQL. Satu file koneksi dipakai semua halaman.
require_once __DIR__ . '/config.php';

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_DATABASE') ?: 'blog_unsrat';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';

$dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    // Pesan error dibuat lebih informatif untuk melacak kegagalan di server cloud
    die('Koneksi database gagal: ' . $e->getMessage()
        . '<br>Jika di lokal, pastikan XAMPP jalan. Jika di Railway, periksa Variabel Lingkungan Anda.');
}