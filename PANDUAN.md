# PANDUAN MEMBANGUN: Portal Blog Komunitas UNSRAT

Panduan langkah-demi-langkah untuk **kamu tulis kodenya sendiri**. Panduan ini memberi
peta jalan, struktur file, konsep yang perlu dipelajari, dan **potongan kecil** untuk
dipahami polanya — bukan kode jadi yang tinggal salin. Tiap milestone punya cara verifikasi
supaya kamu tahu sudah benar sebelum lanjut.

> **Aturan tugas yang wajib dipatuhi:** pakai HTML, CSS, JS, PHP, MySQL. **Tanpa framework
> / library apa pun** (tidak boleh Bootstrap, jQuery, CodeIgniter, dll). Semua CSS & JS
> ditulis manual.

---

## 1. Pengantar & Tujuan

Kamu akan membangun **portal blog komunitas** untuk civitas akademika Universitas Sam
Ratulangi. Admin memoderasi, dosen & mahasiswa bisa posting artikel dan berkomentar,
pengunjung umum bisa membaca.

**Peta fitur → requirement tugas:**

| Requirement tugas | Dipenuhi oleh |
|---|---|
| Halaman statis | `about.php`, `members.php` (anggota redaksi), `contact.php` |
| Halaman dinamis dari DB | `index.php` (artikel terbaru/populer), `article.php` (detail+komentar), `author.php` |
| Login admin/member | `login.php` + role `admin`, `dosen`, `mahasiswa` |
| Manajemen konten (CRUD) | area `admin/` (semua konten) & `user/` (artikel sendiri) |
| Konten bebas | `category.php`, `search.php`, `archive.php`, artikel terpopuler |
| Tanpa framework | PDO murni, CSS & JS sendiri |

---

## 2. Persiapan Lingkungan

1. **Jalankan Apache + MySQL** dari XAMPP Control Panel (atau `C:\xampp\apache_start.bat`
   dan `C:\xampp\mysql_start.bat`).
2. **Hubungkan folder project ke htdocs** dengan *junction* supaya tidak perlu copy ulang
   tiap kali edit. Buka **CMD sebagai Administrator**, lalu:
   ```
   mklink /J C:\xampp\htdocs\project_akhir "D:\Tugas Semester 4\Pemrograman Web\project_akhir"
   ```
   (Junction = folder htdocs menunjuk ke folder asli di D:. Edit di D:, langsung kebaca.)
3. **Akses aplikasi** di browser: `http://localhost/project_akhir`
4. **Kelola database** lewat phpMyAdmin: `http://localhost/phpmyadmin`
   (user `root`, password kosong — default XAMPP).

**Cara verifikasi:** buat file `index.php` berisi `<?php phpinfo(); ?>`, buka
`http://localhost/project_akhir`. Kalau muncul halaman info PHP, lingkungan siap.

---

## 3. Struktur Folder Target

Bangun bertahap (jangan buat semua sekaligus — ikuti milestone). Target akhir:

```
project_akhir/
├── config/
│   ├── config.php          # konstanta global: BASE_URL, dll
│   └── database.php        # koneksi PDO ke MySQL
├── includes/
│   ├── auth.php            # session, cek login, cek role
│   ├── functions.php       # helper: e(), slugify(), redirect(), flash(), csrf
│   ├── header.php          # <head> + navbar (dipakai semua halaman)
│   └── footer.php
├── assets/
│   ├── css/style.css       # styling manual (tema UNSRAT)
│   ├── js/main.js          # interaksi: toggle menu, confirm hapus, validasi
│   └── uploads/            # tempat simpan cover artikel & avatar
├── index.php               # beranda
├── article.php             # detail artikel + komentar
├── category.php            # artikel per kategori
├── search.php              # hasil pencarian
├── archive.php             # arsip per bulan/tahun
├── author.php              # profil publik penulis
├── about.php  members.php  contact.php   # halaman statis
├── login.php  register.php  logout.php
├── user/                   # area login (dosen & mahasiswa)
│   ├── dashboard.php
│   ├── articles.php
│   ├── article-form.php    # tambah/edit artikel sendiri
│   ├── article-delete.php
│   └── profile.php
├── admin/                  # area admin saja
│   ├── dashboard.php
│   ├── articles.php        # kelola SEMUA artikel
│   ├── categories.php      # CRUD kategori
│   ├── comments.php        # moderasi komentar
│   └── users.php           # kelola user
└── sql/schema.sql          # struktur tabel + data contoh
```

