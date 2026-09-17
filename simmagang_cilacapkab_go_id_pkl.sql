-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: simmagang_cilacapkab_go_id_pkl
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `content` varchar(255) NOT NULL,
  `published_at` varchar(20) NOT NULL,
  `created_by` varchar(20) DEFAULT NULL,
  `created_at` varchar(20) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (2,'coba','Pengumuman','coba','2026-07-15 00:00:00','3','2026-07-14 11:24:59','2026-07-14 04:24:59'),(3,'coba lagi','Akademik','coba lagi','2026-07-13 00:00:00','3','2026-07-14 11:25:29','2026-07-14 04:25:29'),(6,'Pendaftaran Magang Batch 3 Dibuka','info','Pendaftaran magang untuk batch 3 tahun 2025 telah dibuka. Siswa dapat mendaftar melalui sistem mulai tanggal 5 Juli 2025.','2026-07-14 13:31:25','1','2026-07-16 13:31:25','2026-07-16 06:31:25'),(7,'Perubahan Jadwal Orientasi Magang','warning','Jadwal orientasi magang batch 2 diundur dari tanggal 10 Juli menjadi 15 Juli 2025.','2026-07-11 13:31:25','1','2026-07-16 13:31:25','2026-07-16 06:31:25'),(8,'Sertifikat Magang Batch 1 Telah Siap','success','Sertifikat untuk siswa magang batch 1 telah selesai dicetak dan siap untuk diambil.','2026-07-09 13:31:25','1','2026-07-16 13:31:25','2026-07-16 06:31:25'),(10,'kfjwek','Beasiswa','fmw','2026-07-27 00:00:00','3','2026-07-27 13:56:07','2026-07-27 06:56:07');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_sertifikat` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `time` time DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_id_sertifikat_date_unique` (`id_sertifikat`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kuota_bidang`
--

DROP TABLE IF EXISTS `kuota_bidang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kuota_bidang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_bidang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kuota` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kuota_bidang_nama_bidang_unique` (`nama_bidang`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kuota_bidang`
--

LOCK TABLES `kuota_bidang` WRITE;
/*!40000 ALTER TABLE `kuota_bidang` DISABLE KEYS */;
INSERT INTO `kuota_bidang` VALUES (1,'Bidang Informatika',10,'2026-07-16 03:40:23','2026-07-16 03:40:23'),(2,'Sekretariat',8,'2026-07-16 03:40:23','2026-07-16 03:40:23'),(3,'Bidang Informasi dan Kepentingan Publik',12,'2026-07-16 03:40:23','2026-07-16 03:40:23'),(4,'Bidang Statistik dan Persandian',6,'2026-07-16 03:40:23','2026-07-16 03:40:23'),(5,'Lembaga Penyiaran Publik Lokal',5,'2026-07-16 03:40:23','2026-07-16 03:40:23');
/*!40000 ALTER TABLE `kuota_bidang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mentor_siswa`
--

DROP TABLE IF EXISTS `mentor_siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mentor_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mentor_id` bigint NOT NULL COMMENT 'ID mentor dari tbl_login',
  `siswa_id` bigint unsigned NOT NULL COMMENT 'ID sertifikat siswa dari tbl_sertifikat_pkl',
  `tanggal_bimbingan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Tanggal mulai bimbingan',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Catatan tambahan',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status bimbingan aktif/tidak',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mentor_siswa_mentor_id_siswa_id_unique` (`mentor_id`,`siswa_id`),
  KEY `mentor_siswa_mentor_id_index` (`mentor_id`),
  KEY `mentor_siswa_siswa_id_index` (`siswa_id`),
  KEY `mentor_siswa_is_active_index` (`is_active`),
  CONSTRAINT `mentor_siswa_mentor_id_foreign` FOREIGN KEY (`mentor_id`) REFERENCES `tbl_login` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mentor_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `tbl_sertifikat_pkl` (`id_sertifikat`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mentor_siswa`
--

LOCK TABLES `mentor_siswa` WRITE;
/*!40000 ALTER TABLE `mentor_siswa` DISABLE KEYS */;
INSERT INTO `mentor_siswa` VALUES (3,29,17,'2026-07-29 01:32:30','Bimbingan aktif',1,'2026-07-29 01:32:30','2026-07-29 01:32:30'),(5,30,23,'2026-07-29 01:32:30','Bimbingan aktif',1,'2026-07-29 01:32:30','2026-07-29 01:32:30'),(6,30,24,'2026-07-29 01:32:30','Bimbingan aktif',1,'2026-07-29 01:32:30','2026-07-29 01:32:30'),(7,29,18,'2026-08-03 01:51:53','Bimbingan aktif',1,'2026-08-03 01:51:53','2026-08-03 01:51:53'),(8,20,136,'2026-08-03 03:34:48','Ditambahkan otomatis saat persetujuan pengajuan magang',1,'2026-08-03 03:34:48','2026-08-03 03:34:48');
/*!40000 ALTER TABLE `mentor_siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (16,'0001_01_01_000000_create_users_table',1),(17,'0001_01_01_000001_create_cache_table',1),(18,'0001_01_01_000002_create_jobs_table',1),(19,'2024_09_03_063021_normalize_tbl_sertifikat_pkl_table',2),(20,'2025_06_03_013948_add_id_sertifikat_to_tbl_login_table',2),(21,'2025_06_04_062457_fix_id_sertifikat_column',2),(22,'2025_07_21_045659_add_foto_profil_url_to_tbl_sertifikat_pkl_table',2),(23,'2025_07_28_133841_create_attendances_table',2),(24,'2025_08_28_035333_add_user_pkl_id_to_portofolios_table',3),(25,'2025_08_28_035518_add_user_pkl_id_to_portofolios_table',3),(26,'2025_08_28_040328_add_fk_sertifikat_to_portofolios_table',3),(27,'2025_09_01_024928_update_portofolios_user_fk',3),(28,'2025_09_01_025200_fix_portofolios_user_column',3),(29,'2025_09_01_025501_fix_portofolios_user_fk',3),(30,'2025_09_24_015057_change_id_sertifikat_type_in_attendances_table',3),(31,'2025_09_24_032521_fix_user_pkl_id_type_in_portofolios',3),(32,'2025_09_24_035355_drop_user_pkl_id_new_from_portofolios_table',3),(33,'2025_09_24_061155_remove_user_pkl_id_new_from_portofolios',3),(34,'2025_11_25_031829_add_jabatan_pegawai_to_tbl_sertifikat_pkl_table',4),(35,'2025_11_26_102456_create_portofolios_table',4),(36,'2026_07_14_105857_create_tasks_table',4),(37,'2026_07_15_114328_add_email_to_tbl_login_table',5),(38,'2026_07_15_115637_create_pengajuan_magang_table',5),(39,'2026_07_15_120000_update_existing_users_add_email_nama',5),(40,'2026_07_15_141601_create_task_submissions_table',5),(41,'2026_07_16_000000_make_no_sertifikat_nullable',5),(42,'2026_07_16_add_kuota_bidang_table',6),(43,'2026_07_16_add_sekretariat_support',7),(44,'2026_07_16_add_sekretariat_role_to_enum',8),(45,'2026_07_17_144747_add_tempat_lahir_tgl_lahir_to_pengajuan_magang_table',9),(46,'2026_07_21_085034_add_ip_useragent_to_attendances_table',10),(47,'2026_07_23_094509_add_bidang_to_tbl_login',11),(49,'2026_07_23_000001_fix_sertifikat_pkl_nim_nis_and_tempat_lahir',12),(50,'2026_07_23_153951_create_penempatan_bidang_table',13),(51,'2026_07_24_162234_add_created_id_to_tbl_sertifikat_pkl_table',14),(52,'2026_07_27_021805_add_tempat_tgl_lahir_to_pengajuan_magang_table',15),(53,'2026_07_28_100000_add_bidang_to_tbl_login_table',16),(54,'2026_07_28_100001_add_catatan_izin_to_tasks_table',16),(55,'2026_07_28_100002_create_mentor_siswa_table',16),(56,'2026_07_28_100003_set_demo_mentor_bidang',16),(57,'2026_07_30_101521_make_email_nullable_in_tbl_login',17),(58,'2026_07_30_101855_make_nama_nullable_in_tbl_login',18),(59,'2026_08_03_102128_add_viewed_at_to_task_submissions_table',19);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penempatan_bidang`
--

DROP TABLE IF EXISTS `penempatan_bidang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penempatan_bidang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_sertifikat` bigint unsigned NOT NULL,
  `bidang_asal` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bidang asal dari pengajuan siswa',
  `bidang_ditempatkan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Bidang tempat penempatan',
  `ditempatkan_oleh` enum('mentor','sekretariat') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Role yang melakukan penempatan',
  `user_id` bigint NOT NULL COMMENT 'User yang melakukan penempatan',
  `tanggal_penempatan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penempatan_bidang_id_sertifikat_unique` (`id_sertifikat`),
  KEY `penempatan_bidang_user_id_foreign` (`user_id`),
  KEY `penempatan_bidang_bidang_ditempatkan_index` (`bidang_ditempatkan`),
  CONSTRAINT `penempatan_bidang_id_sertifikat_foreign` FOREIGN KEY (`id_sertifikat`) REFERENCES `tbl_sertifikat_pkl` (`id_sertifikat`) ON DELETE CASCADE,
  CONSTRAINT `penempatan_bidang_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_login` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penempatan_bidang`
--

LOCK TABLES `penempatan_bidang` WRITE;
/*!40000 ALTER TABLE `penempatan_bidang` DISABLE KEYS */;
/*!40000 ALTER TABLE `penempatan_bidang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengajuan_magang`
--

DROP TABLE IF EXISTS `pengajuan_magang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengajuan_magang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nim_nis` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asal_sekolah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `bidang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `dokumen_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `alasan_reject` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengajuan_magang`
--

LOCK TABLES `pengajuan_magang` WRITE;
/*!40000 ALTER TABLE `pengajuan_magang` DISABLE KEYS */;
INSERT INTO `pengajuan_magang` VALUES (1,17,'Wakhid','H1D024003','UNSOED','Informatika',NULL,NULL,'Informatika','2026-07-13','2026-08-13',NULL,'rejected','penuh',3,'2026-07-17 04:24:27','2026-07-15 06:22:20','2026-07-17 04:24:27'),(2,18,'cobaxyz','123456','A','B',NULL,NULL,'Informatika','2026-08-01','2026-08-07',NULL,'approved',NULL,3,'2026-07-17 06:45:30','2026-07-17 06:42:05','2026-07-17 06:45:30'),(3,22,'budi','budi gaming 123','B','Sastra Jawa','coba','2026-07-19','Informatika','2026-07-21','2026-07-22','dokumen_pengajuan/lYGv1tjGu9m4YgcOiPQTlovxebGOepiOCFBSRYPh.docx','rejected','sesuai',3,'2026-07-20 06:34:15','2026-07-20 01:08:32','2026-07-20 06:34:15'),(4,22,'budi2','1','c','d','e','2026-07-07','Informatika','2026-07-15','2026-07-21',NULL,'rejected','?',3,'2026-07-20 06:35:28','2026-07-20 01:32:54','2026-07-20 06:35:28'),(5,22,'budi','123','1','2','3','2026-07-19','Informatika','2026-07-14','2026-07-31','dokumen_pengajuan/9Fa5fSgvtMFGKBlLqeOx6MYlMRo7gizUl8Kum8Nx.docx','rejected','ga logis',3,'2026-07-20 08:00:42','2026-07-20 07:58:24','2026-07-20 08:00:42'),(6,22,'budi','1','2','3','4','2026-07-16','Informatika','2026-07-20','2026-07-21','dokumen_pengajuan/5pS7onFFxwRHGqyLiuHELNkO6nywzOzoi2mZUUOh.docx','approved',NULL,3,'2026-07-20 08:04:01','2026-07-20 08:03:14','2026-07-20 08:04:01'),(7,24,'budi3','1','2','3','4','2026-07-09','Informatika','2026-07-20','2026-07-21','dokumen_pengajuan/LAig1ktgpERgfTjQEmVIlJbKcABn394rU22FmFk5.png','approved',NULL,3,'2026-07-20 08:33:14','2026-07-20 08:31:30','2026-07-20 08:33:14'),(8,25,'cobacoba','fjwiojd','vfnklw','kndkwl','ckfnwlf','2026-07-15','Informatika','2026-07-22','2026-07-28',NULL,'rejected','fmjfi',3,'2026-07-27 02:50:20','2026-07-27 02:48:37','2026-07-27 02:50:20'),(9,26,'fgnef','gne','gjgo','vnd','fnei','2003-03-12','Informatika','0067-04-13','0678-05-24','dokumen_pengajuan/Xn5v15LgYJm11VWer12oNt7R15IINendmoXHso9i.png','approved',NULL,3,'2026-07-27 06:59:26','2026-07-27 04:13:36','2026-07-27 06:59:26'),(10,19,'ininugroho','jdnw','jscn','sjandka','jfje','0004-03-12','Bidang Informatika','0045-03-12','0456-03-12',NULL,'approved',NULL,14,'2026-07-31 08:08:02','2026-07-31 08:07:23','2026-07-31 08:08:02'),(11,33,'Nugroho1','nugroho123456','U','TI','nugroho','2026-07-29','Bidang Informatika','2026-02-01','2026-02-02','dokumen_pengajuan/plLbMEql3LCnxYL0Nhq4VE0kSKo3uH8Hr4Xn1IUm.png','approved',NULL,29,'2026-08-03 02:12:02','2026-08-03 02:10:41','2026-08-03 02:12:02'),(12,34,'nugroho2','nugroho123456','u','i','c','2026-07-28','Bidang Informatika','2026-08-03','2026-08-19',NULL,'approved',NULL,20,'2026-08-03 03:34:48','2026-08-03 03:33:38','2026-08-03 03:34:48');
/*!40000 ALTER TABLE `pengajuan_magang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portofolios`
--

DROP TABLE IF EXISTS `portofolios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portofolios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sertifikat_pkl_id` bigint unsigned NOT NULL,
  `user_pkl_id` bigint NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bidang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `portofolios_sertifikat_pkl_id_foreign` (`sertifikat_pkl_id`),
  KEY `portofolios_user_pkl_id_foreign` (`user_pkl_id`),
  CONSTRAINT `portofolios_sertifikat_pkl_id_foreign` FOREIGN KEY (`sertifikat_pkl_id`) REFERENCES `tbl_sertifikat_pkl` (`id_sertifikat`) ON DELETE CASCADE,
  CONSTRAINT `portofolios_user_pkl_id_foreign` FOREIGN KEY (`user_pkl_id`) REFERENCES `tbl_login` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portofolios`
--

LOCK TABLES `portofolios` WRITE;
/*!40000 ALTER TABLE `portofolios` DISABLE KEYS */;
/*!40000 ALTER TABLE `portofolios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sertifikat_handovers`
--

DROP TABLE IF EXISTS `sertifikat_handovers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sertifikat_handovers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_sertifikat` bigint unsigned NOT NULL,
  `from_user_id` bigint unsigned NOT NULL,
  `to_user_id` bigint unsigned NOT NULL,
  `status` enum('pending','accepted','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `accepted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sertifikat_handovers_id_sertifikat_foreign` (`id_sertifikat`),
  CONSTRAINT `sertifikat_handovers_id_sertifikat_foreign` FOREIGN KEY (`id_sertifikat`) REFERENCES `tbl_sertifikat_pkl` (`id_sertifikat`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sertifikat_handovers`
--

LOCK TABLES `sertifikat_handovers` WRITE;
/*!40000 ALTER TABLE `sertifikat_handovers` DISABLE KEYS */;
/*!40000 ALTER TABLE `sertifikat_handovers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('3F59Zu7ebhTueLdPUfBOP4nVrrxG91k2Jf0MvvmB',20,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTzV0THd1UVRzSDRQZlpiUmN2UXZzYXdIaU9RWlNpUDZlaTlYNlFDSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZW50b3IvcHJvZmlsZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjIwO3M6NzoiaWRfdXNlciI7aToyMDt9',1785738807),('ub0mQZOINtYHsz7baDaE930Wx01WRXkNzjHTBZqm',20,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaGlKbFcwZ0lQVDZIMHkyWEwwam1EeHhnME5jZ1AwQjJqczdSZGZrOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZW50b3IvdGFza3MvZG93bmxvYWQtc3VibWlzc2lvbi8xIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjA7czo3OiJpZF91c2VyIjtpOjIwO30=',1785729895);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_submissions`
--

DROP TABLE IF EXISTS `task_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `task_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `file_paths` json DEFAULT NULL,
  `status` enum('pending','submitted','graded','revision') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `grade` int DEFAULT NULL,
  `mentor_feedback` text COLLATE utf8mb4_unicode_ci,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `viewed_at` timestamp NULL DEFAULT NULL COMMENT 'Timestamp saat mentor melihat pengumpulan ini. NULL = belum dilihat',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_submissions_task_id_index` (`task_id`),
  KEY `task_submissions_student_id_index` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_submissions`
--

LOCK TABLES `task_submissions` WRITE;
/*!40000 ALTER TABLE `task_submissions` DISABLE KEYS */;
INSERT INTO `task_submissions` VALUES (1,12,34,'hvjj','[\"task_submissions/rAi9HkcKMAYGXJN1sE4aT0B37Vr55g2o4h37ThoE.png\"]','submitted',NULL,NULL,'2026-08-03 03:39:04',NULL,'2026-08-03 03:41:06','2026-08-03 03:39:04','2026-08-03 03:41:06');
/*!40000 ALTER TABLE `task_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sertifikat_pkl_id` bigint unsigned NOT NULL,
  `assigned_by` bigint DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `due_date` date DEFAULT NULL,
  `status` enum('pending','completed','in progress') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `catatan_izin` text COLLATE utf8mb4_unicode_ci COMMENT 'Catatan alasan ketika mentor memberi tugas ke siswa bukan bimbingannya (cross-bidang)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_sertifikat_pkl_id_foreign` (`sertifikat_pkl_id`),
  KEY `tasks_assigned_by_foreign` (`assigned_by`),
  CONSTRAINT `tasks_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `tbl_login` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_sertifikat_pkl_id_foreign` FOREIGN KEY (`sertifikat_pkl_id`) REFERENCES `tbl_sertifikat_pkl` (`id_sertifikat`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (1,101,3,'Test Edit via Real HTTP','Test','2026-08-15','pending',0,NULL,'2026-07-24 03:14:59','2026-07-29 06:43:21'),(2,101,3,'coba','cobacoba',NULL,'pending',0,NULL,'2026-07-24 03:15:05','2026-07-24 03:15:05'),(3,113,3,'coba','mmm',NULL,'pending',0,NULL,'2026-07-24 08:14:52','2026-07-24 08:14:52'),(4,113,3,'coba','ci',NULL,'pending',0,NULL,'2026-07-27 02:45:38','2026-07-27 02:45:38'),(5,3,NULL,'Laporan Mingguan 1','Buat laporan mingguan pertama tentang kegiatan magang yang telah dilakukan. Laporan harus mencakup: aktivitas harian, pembelajaran yang diperoleh, dan kendala yang dihadapi.','2026-08-05','pending',0,NULL,'2026-07-29 01:33:52','2026-07-29 01:33:52'),(9,106,NULL,'coba',NULL,'0056-04-13','pending',0,NULL,'2026-07-29 08:52:15','2026-07-29 08:52:15'),(10,113,NULL,'coba','ckmsl','0056-04-13','pending',0,NULL,'2026-07-30 00:59:36','2026-07-30 00:59:36'),(12,136,20,'hello','nflfls','2026-08-05','pending',0,NULL,'2026-08-03 03:36:32','2026-08-03 03:36:32');
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_login`
--

DROP TABLE IF EXISTS `tbl_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_login` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pass` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('siswa','mentor','sekretariat') COLLATE utf8mb4_general_ci NOT NULL,
  `bidang` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_sertifikat` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_login_id_sertifikat_unique` (`id_sertifikat`),
  CONSTRAINT `tbl_login_id_sertifikat_foreign` FOREIGN KEY (`id_sertifikat`) REFERENCES `tbl_sertifikat_pkl` (`id_sertifikat`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_login`
--

LOCK TABLES `tbl_login` WRITE;
/*!40000 ALTER TABLE `tbl_login` DISABLE KEYS */;
INSERT INTO `tbl_login` VALUES (1,'220302071','220302071@simgang.local','User 220302071','$2y$12$OF8ncKjUbQc7K8rNNKQ0COCvvLaVkvGDCy0gbDR.yMcLmdYbZofae','siswa',NULL,NULL),(3,'mentor1','mentor@simgang.local','Mentor Administrator','$2y$12$/lOLnW1dLW9OsvDdXZhkduePU9ePczWVvwURfZSV2SRot2.q0zRky','mentor','Bidang Informatika',NULL),(6,'15823','15823@simgang.local','User 15823','$2y$12$SSvYTq2pC5fV8.UZzJfKAOW.AE.KnOCPevlG/1w2WCYtBfzCa/DPq','siswa',NULL,NULL),(7,'15843','15843@simgang.local','User 15843','$2y$12$g0uQm9xsSklZmTn.o5eavOGSKOzv9sr1OleuKrUzHhGgt3e0xzW1K','siswa',NULL,NULL),(9,'mame','mame@simgang.local','User mame','$2y$12$DmzzO1t2o634z8YZYyla4OZYkVECaFrHElDbEjDrUbvE291Lclhou','mentor','Bidang Informatika',NULL),(10,'wkwkwkw','wkwkwkw@simgang.local','User wkwkwkw','$2y$12$cdYJIw5L3RIrqPyJ49Y3I..uy3vWueIzPWpDJoUL5OVsQpoHTjoTm','siswa',NULL,NULL),(11,'sekretariat1','sekretariat1@simgang.local','User sekretariat1','$2y$12$KvJKLfWz6uaM2uQZOdeAF.y7OSmtg1hq3jUt67nCRQS8DNtccVS76','sekretariat',NULL,NULL),(13,'2211102004','2211102004@simgang.local','User 2211102004','$2y$12$qjGfAnOX9bQQdCyOmbmiM.eEroG.m7dXDsOnl07rYEvWyEYtrvHqS','siswa',NULL,NULL),(14,'sekretariat','sekretariat@simgang.local','User sekretariat','$2y$12$mB4p7huj3DB6M3/Q/1A1y.Bjq6YOpSzfGPHgWjxYLqNZuy8vJbsdS','sekretariat',NULL,NULL),(15,'230102079','230102079@simgang.local','User 230102079','$2y$12$Y2xLf5Nmicft6TdLbRGxausLFpAQjwx9Y5s7JVNM3/Yt0SvZt/G0.','siswa',NULL,NULL),(16,'abdafiras','abdafiras@simgang.local','User abdafiras','$2y$12$fYkLbmtT/rP.zsscXmGz/.X51c3dE8WXbmkiKcDb8d8p9cOqopQJi','siswa',NULL,NULL),(17,'wakhid','wakhid@email.com','wakhid','$2y$12$5oaDiKzzgg9B19sjpkVxo.aK/tukfn9vH8b1RWUxopSy.fWWq7Fs6','siswa',NULL,NULL),(18,'xyz','xyz@email.com','cobaxyz','$2y$12$ftAQe2UZKQC.DbvpazFbqOyE01KFZVtN8l5CZa8qwIP3pU90soO9.','siswa',NULL,NULL),(19,'siswa_test','siswa@example.com','Siswa Test','$2y$12$RhDJSWttBVZxcLL0E6PJzeIdulJywmCR9N8d2IGeSAuF1RPcGPY6.','siswa',NULL,134),(20,'mentor_test','mentor@example.com','yo','$2y$12$AXLCXw0rrkjTp6y1lDPkZO7UuZg7HbeQ47ZodUGMkkEinVjHzrBKm','mentor','Bidang Informatika',NULL),(21,'sekretariat_test','sekretariat@example.com','Admin Sekretariat','$2y$12$MjrYykx.EqLpKoECnwPmxeMhHlIDZPWuQfXqhbVcqVjtczxtyBONq','sekretariat',NULL,NULL),(22,'budi','budi@email.com','budi','$2y$12$8lsChqLczHPoiyh2kAQi8uJeik7sF2c4Gmpt4ZyOl6g1U8Y.sh8wW','siswa',NULL,NULL),(23,'budi2','budi2@email.com','budi2','$2y$12$OUr8Of2OHbkISTOlrwDKS.E0yJpGpEEVC0ahoeuUMq3VJEpuEG0jG','siswa',NULL,NULL),(24,'budi31','budi3@email.com','budi3','$2y$12$iPcC1CFRtyGee6STd2BXWe4ZbQhKTzKL6BVnnhc83thna014SD0Lq','siswa',NULL,110),(29,'mentor_informatika','mentor_informatika@example.com','Ani Wijaya','$2y$12$ZCPbkkwYcX.kn/LEJ7eUB.nPuwrG/E31RJ9b4pfw6g5FWZhxo4A3u','mentor','Bidang Informatika',NULL),(30,'mentor_statistik','mentor_statistik@example.com','Cahyo Pratama','$2y$12$V6dOgY14ioahubK5y3YukuWjdPM.3qUpezWF0EkdCfV/Z6.H2psyq','mentor','Bidang Statistik dan Persandian',NULL),(33,'nugroho1','nugroho1@gmail.com','Nugroho1','$2y$12$NVMjlZNIDh40mgqw5gtPLODYi5UXzNDzyeTPVzFG7Q3zCsXIAlfnm','siswa',NULL,135),(34,'nugroho2','nugroho2@gmail.com','nugroho2','$2y$12$4yivbBlj0JPheRgvqX28aOJ5pIPDXtOJ2LuAcvZNFnuq.uWGr80.C','siswa',NULL,136);
/*!40000 ALTER TABLE `tbl_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_penilaian`
--

DROP TABLE IF EXISTS `tbl_penilaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_penilaian` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `aspek` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_parent` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_parent` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nilai` int DEFAULT NULL,
  `ket` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_sertifikat` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=236 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_penilaian`
--

LOCK TABLES `tbl_penilaian` WRITE;
/*!40000 ALTER TABLE `tbl_penilaian` DISABLE KEYS */;
INSERT INTO `tbl_penilaian` VALUES (14,'Kemampuan teknis pada bidang Informatika','','',90,'A',3),(15,'Kemampuan adaptasi dengan lingkungan kerja','','',95,'A',3),(16,'Kemampuan komunikasi dengan atasan dan karyawan','','',95,'A',3),(17,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',92,'A',3),(18,'Kemampuan administrasi dokumen','','',90,'A',3),(19,'Kedisiplinan / Kehadiran','','',95,'A',3),(20,'Sikap dan Perilaku','','',92,'A',3),(21,'Kemampuan teknis pada bidang Informatika','','',90,'A',16),(22,'Kemampuan adaptasi dengan lingkungan kerja','','',87,'B',16),(23,'Kemampuan komunikasi dengan atasan dan karyawan','','',90,'A',16),(24,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',88,'B',16),(25,'Kemampuan administrasi dokumen','','',85,'B',16),(26,'Kedisiplinan / Kehadiran','','',95,'A',16),(27,'Sikap dan Perilaku','','',95,'A',16),(28,'Kemampuan teknis pada bidang Informatika','','',90,'A',17),(29,'Kemampuan adaptasi dengan lingkungan kerja','','',90,'A',17),(30,'Kemampuan komunikasi dengan atasan dan karyawan','','',90,'A',17),(31,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',95,'A',17),(32,'Kemampuan administrasi dokumen','','',85,'B',17),(33,'Kedisiplinan / Kehadiran','','',95,'A',17),(34,'Sikap dan Perilaku','','',95,'A',17),(35,'Kemampuan teknis pada bidang Informatika','','',95,'A',18),(36,'Kemampuan adaptasi dengan lingkungan kerja','','',90,'A',18),(37,'Kemampuan komunikasi dengan atasan dan karyawan','','',95,'A',18),(38,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',95,'A',18),(39,'Kemampuan administrasi dokumen','','',85,'B',18),(40,'Kedisiplinan / Kehadiran','','',95,'A',18),(41,'Sikap dan Perilaku','','',95,'A',18),(42,'Kemampuan teknis pada bidang TKJ ( Teknik Komputer dan Jaringan)','','',98,'A',23),(43,'Kemampuan adaptasi dengan lingkungan kerja','','',97,'A',23),(44,'Kemampuan komunikasi dengan atasan dan karyawan','','',97,'A',23),(45,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',98,'A',23),(46,'Kemampuan administrasi dokumen','','',97,'A',23),(47,'Kedisiplinan / Kehadiran','','',98,'A',23),(48,'Sikap dan Perilaku','','',98,'A',23),(49,'Kemampuan teknis pada bidang TKJ ( Teknik Komputer dan Jaringan)','','',98,'A',24),(50,'Kemampuan adaptasi dengan lingkungan kerja','','',97,'A',24),(51,'Kemampuan komunikasi dengan atasan dan karyawan','','',97,'A',24),(52,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',98,'A',24),(53,'Kemampuan administrasi dokumen','','',97,'A',24),(54,'Kedisiplinan / Kehadiran','','',98,'A',24),(55,'Sikap dan Perilaku','','',98,'A',24),(56,'Kemampuan teknis pada bidang TKJ ( Teknik Komputer dan Jaringan)','','',98,'A',25),(57,'Kemampuan adaptasi dengan lingkungan kerja','','',97,'A',25),(58,'Kemampuan komunikasi dengan atasan dan karyawan','','',97,'A',25),(59,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',98,'A',25),(60,'Kemampuan administrasi dokumen','','',97,'A',25),(61,'Kedisiplinan / Kehadiran','','',98,'A',25),(62,'Sikap dan Perilaku','','',98,'A',25),(63,'Kemampuan teknis pada bidang TKJ ( Teknik Komputer dan Jaringan)','','',98,'A',26),(64,'Kemampuan adaptasi dengan lingkungan kerja','','',97,'A',26),(65,'Kemampuan komunikasi dengan atasan dan karyawan','','',97,'A',26),(66,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',98,'A',26),(67,'Kemampuan administrasi dokumen','','',97,'A',26),(68,'Kedisiplinan / Kehadiran','','',98,'A',26),(69,'Sikap dan Perilaku','','',98,'A',26),(70,'Kemampuan teknis pada bidang TKJ ( Teknik Komputer dan Jaringan)','','',88,'B',27),(71,'Kemampuan adaptasi dengan lingkungan kerja','','',92,'A',27),(72,'Kemampuan komunikasi dengan atasan dan karyawan','','',90,'A',27),(73,'Kemampuan administrasi dokumen','','',90,'A',27),(74,'Kedisiplinan / Kehadiran','','',90,'A',27),(75,'Sikap dan Perilaku','','',90,'A',27),(76,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',88,'B',27),(77,'Kemampuan teknis pada bidang TKJ ( Teknik Komputer dan Jaringan)','','',88,'B',28),(78,'Kemampuan adaptasi dengan lingkungan kerja','','',92,'A',28),(79,'Kemampuan komunikasi dengan atasan dan karyawan','','',90,'A',28),(80,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',88,'B',28),(81,'Kemampuan administrasi dokumen','','',90,'A',28),(82,'Kedisiplinan / Kehadiran','','',90,'A',28),(83,'Sikap dan Perilaku','','',90,'A',28),(84,'Kemampuan teknis pada bidang TKJ ( Teknik Komputer dan Jaringan)','','',88,'B',29),(85,'Kemampuan adaptasi dengan lingkungan kerja','','',92,'A',29),(86,'Kemampuan komunikasi dengan atasan dan karyawan','','',90,'A',29),(87,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',88,'B',29),(88,'Kemampuan administrasi dokumen','','',90,'A',29),(89,'Kedisiplinan / Kehadiran','','',90,'A',29),(90,'Sikap dan Perilaku','','',90,'A',29),(91,'Kemampuan teknis pada bidang TKJ ( Teknik Komputer dan Jaringan)','','',88,'B',30),(92,'Kemampuan adaptasi dengan lingkungan kerja','','',92,'A',30),(93,'Kemampuan komunikasi dengan atasan dan karyawan','','',90,'A',30),(94,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',88,'B',30),(95,'Kemampuan administrasi dokumen','','',90,'A',30),(96,'Kedisiplinan / Kehadiran','','',90,'A',30),(97,'Sikap dan Perilaku','','',90,'A',30),(98,'Kehadiran','','',95,'A',31),(99,'Kedisiplinan','','',95,'A',31),(100,'Tanggung Jawab','','',95,'A',31),(101,'Ketekunan','','',90,'A',31),(102,'Sopan Santun','','',90,'A',31),(103,'Persiapan Kerja: memahami tugas pekerjaan, menganalisi tugas, persiapan tools dan environment dengan benar.','','',95,'A',31),(104,'Hasil Kerja: kualitas hasil pekerjaan, dokumentasi teknis tersedia dan dapat dipahami oleh rekan tim, ketepatan waktu dalam menyelesaikan tugas. *','','',90,'A',31),(105,'Kerja sama/membantu dalam menyelesaikan tugas','','',90,'A',31),(106,'Hubungan dengan atasan, pembimbing lapangan, karyawan','','',95,'A',31),(107,'Mampu menerima kritik dan masukan dengan sikap terbuka.','','',93,'A',31),(108,'Pelaksanaan Kerja: bekerja secara sistematis berdasarkan alur kerja yang dirancang, menulis kode yang rapi dan terdokumentasi dengan baik. *','','',90,'A',31),(109,'Kemampuan teknis pada bidang Informatika','','',90,'A',32),(110,'Kemampuan adaptasi dengan lingkungan kerja','','',95,'A',32),(111,'Kemampuan komunikasi dengan atasan dan karyawan','','',95,'A',32),(112,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',90,'A',32),(113,'Kemampuan administrasi dokumen','','',90,'A',32),(114,'Kedisiplinan / Kehadiran','','',95,'A',32),(115,'Sikap dan Perilaku','','',95,'A',32),(116,'Kemampuan teknis pada bidang TKJ (Teknik Komputer dan Jaringan)','','',96,'A',44),(117,'Kemampuan adaptasi dengan lingkungan kerja','','',95,'A',44),(118,'Kemampuan komunikasi dengan atasan dan karyawan','','',98,'A',44),(119,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',96,'A',44),(120,'Kemampuan administrasi dokumen','','',94,'A',44),(121,'Kedisiplinan / Kehadiran','','',94,'A',44),(122,'Sikap dan Perilaku','','',98,'A',44),(123,'Kemampuan teknis pada bidang TKJ (Teknik Komputer dan Jaringan)','','',96,'A',42),(124,'Kemampuan adaptasi dengan lingkungan kerja','','',95,'A',42),(125,'Kemampuan komunikasi dengan atasan dan karyawan','','',98,'A',42),(126,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',96,'A',42),(127,'Kemampuan administrasi dokumen','','',94,'A',42),(128,'Kedisiplinan / Kehadiran','','',94,'A',42),(129,'Sikap dan Perilaku','','',98,'A',42),(130,'Kemampuan teknis pada bidang TKJ (Teknik Komputer dan Jaringan)','','',96,'A',41),(131,'Kemampuan adaptasi dengan lingkungan kerja','','',95,'A',41),(132,'Kemampuan komunikasi dengan atasan dan karyawan','','',98,'A',41),(133,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',96,'A',41),(134,'Kemampuan administrasi dokumen','','',94,'A',41),(135,'Kedisiplinan / Kehadiran','','',98,'A',41),(136,'Sikap dan Perilaku','','',98,'A',41),(137,'Kemampuan teknis pada bidang TKJ (Teknik Komputer dan Jaringan)','','',86,'B',43),(138,'Kemampuan adaptasi dengan lingkungan kerja','','',86,'B',43),(139,'Kemampuan komunikasi dengan atasan dan karyawan','','',84,'B',43),(140,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',84,'B',43),(141,'Kemampuan administrasi dokumen','','',94,'A',43),(142,'Kedisiplinan / Kehadiran','','',86,'B',43),(143,'Sikap dan Perilaku','','',84,'B',43),(144,'Kedisiplinan','','',95,'A',38),(145,'Kemampuan beradaptasi dengan Lingkungan','','',91,'A',38),(146,'Tanggung Jawab Terhadap Tugas','','',95,'A',38),(147,'Kemandirian','','',90,'A',38),(148,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',95,'A',38),(149,'Kemampuan administrasi dokumen','','',93,'A',38),(150,'Sikap dan Perilaku','','',95,'A',38),(151,'Kedisiplinan','','',95,'A',40),(152,'Kemampuan beradaptasi dengan Lingkungan','','',95,'A',40),(153,'Tanggung Jawab Terhadap Tugas','','',95,'A',40),(154,'Kemandirian','','',95,'A',40),(155,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',90,'A',40),(156,'Kemampuan administrasi dokumen','','',90,'A',40),(157,'Sikap dan Perilaku','','',90,'A',40),(158,'Kedisiplinan','','',95,'A',39),(159,'Kemampuan beradaptasi dengan Lingkungan','','',89,'A',39),(160,'Tanggung Jawab Terhadap Tugas','','',93,'A',39),(161,'Kemandirian','','',90,'A',39),(162,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',89,'A',39),(163,'Kemampuan administrasi dokumen','','',90,'A',39),(164,'Sikap dan Perilaku','','',91,'A',39),(165,'Kedisiplinan','','',95,'A',34),(166,'Kemampuan beradaptasi dengan Lingkungan','','',95,'A',34),(167,'Tanggung Jawab Terhadap Tugas','','',95,'A',34),(168,'Kemandirian','','',95,'A',34),(169,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',90,'A',34),(170,'Kemampuan administrasi dokumen','','',90,'A',34),(171,'Sikap dan Perilaku','','',90,'A',34),(172,'Kedisiplinan','','',95,'A',35),(173,'Kemampuan beradaptasi dengan Lingkungan','','',95,'A',35),(174,'Tanggung Jawab Terhadap Tugas','','',95,'A',35),(175,'Kemandirian','','',95,'A',35),(176,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',90,'A',35),(177,'Kemampuan administrasi dokumen','','',90,'A',35),(178,'Sikap dan Perilaku','','',90,'A',35),(179,'Kedisiplinan','','',95,'A',33),(180,'Kemampuan beradaptasi dengan Lingkungan','','',89,'A',33),(181,'Tanggung Jawab Terhadap Tugas','','',94,'A',33),(182,'Kemandirian','','',89,'A',33),(183,'Kemampuan analisis, pemahaman, dan pemecahan masalah','','',89,'A',33),(184,'Kemampuan administrasi dokumen','','',90,'A',33),(185,'Sikap dan Perilaku','','',92,'A',33),(186,'akademis',NULL,'1',100,'AB',19),(187,'Tanggung Jawab Terhadap Tugas','186','-',NULL,NULL,19),(192,'Komunikasi','186','1',95,'A',47),(193,'Kejujuran','186','1',97,'A',47),(194,'Kedisiplinan','186','1',92,'A',47),(195,'Tanggung Jawab','186','1',95,'A',47),(196,'Etos Kerja','186','1',95,'A',47),(197,'Kemandirian','186','1',96,'A',47),(198,'Kepedulian Sosial dan Lingkungan','186','1',91,'A',47),(199,'Ketaatan Terhadap Norma','186','1',97,'A',47),(200,'Menggunakan Peralatan Kantor','186','1',93,'A',47),(201,'Pengurusan Dokumen Masuk dan Keluar','186','1',94,'A',47),(202,'Pelayanan Kepada Kolega dan Pelanggan','186','1',89,'B',47),(203,'Menggunakan Aplikasi Komputer','186','1',95,'A',47),(204,'Mengaplikasikan Penyelenggaraan Rapat','186','1',89,'B',47),(205,'Pengurusan Dokumen Keuangan','186','1',89,'B',47),(206,'Komunikasi','186','1',95,'A',49),(207,'Kejujuran','186','1',97,'A',49),(208,'Kedisiplinan','186','1',92,'A',49),(209,'Tanggung Jawab','186','1',95,'A',49),(210,'Etos Kerja','186','1',95,'A',49),(211,'Kemandirian','186','1',96,'A',49),(212,'Kepedulian Sosial dan Lingkungan','186','1',91,'A',49),(213,'Ketaatan Terhadap Norma','186','1',97,'A',49),(214,'Menggunakan Peralatan Kantor','186','1',93,'A',49),(215,'Pengurusan Dokumen Masuk dan Keluar','186','1',94,'A',49),(216,'Pelayanan Kepada Kolega dan Pelanggan','186','1',89,'B',49),(217,'Menggunakan Aplikasi Komputer','186','1',95,'A',49),(218,'Mengaplikasikan Penyelenggaraan Rapat','186','1',89,'B',49),(219,'Pengurusan Dokumen Keuangan','186','1',89,'B',49),(220,'Menyelesaikan setiap tugas yang diberikan oleh pembimbing lapangan.','186','-',93,'A',97),(221,'Mampu menjalin kerjasama dalam tim, peka akan kebutuhan orang lain dan memberikan kontribusi dalam aktivitas tim untuk mencapai tujuan dan hasil yang positif.','186','-',90,'A',97),(222,'Kemampuan untuk menyampaikan informasi, mendengarkan orang lain dan berkomunikasi secara efektif dan memberikan respon positif yang mendorong komunikasi terbuka.','186','-',89,'A',97),(223,'Merespon masalah secara proaktif dan gigih, menjajaki kesempatan yang ada, melakukan sesuatu tanpa disuruh guna mengatasi hambatan, yang ditampilkan secara motorik/verbal (yang berkonsekuen berupa tindakan).','186','-',88,'A',97),(224,'Kemauan untuk mematuhi aturan yang berlaku dan dapat menyesuaikan perilaku agar dapat bekerja secara efektif dan efisien saat adanya informasi baru, perubahan situasi atau kondisi lingkungan kerja yang berbeda.','186','-',90,'A',97),(225,'coba',NULL,NULL,1000,'A',101),(226,'coba',NULL,NULL,100,'A',101),(227,'coba',NULL,NULL,101,'A',111),(228,'coba',NULL,NULL,101,'A',111),(229,'coba',NULL,NULL,101,'B',91),(230,'Kedisiplinan',NULL,NULL,10000,'A',110),(231,'Kedisiplinan',NULL,NULL,123,'A',123),(233,'Kerjasama Tim',NULL,NULL,0,'E',123),(234,'fkwf',NULL,NULL,1,'E',127),(235,'1',NULL,NULL,1,'E',113);
/*!40000 ALTER TABLE `tbl_penilaian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_sertifikat_pkl`
--

DROP TABLE IF EXISTS `tbl_sertifikat_pkl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_sertifikat_pkl` (
  `id_sertifikat` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `asal_sekolah` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nim_nis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tempat_lahir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tgl_lahir` date NOT NULL,
  `jurusan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `tgl_sertifikat` date NOT NULL,
  `nm_pembimbing` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nik_pembimbing` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `jabatan_pembimbing` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `jabatan_pegawai` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pejabat_ttd` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bidang` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `no_sertifikat` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nm_kadis` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sts_kadis` int NOT NULL,
  `nip_kadis` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `aprove_pembimbing` int NOT NULL,
  `approve_kadis` int NOT NULL,
  `sts_diambil` int NOT NULL,
  `created_id` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_by_role` enum('mentor','sekretariat') COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Role yang membuat sertifikat',
  `sertifikat_status` enum('draft','ready_for_handover','handed_over','printed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft' COMMENT 'Status sertifikat dalam workflow',
  `handed_to_mentor_id` bigint unsigned DEFAULT NULL COMMENT 'ID Mentor yang menerima sertifikat dari Sekretariat',
  `handed_over_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu sertifikat diserahkan ke Mentor',
  `foto_profil_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_sertifikat`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_sertifikat_pkl`
--

LOCK TABLES `tbl_sertifikat_pkl` WRITE;
/*!40000 ALTER TABLE `tbl_sertifikat_pkl` DISABLE KEYS */;
INSERT INTO `tbl_sertifikat_pkl` VALUES (3,'Tegar Panggalih','PNC','220302071','-','2004-03-20','D3 Teknik Informatika','2024-08-05','2024-12-31','2025-01-02','Slamet Arifin,A.md.T.','19930420 202202 1 001','Staff','Kepala Dinas Komunikasi dan Informatika','Kepala Dinas','Bidang Informatika','400.14.5.4/0001/33','SUPRIYANTO, S.H.,M.Si.',1,'19650825 199403 1 009',1,1,1,'3',NULL,'draft',NULL,NULL,NULL),(16,'LUQMAN ZAIN KHILMY','Universitas Telkom Purwokerto','21104032','Cilacap','2004-03-27','Rekayasa Perangkat Lunak','2024-07-15','2024-09-01','2024-09-10','Slamet Arifin,A.md.T.','19930420 202202 1 001','Operator Layanan Operasional (5)',NULL,NULL,'Bidang Informatika','400.14.5.4/1335/33','SUPRIYANTO, S.H.,M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(17,'Fa\'iq Fawwaz','Universitas Ahmad Dahlan','2100018041','Cilacap','2002-11-10','Informatika','2024-09-19','2024-12-18','2024-12-20','Slamet Arifin,A.md.T.','19930420 202202 1 001','Operator Layanan Operasional (5)',NULL,NULL,'Bidang Informatika','400.14.5.4/1971/33','SUPRIYANTO, S.H.,M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(18,'Melisa Galuh Parwati','Universitas Pendidikan Indonesia','2100750','Cilacap','2004-05-15','Mekatronika dan Kecerdasan Buatan','2024-09-09','2024-12-09','2024-12-10','Slamet Arifin,A.md.T.','19930420 202202 1 001','Operator Layanan Operasional (5)',NULL,NULL,'Bidang Informatika','400.14.5.4/1867/33','SUPRIYANTO, S.H.,M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(23,'TRISTA FAUZIYAH','SMK NEGERI 1 CILACAP','15843','Cilacap','2007-07-03','Teknik Jaringan Komputer dan Telekomunikasi','2024-09-02','2025-02-28','2025-03-03','YUNI WAHYU UTOMO, S.Kom','-','TENAGA AHLI EXPERT JARINGAN',NULL,NULL,'Bidang Informatika','400.14.5.4/0361/33','SUPRIYANTO, S.H., M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(24,'ISTIANA','SMK NEGERI 1 CILACAP','15823','Cilacap','2007-04-10','Teknik Jaringan Komputer dan Telekomunikasi','2024-09-02','2025-02-28','2025-03-03','YUNI WAHYU UTOMO, S.Kom','-','TENAGA AHLI EXPERT JARINGAN',NULL,NULL,'Bidang Informatika','400.14.5.4/0362/33','SUPRIYANTO, S.H., M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(25,'RAJA UBAID FAWWAZ','SMK NEGERI 1 CILACAP','15833','Malang','2007-09-05','Teknik Jaringan Komputer dan Telekomunikasi','2024-09-02','2025-02-28','2025-03-03','YUNI WAHYU UTOMO, S.Kom','-','TENAGA AHLI EXPERT JARINGAN',NULL,NULL,'Bidang Informatika','400.14.5.4/0359/33','SUPRIYANTO, S.H., M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(26,'AIMAR NUR HIDAYAT','SMK NEGERI 1 CILACAP','15810','Cilacap','2007-12-31','Teknik Jaringan Komputer dan Telekomunikasi','2024-09-02','2025-02-28','2025-03-03','YUNI WAHYU UTOMO, S.Kom','-','TENAGA AHLI EXPERT JARINGAN',NULL,NULL,'Bidang Informatika','400.14.5.4/0360/33','SUPRIYANTO, S.H., M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(27,'META MERIDA','SMK NEGERI 1 BINANGUN','225266','Cilacap','2006-04-29','Teknik Jaringan Komputer dan Telekomunikasi','2024-12-23','2025-04-26','2025-04-28','YUNI WAHYU UTOMO, S.Kom','-','TENAGA AHLI EXPERT JARINGAN',NULL,NULL,'Bidang Informatika','400.14.5.4/0647/33','SUPRIYANTO, S.H., M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(28,'INTAN PURNAMA SARI','SMK NEGERI 1 BINANGUN','225238','Cilacap','2007-01-22','Teknik Jaringan Komputer dan Telekomunikasi','2024-12-23','2025-04-25','2025-04-28','YUNI WAHYU UTOMO, S.Kom','-','TENAGA AHLI EXPERT JARINGAN',NULL,NULL,'Bidang Informatika','400.14.5.4/0645/33','SUPRIYANTO, S.H., M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(29,'MEY LINA KHOIRUNNISA','SMK NEGERI 1 BINANGUN','225267','Cilacap','2007-05-22','Teknik Jaringan Komputer dan Telekomunikasi','2024-12-23','2025-04-25','2025-04-28','YUNI WAHYU UTOMO, S.Kom','-','TENAGA AHLI EXPERT JARINGAN',NULL,NULL,'Bidang Informatika','400.14.5.4/0648/33','SUPRIYANTO, S.H., M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(30,'JOHARA AGUSTIN','SMK NEGERI 1 BINANGUN','225243','Cilacap','2007-08-24','Teknik Jaringan Komputer dan Telekomunikasi','2024-12-23','2025-04-25','2025-04-28','YUNI WAHYU UTOMO, S.Kom','-','TENAGA AHLI EXPERT JARINGAN',NULL,NULL,'Bidang Informatika','400.14.5.4/0646/33','SUPRIYANTO, S.H., M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(31,'IS\'AD MAULANA','Universitas Negeri Semarang','4611422114','Cilacap','2004-05-04','Teknik Informatika','2025-02-10','2025-07-10','2025-07-14','Slamet Arifin, A.Md.T.','19930420 202202 1 001','Operator Layanan Operasional',NULL,NULL,'Bidang Informatika','400.14.5.4/1029/33','Supriyanto,S.H.,M.Si.',1,'19650825 199403 1 009',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(32,'RULI ATHALLAH YAHYA','Universitas Telkom Purwokerto','2211102004','Cilacap','2004-04-13','Teknik Informatika','2025-07-01','2025-08-12','2025-08-12','SLAMET ARIFIN, A.Md. T.','199304202022021001','Operator Layanan Operasional',NULL,NULL,'Bidang Informatika','400.14.5.4/1165/33','SUPRIYANTO, S.H.,M.Si.',1,'19650825 199403 1 009',1,1,1,'3',NULL,'draft',NULL,NULL,NULL),(33,'Eka Adit Prasetyo','Politeknik Negeri Cilacap','230102079','Cilacap','2005-06-08','Komputer dan Bisnis','2025-08-18','2025-12-15','2025-12-15','Slamet Arifin, A.Md.T.','199304202022021001','Operator Layanan Operasional',NULL,'Kepala Dinas','Bidang Informatika','400.14.5.4/1829/33','BUDDY HARYANTO, S.Sos., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(34,'Aqila Rakhsa Fadzila Tunisa','Politeknik Negeri Cilacap','230102076','Cilacap','2006-02-20','Komputer dan Bisnis','2025-08-18','2025-12-15','2025-12-15','Eddo Ertino, S.Kom.','19890405202511048','PRANATA KOMPUTER AHLI PERTAMA',NULL,'Kepala Dinas','Bidang Informatika','400.14.5.4/1827/33','BUDDY HARYANTO, S.Sos., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(35,'Destri Amelia Harsanti','Politeknik Negeri Cilacap','230202078','Cilacap','2005-12-03','Komputer dan Bisnis','2025-08-18','2025-12-15','2025-12-15','Eddo Ertino, S.Kom.','19890405202511048','PRANATA KOMPUTER AHLI PERTAMA',NULL,'Kepala Dinas','Bidang Informatika','400.14.5.4/1826/33','BUDDY HARYANTO, S.Sos., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(36,'Mega Aryani','SMK Bina Bhakti Cilacap','1877','Cilacap','2008-06-26','Akuntansi Keuangan & Lembaga','2025-07-30','2025-08-30','2025-08-30','YUDDY HERMAWAN, S.Sos., M.M.','197802042003121008','KASUBBAG UMUM DAN KEPEGAWAIAN',NULL,NULL,'Sekretariat','PKL/2025/09/001','Ferry Adhi Dharma. S.T., M.Si',0,'19770209 200312 1 006',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(37,'Yanes Reksa Rahma Dhani','SMK Bina Bhakti Cilacap','1901','Cilacap','2007-10-15','Menejemen Perkantoran & Layanan Bisnis','2025-07-30','2025-08-30','2025-08-30','YUDDY HERMAWAN, S.Sos., M.M.','197802042003121008','KASUBBAG UMUM DAN KEPEGAWAIAN',NULL,NULL,'Sekretariat','PKL/2025/09/001','Ferry Adhi Dharma. S.T., M.Si',0,'19770209 200312 1 006',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(38,'Septiana Lestari','Politeknik Negeri Cilacap','230202089','Cilacap','2005-09-09','Komputer dan Bisnis','2025-08-18','2025-12-15','2025-12-15','Slamet Arifin, A.Md.T.','199304202022021001','Operator Layanan Operasional',NULL,'Kepala Dinas','Bidang Informatika','400.14.5.4/1825/33','BUDDY HARYANTO, S.Sos., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(39,'Elyza Restiana','Politeknik Negeri Cilacap','230302080','Cilacap','2005-09-26','Komputer dan Bisnis','2025-08-18','2025-12-15','2025-12-15','Slamet Arifin, A.Md.T.','199304202022021001','Operator Layanan Operasional',NULL,'Kepala Dinas','Bidang Informatika','400.14.5.4/1824/33','BUDDY HARYANTO, S.Sos., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(40,'Fatimah Azzahra','Politeknik Negeri Cilacap','230102081','Cilacap','2005-10-25','Komputer dan Bisnis','2025-08-18','2025-12-15','2025-12-15','Eddo Ertino, S.Kom.','19890405202511048','PRANATA KOMPUTER AHLI PERTAMA',NULL,'Kepala Dinas','Bidang Informatika','400.14.5.4/1823/33','BUDDY HARYANTO, S.Sos., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(41,'DWIKI ANDHIKA','SMK DARUSSALAM KARANGPUCUNG','23247743','Cilacap','2007-07-12','Teknik Jaringan Komputer dan Telekomunikasi','2025-07-01','2025-11-28','2025-11-28','YUNI WAHYU UTOMO, S.Kom.','-','Tenaga Ahli Jaringan',NULL,'Kepala Dinas','Bidang Informatika','400.14.5.4/1750/33','BUDDY HARYANTO, S.SOS., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(42,'MUHAMMAD ILHAM NURKHOLIQ','SMK DARUSSALAM KARANGPUCUNG','23247856','Cilacap','2008-06-01','Teknik Jaringan Komputer dan Telekomunikasi','2025-07-01','2025-11-28','2025-11-28','YUNI WAHYU UTOMO, S.Kom.','-','Tenaga Ahli Jaringan',NULL,'Kepala Dinas','Bidang Informatika','400.14.5.4/1751/33','BUDDY HARYANTO, S.SOS., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(43,'RIDHO WARIHSAN','SMK DARUSSALAM KARANGPUCUNG','23247918','Cilacap','2008-07-21','Teknik Jaringan Komputer dan Telekomunikasi','2025-07-01','2025-11-28','2025-11-28','YUNI WAHYU UTOMO, S.Kom.','-','Tenaga Ahli Jaringan',NULL,'Kepala Dinas','Bidang Informatika','400.14.5.4/1752/33','BUDDY HARYANTO, S.SOS., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(44,'VIDO RIFKY DENIRO','SMK DARUSSALAM KARANGPUCUNG','23248012','Cilacap','2008-05-15','Teknik Jaringan Komputer dan Telekomunikasi','2025-07-01','2025-11-28','2025-11-28','YUNI WAHYU UTOMO, S.Kom.','-','Tenaga Ahli Jaringan',NULL,'Kepala Dinas','Bidang Informatika','400.14.5.4/1753/33','BUDDY HARYANTO, S.SOS., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(47,'Alin Rosiana','SMK Muhammadiyah 1 Ajibarang','11499','Banyumas','2008-07-07','Teknik Komputer dan Jaringan','2025-10-20','2026-01-30','2026-01-30','Sevan Dwi Kusuma','199909062022021002','Operator Layanan Operasional','Kepala Dinas Komunikasi dan Informatika','Kepala Dinas','Bidang Informatika','400.14.5.4/0170/33','BUDDY HARYANTO, S.Sos., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(49,'Ratu Ayu Kartini','SMK Muhammadiyah 1 Ajibarang','11518','Bandung','2008-04-21','Teknik Komputer dan Jaringan','2025-10-20','2026-01-30','2026-01-30','Sevan Dwi Kusuma','199909062022021002','Operator Layanan Operasional','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','400.14.5.4/0171/33','BUDDY HARYANTO, S.Sos., M.M.',1,'197112031998031002',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(50,'Aini Farah Azahra','SMK N 1 Cilacap','16708','Cilacap','2008-11-18','Akuntansi dan Keuangan Lembaga','2025-11-05','2026-02-28','2026-02-28','Yuddy Hermawan, S.Sos., M.M.','19780204 200312 1 008','Kasubbag Umum dan Kepegawaian','Kepala Dinas Komunikasi dan Informatika','Kepala Dinas','Sekretariat','masih ada','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(51,'Nila Alfiaturochmah','SMK N 1 Cilacap','16799','Cilacap','2007-12-14','Akuntansi dan Keuangan Lembaga','2025-11-05','2026-02-28','2026-02-28','Yuddy Hermawan, S.Sos., M.M.','19780204 200312 1 008','Kasubbag Umum dan Kepegawaian','Kepala Dinas Komunikasi dan Informatika','Kepala Dinas','Sekretariat','masih ada','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(52,'Fina Roihatul Jannah','SMKN 1 Cilacap','-','Cilacap','2008-05-13','Teknik Jaringan Komputer dan Telekomunikasi','2025-11-05','2026-02-28','2026-02-28','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(53,'Nanda Syifa fauzia','SMKN 1 Cilacap','-','Cilacap','2008-10-27','Desain Komunikasi Visual','2025-11-05','2026-02-28','2026-02-28','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(54,'Juan Luanda Sitompul','SMKN 1 Cilacap','-','Cilacap','2008-06-12','Desain Komunikasi Visual','2025-11-05','2026-02-28','2026-02-28','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(55,'Elang Mahardika H','SMKN 1 Cilacap','-','Cilacap','2008-08-06','Desain Komunikasi Visual','2025-11-05','2026-02-28','2026-02-28','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(56,'Denis Farrel Putra P','SMKN 1 Cilacap','-','Cilacap','2008-09-16','Desain Komunikasi Visual','2025-11-05','2026-02-28','2026-02-28','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(57,'Feri Ages Refangga','UNUGHA CILACAP','-','Cilacap','2004-06-16','Informatika','2025-11-17','2026-01-09','2026-01-09','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(58,'Alfiah Khusnaeni','UNUGHA CILACAP','-','Cilacap','2004-08-10','Informatika','2025-11-17','2026-01-09','2026-01-09','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','0000','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(59,'Melysa Nur Intan Sari','SMK N 1 Binangun','-','Binangun','2008-06-11','Teknik Jaringan Komputer dan Telekomunikasi','2025-12-01','2026-04-30','2026-04-30','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(60,'Deswita Palupianty','SMKN 1 Cilacap','16461','Cilacap','2008-02-01','Teknik Jaringan Komputer dan Telekomunikasi','2025-11-05','2026-02-27','2026-02-27','Sevan Dwi Kusuma, A.Md.T','199909062022021002','Operator Layanan Operasional','Kepala Dinas Komunikasi dan Informatika','Kepala Dinas','Bidang Informatika','400.14.5.4/0298/33','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(61,'Mawar Siti Azizah','SMK N 1 Binangun','-','Binangun','2008-01-22','Teknik Jaringan Komputer dan Telekomunikasi','2025-12-01','2026-04-30','2026-04-30','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(62,'Icha Roro Wening Ingtiyas','SMK N 1 Binangun','-','Binangun','2008-10-20','Teknik Jaringan Komputer dan Telekomunikasi','2025-12-01','2026-04-30','2026-04-30','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(63,'Frency Insya Nurrahmah','SMK N 1 Binangun','-','Binangun','2008-06-01','Teknik Jaringan Komputer dan Telekomunikasi','2025-12-01','2026-04-30','2026-04-30','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(64,'Keisya Elmira','SMK N 1 Banyumas','-','Banyumas','2008-03-18','Teknik Jaringan Komputer dan Telekomunikasi','2025-12-01','2026-04-06','2026-04-06','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(65,'Andhika Rasya Putra Pratama','SMK N 1 Purwokerto','-','Purwokerto','2008-02-05','Teknik Jaringan Komputer dan Telekomunikasi','2025-12-01','2026-04-01','2026-04-01','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(66,'Apik Rendi Firmansyah','SMK N 1 Purwokerto','-','Purwokerto','2008-05-06','Teknik Jaringan Komputer dan Telekomunikasi','2025-12-01','2026-04-01','2026-04-01','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(67,'Mohammad Reza Ardiansyah','SMK N 1 Purwokerto','-','Purwokerto','2008-07-03','Teknik Jaringan Komputer dan Telekomunikasi','2025-12-01','2026-04-01','2026-04-01','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(68,'Raffa Aditya Nugroho','SMK N 1 Purwokerto','-','Purwokerto','2008-11-16','Teknik Jaringan Komputer dan Telekomunikasi','2025-12-01','2026-04-01','2026-04-01','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(69,'Andin Saskiya','SMK Negeri 1 Cilacap','-','Cilacap','2007-06-11','Teknik Jaringan Komputer dan Telekomunikasi','2024-09-01','2025-02-28','2025-02-28','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(70,'Syifa Desfira','SMK Negeri 1 Cilacap','-','Cilacap','2007-02-05','Teknik Jaringan Komputer dan Telekomunikasi','2024-09-01','2025-02-28','2025-02-28','Yuni Wahyu Utomo, S.Kom.','-','Tenaga Ahli Jaringan','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informatika','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(71,'Ayu Kusuma Ningrum','SMK Negeri 1 Cilacap','-','Cilacap','2007-11-13','Desain Komunikasi Visual','2024-09-01','2025-02-28','2025-02-28','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(72,'Evan Dienatra Maulana','SMK Negeri 1 Cilacap','-','Cilacap','2007-05-02','Desain Komunikasi Visual','2024-09-01','2025-02-28','2025-02-28','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(73,'Galuh Indah Nurul Astusi','SMK Negeri 1 Cilacap','-','Cilacap','2007-01-10','Desain Komunikasi Visual','2024-09-01','2025-02-28','2025-02-28','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(74,'Irfansyah Nabil','SMK Negeri 1 Cilacap','-','Cilacap','2007-09-04','Desain Komunikasi Visual','2024-09-01','2025-02-28','2025-02-28','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(75,'Riki Adrian','Politeknik Negeri Cilacap','-','Cilacap','2007-06-04','D4 Rekayasa Keamanan Siber','2025-02-01','2025-06-30','2025-06-30','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(76,'Albaith Zain Pratama','Politeknik Negeri Cilacap','-','Cilacap','2007-08-16','D4 Rekayasa Keamanan Siber','2025-02-01','2025-06-30','2025-06-30','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(77,'Rachma Athaya Candrawati S','Politeknik Negeri Cilacap','-','Cilacap','2007-05-07','D4 Rekayasa Keamanan Siber','2025-02-01','2025-06-30','2025-06-30','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(78,'Wahyu Aji Pratama','Politeknik Negeri Cilacap','-','Cilacap','2007-11-07','D4 Rekayasa Keamanan Siber','2025-02-01','2025-06-30','2025-06-30','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(79,'Bestari Anggun W','Politeknik Negeri Cilacap','-','Cilacap','2007-06-21','D4 Rekayasa Keamanan Siber','2025-02-01','2025-06-30','2025-06-30','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(80,'Saniya Wardah Musbita','SMK Muhammadiyah 1 Ajibarang','-','Ajibarang','2008-07-09','TKJ','2025-06-23','2025-10-10','2025-10-10','PRIATMOKO, S.Sos.','197702251997031004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(81,'Rosaliyana','SMK Muhammadiyah 1 Ajibarang','-','Ajibarang','2008-03-25','TKJ','2025-06-23','2025-10-10','2025-10-10','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(82,'Okti Nur Aini','SMK Muhammadiyah 1 Ajibarang','-','Ajibarang','2008-12-07','TKJ','2025-06-23','2025-10-10','2025-10-10','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(83,'Melisa Klaura Devi Artiks','SMK Muhammadiyah 1 Ajibarang','-','Ajibarang','2008-05-29','TKJ','2025-06-23','2025-10-10','2025-10-10','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(84,'Hardika Maulana','SMK Telkom Purwokerto','-','Purwokerto','2008-03-18','Pengembangan Perangkat Lunak dan Gim','2025-06-23','2025-10-17','2025-10-17','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(85,'Fauzan Akmal Rabbani','SMK Telkom Purwokerto','-','Purwokerto','2008-10-04','Pengembangan Perangkat Lunak dan Gim','2025-06-23','2025-10-17','2025-10-17','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(86,'Bojan Aji Wijayanto','SMK Telkom Purwokerto','-','Purwokerto','2008-12-04','Pengembangan Perangkat Lunak dan Gim','2025-06-23','2025-10-17','2025-10-17','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(87,'Anastasya Putri','SMK N 1 Wanareja','-','Wanareja','2008-08-30','Desain Komunikasi Visual','2025-08-04','2025-11-29','2025-11-29','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(88,'Salma Dwi Aryanti','SMK N 1 Wanareja','-','Wanareja','2008-02-25','Desain Komunikasi Visual','2025-08-04','2025-11-29','2025-11-29','PRIATMOKO, S.Sos.','19770225 199703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(89,'Azizah Haura Dewi Kurniawan','Universitas Tidar','-','Magelang','2008-10-01','S1 Ilmu Komunikasi','2025-07-07','2025-08-20','2025-08-20','PRIATMOKO, S.Sos.','19770225 19703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(90,'Aditya Putra Tri Anggara','Universitas Muhammadiyah Yogyakarta','-','Yogyakarta','2008-07-20','Ilmu Komunikasi Adversiting','2025-08-01','2025-09-01','2025-09-01','PRIATMOKO, S.Sos.','19770225 19703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,1,'14',NULL,'draft',NULL,NULL,NULL),(91,'Zahra Dewi Maharani','SMK N 1 Kawunganten','-','Kawunganten','2009-06-16','DKV','2026-06-01','2026-09-30','2026-09-30','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(92,'Nazwa Frestian Diantri','SMK N 1 Kawunganten','-','Kawunganten','2009-05-11','DKV','2026-06-01','2026-09-30','2026-09-30','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(93,'Tiara Febri Natasya','SMK N 1 Kawunganten','-','Kawunganten','2009-07-15','DKV','2026-06-01','2026-09-30','2026-09-30','IRPAN SETIAWAN, S.Kom.','19760908 200501 1 011','Kabid Statistik dan Persandian','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Statistik dan Persandian','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(94,'Lia Rasma Febriani','UNIVERSITAS JENDERAL SOEDIRMAN','-','Purwokerto','2005-06-13','S1 Ilmu Komunikasi','2026-01-05','2026-02-06','2026-02-06','PRIATMOKO, S.Sos.','19770225 19703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(95,'Syahita Pramesti Pramadani','UNIVERSITAS JENDERAL SOEDIRMAN','-','Purwokerto','2005-09-12','S1 Ilmu Komunikasi','2026-01-05','2026-02-06','2026-02-06','PRIATMOKO, S.Sos.','19770225 19703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(96,'Safira Rahmadani','UNIVERSITAS JENDERAL SOEDIRMAN','-','Purwokerto','2005-04-12','S1 Ilmu Komunikasi','2026-01-05','2026-02-06','2026-02-06','PRIATMOKO, S.Sos.','19770225 19703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Bidang Informasi dan Komunikasi Publik','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(97,'Abda Firas Rahman','Universitas Telkom Purwokerto','2311102049','Brebes','2005-03-21','S1 Teknik Informatika','2026-01-19','2026-02-20','2026-02-20','Slamet Arifin','199304202022021001','Operator Layanan Operasional','Kepala Dinas Komunikasi dan Informatika','Kepala Dinas','Bidang Informatika','400.14.5.4 / 0260 / 33','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(98,'Arlie Gangsta Ramadhan','SMK Diponegoro Majenang','-','Majenang','2008-03-13','DKV','2025-12-08','2026-04-06','2026-04-06','PRIATMOKO, S.Sos.','19770225 19703 1 004','Kabid Informasi dan Komunikasi Publik','Kepala Dinas Komunikasi dan Informatika',NULL,'Lembaga Penyiaran Publik Lokal','-','Buddy Hariyanto S.Sos., M.M.',1,'19711203 199803 1 002',1,1,0,'14',NULL,'draft',NULL,NULL,NULL),(101,'Wakhid Nugroho','UNSOED','H1D024003','CILACAP','2026-07-01','INFORMATIKA','2026-07-13','2026-08-13','2026-07-14','-','-','-','Kepala Bidang Informatika','coba','Bidang Informatika','001','coba',1,'coba',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(106,'Wakhid','UNSOED','H1D02400','coba','2026-07-01','coba','2026-07-16','2026-07-16','2026-07-16','coba','coba','coba','Kepala Bidang Informatika','-','Bidang Informatika',NULL,'-',1,'-',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(110,'budi3','2','1','4','2026-07-09','3','2026-07-20','2026-07-21','2026-07-20','Belum ditentukan','0000000000000000','Pembimbing','Pegawai','Belum ditentukan','Informatika','CERT-20260720153314-24','Kepala Dinas',0,'0000000000000000',0,0,0,'24','mentor','draft',NULL,NULL,NULL),(111,'coba99','coba0','00012','coba','2026-07-10','Informatika','2026-07-24','2026-07-25','2026-07-25','coba8','098','coba00','Kepala Bidang Informatika','Kabid Bidang Informatika','Bidang Informatika',NULL,'coba6',1,'89y9',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(112,'coba5','coba0','000179','coba','2026-07-10','Informatika','2026-07-24','2026-07-28','2026-07-30','coba8','098','coba00','Kepala Bidang Informatika','-','Bidang Informatika',NULL,'coba6',1,'89y9',1,1,1,'3',NULL,'draft',NULL,NULL,NULL),(113,'coba4','coba0','000177','coba','2026-07-10','Informatika','2026-07-24','2026-07-31','2026-07-25','gfjkwskf','098','coba00','Kepala Bidang Informatika','Kabid Bidang Informatika','Bidang Informatika',NULL,'coba6',1,'89y9',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(115,'vmvj','kmfskl','cmskl','mskl','0007-05-24','dlv,sl','0005-03-12','4678-05-04','2060-05-14','Mentor Administrator','fgreghr','gwee','Kepala Bidang Informatika','grh','Bidang Informatika',NULL,'fwf',1,'fg,w,',1,1,1,'3',NULL,'draft',NULL,NULL,NULL),(116,'vmvjrr','kmfskl','cmsklgtrh','mskl','0007-05-24','dlv,sl','1234-03-12','2345-04-23','2008-03-12','fkeel;','kgnwlk','kgfnekl','Kepala Bidang Informatika',',gawle','Bidang Informatika',NULL,'kgemk',1,'kds;',1,0,1,'3',NULL,'draft',NULL,NULL,NULL),(117,'vmvjrrjcj','kmfskl','cmsklcnk','mskl','0007-05-24','dlv,sl','4567-03-12','5687-04-12','0456-03-12','fnk','gnek','fnw','Kepala Bidang Informatika','g e,m','Bidang Informatika',NULL,'f kf',1,'nrew,',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(119,'vmvjrrjcjcnsdfd','kmfskl','cmsklcnkcmmafe','mskl','0007-05-24','dlv,sl','0004-03-12','0056-04-13','0004-03-12','rfgh','tt','fg','Kepala Bidang Informatika',',gawle','Bidang Informatika',NULL,'kgemk',1,'kds;',1,1,1,'3',NULL,'draft',NULL,NULL,NULL),(121,'ini','jjk','jjn','h vh','0456-03-12','bhh','0004-03-12','0567-04-23','0004-03-12','buk','bjk','nm jn','Kepala Bidang Informatika','Kabid Bidang Informatika','Bidang Informatika',NULL,'vbjk',1,'hbn',1,1,0,'3',NULL,'draft',NULL,NULL,NULL),(122,'fgnef','gjgo','gne','fnei','2003-03-12','vnd','0067-04-13','0678-05-24','2026-07-27','-','-','-',NULL,NULL,'Informatika',NULL,'-',0,'-',0,0,0,NULL,'mentor','draft',NULL,NULL,NULL),(123,'qw','dw,m','jfnkwl','fmwk','2003-03-12','kfnwkl','2026-07-28','2026-08-27','0004-03-12','14','5','tg','Kepala Bidang Informatika','fghg','Bidang Informatika',NULL,'rg',1,'dfg',1,1,1,'3',NULL,'draft',NULL,NULL,NULL),(127,'coba0111','mevm','vmmee','vm ,','2026-07-30','mv m','2026-07-30','2026-07-30','2026-07-30','mcl','fkmewl','cfnw','Kepala Bidang Informatika','ckwsl','Bidang Informatika',NULL,'fmlw;e',1,'fnkwl',1,1,0,'28',NULL,'draft',NULL,NULL,NULL),(134,'ininugroho','jscn','jdnw','jfje','0004-03-12','sjandka','0045-03-12','0456-03-12','2026-07-31','-','-','-',NULL,NULL,'Bidang Informatika',NULL,'-',0,'-',0,0,0,'19','mentor','draft',NULL,NULL,NULL),(135,'Nugroho1','U','nugroho123456','nugroho','2026-07-29','TI','2026-02-01','2026-02-02','2026-08-03','-','-','-',NULL,NULL,'Bidang Informatika',NULL,'-',0,'-',0,0,0,'33','mentor','draft',NULL,NULL,NULL),(136,'nugroho2','u','nugroho123456','c','2026-07-28','i','2026-08-03','2026-08-19','2026-08-03','-','-','-',NULL,NULL,'Bidang Informatika',NULL,'-',0,'-',0,0,0,'34','mentor','draft',NULL,NULL,NULL);
/*!40000 ALTER TABLE `tbl_sertifikat_pkl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-03 15:55:58
