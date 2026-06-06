-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2026 at 07:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog_unsrat`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `excerpt` varchar(400) DEFAULT NULL,
  `content` text NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `category_id`, `title`, `slug`, `excerpt`, `content`, `cover_image`, `status`, `views`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Inovasi Riset Unggulan UNSRAT 2024: Menuju Standar Internasional', 'inovasi-riset-unggulan-unsrat-2024', 'Universitas Sam Ratulangi kembali mencatat prestasi gemilang dalam pemeringkatan riset global melalui kolaborasi lintas fakultas yang inovatif.', 'Universitas Sam Ratulangi kembali mencatat prestasi gemilang dalam pemeringkatan riset global melalui kolaborasi lintas fakultas yang inovatif.\n\nTahun 2024 menjadi tonggak penting bagi UNSRAT dalam upaya mencapai standar riset internasional. Berbagai program unggulan diluncurkan untuk mendorong publikasi ilmiah berkualitas dan kolaborasi dengan institusi global.\n\nKolaborasi lintas fakultas menjadi kunci keberhasilan ini, menggabungkan keahlian dari berbagai disiplin ilmu untuk menjawab tantangan riset masa kini.', NULL, 'published', 1241, '2024-10-12 09:30:00', '2026-06-04 03:47:51'),
(2, 4, 2, 'Terobosan Baru Penanganan Penyakit Tropis di Sulawesi Utara', 'terobosan-penanganan-penyakit-tropis-sulut', 'Mahasiswa Fakultas Kedokteran UNSRAT melakukan penelitian intensif mengenai mitigasi penyebaran virus di daerah tropis.', 'Mahasiswa Fakultas Kedokteran UNSRAT melakukan penelitian intensif mengenai mitigasi penyebaran virus di daerah tropis.\n\nPenelitian ini berfokus pada karakteristik penyakit tropis yang umum di Sulawesi Utara, serta strategi pencegahan berbasis komunitas.\n\nHasil awal menunjukkan pendekatan edukasi masyarakat berdampak signifikan terhadap penurunan angka kasus.', NULL, 'published', 541, '2024-10-30 14:00:00', '2026-06-04 02:29:27'),
(3, 5, 1, 'Penerapan Smart City di Manado: Peran Alumni Teknik UNSRAT', 'penerapan-smart-city-manado', 'Diskusi panel mengenai infrastruktur digital masa depan yang melibatkan praktisi dan akademisi terkemuka.', 'Diskusi panel mengenai infrastruktur digital masa depan yang melibatkan praktisi dan akademisi terkemuka.\n\nKota Manado bergerak menuju konsep smart city dengan dukungan alumni Teknik UNSRAT yang kini berkarier di industri teknologi.\n\nPanel membahas tata kelola data kota, transportasi cerdas, dan layanan publik berbasis digital.', NULL, 'published', 981, '2024-09-25 11:00:00', '2026-06-04 02:01:23'),
(4, 6, 3, 'Analisis Pertumbuhan UMKM Pasca Pandemi di Sulawesi', 'analisis-pertumbuhan-umkm-pasca-pandemi', 'Fakultas Ekonomi dan Bisnis merilis data terbaru mengenai ketahanan ekonomi lokal di tengah inflasi global.', 'Fakultas Ekonomi dan Bisnis merilis data terbaru mengenai ketahanan ekonomi lokal di tengah inflasi global.\n\nUMKM di Sulawesi Utara menunjukkan pemulihan yang kuat pasca pandemi, didukung oleh adopsi digital dan dukungan pembiayaan.\n\nStudi ini menyoroti sektor kuliner dan pariwisata sebagai motor pertumbuhan.', NULL, 'published', 410, '2024-09-10 08:00:00', '2026-06-03 14:53:42'),
(5, 2, 5, 'Eksplorasi Keanekaragaman Hayati Bunaken Melalui Teknologi DNA', 'eksplorasi-hayati-bunaken-dna', 'Pusat Studi Biologi Laut melakukan pengkodean barcode DNA untuk spesies langka di taman nasional.', 'Pusat Studi Biologi Laut melakukan pengkodean barcode DNA untuk spesies langka di taman nasional.\n\nTeknologi DNA barcoding memungkinkan identifikasi spesies laut secara akurat, mendukung upaya konservasi Taman Nasional Bunaken.\n\nProyek ini melibatkan mahasiswa MIPA dalam pengambilan dan analisis sampel.', NULL, 'published', 350, '2024-08-20 13:00:00', '2026-06-03 14:53:42'),
(6, 3, 1, 'Implementasi Kurikulum MBKM di Fakultas Teknik UNSRAT: Langkah Menuju Akreditasi Internasional', 'implementasi-kurikulum-mbkm-teknik-unsrat', 'Fakultas Teknik menjadi pionir dalam mengintegrasikan kurikulum MBKM dengan standar internasional untuk mempersiapkan lulusan yang kompetitif.', 'Universitas Sam Ratulangi terus berupaya meningkatkan kualitas pendidikan melalui program Merdeka Belajar Kampus Merdeka (MBKM). Fakultas Teknik menjadi salah satu pionir dalam mengintegrasikan kurikulum ini dengan standar internasional untuk mempersiapkan lulusan yang kompetitif secara global.\n\n\"Transformasi ini bukan sekadar pergantian mata kuliah, melainkan reposisi mentalitas akademik kita untuk lebih fleksibel terhadap kebutuhan industri,\" ujar Dekan dalam pidato pembukaannya.\n\nSalah satu tantangan utama dalam implementasi ini adalah sinkronisasi jadwal antara kalender akademik universitas dan siklus proyek di industri. Namun, dengan sistem manajemen pembelajaran digital yang baru, hambatan tersebut perlahan dapat diatasi. Mahasiswa kini memiliki akses ke portal khusus yang memantau progres capaian pembelajaran selama mereka berada di luar kampus.\n\nHingga semester genap tahun ajaran ini, tercatat ada peningkatan sebesar 35% mahasiswa yang mengambil program magang bersertifikat dibandingkan tahun sebelumnya.', NULL, 'published', 2100, '2024-08-24 10:00:00', '2026-06-03 14:53:42'),
(7, 2, 1, 'Masa Depan AI di Pendidikan Tinggi: Peluang dan Tantangan', 'masa-depan-ai-pendidikan-tinggi', 'Analisis mendalam mengenai potensi integrasi kecerdasan buatan dalam kurikulum teknik informatika dan dampaknya.', 'Analisis mendalam mengenai potensi integrasi kecerdasan buatan dalam kurikulum teknik informatika dan dampaknya bagi mahasiswa.\n\nKecerdasan buatan menawarkan peluang personalisasi pembelajaran, namun juga menuntut kesiapan etika dan literasi digital.\n\nDosen dituntut beradaptasi dengan peran baru sebagai fasilitator pembelajaran berbantuan AI.', NULL, 'published', 1200, '2024-07-15 09:00:00', '2026-06-03 14:53:42'),
(8, 2, 6, 'Metode Pembelajaran Hybrid: Refleksi Satu Tahun', 'metode-pembelajaran-hybrid-refleksi', 'Bagaimana tantangan dan peluang yang muncul selama transisi dari pembelajaran daring sepenuhnya.', 'Bagaimana tantangan dan peluang yang muncul selama transisi dari pembelajaran daring sepenuhnya menuju model hybrid.\n\nRefleksi satu tahun menunjukkan pentingnya keseimbangan antara fleksibilitas dan interaksi tatap muka.\n\nMahasiswa mengapresiasi rekaman materi, namun tetap menghargai diskusi langsung di kelas.', NULL, 'published', 856, '2024-07-28 10:30:00', '2026-06-03 14:53:42'),
(9, 2, 6, 'Membangun Budaya Menulis di Lingkungan Akademik', 'membangun-budaya-menulis-akademik', 'Mengapa setiap mahasiswa dan dosen perlu memiliki blog publik sebagai portofolio intelektual di era disrupsi.', 'Mengapa setiap mahasiswa dan dosen perlu memiliki blog publik sebagai portofolio intelektual di era disrupsi informasi.\n\nMenulis secara teratur melatih kemampuan berpikir kritis dan menyusun argumen yang runtut.\n\nPortal komunitas ini hadir sebagai wadah berbagi gagasan lintas fakultas.', NULL, 'published', 2400, '2024-08-12 08:00:00', '2026-06-03 14:53:42'),
(10, 1, 4, 'Panduan Penulisan Jurnal Internasional', 'panduan-penulisan-jurnal-internasional', 'Langkah praktis menyiapkan naskah untuk publikasi pada jurnal terindeks bereputasi.', 'Langkah praktis menyiapkan naskah untuk publikasi pada jurnal terindeks bereputasi.\n\nMulai dari pemilihan jurnal target, struktur naskah, hingga proses revisi dengan reviewer.\n\nArtikel ini masih dalam tahap penyusunan.', NULL, 'draft', 0, '2024-10-22 16:00:00', '2026-06-03 14:53:42'),
(11, 4, 1, 'JIKA SUARA DILARANG, DIAM JADI PERLAWANAN', 'jika-suara-dilarang-diam-jadi-perlawanan', 'Arak-arakan fakultas teknik', 'Pemandangan tak biasa terlihat dalam arak-arakan Fakultas Teknik hari ini.\r\nTidak ada genderang, tidak ada yel-yel yang memecah langit. Mahasiswa memilih berjalan dalam senyap sebagai respon atas kebijakan kampus yang membatasi ruang gerak dan ekspresi mahasiswa di lingkungan akademik.\r\nAlasan \"menganggu kegiatan belajar-mengajar\" kini menjadi tembok yang memisahkan tradisi dengan birokrasi. Jika suara kami dianggap polusi, maka biarlah diam yang berbicara tentang restriksi.', 'cover_1780510369_889f57d9.jpg', 'published', 3, '2026-06-04 02:12:49', '2026-06-04 03:47:57'),
(12, 4, 1, 'TEKNIK jadi REKAYASA?!', 'teknik-jadi-rekayasa', 'Pemerintah rubah nama jurusan Teknik jadi Rekayasa', 'Pemerintah lewat Kemendiksaintek mengubah nama jurusan \"Teknik\" menjadi jurusan \"Rekayasa\". Hal ini tertuang dalam SK Nomor 96/B/KPT/2025. Sejumlah program studi yang dulunya pakai nama \"Teknik\" sekarang berubah jadi \"Rekayasa\". Pihak kampus juga bisa memilih untuk tetap menggunakan kata \"Teknik\".', 'cover_1780511288_0ecf4851.jpg', 'published', 1, '2026-06-04 02:28:08', '2026-06-04 02:29:07'),
(13, 4, 1, 'Pembatasan Ruang Gerak Mahasiswa', 'pembatasan-ruang-gerak-mahasiswa', 'Mahasiswa merasa ruang gerak untuk berekspresi dibatasi birokrasi', 'Banyak keluhan dari mahasiswa Fakultas Teknik yang merasa dibatasi untuk membuat/melaksanakan kegiatan di dalam lingkup Fakultas Teknik. Sulitnya mendapat izin pelaksanaan kegiatan mahasiswa di dalam lingkup fakultas menjadi alasan utama banyaknya keluhan dan keresahan dari pihak mahasiswa. Padahal kegiatan-kegiatan tersebut dapat berdampak positif bagi mahasiswa dan masyarakat yang secara tidak langsung bisa mengharumkan nama Fakultas Teknik.', 'cover_1780511986_41f63a79.jpg', 'published', 0, '2026-06-04 02:39:46', '2026-06-04 02:39:46');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Teknik', 'teknik', '2026-06-03 14:53:42'),
(2, 'Kedokteran', 'kedokteran', '2026-06-03 14:53:42'),
(3, 'Ekonomi', 'ekonomi', '2026-06-03 14:53:42'),
(4, 'Hukum', 'hukum', '2026-06-03 14:53:42'),
(5, 'MIPA', 'mipa', '2026-06-03 14:53:42'),
(6, 'FISIP', 'fisip', '2026-06-03 14:53:42'),
(7, 'FPIK', 'fpik', '2026-06-03 14:53:42'),
(8, 'Pertanian', 'pertanian', '2026-06-03 14:53:42');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` enum('approved','pending') NOT NULL DEFAULT 'approved',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `user_id`, `content`, `status`, `created_at`) VALUES
(1, 6, 5, 'Sangat menginspirasi! Sebagai mahasiswa teknik, saya merasa program MBKM ini memang membuka perspektif baru yang tidak didapatkan di ruang kelas konvensional.', 'approved', '2024-08-25 09:00:00'),
(2, 6, 6, 'Tantangan sinkronisasi memang nyata, tapi kami di Fakultas sedang renegosiasi sistem monitoring yang lebih real-time agar mahasiswa tidak merasa ditinggalkan saat magang.', 'approved', '2024-08-25 10:30:00'),
(3, 6, 4, 'Apakah program ini juga berlaku untuk Fakultas Kedokteran? Tertarik sekali ikut magang bersertifikat.', 'pending', '2024-08-26 08:00:00'),
(4, 1, 2, 'Prestasi yang membanggakan untuk seluruh civitas akademika UNSRAT.', 'approved', '2024-10-13 07:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','dosen','mahasiswa') NOT NULL DEFAULT 'mahasiswa',
  `faculty` varchar(120) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `faculty`, `bio`, `avatar`, `created_at`) VALUES
