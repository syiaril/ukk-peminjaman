-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: peminjaman
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `alat`
--

DROP TABLE IF EXISTS `alat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alat` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kategori_id` bigint unsigned NOT NULL,
  `nama_alat` varchar(255) NOT NULL,
  `deskripsi` text,
  `stok` int NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alat_kategori_id_foreign` (`kategori_id`),
  CONSTRAINT `alat_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alat`
--

LOCK TABLES `alat` WRITE;
/*!40000 ALTER TABLE `alat` DISABLE KEYS */;
INSERT INTO `alat` VALUES (1,1,'Mikroskop Binokuler','Mikroskop binokuler untuk pengamatan sel dan jaringan dengan perbesaran hingga 1000x.',8,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(2,1,'Tabung Reaksi','Tabung reaksi kaca borosilikat ukuran 15ml untuk eksperimen kimia.',50,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(3,1,'Bunsen Burner','Pembakar bunsen untuk pemanasan dalam eksperimen kimia.',15,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(4,1,'Gelas Ukur 100ml','Gelas ukur presisi untuk mengukur volume cairan.',24,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(5,1,'Neraca Analitik','Neraca digital dengan ketelitian 0.01 gram untuk mengukur massa.',5,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(6,1,'Pipet Tetes','Pipet tetes kaca untuk memindahkan cairan dalam jumlah kecil.',40,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(7,2,'Laptop ASUS VivoBook','Laptop ASUS VivoBook 14 inch, Intel Core i5, RAM 8GB untuk kegiatan belajar.',20,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(8,2,'Mouse Wireless Logitech','Mouse wireless Logitech M331 silent click untuk lab komputer.',30,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(9,2,'Keyboard Mechanical','Keyboard mechanical RGB untuk kegiatan programming dan typing.',13,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(10,2,'USB Flash Drive 32GB','Flash drive USB 3.0 kapasitas 32GB untuk penyimpanan data.',40,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(11,2,'Kabel LAN Cat6','Kabel LAN kategori 6 untuk koneksi jaringan komputer.',19,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(12,3,'Bola Basket Molten','Bola basket Molten GG7X official size 7 untuk pertandingan resmi.',8,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(13,3,'Bola Voli Mikasa','Bola voli Mikasa MVA200 untuk latihan dan pertandingan.',9,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(14,3,'Raket Badminton Yonex','Raket badminton Yonex Astrox untuk latihan club badminton.',12,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(15,3,'Net Badminton','Net badminton standar pertandingan dengan tiang penyangga.',2,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(16,3,'Matras Senam','Matras senam tebal 5cm ukuran 200x100cm untuk kegiatan senam.',15,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(17,3,'Stopwatch Digital','Stopwatch digital untuk mengukur waktu dalam kegiatan olahraga.',10,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(18,4,'Gitar Akustik Yamaha','Gitar akustik Yamaha C315 untuk latihan musik dan pertunjukan.',6,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(19,4,'Keyboard Yamaha PSR','Keyboard Yamaha PSR-E373 61 keys untuk pelajaran musik.',4,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(20,4,'Drum Pad Alesis','Drum pad electronic Alesis untuk latihan drum tanpa suara keras.',3,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(21,4,'Biola 4/4','Biola ukuran penuh 4/4 untuk pelajaran dan latihan orkestra.',6,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(22,4,'Seruling Bambu','Seruling bambu tradisional untuk pelajaran seni budaya.',18,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(23,5,'Jangka Sorong Digital','Jangka sorong digital dengan ketelitian 0.01mm untuk praktikum fisika.',14,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(24,5,'Model Bangun Ruang','Set model bangun ruang transparan (kubus, balok, kerucut, limas, bola).',10,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(25,5,'Penggaris Segitiga Set','Set penggaris segitiga 30cm untuk menggambar geometri di papan tulis.',19,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(26,5,'Kalkulator Scientific','Kalkulator scientific Casio FX-991ID Plus untuk perhitungan matematika.',25,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(27,6,'Vacuum Cleaner Portable','Vacuum cleaner portable untuk membersihkan ruang kelas dan lab.',5,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(28,6,'Mesin Pel Lantai','Mesin pel lantai otomatis untuk membersihkan lantai gedung sekolah.',2,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(29,6,'Sprayer Disinfektan','Sprayer elektrik untuk penyemprotan disinfektan di lingkungan sekolah.',8,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(30,7,'Proyektor Epson','Proyektor Epson EB-X51 3800 lumens untuk presentasi di kelas.',9,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(31,7,'Printer HP LaserJet','Printer laser HP LaserJet Pro untuk mencetak dokumen sekolah.',4,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(32,7,'Scanner Epson','Scanner Epson Perfection V39 untuk scan dokumen dan foto.',4,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(33,7,'Whiteboard Portable','Whiteboard portable ukuran 120x80cm dengan tripod stand.',8,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(34,7,'Laminator A3','Mesin laminator ukuran A3 untuk melapisi dokumen penting.',3,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(35,8,'Multimeter Digital','Multimeter digital untuk mengukur tegangan, arus, dan resistansi.',12,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(36,8,'Solder Station','Solder station dengan pengaturan suhu untuk praktikum elektronika.',8,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(37,8,'Breadboard Kit','Breadboard kit lengkap dengan kabel jumper untuk rangkaian elektronik.',20,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(38,8,'Arduino Uno R3','Board Arduino Uno R3 untuk belajar microcontroller dan IoT.',15,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(39,8,'Oscilloscope Digital','Oscilloscope digital 2 channel untuk menganalisis sinyal elektronik.',4,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(40,8,'Power Supply Variable','Power supply variable 0-30V 5A untuk eksperimen elektronika.',6,NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08');
/*!40000 ALTER TABLE `alat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori`
--

LOCK TABLES `kategori` WRITE;
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
INSERT INTO `kategori` VALUES (1,'Alat Laboratorium IPA','2026-02-10 06:34:08','2026-02-10 06:34:08'),(2,'Alat Laboratorium Komputer','2026-02-10 06:34:08','2026-02-10 06:34:08'),(3,'Alat Olahraga','2026-02-10 06:34:08','2026-02-10 06:34:08'),(4,'Alat Musik','2026-02-10 06:34:08','2026-02-10 06:34:08'),(5,'Alat Peraga Matematika','2026-02-10 06:34:08','2026-02-10 06:34:08'),(6,'Alat Kebersihan','2026-02-10 06:34:08','2026-02-10 06:34:08'),(7,'Peralatan Kantor','2026-02-10 06:34:08','2026-02-10 06:34:08'),(8,'Alat Elektronik','2026-02-10 06:34:08','2026-02-10 06:34:08');
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_aktivitas`
--

DROP TABLE IF EXISTS `log_aktivitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_aktivitas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pengguna_id` bigint unsigned NOT NULL,
  `aksi` varchar(255) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_aktivitas_pengguna_id_foreign` (`pengguna_id`),
  CONSTRAINT `log_aktivitas_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_aktivitas`
--

LOCK TABLES `log_aktivitas` WRITE;
/*!40000 ALTER TABLE `log_aktivitas` DISABLE KEYS */;
INSERT INTO `log_aktivitas` VALUES (1,1,'Login','Administrator berhasil login ke sistem.','2026-02-09 06:34:08','2026-02-09 06:34:08'),(2,2,'Login','Petugas Budi Santoso berhasil login ke sistem.','2026-02-09 06:34:08','2026-02-09 06:34:08'),(3,3,'Login','Petugas Siti Rahayu berhasil login ke sistem.','2026-02-08 06:34:08','2026-02-08 06:34:08'),(4,4,'Login','Petugas Ahmad Fauzi berhasil login ke sistem.','2026-02-07 06:34:08','2026-02-07 06:34:08'),(5,1,'Tambah Alat','Menambahkan alat baru: Mikroskop Binokuler.','2026-01-11 06:34:08','2026-01-11 06:34:08'),(6,1,'Tambah Alat','Menambahkan alat baru: Laptop ASUS VivoBook.','2026-01-11 06:34:08','2026-01-11 06:34:08'),(7,1,'Tambah Alat','Menambahkan alat baru: Bola Basket Molten.','2026-01-13 06:34:08','2026-01-13 06:34:08'),(8,1,'Edit Alat','Mengubah stok Tabung Reaksi menjadi 50.','2026-01-21 06:34:08','2026-01-21 06:34:08'),(9,1,'Edit Alat','Mengubah deskripsi Proyektor Epson.','2026-01-26 06:34:08','2026-01-26 06:34:08'),(10,1,'Tambah Kategori','Menambahkan kategori baru: Alat Laboratorium IPA.','2026-01-06 06:34:08','2026-01-06 06:34:08'),(11,1,'Tambah Kategori','Menambahkan kategori baru: Alat Olahraga.','2026-01-06 06:34:08','2026-01-06 06:34:08'),(12,1,'Tambah Kategori','Menambahkan kategori baru: Alat Elektronik.','2026-01-07 06:34:08','2026-01-07 06:34:08'),(13,2,'Setujui Peminjaman','Peminjaman #1 disetujui oleh Budi Santoso.','2026-01-16 06:34:08','2026-01-16 06:34:08'),(14,2,'Setujui Peminjaman','Peminjaman #2 disetujui oleh Budi Santoso.','2026-01-19 06:34:08','2026-01-19 06:34:08'),(15,3,'Setujui Peminjaman','Peminjaman #3 disetujui oleh Siti Rahayu.','2026-01-21 06:34:08','2026-01-21 06:34:08'),(16,3,'Setujui Peminjaman','Peminjaman #5 disetujui oleh Siti Rahayu.','2026-01-23 06:34:08','2026-01-23 06:34:08'),(17,4,'Setujui Peminjaman','Peminjaman #7 disetujui oleh Ahmad Fauzi.','2026-01-26 06:34:08','2026-01-26 06:34:08'),(18,2,'Setujui Peminjaman','Peminjaman #10 disetujui oleh Budi Santoso.','2026-01-31 06:34:08','2026-01-31 06:34:08'),(19,3,'Tolak Peminjaman','Peminjaman ditolak: Stok alat tidak tersedia.','2026-01-22 06:34:08','2026-01-22 06:34:08'),(20,4,'Tolak Peminjaman','Peminjaman ditolak: Jadwal bentrok dengan peminjam lain.','2026-01-27 06:34:08','2026-01-27 06:34:08'),(21,2,'Tolak Peminjaman','Peminjaman ditolak: Data peminjam tidak lengkap.','2026-02-02 06:34:08','2026-02-02 06:34:08'),(22,2,'Konfirmasi Pengembalian','Peminjaman #1 dikembalikan. Denda: 0.','2026-01-29 06:34:08','2026-01-29 06:34:08'),(23,3,'Konfirmasi Pengembalian','Peminjaman #2 dikembalikan. Denda: 15000.','2026-01-31 06:34:08','2026-01-31 06:34:08'),(24,4,'Konfirmasi Pengembalian','Peminjaman #3 dikembalikan. Denda: 0.','2026-02-02 06:34:08','2026-02-02 06:34:08'),(25,2,'Konfirmasi Pengembalian','Peminjaman #5 dikembalikan. Denda: 25000.','2026-02-05 06:34:08','2026-02-05 06:34:08'),(26,1,'Tambah Pengguna','Menambahkan akun petugas baru: Budi Santoso.','2026-01-01 06:34:08','2026-01-01 06:34:08'),(27,1,'Tambah Pengguna','Menambahkan akun petugas baru: Siti Rahayu.','2026-01-01 06:34:08','2026-01-01 06:34:08'),(28,1,'Tambah Pengguna','Menambahkan akun petugas baru: Ahmad Fauzi.','2026-01-02 06:34:08','2026-01-02 06:34:08'),(29,1,'Edit Pengguna','Mengubah data pengguna: Andi Pratama.','2026-01-31 06:34:08','2026-01-31 06:34:08'),(30,5,'Ajukan Peminjaman','Andi Pratama mengajukan peminjaman Laptop ASUS.','2026-02-05 06:34:08','2026-02-05 06:34:08'),(31,6,'Ajukan Peminjaman','Dewi Lestari mengajukan peminjaman Gitar Akustik.','2026-02-06 06:34:08','2026-02-06 06:34:08'),(32,7,'Ajukan Peminjaman','Rizky Hidayat mengajukan peminjaman Arduino Uno.','2026-02-07 06:34:08','2026-02-07 06:34:08'),(33,8,'Ajukan Peminjaman','Putri Ayu mengajukan peminjaman Mikroskop.','2026-02-08 06:34:08','2026-02-08 06:34:08'),(34,9,'Ajukan Peminjaman','Fajar Nugroho mengajukan peminjaman Bola Basket.','2026-02-09 06:34:08','2026-02-09 06:34:08'),(35,10,'Ajukan Peminjaman','Maya Sari mengajukan peminjaman Proyektor Epson.','2026-02-10 06:34:08','2026-02-10 06:34:08'),(36,1,'Konfirmasi Pengembalian','Peminjaman 41 dikembalikan. Denda: 45000.00','2026-02-10 06:38:37','2026-02-10 06:38:37'),(37,2,'Setujui Peminjaman','Peminjaman 51 disetujui','2026-02-24 01:45:53','2026-02-24 01:45:53'),(38,2,'Konfirmasi Pengembalian','Peminjaman 38 dikembalikan. Denda: 110000.00','2026-02-24 01:51:45','2026-02-24 01:51:45'),(39,2,'Setujui Peminjaman','Peminjaman 52 disetujui','2026-02-24 03:25:18','2026-02-24 03:25:18'),(40,2,'Konfirmasi Pengembalian','Peminjaman 52 dikembalikan. Denda: 15000.00','2026-02-24 03:25:18','2026-02-24 03:25:18'),(41,2,'Setujui Peminjaman','Peminjaman 54 disetujui','2026-02-24 03:25:18','2026-02-24 03:25:18'),(42,2,'Konfirmasi Pengembalian','Peminjaman 54 dikembalikan. Denda: 0.00','2026-02-24 03:25:18','2026-02-24 03:25:18');
/*!40000 ALTER TABLE `log_aktivitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_buat_tabel_pengguna',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_18_214530_buat_tabel_kategori',1),(5,'2026_01_18_214538_buat_tabel_alat',1),(6,'2026_01_18_214550_buat_tabel_peminjaman',1),(7,'2026_01_18_214631_buat_tabel_log_aktivitas',1),(8,'2026_01_18_214712_buat_trigger_dan_prosedur',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peminjaman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pengguna_id` bigint unsigned NOT NULL,
  `alat_id` bigint unsigned NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_wajib_kembali` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('diajukan','disetujui','sedang_dikembalikan','dikembalikan','ditolak') NOT NULL DEFAULT 'diajukan',
  `denda` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peminjaman_pengguna_id_foreign` (`pengguna_id`),
  KEY `peminjaman_alat_id_foreign` (`alat_id`),
  CONSTRAINT `peminjaman_alat_id_foreign` FOREIGN KEY (`alat_id`) REFERENCES `alat` (`id`),
  CONSTRAINT `peminjaman_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman`
--

LOCK TABLES `peminjaman` WRITE;
/*!40000 ALTER TABLE `peminjaman` DISABLE KEYS */;
INSERT INTO `peminjaman` VALUES (1,22,13,'2025-12-03','2025-12-06','2025-12-03','dikembalikan',0.00,'2025-12-03 06:34:08','2025-12-03 06:34:08'),(2,19,40,'2025-12-19','2025-12-28','2025-12-25','dikembalikan',0.00,'2025-12-19 06:34:08','2025-12-25 06:34:08'),(3,5,35,'2025-12-21','2025-12-29','2025-12-31','dikembalikan',-10000.00,'2025-12-21 06:34:08','2025-12-31 06:34:08'),(4,6,20,'2025-12-28','2026-01-07','2026-01-06','dikembalikan',0.00,'2025-12-28 06:34:08','2026-01-06 06:34:08'),(5,9,33,'2025-12-13','2025-12-20','2025-12-18','dikembalikan',0.00,'2025-12-13 06:34:08','2025-12-18 06:34:08'),(6,20,21,'2025-12-20','2025-12-23','2025-12-23','dikembalikan',0.00,'2025-12-20 06:34:08','2025-12-23 06:34:08'),(7,18,30,'2025-11-30','2025-12-14','2025-12-12','dikembalikan',0.00,'2025-11-30 06:34:08','2025-12-12 06:34:08'),(8,20,15,'2025-12-18','2025-12-26','2025-12-25','dikembalikan',0.00,'2025-12-18 06:34:08','2025-12-25 06:34:08'),(9,11,8,'2026-01-02','2026-01-14','2026-01-13','dikembalikan',0.00,'2026-01-02 06:34:08','2026-01-13 06:34:08'),(10,24,37,'2025-11-28','2025-12-01','2025-12-01','dikembalikan',0.00,'2025-11-28 06:34:08','2025-12-01 06:34:08'),(11,8,2,'2025-12-06','2025-12-16','2025-12-20','dikembalikan',-20000.00,'2025-12-06 06:34:08','2025-12-20 06:34:08'),(12,8,20,'2025-12-19','2025-12-22','2025-12-22','dikembalikan',0.00,'2025-12-19 06:34:08','2025-12-22 06:34:08'),(13,19,33,'2025-12-23','2026-01-06','2026-01-03','dikembalikan',0.00,'2025-12-23 06:34:08','2026-01-03 06:34:08'),(14,8,39,'2025-12-30','2026-01-10','2026-01-07','dikembalikan',0.00,'2025-12-30 06:34:08','2026-01-07 06:34:08'),(15,22,13,'2025-12-16','2025-12-27','2025-12-27','dikembalikan',0.00,'2025-12-16 06:34:08','2025-12-27 06:34:08'),(16,24,22,'2026-02-01','2026-02-19',NULL,'disetujui',0.00,'2026-02-01 06:34:08','2026-02-01 06:34:08'),(17,11,9,'2026-02-04','2026-02-22',NULL,'disetujui',0.00,'2026-02-04 06:34:08','2026-02-04 06:34:08'),(18,15,31,'2026-02-05','2026-02-18',NULL,'disetujui',0.00,'2026-02-05 06:34:08','2026-02-05 06:34:08'),(19,13,13,'2026-02-01','2026-02-17',NULL,'disetujui',0.00,'2026-02-01 06:34:08','2026-02-01 06:34:08'),(20,6,18,'2026-01-31','2026-02-15',NULL,'disetujui',0.00,'2026-01-31 06:34:08','2026-01-31 06:34:08'),(21,14,22,'2026-02-08','2026-02-22',NULL,'disetujui',0.00,'2026-02-08 06:34:08','2026-02-08 06:34:08'),(22,7,11,'2026-02-01','2026-02-21',NULL,'disetujui',0.00,'2026-02-01 06:34:08','2026-02-01 06:34:08'),(23,14,25,'2026-02-02','2026-02-17',NULL,'disetujui',0.00,'2026-02-02 06:34:08','2026-02-02 06:34:08'),(24,15,9,'2026-01-27','2026-02-07',NULL,'disetujui',0.00,'2026-01-27 06:34:08','2026-01-27 06:34:08'),(25,7,23,'2026-01-27','2026-02-05',NULL,'disetujui',0.00,'2026-01-27 06:34:08','2026-01-27 06:34:08'),(26,15,28,'2026-02-06','2026-02-24',NULL,'disetujui',0.00,'2026-02-06 06:34:08','2026-02-06 06:34:08'),(27,18,15,'2026-01-29','2026-02-05',NULL,'disetujui',0.00,'2026-01-29 06:34:08','2026-01-29 06:34:08'),(28,14,40,'2026-02-09','2026-02-20',NULL,'diajukan',0.00,'2026-02-09 06:34:08','2026-02-09 06:34:08'),(29,5,26,'2026-02-07','2026-02-13',NULL,'diajukan',0.00,'2026-02-07 06:34:08','2026-02-07 06:34:08'),(30,24,3,'2026-02-07','2026-02-20',NULL,'diajukan',0.00,'2026-02-07 06:34:08','2026-02-07 06:34:08'),(31,10,2,'2026-02-10','2026-02-22',NULL,'diajukan',0.00,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(32,13,22,'2026-02-05','2026-02-18',NULL,'diajukan',0.00,'2026-02-05 06:34:08','2026-02-05 06:34:08'),(33,22,13,'2026-02-06','2026-02-20',NULL,'diajukan',0.00,'2026-02-06 06:34:08','2026-02-06 06:34:08'),(34,6,24,'2026-02-10','2026-02-23',NULL,'diajukan',0.00,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(35,18,30,'2026-02-07','2026-02-16',NULL,'diajukan',0.00,'2026-02-07 06:34:08','2026-02-07 06:34:08'),(36,12,21,'2026-02-05','2026-02-13',NULL,'diajukan',0.00,'2026-02-05 06:34:08','2026-02-05 06:34:08'),(37,6,32,'2026-02-05','2026-02-19',NULL,'diajukan',0.00,'2026-02-05 06:34:08','2026-02-05 06:34:08'),(38,22,35,'2026-01-27','2026-02-02','2026-02-24','dikembalikan',110000.00,'2026-01-27 06:34:08','2026-02-09 06:34:08'),(39,11,18,'2026-02-01','2026-02-08',NULL,'sedang_dikembalikan',0.00,'2026-02-01 06:34:08','2026-02-10 06:34:08'),(40,24,4,'2026-01-27','2026-02-10',NULL,'sedang_dikembalikan',0.00,'2026-01-27 06:34:08','2026-02-09 06:34:08'),(41,11,13,'2026-01-22','2026-02-01','2026-02-10','dikembalikan',45000.00,'2026-01-22 06:34:08','2026-02-09 06:34:08'),(42,14,30,'2026-01-26','2026-02-09',NULL,'sedang_dikembalikan',0.00,'2026-01-26 06:34:08','2026-02-09 06:34:08'),(43,20,11,'2026-01-18','2026-01-26',NULL,'ditolak',0.00,'2026-01-18 06:34:08','2026-01-19 06:34:08'),(44,15,37,'2026-01-01','2026-01-08',NULL,'ditolak',0.00,'2026-01-01 06:34:08','2026-01-02 06:34:08'),(45,13,35,'2025-12-26','2026-01-01',NULL,'ditolak',0.00,'2025-12-26 06:34:08','2025-12-27 06:34:08'),(46,23,8,'2026-01-31','2026-02-05',NULL,'ditolak',0.00,'2026-01-31 06:34:08','2026-02-01 06:34:08'),(47,19,21,'2025-12-30','2026-01-07',NULL,'ditolak',0.00,'2025-12-30 06:34:08','2025-12-31 06:34:08'),(48,16,6,'2025-12-12','2025-12-26',NULL,'ditolak',0.00,'2025-12-12 06:34:08','2025-12-13 06:34:08'),(49,13,21,'2026-01-15','2026-01-23',NULL,'ditolak',0.00,'2026-01-15 06:34:08','2026-01-16 06:34:08'),(50,7,16,'2025-12-17','2025-12-26',NULL,'ditolak',0.00,'2025-12-17 06:34:08','2025-12-18 06:34:08'),(51,24,1,'2026-02-24','2026-02-25',NULL,'disetujui',0.00,'2026-02-24 01:43:01','2026-02-24 01:43:01'),(52,5,1,'2026-02-24','2026-02-21','2026-02-24','dikembalikan',15000.00,'2026-02-24 03:25:18','2026-02-24 03:25:18'),(54,5,1,'2026-02-21','2026-02-26','2026-02-24','dikembalikan',0.00,'2026-02-24 03:25:18','2026-02-24 03:25:18');
/*!40000 ALTER TABLE `peminjaman` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `kurangi_stok_setelah_disetujui` AFTER UPDATE ON `peminjaman` FOR EACH ROW BEGIN
                IF NEW.status = "disetujui" AND OLD.status != "disetujui" THEN
                    UPDATE alat SET stok = stok - 1 WHERE id = NEW.alat_id;
                END IF;
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tambah_stok_setelah_dikembalikan` AFTER UPDATE ON `peminjaman` FOR EACH ROW BEGIN
                IF NEW.status = "dikembalikan" AND OLD.status != "dikembalikan" THEN
                    UPDATE alat SET stok = stok + 1 WHERE id = NEW.alat_id;
                END IF;
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengguna` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `kata_sandi` varchar(255) NOT NULL,
  `peran` enum('admin','petugas','peminjam') NOT NULL DEFAULT 'peminjam',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengguna_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengguna`
--

LOCK TABLES `pengguna` WRITE;
/*!40000 ALTER TABLE `pengguna` DISABLE KEYS */;
INSERT INTO `pengguna` VALUES (1,'Administrator','admin@admin.com',NULL,'$2y$12$AwkbnGHohYJWXkZsIswHfe.z1KWJEldu0sbRr5JQXDkAjqgYMCjm2','admin',NULL,'2026-02-10 06:34:01','2026-02-10 06:34:01'),(2,'Budi Santoso','budi.petugas@sekolah.com',NULL,'$2y$12$ZE6h9wLHvc8NrfQEdE0Q5eYIdKCL1YY34iVvWZhLmfUk2UhmfhunS','petugas',NULL,'2026-02-10 06:34:01','2026-02-10 06:34:01'),(3,'Siti Rahayu','siti.petugas@sekolah.com',NULL,'$2y$12$URN0gH1.qo/5dlASghx2KOPtqrMVgvCMLow2JCG5yvuC7TPqM2xKG','petugas',NULL,'2026-02-10 06:34:01','2026-02-10 06:34:01'),(4,'Ahmad Fauzi','ahmad.petugas@sekolah.com',NULL,'$2y$12$SJZEPNfqsJFBJK.IW4UQROrEhsVjxZ4KO/0pp.fQzuv/bGPOz37GO','petugas',NULL,'2026-02-10 06:34:02','2026-02-10 06:34:02'),(5,'Andi Pratama','andi.pratama@siswa.com',NULL,'$2y$12$fdsIoMcch3o8k58vKHJr1.kW1Pc5yZFtQJwecadV3Kh4VWgvYt3Mq','peminjam',NULL,'2026-02-10 06:34:02','2026-02-10 06:34:02'),(6,'Dewi Lestari','dewi.lestari@siswa.com',NULL,'$2y$12$3WjBoX0pbEj23DIRQMuIVezWYGEPUDhkvvukMRfG4lIzN5NDbWuLu','peminjam',NULL,'2026-02-10 06:34:02','2026-02-10 06:34:02'),(7,'Rizky Hidayat','rizky.hidayat@siswa.com',NULL,'$2y$12$t4qJSTLO83RM8gmTDAqEtOAUgUWl92W.MX.2srM0CBYC5r3SSUqp.','peminjam',NULL,'2026-02-10 06:34:03','2026-02-10 06:34:03'),(8,'Putri Ayu','putri.ayu@siswa.com',NULL,'$2y$12$AJifzFUv1BpJJwdIS0LCzeytyKgvEtMZzPWzMKekearq3N4Rd7KZK','peminjam',NULL,'2026-02-10 06:34:03','2026-02-10 06:34:03'),(9,'Fajar Nugroho','fajar.nugroho@siswa.com',NULL,'$2y$12$ToaQ1ypzUJBMGmPhS4RNyO2ybTOvzXvlPQbM24IOJ1GOmX4pw5qrS','peminjam',NULL,'2026-02-10 06:34:03','2026-02-10 06:34:03'),(10,'Maya Sari','maya.sari@siswa.com',NULL,'$2y$12$/SDRvB8Lc1qAFLpjOMSIEOUlc48t1ij.Fg3kdzrGTMXWJ.0HWjQfm','peminjam',NULL,'2026-02-10 06:34:04','2026-02-10 06:34:04'),(11,'Dian Permata','dian.permata@siswa.com',NULL,'$2y$12$0XlRO5z3Q6xCcZcC18WSeO8ARJbSutMM/MTQDyvE4y4MJVkbq663.','peminjam',NULL,'2026-02-10 06:34:04','2026-02-10 06:34:04'),(12,'Rendi Kurniawan','rendi.kurniawan@siswa.com',NULL,'$2y$12$QWmt3zCX7Z5rHAIDk/guhuMwQTeEI4lwLT.Pb5giW0VUGr.5Fa.OG','peminjam',NULL,'2026-02-10 06:34:04','2026-02-10 06:34:04'),(13,'Nadia Fitri','nadia.fitri@siswa.com',NULL,'$2y$12$2xv/LWtq80eAhlNCWCcsHeq8ofrQsLGLqimX1UiWK4mOvqpow3jDu','peminjam',NULL,'2026-02-10 06:34:05','2026-02-10 06:34:05'),(14,'Hendra Wijaya','hendra.wijaya@siswa.com',NULL,'$2y$12$NScWPcha0XGMNZ7b9M/uNey7CJX40C.lQATIMnH9YqR5O8Mgkydyu','peminjam',NULL,'2026-02-10 06:34:05','2026-02-10 06:34:05'),(15,'Sinta Maharani','sinta.maharani@siswa.com',NULL,'$2y$12$XdvDTSlXu7N2El/Xp8TDCO5wvjhjNf5s.bbiRNnVojSg.qiQkqszW','peminjam',NULL,'2026-02-10 06:34:05','2026-02-10 06:34:05'),(16,'Yoga Aditya','yoga.aditya@siswa.com',NULL,'$2y$12$iu11vHqI4Xl0jdrbUWjAnuoyw6dey26qOc/vOIq4wXV8zIZy2xjpy','peminjam',NULL,'2026-02-10 06:34:05','2026-02-10 06:34:05'),(17,'Laras Wulandari','laras.wulandari@siswa.com',NULL,'$2y$12$arhnz58MPe5TE5vsttpree5ikXqRbePdKAVMjEu.16fziYlaxV1CO','peminjam',NULL,'2026-02-10 06:34:06','2026-02-10 06:34:06'),(18,'Bayu Setiawan','bayu.setiawan@siswa.com',NULL,'$2y$12$BiLyicMK7MUqeGnJrNUC8O4fhuvb3YqFsrkl/SInJxY.B4qq.yXRK','peminjam',NULL,'2026-02-10 06:34:06','2026-02-10 06:34:06'),(19,'Rina Oktaviani','rina.oktaviani@siswa.com',NULL,'$2y$12$f3wo4L.Q3At2ENeTwpCy2eztcPN8dOG8SOy5nPT6IO2.9FBtPErlK','peminjam',NULL,'2026-02-10 06:34:06','2026-02-10 06:34:06'),(20,'Gilang Ramadhan','gilang.ramadhan@siswa.com',NULL,'$2y$12$EteZtimG6uRQ4rTFJHdpyOvvNSW2FyvbQM.k3PXx.E0ScNN./iwlK','peminjam',NULL,'2026-02-10 06:34:07','2026-02-10 06:34:07'),(21,'Anisa Putri','anisa.putri@siswa.com',NULL,'$2y$12$orlRX8qD9.1xs1h7onqkzufxLvIZujfrRkzWDtM3/EJ8.51QCE9s.','peminjam',NULL,'2026-02-10 06:34:07','2026-02-10 06:34:07'),(22,'Taufik Hidayat','taufik.hidayat@siswa.com',NULL,'$2y$12$ogi.yp5Vltt0D3KHTJ33EuSaSRjmFk2skhSykqdDi27FXHJLPO3Mi','peminjam',NULL,'2026-02-10 06:34:07','2026-02-10 06:34:07'),(23,'Winda Sari','winda.sari@siswa.com',NULL,'$2y$12$iitOgnsptQV5QUVOJGIDN.jwrekVZUP7hAKZ1V5H.Y0eWOQV7ms6C','peminjam',NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08'),(24,'Irfan Maulana','irfan.maulana@siswa.com',NULL,'$2y$12$9U4On/UnHlT1vSYKknX5Quen4zoWKkEiZwEp9LGmgmtm0rsBS6yje','peminjam',NULL,'2026-02-10 06:34:08','2026-02-10 06:34:08');
/*!40000 ALTER TABLE `pengguna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('mbj8NzZFPaI58L9uLR7EFzEmC1wEh3YJTPfi0pi1',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoianRuMWpoVE9OZktkZkVZOGxXQ1RDQzhqd2tMQWxvTzg2S0RHbng3TSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',1771897491),('yArV2uSOHMJ0QcGrAaKMYt8TlV72izD9KS6L7Rct',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicUFLaVFrYU1sVHc0OGsyR0VxSTB6SExJS05XSnp4d21reHJBRE5udyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=',1771901465);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'peminjaman'
--
/*!50003 DROP FUNCTION IF EXISTS `hitung_denda` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `hitung_denda`(tanggal_wajib DATE, tanggal_kembali DATE) RETURNS decimal(10,2)
    DETERMINISTIC
BEGIN
                DECLARE denda DECIMAL(10,2);
                DECLARE hari_terlambat INT;
                SET denda = 0;
                IF tanggal_kembali > tanggal_wajib THEN
                    SET hari_terlambat = DATEDIFF(tanggal_kembali, tanggal_wajib);
                    SET denda = hari_terlambat * 5000;
                END IF;
                RETURN denda;
            END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `proses_pengembalian` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `proses_pengembalian`(IN id_peminjaman INT, IN id_petugas INT)
BEGIN
                DECLARE tanggal_wajib DATE;
                DECLARE nominal_denda DECIMAL(10,2);
                
                DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
                START TRANSACTION;
                
                -- Ambil tanggal wajib kembali
                SELECT tanggal_wajib_kembali INTO tanggal_wajib FROM peminjaman WHERE id = id_peminjaman;
                
                -- Hitung denda
                SET nominal_denda = hitung_denda(tanggal_wajib, CURDATE());
                
                -- Update peminjaman
                UPDATE peminjaman 
                SET status = "dikembalikan", 
                    tanggal_kembali = CURDATE(), 
                    denda = nominal_denda 
                WHERE id = id_peminjaman;
                
                -- Catat log (jika id_petugas diberikan/tidak null)
                IF id_petugas IS NOT NULL THEN
                    INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
                    VALUES (id_petugas, "Konfirmasi Pengembalian", CONCAT("Peminjaman ", id_peminjaman, " dikembalikan. Denda: ", nominal_denda), NOW(), NOW());
                END IF;
                
                COMMIT;
            END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `proses_persetujuan_peminjaman` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `proses_persetujuan_peminjaman`(IN id_peminjaman INT, IN id_petugas INT)
BEGIN
                DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
                START TRANSACTION;
                
                UPDATE peminjaman SET status = "disetujui" WHERE id = id_peminjaman;
                
                INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
                VALUES (id_petugas, "Setujui Peminjaman", CONCAT("Peminjaman ", id_peminjaman, " disetujui"), NOW(), NOW());
                
                COMMIT;
            END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-24 10:35:34
