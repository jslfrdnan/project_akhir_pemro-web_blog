-- =====================================================================
--  Portal Blog Komunitas UNSRAT — struktur tabel + data contoh
--  Import lewat phpMyAdmin (tab Import) atau:
--    C:\xampp\mysql\bin\mysql.exe -u root < sql\schema.sql
--  Password semua akun seed: password123
-- =====================================================================

CREATE DATABASE IF NOT EXISTS blog_unsrat
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blog_unsrat;

DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

-- ---------- users ----------
CREATE TABLE users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('admin','dosen','mahasiswa') NOT NULL DEFAULT 'mahasiswa',
  faculty    VARCHAR(120) NULL,             -- mis. "Fakultas Teknik" (tampil di profil/meta)
  bio        TEXT NULL,
  avatar     VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------- categories ----------
CREATE TABLE categories (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(80) NOT NULL,
  slug       VARCHAR(100) NOT NULL UNIQUE,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------- articles ----------
CREATE TABLE articles (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT NOT NULL,
  category_id INT NOT NULL,
  title       VARCHAR(200) NOT NULL,
  slug        VARCHAR(220) NOT NULL UNIQUE,
  excerpt     VARCHAR(400) NULL,
  content     TEXT NOT NULL,
  cover_image VARCHAR(255) NULL,
  status      ENUM('published','draft') NOT NULL DEFAULT 'published',
  views       INT NOT NULL DEFAULT 0,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_articles_user     FOREIGN KEY (user_id)     REFERENCES users(id)      ON DELETE CASCADE,
  CONSTRAINT fk_articles_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- ---------- comments ----------
CREATE TABLE comments (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  article_id INT NOT NULL,
  user_id    INT NOT NULL,
  content    TEXT NOT NULL,
  status     ENUM('approved','pending') NOT NULL DEFAULT 'approved',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_comments_article FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
  CONSTRAINT fk_comments_user    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE
);

-- =====================================================================
--  DATA SEED  (password semua: password123)
-- =====================================================================

INSERT INTO users (id, name, email, password, role, faculty, bio, created_at) VALUES
(1, 'Dr. Sam Ratulangi', 'admin@unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'admin', 'University Management',
 'Administrator portal blog komunitas Universitas Sam Ratulangi.', '2024-01-10 08:00:00'),
(2, 'Dr. Johannes Lumanauw', 'johannes@unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'dosen', 'Fakultas Teknik',
 'Lektor Kepala di Departemen Teknik Informatika dengan fokus riset pada Inteligensi Buatan dan Analisis Big Data. Berdedikasi untuk memajukan literasi digital di kalangan mahasiswa Sulawesi Utara melalui publikasi ilmiah dan diskusi komunitas yang inklusif.', '2024-02-01 09:00:00'),
(3, 'Dr. Ir. Robert L. Molanguthor', 'robert@unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'dosen', 'Fakultas Teknik',
 'Dekan Fakultas Teknik UNSRAT, penggerak program Merdeka Belajar Kampus Merdeka.', '2024-02-05 09:00:00'),
(4, 'Arif Lumenta', 'arif@student.unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'mahasiswa', 'Fakultas Kedokteran',
 'Mahasiswa Fakultas Kedokteran, peminatan penyakit tropis.', '2024-03-01 10:00:00'),
(5, 'Siti Ramlah', 'siti@student.unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'mahasiswa', 'Fakultas Teknik',
 'Mahasiswa Teknik, antusias pada smart city dan IoT.', '2024-03-10 10:00:00'),
(6, 'Budi Pratama', 'budi@student.unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'mahasiswa', 'Fakultas Ekonomi dan Bisnis',
 'Mahasiswa Ekonomi, fokus pada UMKM dan ekonomi lokal.', '2024-03-15 10:00:00');

INSERT INTO categories (id, name, slug) VALUES
(1, 'Teknik', 'teknik'),
(2, 'Kedokteran', 'kedokteran'),
(3, 'Ekonomi', 'ekonomi'),
(4, 'Hukum', 'hukum'),
(5, 'MIPA', 'mipa'),
(6, 'FISIP', 'fisip'),
(7, 'FPIK', 'fpik'),
(8, 'Pertanian', 'pertanian');

INSERT INTO articles (id, user_id, category_id, title, slug, excerpt, content, status, views, created_at) VALUES
(1, 1, 1, 'Inovasi Riset Unggulan UNSRAT 2024: Menuju Standar Internasional', 'inovasi-riset-unggulan-unsrat-2024',
 'Universitas Sam Ratulangi kembali mencatat prestasi gemilang dalam pemeringkatan riset global melalui kolaborasi lintas fakultas yang inovatif.',
 'Universitas Sam Ratulangi kembali mencatat prestasi gemilang dalam pemeringkatan riset global melalui kolaborasi lintas fakultas yang inovatif.\n\nTahun 2024 menjadi tonggak penting bagi UNSRAT dalam upaya mencapai standar riset internasional. Berbagai program unggulan diluncurkan untuk mendorong publikasi ilmiah berkualitas dan kolaborasi dengan institusi global.\n\nKolaborasi lintas fakultas menjadi kunci keberhasilan ini, menggabungkan keahlian dari berbagai disiplin ilmu untuk menjawab tantangan riset masa kini.',
 'published', 1240, '2024-10-12 09:30:00'),
(2, 4, 2, 'Terobosan Baru Penanganan Penyakit Tropis di Sulawesi Utara', 'terobosan-penanganan-penyakit-tropis-sulut',
 'Mahasiswa Fakultas Kedokteran UNSRAT melakukan penelitian intensif mengenai mitigasi penyebaran virus di daerah tropis.',
 'Mahasiswa Fakultas Kedokteran UNSRAT melakukan penelitian intensif mengenai mitigasi penyebaran virus di daerah tropis.\n\nPenelitian ini berfokus pada karakteristik penyakit tropis yang umum di Sulawesi Utara, serta strategi pencegahan berbasis komunitas.\n\nHasil awal menunjukkan pendekatan edukasi masyarakat berdampak signifikan terhadap penurunan angka kasus.',
 'published', 540, '2024-10-30 14:00:00'),
(3, 5, 1, 'Penerapan Smart City di Manado: Peran Alumni Teknik UNSRAT', 'penerapan-smart-city-manado',
 'Diskusi panel mengenai infrastruktur digital masa depan yang melibatkan praktisi dan akademisi terkemuka.',
 'Diskusi panel mengenai infrastruktur digital masa depan yang melibatkan praktisi dan akademisi terkemuka.\n\nKota Manado bergerak menuju konsep smart city dengan dukungan alumni Teknik UNSRAT yang kini berkarier di industri teknologi.\n\nPanel membahas tata kelola data kota, transportasi cerdas, dan layanan publik berbasis digital.',
 'published', 980, '2024-09-25 11:00:00'),
(4, 6, 3, 'Analisis Pertumbuhan UMKM Pasca Pandemi di Sulawesi', 'analisis-pertumbuhan-umkm-pasca-pandemi',
 'Fakultas Ekonomi dan Bisnis merilis data terbaru mengenai ketahanan ekonomi lokal di tengah inflasi global.',
 'Fakultas Ekonomi dan Bisnis merilis data terbaru mengenai ketahanan ekonomi lokal di tengah inflasi global.\n\nUMKM di Sulawesi Utara menunjukkan pemulihan yang kuat pasca pandemi, didukung oleh adopsi digital dan dukungan pembiayaan.\n\nStudi ini menyoroti sektor kuliner dan pariwisata sebagai motor pertumbuhan.',
 'published', 410, '2024-09-10 08:00:00'),
(5, 2, 5, 'Eksplorasi Keanekaragaman Hayati Bunaken Melalui Teknologi DNA', 'eksplorasi-hayati-bunaken-dna',
 'Pusat Studi Biologi Laut melakukan pengkodean barcode DNA untuk spesies langka di taman nasional.',
 'Pusat Studi Biologi Laut melakukan pengkodean barcode DNA untuk spesies langka di taman nasional.\n\nTeknologi DNA barcoding memungkinkan identifikasi spesies laut secara akurat, mendukung upaya konservasi Taman Nasional Bunaken.\n\nProyek ini melibatkan mahasiswa MIPA dalam pengambilan dan analisis sampel.',
 'published', 350, '2024-08-20 13:00:00'),
(6, 3, 1, 'Implementasi Kurikulum MBKM di Fakultas Teknik UNSRAT: Langkah Menuju Akreditasi Internasional', 'implementasi-kurikulum-mbkm-teknik-unsrat',
 'Fakultas Teknik menjadi pionir dalam mengintegrasikan kurikulum MBKM dengan standar internasional untuk mempersiapkan lulusan yang kompetitif.',
 'Universitas Sam Ratulangi terus berupaya meningkatkan kualitas pendidikan melalui program Merdeka Belajar Kampus Merdeka (MBKM). Fakultas Teknik menjadi salah satu pionir dalam mengintegrasikan kurikulum ini dengan standar internasional untuk mempersiapkan lulusan yang kompetitif secara global.\n\n"Transformasi ini bukan sekadar pergantian mata kuliah, melainkan reposisi mentalitas akademik kita untuk lebih fleksibel terhadap kebutuhan industri," ujar Dekan dalam pidato pembukaannya.\n\nSalah satu tantangan utama dalam implementasi ini adalah sinkronisasi jadwal antara kalender akademik universitas dan siklus proyek di industri. Namun, dengan sistem manajemen pembelajaran digital yang baru, hambatan tersebut perlahan dapat diatasi. Mahasiswa kini memiliki akses ke portal khusus yang memantau progres capaian pembelajaran selama mereka berada di luar kampus.\n\nHingga semester genap tahun ajaran ini, tercatat ada peningkatan sebesar 35% mahasiswa yang mengambil program magang bersertifikat dibandingkan tahun sebelumnya.',
 'published', 2100, '2024-08-24 10:00:00'),
(7, 2, 1, 'Masa Depan AI di Pendidikan Tinggi: Peluang dan Tantangan', 'masa-depan-ai-pendidikan-tinggi',
 'Analisis mendalam mengenai potensi integrasi kecerdasan buatan dalam kurikulum teknik informatika dan dampaknya.',
 'Analisis mendalam mengenai potensi integrasi kecerdasan buatan dalam kurikulum teknik informatika dan dampaknya bagi mahasiswa.\n\nKecerdasan buatan menawarkan peluang personalisasi pembelajaran, namun juga menuntut kesiapan etika dan literasi digital.\n\nDosen dituntut beradaptasi dengan peran baru sebagai fasilitator pembelajaran berbantuan AI.',
 'published', 1200, '2024-07-15 09:00:00'),
(8, 2, 6, 'Metode Pembelajaran Hybrid: Refleksi Satu Tahun', 'metode-pembelajaran-hybrid-refleksi',
 'Bagaimana tantangan dan peluang yang muncul selama transisi dari pembelajaran daring sepenuhnya.',
 'Bagaimana tantangan dan peluang yang muncul selama transisi dari pembelajaran daring sepenuhnya menuju model hybrid.\n\nRefleksi satu tahun menunjukkan pentingnya keseimbangan antara fleksibilitas dan interaksi tatap muka.\n\nMahasiswa mengapresiasi rekaman materi, namun tetap menghargai diskusi langsung di kelas.',
 'published', 856, '2024-07-28 10:30:00'),
(9, 2, 6, 'Membangun Budaya Menulis di Lingkungan Akademik', 'membangun-budaya-menulis-akademik',
 'Mengapa setiap mahasiswa dan dosen perlu memiliki blog publik sebagai portofolio intelektual di era disrupsi.',
 'Mengapa setiap mahasiswa dan dosen perlu memiliki blog publik sebagai portofolio intelektual di era disrupsi informasi.\n\nMenulis secara teratur melatih kemampuan berpikir kritis dan menyusun argumen yang runtut.\n\nPortal komunitas ini hadir sebagai wadah berbagi gagasan lintas fakultas.',
 'published', 2400, '2024-08-12 08:00:00'),
(10, 1, 4, 'Panduan Penulisan Jurnal Internasional', 'panduan-penulisan-jurnal-internasional',
 'Langkah praktis menyiapkan naskah untuk publikasi pada jurnal terindeks bereputasi.',
 'Langkah praktis menyiapkan naskah untuk publikasi pada jurnal terindeks bereputasi.\n\nMulai dari pemilihan jurnal target, struktur naskah, hingga proses revisi dengan reviewer.\n\nArtikel ini masih dalam tahap penyusunan.',
 'draft', 0, '2024-10-22 16:00:00');

INSERT INTO comments (article_id, user_id, content, status, created_at) VALUES
(6, 5, 'Sangat menginspirasi! Sebagai mahasiswa teknik, saya merasa program MBKM ini memang membuka perspektif baru yang tidak didapatkan di ruang kelas konvensional.', 'approved', '2024-08-25 09:00:00'),
(6, 6, 'Tantangan sinkronisasi memang nyata, tapi kami di Fakultas sedang renegosiasi sistem monitoring yang lebih real-time agar mahasiswa tidak merasa ditinggalkan saat magang.', 'approved', '2024-08-25 10:30:00'),
(6, 4, 'Apakah program ini juga berlaku untuk Fakultas Kedokteran? Tertarik sekali ikut magang bersertifikat.', 'pending', '2024-08-26 08:00:00'),
(1, 2, 'Prestasi yang membanggakan untuk seluruh civitas akademika UNSRAT.', 'approved', '2024-10-13 07:00:00');
