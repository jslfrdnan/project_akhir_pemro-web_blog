<?php
// Helper umum dipakai di seluruh aplikasi.

// Escaping output → anti-XSS. Pakai di SETIAP output dinamis.
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

// Ubah judul jadi slug ramah-URL.
function slugify($t) {
    $t = strtolower(trim($t));
    $t = preg_replace('/[^a-z0-9]+/', '-', $t);
    return trim($t, '-');
}

// Redirect lalu hentikan eksekusi (pola PRG).
function redirect($path) {
    header('Location: ' . BASE_URL . $path);
    exit;
}

// --- Flash message (pesan satu kali via session) ---
function set_flash($type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}
function get_flash() {
    if (empty($_SESSION['flash'])) return null;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

// --- CSRF ringan ---
function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}
function csrf_check() {
    if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) {
        http_response_code(400);
        exit('CSRF token tidak valid.');
    }
}

// Tanggal relatif sederhana ("2 jam yang lalu").
function time_ago($datetime) {
    $ts = strtotime($datetime);
    $diff = time() - $ts;
    if ($diff < 60)      return 'baru saja';
    if ($diff < 3600)    return floor($diff / 60) . ' menit yang lalu';
    if ($diff < 86400)   return floor($diff / 3600) . ' jam yang lalu';
    if ($diff < 172800)  return 'kemarin';
    if ($diff < 2592000) return floor($diff / 86400) . ' hari yang lalu';
    return tanggal_id($datetime);
}

// Tanggal format Indonesia: 12 Oktober 2024.
function tanggal_id($datetime) {
    $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli',
              'Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($datetime);
    return (int)date('j', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

// Inisial nama untuk avatar fallback (mis. "Dr. Sam Ratulangi" → "SR").
function initials($name) {
    $parts = preg_split('/\s+/', trim($name));
    $parts = array_values(array_filter($parts, fn($p) => !preg_match('/\./', $p)));
    if (!$parts) return '?';
    $first = mb_substr($parts[0], 0, 1);
    $last  = count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '';
    return strtoupper($first . $last);
}

// URL avatar: file upload bila ada, kalau tidak null (header.php render inisial).
function avatar_url($file) {
    return $file ? UPLOAD_URL . '/' . $file : null;
}

// Potong teks panjang.
function excerpt_text($text, $len = 120) {
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
    return mb_strlen($text) > $len ? mb_substr($text, 0, $len) . '…' : $text;
}
