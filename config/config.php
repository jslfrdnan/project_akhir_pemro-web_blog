<?php
// Konfigurasi global aplikasi. Di-include lewat database.php / header.php.

// Mulai session sekali saja, di awal segala output.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Path dasar aplikasi. Semua link & aset HARUS diawali BASE_URL agar benar.
// - Lokal (XAMPP): app ada di subfolder /project_akhir.
// - Railway/cloud: app dilayani di root domain, jadi BASE_URL kosong.
// Bisa juga di-override manual lewat env var APP_BASE_URL.
$appBaseUrl = getenv('APP_BASE_URL');
if ($appBaseUrl !== false) {
    define('BASE_URL', $appBaseUrl);
} elseif (getenv('RAILWAY_ENVIRONMENT') !== false || getenv('PORT') !== false) {
    define('BASE_URL', '');
} else {
    define('BASE_URL', '/project_akhir');
}

// Path folder upload (cover artikel & avatar) — absolut di server.
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads');
define('UPLOAD_URL', BASE_URL . '/assets/uploads');

// Zona waktu untuk tanggal artikel/komentar.
date_default_timezone_set('Asia/Makassar');