**Peran tiap folder:** `config/` = pengaturan koneksi & konstanta; `includes/` = potongan
yang dipakai berulang (jangan tulis ulang HTML header di tiap file!); `assets/` = file
statis; `user/` & `admin/` = halaman yang butuh login.

---

## 4. Skema Database

Buat database bernama `blog_unsrat`. Empat tabel:

**`users`** — akun pengguna.
| kolom | tipe | catatan |
|---|---|---|
| id | INT PK AI | |
| name | VARCHAR(100) | nama tampil |
| email | VARCHAR(150) UNIQUE | untuk login |
| password | VARCHAR(255) | hasil `password_hash()`, makanya panjang |
| role | ENUM('admin','dosen','mahasiswa') | menentukan hak akses |
| bio | TEXT NULL | untuk profil publik |
| avatar | VARCHAR(255) NULL | nama file foto |
| created_at | DATETIME | |

**`categories`** — kategori artikel.
`id` PK, `name` VARCHAR, `slug` VARCHAR UNIQUE (untuk URL ramah, mis. `kegiatan-mahasiswa`),
`created_at`.

**`articles`** — inti blog.
`id` PK, `user_id` FK→users, `category_id` FK→categories, `title`, `slug` UNIQUE, `excerpt`
(ringkasan untuk daftar), `content` TEXT, `cover_image` VARCHAR NULL, `status`
ENUM('published','draft'), `views` INT DEFAULT 0 (untuk fitur terpopuler), `created_at`,
`updated_at`.

**`comments`** — komentar pada artikel.
`id` PK, `article_id` FK→articles, `user_id` FK→users, `content` TEXT, `status`
ENUM('approved','pending') DEFAULT 'approved' (untuk moderasi admin), `created_at`.

