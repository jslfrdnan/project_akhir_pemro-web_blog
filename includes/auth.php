<?php
// Fungsi autentikasi & penjaga akses. Membutuhkan session (config.php) sudah jalan.

// Apakah ada user login?
function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

// Role user saat ini ('admin' | 'dosen' | 'mahasiswa' | '').
function current_role() {
    return $_SESSION['role'] ?? '';
}

function is_admin() {
    return current_role() === 'admin';
}

// Ambil data user login (sekali per request, di-cache di variabel statis).
function current_user($pdo) {
    static $user = null;
    if ($user === null && is_logged_in()) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch() ?: false;
    }
    return $user ?: null;
}

// Wajib login — kalau tidak, lempar ke halaman login.
function require_login() {
    if (!is_logged_in()) {
        set_flash('error', 'Silakan login terlebih dahulu.');
        redirect('/login.php');
    }
}

// Wajib admin.
function require_admin() {
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        exit('Akses ditolak: halaman ini khusus admin.');
    }
}
