-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 06, 2024 at 06:41 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pkl`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_09_03_063021_normalize_tbl_sertifikat_pkl_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('AtCnJLY1gU06FE7zrHe5McJpbI63qvPb11eTw9ka', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTjBEUHhxU3VkdldZRE1RQ3A3T0cxcHZ0M01sRmwwRVNpUzRMNGg0eiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3BlbmlsYWlhbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2VydGlmaWthdC82L2NldGFrLWdhYnVuZ2FuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1733456661),
('EaNX4E6PBBIi4NSZINGygPmzN674XBJJut0NREnE', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN0JxNnNRR1gwSjJkTUFwcHhRMExCZWZBcldUbHNtNEtTRmlEMGN6VCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZW50b3IvY2V0YWstcGRmLzIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1733387414),
('z5AiaxpurZb9F9D9OpsqAYYZLrwbK4gbXSt7Ahqz', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiN2xvZkpmTWdJRlZBRDNRY2c5eEhlRlRDZkg0N1RVbEtFTno2UFBtbSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Npc3dhL3Nob3dzaXN3YSI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2VydGlmaWthdC8zL2NldGFrLWdhYnVuZ2FuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1733466600);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login`
--

CREATE TABLE `tbl_login` (
  `id` bigint NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role` enum('siswa','mentor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_login`
--

INSERT INTO `tbl_login` (`id`, `username`, `pass`, `role`) VALUES
(1, '220302071', '$2y$12$OF8ncKjUbQc7K8rNNKQ0COCvvLaVkvGDCy0gbDR.yMcLmdYbZofae', 'siswa'),
(3, 'mentor1', '$2y$12$Kqmnch3h8ivcYqYxVmDGJ.TTFMozvgqIKkwS2V68aNPjAWgkwSlIi', 'mentor');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penilaian`
--

CREATE TABLE `tbl_penilaian` (
  `id` bigint NOT NULL,
  `aspek` varchar(255) NOT NULL,
  `nilai` int NOT NULL,
  `ket` varchar(20) NOT NULL,
  `id_sertifikat` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_penilaian`
--

INSERT INTO `tbl_penilaian` (`id`, `aspek`, `nilai`, `ket`, `id_sertifikat`) VALUES
(7, 'Kerapihan', 100, 'A', 10),
(8, 'Kerapihan', 100, 'A', 6),
(9, 'Kesopanan', 100, 'A', 2),
(10, 'Keterampilan', 80, 'B', 7),
(11, 'Kemampuan teknis pada bidang TKJ (Teknik Komputer dan Jaringan)', 92, 'A', 6),
(12, 'Kemampuan adaptasi dengan lingkungan kerja', 93, 'A', 6),
(13, 'Kedisiplinan / Kehadiran', 100, 'A', 6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sertifikat_pkl`
--

CREATE TABLE `tbl_sertifikat_pkl` (
  `id_sertifikat` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `asal_sekolah` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nim_nis` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jurusan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `tgl_sertifikat` date NOT NULL,
  `nm_pembimbing` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nik_pembimbing` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `jabatan_pembimbing` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `bidang` varchar(50) NOT NULL,
  `no_sertifikat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nm_kadis` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nip_kadis` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `aprove_pembimbing` int NOT NULL,
  `approve_kadis` int NOT NULL,
  `sts_diambil` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_sertifikat_pkl`
--

INSERT INTO `tbl_sertifikat_pkl` (`id_sertifikat`, `nama_lengkap`, `asal_sekolah`, `nim_nis`, `tempat_lahir`, `tgl_lahir`, `jurusan`, `tgl_mulai`, `tgl_selesai`, `tgl_sertifikat`, `nm_pembimbing`, `nik_pembimbing`, `jabatan_pembimbing`, `bidang`, `no_sertifikat`, `nm_kadis`, `nip_kadis`, `aprove_pembimbing`, `approve_kadis`, `sts_diambil`) VALUES
(2, 'Boyo', 'POLITEKNIK NEGERI CILACAP', '1241249', 'KAWUNGANTEN', '2024-12-05', 'Komputer dan Bisnis', '2024-12-05', '2024-12-05', '2024-12-05', 'Slamet', '123214124', 'Staff', 'Bidang Informatika', '121515125', 'Chairil Taufan', '124124121', 1, 1, 0),
(3, 'Tegar Panggalih', 'POLITEKNIK NEGERI CILACAP', '220302071', 'Cilacap', '2004-03-20', 'Komputer dan Bisnis', '2024-08-05', '2024-12-31', '2024-12-31', 'Slamet Arifin', '90012489', 'Staff', '', '0111924', 'Chairil Taufan', '08124799', 0, 0, 0),
(4, 'Yanuar', 'POLITEKNIK NEGERI CILACAP', '1231241', 'Kebumen', '2024-09-10', 'Komputer dan Bisnis', '2024-09-10', '2024-09-10', '2024-09-10', 'Slamet Arifin', '90012489', 'Staff', '', '01119823', 'Chairil Taufan', '08124799', 1, 1, 0),
(6, 'Ahmad Santoso', 'SMK Negeri 2 Bandung', '220302072', 'Bandung', '2004-03-22', 'Teknik Mesin', '2023-06-10', '2023-09-01', '2023-09-15', 'Andi Wibowo', '2233445566778899', 'Pembimbing Lapangan', '', 'CERT-2023-002', 'Ali Syahbana', '1122334455667788', 1, 0, 0),
(7, 'Rina Astuti', 'SMK Negeri 3 Surabaya', '220302073', 'Surabaya', '2005-07-05', 'Akuntansi', '2023-06-15', '2023-09-10', '2023-09-20', 'Dewi Kartika', '3344556677889900', 'Pembimbing Lapangan', '', 'CERT-2023-003', 'Roni Saputra', '2233445566778899', 0, 0, 1),
(8, 'Agus Wibowo', 'SMK Negeri 4 Malang', '220302074', 'Malang', '2003-08-12', 'Teknik Elektro', '2023-06-20', '2023-09-15', '2023-09-25', 'Ayu Lestari', '5566778899001122', 'Pembimbing Lapangan', '', 'CERT-2023-004', 'Dina Maharani', '3344556677889900', 1, 1, 1),
(9, 'Lilis Handayani', 'SMK Negeri 5 Yogyakarta', '220302075', 'Yogyakarta', '2002-10-20', 'Perhotelan', '2023-06-25', '2023-09-20', '2023-09-30', 'Yusuf Mahendra', '6677889900112233', 'Pembimbing Lapangan', '', 'CERT-2023-005', 'Joko Prasetyo', '4455667788990011', 0, 1, 0),
(10, 'Siti Aminah', 'SMK Negeri 6 Medan', '220302076', 'Medan', '2004-01-10', 'Administrasi Perkantoran', '2023-07-01', '2023-09-30', '2023-10-05', 'Eko Wijaya', '7788990011223344', 'Pembimbing Lapangan', '', 'CERT-2023-006', 'Yuni Kurniawan', '5566778899001122', 1, 0, 1),
(11, 'Bambang Hartono', 'SMK Negeri 7 Semarang', '220302077', 'Semarang', '2003-11-15', 'Teknik Otomotif', '2023-07-05', '2023-10-01', '2023-10-10', 'Susilo Budiarto', '8899001122334455', 'Pembimbing Lapangan', '', 'CERT-2023-007', 'Dewi Sartika', '6677889900112233', 1, 1, 0),
(12, 'Fitri Kurnia', 'SMK Negeri 8 Palembang', '220302078', 'Palembang', '2002-12-25', 'Multimedia', '2023-07-10', '2023-10-05', '2023-10-15', 'Bambang Sugiarto', '9900112233445566', 'Pembimbing Lapangan', '', 'CERT-2023-008', 'Ratna Puspita', '7788990011223344', 0, 1, 1),
(13, 'Joko Setiawan', 'SMK Negeri 9 Bogor', '220302079', 'Bogor', '2004-05-30', 'Teknik Sipil', '2023-07-15', '2023-10-10', '2023-10-20', 'Iwan Kurniawan', '1122334455667788', 'Pembimbing Lapangan', '', 'CERT-2023-009', 'Indra Permana', '8899001122334455', 0, 0, 1),
(14, 'Nur Aisyah', 'SMK Negeri 10 Bali', '220302080', 'Denpasar', '2003-06-17', 'Pariwisata', '2023-07-20', '2023-10-15', '2023-10-25', 'Sari Purnama', '2233445566778899', 'Pembimbing Lapangan', '', 'CERT-2023-010', 'Rizki Fauziah', '9900112233445566', 1, 1, 0),
(15, 'Sachana', 'POLITEKNIK NEGERI CILACAP', '220302073', 'Cilacap', '2024-12-05', 'Komputer dan Bisnis', '2024-12-05', '2024-12-05', '2024-12-05', 'Slamet Arifin', '90012489', 'Staff', 'Bidang Informasi dan Kepentingan Publik', '123455677', 'Supriyanto', '08124799', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sertifikat_pkl`
--
ALTER TABLE `tbl_sertifikat_pkl`
  ADD PRIMARY KEY (`id_sertifikat`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_login`
--
ALTER TABLE `tbl_login`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_sertifikat_pkl`
--
ALTER TABLE `tbl_sertifikat_pkl`
  MODIFY `id_sertifikat` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