**Contoh DDL (pola — tulis tabel lain sendiri mengikuti ini):**
```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','dosen','mahasiswa') NOT NULL DEFAULT 'mahasiswa',
  bio TEXT NULL,
  avatar VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

**Data seed:** isi minimal 1 admin, 1 dosen, 1 mahasiswa, 4 kategori (Akademik, Kegiatan
Mahasiswa, Penelitian, Opini), beberapa artikel + komentar, supaya halaman langsung berisi.

> **Penting soal password seed:** `password_hash()` jalan di PHP, bukan SQL. Cara mudah:
> buat sekali file PHP berisi `echo password_hash('admin123', PASSWORD_DEFAULT);`, salin
> hasilnya ke kolom password di `schema.sql`. Catat kredensial login di README/komentar.

**Cara verifikasi:** import `sql/schema.sql` lewat phpMyAdmin (tab Import) atau:
```
C:\xampp\mysql\bin\mysql.exe -u root < sql\schema.sql
```
Lalu buka phpMyAdmin → pastikan 4 tabel ada dan tabel `articles` berisi data.

---

## 5. Milestone Bertahap

Kerjakan berurutan. Jangan lompat — tiap milestone jadi fondasi berikutnya.

### M0 — Setup & Koneksi DB
- **Tujuan:** PHP bisa terhubung ke MySQL.
- **File:** `config/config.php`, `config/database.php`.
- **Konsep:** konstanta `define()`, PDO, `try/catch`, mengapa pakai satu file koneksi.
- **Snippet pola (database.php):**
  ```php
  <?php
  $dsn = 'mysql:host=127.0.0.1;dbname=blog_unsrat;charset=utf8mb4';
  try {
      $pdo = new PDO($dsn, 'root', '', [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
  } catch (PDOException $e) {
      die('Koneksi gagal: ' . $e->getMessage());
  }
  ```
  Di `config.php` definisikan: `define('BASE_URL', '/project_akhir');` (dipakai untuk semua
  link & path aset agar benar di subfolder).
- **Verifikasi:** buat halaman uji yang `require` keduanya lalu
  `$pdo->query('SELECT 1')` — tidak error = sukses.

### M1 — Layout & Styling
- **Tujuan:** kerangka tampilan yang dipakai ulang semua halaman.
- **File:** `includes/header.php`, `includes/footer.php`, `assets/css/style.css`,
  `includes/functions.php` (mulai dengan helper `e()`).
- **Konsep:** `include`/`require` untuk DRY; CSS manual (flexbox/grid, variabel CSS untuk
  warna tema UNSRAT mis. oranye `#f57c00`); responsif via `@media`.
- **Snippet pola — helper escaping (functions.php):**
  ```php
  function e($text) { return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8'); }
  ```
- **Snippet pola — pakai layout di sebuah halaman:**
  ```php
  <?php require __DIR__ . '/includes/header.php'; ?>
  <main> ... isi halaman ... </main>
  <?php require __DIR__ . '/includes/footer.php'; ?>
  ```
  Di `header.php`, tautkan CSS dengan BASE_URL: `<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">`.
- **Verifikasi:** buka halaman apa pun → navbar & footer muncul, CSS termuat (cek tab
  Network browser, `style.css` status 200).

### M2 — Beranda Dinamis
- **Tujuan:** menampilkan daftar artikel dari DB.
- **File:** `index.php`.
- **Konsep:** query + loop, prepared statement, escaping output, membuat link ke detail.
- **Snippet pola (prepared statement + loop):**
  ```php
  $stmt = $pdo->prepare(
    "SELECT a.*, u.name AS penulis, c.name AS kategori
     FROM articles a JOIN users u ON a.user_id=u.id
     JOIN categories c ON a.category_id=c.id
     WHERE a.status='published' ORDER BY a.created_at DESC LIMIT 6");
  $stmt->execute();
  foreach ($stmt as $a) {
      echo '<h3><a href="' . BASE_URL . '/article.php?slug=' . e($a['slug']) . '">'
         . e($a['title']) . '</a></h3>';
  }
  ```
- **Verifikasi:** beranda menampilkan judul-judul artikel dari tabel `articles`.

### M3 — Detail Artikel
- **Tujuan:** halaman baca satu artikel + hitung views.
- **File:** `article.php`.
- **Konsep:** ambil parameter `$_GET['slug']` (selalu lewat prepared statement!), tampilkan
  isi, `UPDATE articles SET views = views + 1`. Tangani artikel tidak ditemukan (404).
- **Snippet pola (parameter aman):**
  ```php
  $slug = $_GET['slug'] ?? '';
  $stmt = $pdo->prepare("SELECT * FROM articles WHERE slug = ? AND status='published'");
  $stmt->execute([$slug]);
  $artikel = $stmt->fetch();
  if (!$artikel) { http_response_code(404); exit('Artikel tidak ditemukan'); }
  ```
- **Verifikasi:** klik artikel di beranda → isi tampil; refresh → kolom `views` di DB naik.

### M4 — Autentikasi (Login/Register/Role)
- **Tujuan:** pengguna bisa daftar, login, logout; halaman tertentu butuh login/role.
- **File:** `register.php`, `login.php`, `logout.php`, `includes/auth.php`.
- **Konsep:** `session_start()`, `password_hash()` saat register, `password_verify()` saat
  login, simpan `$_SESSION['user_id']` & `role`, fungsi penjaga akses.
- **Snippet pola (auth.php):**
  ```php
  function require_login() {
      if (empty($_SESSION['user_id'])) { header('Location: ' . BASE_URL . '/login.php'); exit; }
  }
  function require_admin() {
      require_login();
      if (($_SESSION['role'] ?? '') !== 'admin') { http_response_code(403); exit('Akses ditolak'); }
  }
  ```
- **Snippet pola (verifikasi login):**
  ```php
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$_POST['email']]);
  $u = $stmt->fetch();
  if ($u && password_verify($_POST['password'], $u['password'])) {
      $_SESSION['user_id'] = $u['id']; $_SESSION['role'] = $u['role'];
      header('Location: ' . BASE_URL . '/index.php'); exit;
  }
  ```
- **Verifikasi:** register user baru → cek hash tersimpan di DB → logout → login lagi
  berhasil → buka `user/dashboard.php` saat logout = dilempar ke login.

### M5 — Area User: CRUD Artikel Sendiri
- **Tujuan:** dosen/mahasiswa kelola artikelnya sendiri + upload cover.
- **File:** `user/dashboard.php`, `user/articles.php`, `user/article-form.php`,
  `user/article-delete.php`, `user/profile.php`.
- **Konsep:** **pola PRG (Post/Redirect/Get)** — proses POST di atas file, lalu `redirect()`
  agar refresh tak mengirim ulang form; generate `slug` dari judul; upload file aman
  (cek ekstensi & ukuran, pindahkan dengan `move_uploaded_file`); **batasi**: user hanya
  boleh edit/hapus artikel dengan `user_id` = dirinya.
- **Snippet pola (slugify di functions.php):**
  ```php
  function slugify($t) {
      $t = strtolower(trim($t));
      $t = preg_replace('/[^a-z0-9]+/', '-', $t);
      return trim($t, '-');
  }
  ```
- **Snippet pola (validasi upload):**
  ```php
  $ok = ['image/jpeg','image/png','image/webp'];
  if ($_FILES['cover']['error'] === 0 && in_array($_FILES['cover']['type'], $ok)
      && $_FILES['cover']['size'] < 2*1024*1024) {
      $nama = time() . '_' . basename($_FILES['cover']['name']);
      move_uploaded_file($_FILES['cover']['tmp_name'], __DIR__ . '/../assets/uploads/' . $nama);
  }
  ```
  Ingat: form upload butuh `enctype="multipart/form-data"`.
- **Verifikasi:** login sebagai mahasiswa → buat artikel (dengan cover) → muncul di beranda
  & di "artikel saya" → edit → hapus. Coba edit artikel milik orang lain via ubah `id` di
  URL → harus ditolak.

### M6 — Komentar
- **Tujuan:** user login bisa berkomentar di artikel.
- **File:** tambahkan form & daftar komentar di `article.php`.
- **Konsep:** insert komentar (prepared statement), tampilkan komentar `approved` urut waktu,
  hanya user login yang boleh mengirim.
- **Verifikasi:** login → kirim komentar di sebuah artikel → komentar muncul di bawah artikel.

### M7 — Area Admin
- **Tujuan:** admin mengelola semua konten.
- **File:** `admin/dashboard.php` (statistik COUNT), `admin/articles.php` (kelola SEMUA
  artikel), `admin/categories.php` (CRUD kategori), `admin/comments.php` (approve/hapus
  komentar), `admin/users.php` (ubah role/hapus user).
- **Konsep:** `require_admin()` di tiap file admin; CRUD lengkap; pola PRG sama seperti M5.
- **Verifikasi:** login admin → tambah kategori baru (muncul saat buat artikel) → hapus
  komentar → ubah role user → semua perubahan tercermin di DB & tampilan.

### M8 — Halaman Lain (Konten Bebas)
- **Tujuan:** memenuhi syarat "halaman lain" & memperkaya navigasi.
- **File:** `category.php`, `search.php`, `archive.php`, `author.php` (+ link "Terpopuler"
  di beranda/navbar).
- **Konsep:**
  - Pencarian: `WHERE title LIKE ? OR content LIKE ?` dengan parameter `'%'.$q.'%'`.
  - Arsip: `WHERE YEAR(created_at)=? AND MONTH(created_at)=?`.
  - Terpopuler: `ORDER BY views DESC LIMIT n`.
  - Profil publik: `author.php?id=` → data user + daftar artikelnya.
- **Verifikasi:** cari kata kunci → hasil relevan; buka arsip bulan tertentu → hanya artikel
  bulan itu; daftar terpopuler urut views; klik nama penulis → profil + artikelnya.

### M9 — Halaman Statis
- **Tujuan:** memenuhi syarat halaman statis.
- **File:** `about.php` (tentang portal), `members.php` (anggota redaksi/kelompok —
  **4 kartu placeholder**: nama, NIM, peran, foto; mudah kamu edit nanti), `contact.php`.
- **Konsep:** halaman HTML biasa yang tetap memakai `header.php`/`footer.php`.
- **Verifikasi:** ketiga halaman tampil rapi dengan layout yang sama.

### M10 — Keamanan & Polish
- **Tujuan:** rapikan & amankan.
- **File:** `assets/js/main.js`, sentuhan akhir di semua form.
- **Konsep & checklist:**
  - **Escaping:** pastikan SEMUA data dari DB/user dibungkus `e()` saat ditampilkan (anti XSS).
  - **Prepared statement:** pastikan TIDAK ADA query yang menyambung string `$_GET`/`$_POST`
    langsung (anti SQL injection).
  - **CSRF ringan:** sisipkan token tersembunyi di form pengubah data, cek saat POST.
  - **JS vanilla:** toggle menu mobile, `confirm('Yakin hapus?')` sebelum hapus, validasi
    sederhana form komentar/artikel di sisi klien.
- **Snippet pola (CSRF di functions.php):**
  ```php
  function csrf_token() {
      if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
      return $_SESSION['csrf'];
  }
  function csrf_check() {
      if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) exit('CSRF token tidak valid');
  }
  ```
- **Verifikasi:** coba masukkan `<script>alert(1)</script>` sebagai judul/komentar → saat
  tampil harus jadi teks biasa, bukan tereksekusi.

---

## 6. Checklist Requirement Tugas

Centang sebelum dikumpulkan:

- [ ] Menggunakan HTML, CSS, JS, PHP, MySQL.
- [ ] Tidak memakai framework/library apa pun.
- [ ] Ada halaman statis (about / members / contact).
- [ ] Ada halaman dinamis yang menampilkan data dari MySQL.
- [ ] Ada halaman statis berisi info anggota kelompok.
- [ ] Ada mekanisme login (admin & member).
- [ ] Ada halaman manajemen konten: Tambah, Edit, Hapus.
- [ ] Ada halaman konten lain sesuai tema (kategori/pencarian/arsip/populer).
- [ ] Pengguna bisa posting artikel & berkomentar.
- [ ] Multi-user (admin, dosen, mahasiswa) & multi-konten.

---

## 7. Tips & Jebakan Umum

- **BASE_URL di subfolder:** karena app di `/project_akhir`, semua link & aset harus
  diawali `<?= BASE_URL ?>`. Link yang mulai dari `/` tanpa prefix akan menunjuk ke root
  `localhost` dan rusak.
- **Lupa `htmlspecialchars`:** sumber utama XSS & tampilan rusak. Biasakan `e()` di SETIAP
  output dinamis.
- **"Connection refused" / koneksi gagal:** MySQL belum di-start di XAMPP, atau nama db
  salah. Start MySQL dulu.
- **Folder `assets/uploads/` tidak ada / tak bisa ditulis:** buat foldernya manual sebelum
  uji upload; pastikan path tujuan benar.
- **Slug duplikat:** dua artikel berjudul sama menghasilkan slug sama → error UNIQUE.
  Tambahkan akhiran unik (mis. `-` + `id` atau `time()`) bila perlu.
- **Header sudah terkirim ("headers already sent"):** jangan ada spasi/HTML sebelum
  `header('Location: ...')` atau `session_start()`. Letakkan logika redirect di paling atas
  file, sebelum output apa pun.
- **Jangan simpan password plain:** selalu `password_hash()`; jangan pernah `md5`.

Selamat ngoding! Kerjakan milestone berurutan dan verifikasi tiap langkah sebelum lanjut.
