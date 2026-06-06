# BAB ... ACTIVITY DIAGRAM

## 1. Pengertian Activity Diagram

Activity diagram merupakan salah satu diagram dalam *Unified Modeling Language* (UML)
yang digunakan untuk menggambarkan alur kerja (*workflow*) atau aliran aktivitas dari
sebuah proses pada sistem. Activity diagram menggambarkan rangkaian aktivitas yang
terjadi mulai dari titik awal (*initial node*), urutan aktivitas, percabangan keputusan
(*decision*), hingga titik akhir (*final node*). Berbeda dengan use case diagram yang
menggambarkan *apa* yang dapat dilakukan sistem, activity diagram menggambarkan
*bagaimana* alur sebuah proses berjalan langkah demi langkah.

Pada perancangan aplikasi **UNSRAT Community Blog**, activity diagram digunakan untuk
memodelkan alur setiap proses utama yang melibatkan pengguna (mahasiswa/dosen) maupun
admin. Notasi yang digunakan dalam activity diagram pada bab ini adalah sebagai berikut:

| Simbol | Nama | Keterangan |
|--------|------|------------|
| ● (lingkaran penuh) | *Initial Node* | Menandakan titik awal aktivitas |
| Kotak sudut tumpul | *Action / Activity* | Menggambarkan satu aktivitas yang dikerjakan |
| ◇ (belah ketupat) | *Decision / Merge* | Percabangan atau penggabungan kondisi |
| Garis dengan panah | *Control Flow* | Arah aliran dari satu aktivitas ke aktivitas lain |
| Garis vertikal (*swimlane*) | *Partition* | Memisahkan aktivitas berdasarkan pelaku (aktor/sistem) |
| ◉ (lingkaran berbingkai) | *Final Node* | Menandakan titik akhir aktivitas |

Pada penggambaran berikut digunakan *swimlane* (partisi) untuk memisahkan aktivitas
yang dilakukan oleh **Pengguna/Admin** dengan aktivitas yang diproses oleh **Sistem**.

---

## 2. Activity Diagram Registrasi (Pendaftaran Akun)

Proses ini menggambarkan alur ketika pengunjung mendaftarkan akun baru sebagai dosen
atau mahasiswa. Sistem memvalidasi data masukan, memeriksa keunikan email, kemudian
menyimpan akun dan langsung membuat sesi login.

```mermaid
flowchart TD
    A([Mulai]) --> B[Buka halaman Daftar]
    B --> C[Isi nama, email, peran,<br/>fakultas, password]
    C --> D[Klik tombol Daftar]
    D --> E{Validasi data<br/>lengkap & valid?}
    E -- Tidak --> F[Tampilkan pesan error]
    F --> C
    E -- Ya --> G{Email sudah<br/>terdaftar?}
    G -- Ya --> H[Tampilkan pesan<br/>'Email sudah terdaftar']
    H --> C
    G -- Tidak --> I[Hash password &<br/>simpan data user]
    I --> J[Buat sesi login otomatis]
    J --> K[Arahkan ke Beranda]
    K --> L([Selesai])
```

**Penjelasan:** Sistem memvalidasi kelengkapan data (nama wajib diisi, format email
valid, password minimal 6 karakter, dan peran harus dosen/mahasiswa). Apabila valid,
sistem memeriksa apakah email sudah pernah terdaftar. Jika belum, password dienkripsi
menggunakan `password_hash()` lalu data disimpan ke tabel `users`, dan pengguna langsung
masuk ke sesi login.

---

## 3. Activity Diagram Login

Proses login menggambarkan alur autentikasi pengguna. Sistem mencocokkan email dan
password, kemudian mengarahkan pengguna sesuai perannya (admin diarahkan ke dasbor
admin, pengguna biasa ke beranda).

```mermaid
flowchart TD
    A([Mulai]) --> B[Buka halaman Login]
    B --> C[Masukkan email & password]
    C --> D[Klik tombol Login]
    D --> E[Cari user berdasarkan email]
    E --> F{Email ditemukan &<br/>password cocok?}
    F -- Tidak --> G[Tampilkan pesan<br/>'Email atau password salah']
    G --> C
    F -- Ya --> H[Buat sesi: user_id & role]
    H --> I{Peran =<br/>admin?}
    I -- Ya --> J[Arahkan ke<br/>Dashboard Admin]
    I -- Tidak --> K[Arahkan ke Beranda]
    J --> L([Selesai])
    K --> L
```

**Penjelasan:** Sistem memverifikasi password menggunakan `password_verify()`. Jika
kredensial benar, sistem menyimpan `user_id` dan `role` ke dalam sesi, lalu melakukan
pengalihan halaman (*redirect*) sesuai peran pengguna.

---

## 4. Activity Diagram Menulis / Mengelola Artikel

Diagram ini menggambarkan alur ketika pengguna (dosen/mahasiswa) yang sudah login
membuat atau memperbarui artikel. Termasuk di dalamnya validasi input, proses unggah
gambar sampul (*cover*) yang bersifat opsional, dan pembuatan *slug* unik.