(1, 'Dr. Sam Ratulangi', 'admin@unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'admin', 'University Management', 'Administrator portal blog komunitas Universitas Sam Ratulangi.', NULL, '2024-01-10 08:00:00'),
(2, 'Dr. Johannes Lumanauw', 'johannes@unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'dosen', 'Fakultas Teknik', 'Lektor Kepala di Departemen Teknik Informatika dengan fokus riset pada Inteligensi Buatan dan Analisis Big Data. Berdedikasi untuk memajukan literasi digital di kalangan mahasiswa Sulawesi Utara melalui publikasi ilmiah dan diskusi komunitas yang inklusif.', NULL, '2024-02-01 09:00:00'),
(3, 'Dr. Ir. Robert L. Molanguthor', 'robert@unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'dosen', 'Fakultas Teknik', 'Dekan Fakultas Teknik UNSRAT, penggerak program Merdeka Belajar Kampus Merdeka.', NULL, '2024-02-05 09:00:00'),
(4, 'Arif Lumenta', 'arif@student.unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'mahasiswa', 'teknik', 'Mahasiswa Fakultas Teknik,', NULL, '2024-03-01 10:00:00'),
(5, 'Siti Ramlah', 'siti@student.unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'mahasiswa', 'Fakultas Teknik', 'Mahasiswa Teknik, antusias pada smart city dan IoT.', NULL, '2024-03-10 10:00:00'),
(6, 'Budi Pratama', 'budi@student.unsrat.ac.id', '$2y$10$jGqhhSIyh1tO/AA0T2BdpOVGrPuC.h2K8I.jMSoJHuTErOQqfta5O', 'mahasiswa', 'Fakultas Ekonomi dan Bisnis', 'Mahasiswa Ekonomi, fokus pada UMKM dan ekonomi lokal.', NULL, '2024-03-15 10:00:00'),
(7, 'Ayabadak', 'ayabadak@student.unsrat.ac.id', '$2y$10$tcGcA0M5y7U.ggS27w15tuZpnp9pX3ElQZyMJ/Emk2spFg11zTgAe', 'mahasiswa', 'Teknik', NULL, NULL, '2026-06-04 11:00:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_articles_user` (`user_id`),
  ADD KEY `fk_articles_category` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comments_article` (`article_id`),
  ADD KEY `fk_comments_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_articles_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_articles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
