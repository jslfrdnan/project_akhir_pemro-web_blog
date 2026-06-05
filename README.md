# UNSRAT Community Blog

Portal blog komunitas civitas akademika Universitas Sam Ratulangi.
Dibangun dengan **HTML, CSS, JS, PHP, MySQL murni — tanpa framework/library apa pun**.

## Fitur

- **Halaman statis:** Tentang (`about.php`), Tim Redaksi/Anggota Kelompok (`members.php`), Kontak (`contact.php`).
- **Halaman dinamis dari MySQL:** Beranda (`index.php`), Detail artikel + komentar (`article.php`), Profil publik penulis (`author.php`).
- **Login multi-role:** Admin, Dosen, Mahasiswa (`login.php` / `register.php`).
- **Manajemen konten (CRUD):**
  - Area user (`user/`): kelola artikel sendiri + edit profil.
  - Area admin (`admin/`): kelola semua artikel, kategori, komentar, dan pengguna.
- **Konten lain:** kategori (`category.php`), pencarian (`search.php`), arsip per bulan (`archive.php`), artikel terpopuler (di beranda).
- Pengguna login bisa **posting artikel** (dengan upload cover) dan **berkomentar**.

## Cara Menjalankan (XAMPP)

1. Start **Apache** + **MySQL** dari XAMPP Control Panel.
2. Hubungkan folder ke `htdocs` (CMD sebagai Administrator):
   ```
   mklink /J C:\xampp\htdocs\project_akhir "D:\Tugas Semester 4\Pemrograman Web\project_akhir"
   ```
3. Impor database: buka `http://localhost/phpmyadmin` → tab **Import** → pilih `sql/schema.sql`.
   Atau lewat CLI:
   ```
   C:\xampp\mysql\bin\mysql.exe -u root < sql\schema.sql
   ```
4. Buka aplikasi: `http://localhost/project_akhir`

## Akun Demo (password semua: `password123`)

| Peran      | Email                      |
|------------|----------------------------|
| Admin      | admin@unsrat.ac.id         |
| Dosen      | johannes@unsrat.ac.id      |
| Mahasiswa  | arif@student.unsrat.ac.id  |

## Struktur

```
config/     koneksi & konstanta (BASE_URL, PDO)
includes/   header/footer publik & admin, auth, helper, partial kartu
assets/     css/ js/ uploads/ (cover & avatar)
sql/        schema.sql (struktur + data seed)
user/       area dosen & mahasiswa (CRUD artikel sendiri)
admin/      area admin (kelola semua konten)
```

> **Catatan:** `BASE_URL` di `config/config.php` diset ke `/project_akhir`. Jika folder
> diakses dengan nama lain, sesuaikan nilai tersebut.
> Edit data anggota kelompok di bagian atas `members.php`.
