-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Jun 2025 pada 03.43
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simmagang_cilacapkab_go_id_pkl`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_09_03_063021_normalize_tbl_sertifikat_pkl_table', 2),
(5, '2025_06_03_013948_add_id_sertifikat_to_tbl_login_table', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('dLC8iTPQKWVQOKgA9lYGHllnNdXAyKKql3lPeWNa', 25, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiU1d3WmRIREd4ZnptU1pKZldDcFBlSHNneWc0cnJKRUtNZmQ2Q05LbiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXN3YS9uaWxhaSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI1O3M6NzoiaWRfdXNlciI7aToyNTt9', 1750036719);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_login`
--

CREATE TABLE `tbl_login` (
  `id` bigint(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role` enum('siswa','mentor') NOT NULL,
  `id_sertifikat` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_login`
--

INSERT INTO `tbl_login` (`id`, `username`, `pass`, `role`, `id_sertifikat`) VALUES
(1, '220302071', '$2y$12$OF8ncKjUbQc7K8rNNKQ0COCvvLaVkvGDCy0gbDR.yMcLmdYbZofae', 'siswa', NULL),
(2, 'simmagang.cilacapkab.go.id', 'i5eOIwJt2n~a?rx0\r\n\r\n', 'mentor', NULL),
(3, 'mentor1', '$2y$12$Kqmnch3h8ivcYqYxVmDGJ.TTFMozvgqIKkwS2V68aNPjAWgkwSlIi', 'mentor', NULL),
(9, 'ijat1', '$2y$12$fvDgs61mSgkparx07p1BlOJfl3ajxsvGPLPZ.hFGNzwJyzxqDQHWK', 'siswa', NULL),
(12, 'ucup1', '$2y$12$hvnSE1gx73llFoD27gtavOdAhluysSzBe1AqJOOWTWkk8Fs0/DSGa', 'siswa', NULL),
(19, '21104032', '$2y$12$hOOPqKcrRPj5fODtXnsgxePobjfAJqao1ozsLhEAha...e1TRZfv6', 'siswa', NULL),
(20, '2100750', '$2y$12$0e05xymP5nVFRfuB//yIbuGj1AfQOtU0JeoPhwMhIK7I9u9dit4QS', 'siswa', NULL),
(21, '46114221255', '$2y$12$WVo7QKIkFCxsgJMPzBGz8uF068rHG6MlPJcXIJqcQCww6SxVTCRGO', 'siswa', NULL),
(23, 'ucup123', '$2y$12$20cFmWM4JUflKdWKRtwVzejg7CuhdH/uM1fOUlmqs0epojTYJE8au', 'mentor', NULL),
(25, '46114221233', '$2y$12$YwJ5BMrin1TSLN2vbT9O9OJXbGAx.c9uzj03/BtY5f313DDhHgs8C', 'siswa', 23),
(26, '4611422122', '$2y$12$G1.iOtX5O5xdVNYUkfGEreNaao0DPdh5lgEkIGt.ph6Gh/EbtNiXS', 'siswa', 22);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_penilaian`
--

CREATE TABLE `tbl_penilaian` (
  `id` bigint(20) NOT NULL,
  `aspek` varchar(255) NOT NULL,
  `nilai` int(11) NOT NULL,
  `ket` varchar(20) NOT NULL,
  `id_sertifikat` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_penilaian`
--

INSERT INTO `tbl_penilaian` (`id`, `aspek`, `nilai`, `ket`, `id_sertifikat`) VALUES
(14, 'Kemampuan teknis pada bidang Informatika', 90, 'A', 3),
(15, 'Kemampuan adaptasi dengan lingkungan kerja', 95, 'A', 3),
(16, 'Kemampuan komunikasi dengan atasan dan karyawan', 95, 'A', 3),
(17, 'Kemampuan analisis, pemahaman, dan pemecahan masalah', 92, 'A', 3),
(18, 'Kemampuan administrasi dokumen', 90, 'A', 3),
(19, 'Kedisiplinan / Kehadiran', 95, 'A', 3),
(20, 'Sikap dan Perilaku', 92, 'A', 3),
(21, 'Kemampuan teknis pada bidang Informatika', 90, 'A', 16),
(22, 'Kemampuan adaptasi dengan lingkungan kerja', 87, 'B', 16),
(23, 'Kemampuan komunikasi dengan atasan dan karyawan', 90, 'A', 16),
(24, 'Kemampuan analisis, pemahaman, dan pemecahan masalah', 88, 'B', 16),
(25, 'Kemampuan administrasi dokumen', 85, 'B', 16),
(26, 'Kedisiplinan / Kehadiran', 95, 'A', 16),
(27, 'Sikap dan Perilaku', 95, 'A', 16),
(28, 'Kemampuan teknis pada bidang Informatika', 90, 'A', 17),
(29, 'Kemampuan adaptasi dengan lingkungan kerja', 90, 'A', 17),
(30, 'Kemampuan komunikasi dengan atasan dan karyawan', 90, 'A', 17),
(31, 'Kemampuan analisis, pemahaman, dan pemecahan masalah', 95, 'A', 17),
(32, 'Kemampuan administrasi dokumen', 85, 'B', 17),
(33, 'Kedisiplinan / Kehadiran', 95, 'A', 17),
(34, 'Sikap dan Perilaku', 95, 'A', 17),
(35, 'Kemampuan teknis pada bidang Informatika', 95, 'A', 18),
(36, 'Kemampuan adaptasi dengan lingkungan kerja', 90, 'A', 18),
(37, 'Kemampuan komunikasi dengan atasan dan karyawan', 95, 'A', 18),
(38, 'Kemampuan analisis, pemahaman, dan pemecahan masalah', 95, 'A', 18),
(39, 'Kemampuan administrasi dokumen', 85, 'B', 18),
(40, 'Kedisiplinan / Kehadiran', 95, 'A', 18),
(41, 'Sikap dan Perilaku', 95, 'A', 18),
(45, 'Bahasa Jawa', 93, 'A', 19),
(47, 'Bahasa Jawa', 90, 'AB', 21),
(48, 'Jawa', 80, 'B', 21),
(49, 'Indo', 45, 'C', 21);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_sertifikat_pkl`
--

CREATE TABLE `tbl_sertifikat_pkl` (
  `id_sertifikat` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `asal_sekolah` varchar(50) NOT NULL,
  `nim_nis` varchar(25) NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `tgl_sertifikat` date NOT NULL,
  `nm_pembimbing` varchar(50) NOT NULL,
  `nik_pembimbing` varchar(25) NOT NULL,
  `jabatan_pembimbing` varchar(50) NOT NULL,
  `bidang` varchar(50) NOT NULL,
  `no_sertifikat` varchar(50) NOT NULL,
  `nm_kadis` varchar(50) NOT NULL,
  `nip_kadis` varchar(25) NOT NULL,
  `aprove_pembimbing` int(11) NOT NULL,
  `approve_kadis` int(11) NOT NULL,
  `sts_diambil` int(11) NOT NULL,
  `created_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_sertifikat_pkl`
--

INSERT INTO `tbl_sertifikat_pkl` (`id_sertifikat`, `nama_lengkap`, `asal_sekolah`, `nim_nis`, `tempat_lahir`, `tgl_lahir`, `jurusan`, `tgl_mulai`, `tgl_selesai`, `tgl_sertifikat`, `nm_pembimbing`, `nik_pembimbing`, `jabatan_pembimbing`, `bidang`, `no_sertifikat`, `nm_kadis`, `nip_kadis`, `aprove_pembimbing`, `approve_kadis`, `sts_diambil`, `created_id`) VALUES
(3, 'Tegar Panggalih', 'POLITEKNIK NEGERI CILACAP', '220302071', 'Cilacap', '2004-03-20', 'D3 Teknik Informatika', '2024-08-05', '2024-12-31', '2025-01-02', 'Slamet Arifin,A.md.T.', '19930420 202202 1 001', 'Staff', 'Bidang Informatika', '400.14.5.4/0001/33', 'SUPRIYANTO, S.H.,M.Si.', '19650825 199403 1 009', 1, 1, 1, 3),
(16, 'LUQMAN ZAIN KHILMY', 'Universitas Telkom Purwokerto', '21104032', 'Cilacap', '2004-03-27', 'Rekayasa Perangkat Lunak', '2024-07-15', '2024-09-01', '2024-09-10', 'Slamet Arifin,A.md.T.', '19930420 202202 1 001', 'Operator Layanan Operasional (5)', 'Bidang Informatika', '400.14.5.4/1335/33', 'SUPRIYANTO, S.H.,M.Si.', '19650825 199403 1 009', 1, 1, 0, 3),
(17, 'Fa\'iq Fawwaz', 'Universitas Ahmad Dahlan', '2100018041', 'Cilacap', '2002-11-10', 'Informatika', '2024-09-19', '2024-12-18', '2024-12-20', 'Slamet Arifin,A.md.T.', '19930420 202202 1 001', 'Operator Layanan Operasional (5)', 'Bidang Informatika', '400.14.5.4/1971/33', 'SUPRIYANTO, S.H.,M.Si.', '19650825 199403 1 009', 1, 1, 0, 3),
(18, 'Melisa Galuh Parwati', 'Universitas Pendidikan Indonesia', '2100750', 'Cilacap', '2004-05-15', 'Mekatronika dan Kecerdasan Buatan', '2024-09-09', '2024-12-09', '2024-12-10', 'Slamet Arifin,A.md.T.', '19930420 202202 1 001', 'Operator Layanan Operasional (5)', 'Bidang Informatika', '400.14.5.4/1867/33', 'SUPRIYANTO, S.H.,M.Si.', '19650825 199403 1 009', 1, 1, 0, 20),
(21, 'Maulana Ajah', 'Universitas Negeri Semarang', '46114082828', 'Cilacap', '2025-04-01', 'Teknik Informatika', '2025-04-02', '2025-06-20', '2025-04-22', 'Pak Budi Kusuma', '2201230405', 'Programer', 'Bidang Statistik dan Persandian', '004/005/Maulana Ajah', 'Pak Agus', '220123050405', 1, 1, 0, 3),
(22, 'Diva Finella', 'Universitas Negeri Semarang', '4611422122', 'Denpasar', '2004-10-09', 'Teknik Informatika', '2025-03-06', '2025-10-23', '2025-08-25', 'Abi Kurniawan', '2201230333', 'Programer', 'Bidang Informasi dan Kepentingan Publik', '004/005/Diva Finella', 'Pak Agus', '220123050405', 1, 0, 0, 26),
(23, 'Finella Gemoyy', 'Universitas Indonesia', '46114221233', 'Denpasar', '2004-10-09', 'Teknik Informatika', '2025-05-24', '2025-06-07', '2025-06-07', 'Pak Budi Kusuma', '2201230405', 'Programer', 'Bidang Informasi dan Kepentingan Publik', '004/005/Finella Gemoy', 'Pak Agus', '220123050405', 1, 0, 0, 3),
(24, 'Ijatt Gemoyy', 'Universitas Diponegoro', '46114221255', 'Mana Aja', '2004-10-20', 'Teknik Komputer', '2025-05-01', '2025-06-20', '2025-06-20', 'Pak Budi Kusuma', '2201230405', 'Programer', 'Bidang Informatika', '004/005/Ijat Gemoy', 'Pak Agus', '220123050405', 1, 1, 0, 21),
(25, 'CikoMoyy', 'Universitas Negeri Semarang', '46114221123', 'Cianjur', '2004-10-20', 'Teknik Komputer', '2025-05-30', '2025-07-05', '2025-07-11', 'Pak Budi Kusuma', '2201230405', 'Programer', 'Bidang Informasi dan Kepentingan Publik', '004/005/CikoMoyy', 'Pak Agus', '220123050405', 1, 1, 0, 3),
(26, 'Ucup Ajahh', 'Universitas Negeri Semarang', '4611422000', 'Kolong Jembatan', '2004-10-30', 'Teknik Informatika', '2025-07-01', '2025-07-30', '2025-07-31', 'Pak Budi Kusuma', '2201230405', 'Programer', 'Bidang Informatika', '004/005/Ucup Ajahh', 'Pak Agus', '220123050405', 1, 1, 0, 3),
(27, 'Hadid Ramadhan', 'Universitas Negeri Semarang', '4611422083', 'Indramayu', '2004-10-20', 'Teknik Informatika', '2025-06-05', '2025-06-20', '2025-06-20', 'Abi Kurniawan', '2201230333', 'Programer', 'Bidang Informatika', '004/005/Hadid Ramadhan', 'Pak Agus', '220123050405', 1, 1, 0, 23);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_sertifikat` (`id_sertifikat`);

--
-- Indeks untuk tabel `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_sertifikat_pkl`
--
ALTER TABLE `tbl_sertifikat_pkl`
  ADD PRIMARY KEY (`id_sertifikat`),
  ADD KEY `mentor_siswa` (`created_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_login`
--
ALTER TABLE `tbl_login`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT untuk tabel `tbl_sertifikat_pkl`
--
ALTER TABLE `tbl_sertifikat_pkl`
  MODIFY `id_sertifikat` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD CONSTRAINT `fk_id_sertifikat` FOREIGN KEY (`id_sertifikat`) REFERENCES `tbl_sertifikat_pkl` (`id_sertifikat`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `tbl_sertifikat_pkl`
--
ALTER TABLE `tbl_sertifikat_pkl`
  ADD CONSTRAINT `mentor_siswa` FOREIGN KEY (`created_id`) REFERENCES `tbl_login` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
