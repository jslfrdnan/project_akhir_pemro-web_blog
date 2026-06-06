<?php
// Konfigurasi global aplikasi. Di-include lewat database.php / header.php.

// Mulai session sekali saja, di awal segala output.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Path dasar aplikasi (karena berada di subfolder htdocs/project_akhir).
// Semua link & aset HARUS diawali BASE_URL agar benar.
define('BASE_URL', '/project_akhir');

// Path folder upload (cover artikel & avatar) — absolut di server.
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads');
define('UPLOAD_URL', BASE_URL . '/assets/uploads');

// Zona waktu untuk tanggal artikel/komentar.
date_default_timezone_set('Asia/Makassar');