```mermaid
flowchart TD
    A([Mulai]) --> B{Sudah login?}
    B -- Tidak --> C[Arahkan ke halaman Login]
    C --> Z([Selesai])
    B -- Ya --> D[Buka form Tulis/Edit Artikel]
    D --> E[Isi judul, kategori,<br/>ringkasan, konten, status]
    E --> F[Unggah cover<br/>opsional]
    F --> G[Klik Simpan]
    G --> H{Judul, kategori,<br/>konten terisi?}
    H -- Tidak --> I[Tampilkan pesan error]
    I --> E
    H -- Ya --> J{Ada file cover?}
    J -- Ya --> K{Format & ukuran<br/>gambar valid?}
    K -- Tidak --> I
    K -- Ya --> L[Simpan file cover]
    J -- Tidak --> M[Buat slug unik]
    L --> M
    M --> N{Mode edit?}
    N -- Ya --> O[UPDATE data artikel]
    N -- Tidak --> P[INSERT artikel baru]
    O --> Q[Arahkan ke<br/>Daftar Artikel Saya]
    P --> Q
    Q --> Z
```

**Penjelasan:** Sistem memastikan pengguna telah login (`require_login()`). Field judul,
kategori, dan konten wajib diisi. Jika pengguna mengunggah cover, sistem memvalidasi
format (JPG/PNG/WEBP) dan ukuran (maksimal 2 MB). Sistem kemudian membuat *slug* unik
dari judul. Bila artikel sedang diedit, data diperbarui (`UPDATE`); bila artikel baru,
data ditambahkan (`INSERT`) ke tabel `articles`.

---

## 5. Activity Diagram Membaca Artikel & Menambah Komentar

Diagram berikut menggabungkan dua alur yang terjadi pada halaman detail artikel:
membaca artikel (yang menambah jumlah pembaca) dan mengirim komentar (khusus pengguna
yang sudah login).

```mermaid
flowchart TD
    A([Mulai]) --> B[Pilih artikel]
    B --> C{Artikel ditemukan<br/>& berstatus published?}
    C -- Tidak --> D[Tampilkan halaman<br/>'Artikel tidak ditemukan']
    D --> Z([Selesai])
    C -- Ya --> E[Tambah jumlah views +1]
    E --> F[Tampilkan isi artikel,<br/>komentar & artikel terkait]
    F --> G{Pengguna<br/>ingin berkomentar?}
    G -- Tidak --> Z
    G -- Ya --> H{Sudah login?}
    H -- Tidak --> I[Tampilkan ajakan login]
    I --> Z
    H -- Ya --> J[Tulis komentar & kirim]
    J --> K{Komentar tidak kosong?}
    K -- Tidak --> F
    K -- Ya --> L[Simpan komentar<br/>status 'approved']
    L --> M[Tampilkan kembali artikel<br/>dengan komentar baru]
    M --> Z
```

**Penjelasan:** Ketika sebuah artikel dibuka, sistem menambah kolom `views` sebanyak
satu. Form komentar hanya ditampilkan kepada pengguna yang sudah login; pengunjung yang
belum login diarahkan untuk login terlebih dahulu. Komentar yang dikirim disimpan ke
tabel `comments` dan langsung berstatus *approved* sehingga tampil di halaman.

---

## 6. Activity Diagram Moderasi Komentar (Admin)

Diagram ini menggambarkan alur admin dalam mengelola komentar pengguna, yaitu menyetujui
komentar yang masih *pending* atau menghapus komentar.

```mermaid
flowchart TD
    A([Mulai]) --> B{Login sebagai admin?}
    B -- Tidak --> C[Tolak akses /<br/>arahkan ke Login]
    C --> Z([Selesai])
    B -- Ya --> D[Buka halaman<br/>Moderasi Komentar]
    D --> E[Tampilkan daftar komentar]
    E --> F{Pilih aksi}
    F -- Approve --> G[Ubah status<br/>menjadi 'approved']
    F -- Hapus --> H[Hapus komentar<br/>dari database]
    G --> I[Muat ulang daftar komentar]
    H --> I
    I --> J{Ada komentar lain<br/>yang dikelola?}
    J -- Ya --> E
    J -- Tidak --> Z
```

**Penjelasan:** Halaman ini dilindungi oleh `require_admin()` sehingga hanya dapat
diakses oleh admin. Admin dapat menyetujui (*approve*) komentar berstatus *pending* atau
menghapus komentar. Setiap aksi memperbarui data pada tabel `comments` lalu daftar
komentar dimuat ulang.

---

## 7. Activity Diagram Logout

```mermaid
flowchart TD
    A([Mulai]) --> B[Klik tombol Logout]
    B --> C[Hapus data sesi]
    C --> D[Hancurkan sesi]
    D --> E[Arahkan ke Beranda]
    E --> F([Selesai])
```

**Penjelasan:** Saat pengguna logout, sistem menghapus seluruh data sesi dan
menghancurkan sesi (`session_destroy()`), kemudian mengarahkan pengguna kembali ke
halaman beranda sebagai pengunjung biasa.

---

> **Catatan:** Diagram di atas ditulis menggunakan sintaks **Mermaid** sehingga dapat
> langsung dirender di editor yang mendukung Mermaid (mis. VS Code dengan ekstensi
> Markdown Preview Mermaid, atau GitHub). Untuk laporan akhir, diagram dapat diekspor
> menjadi gambar (PNG/SVG) melalui [mermaid.live](https://mermaid.live) lalu disisipkan
> ke dokumen Word.
