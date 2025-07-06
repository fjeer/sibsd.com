-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2025 at 07:38 AM
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
-- Database: `db_bsd`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(12) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telephone` char(15) NOT NULL,
  `role` enum('superadmin','admin','petugas') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id`, `nama`, `email`, `username`, `password`, `no_telephone`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'budi', 'budi@gmail.com', 'budie', '$2y$10$WyS0wHOrPGE7PzTu7Kks2.uh/7RfxiqGrDQYrFXhSum/i9aX73dMC', '081211213213', 'superadmin', 1, '2025-05-19 10:53:02', '2025-07-06 02:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_setoran`
--

CREATE TABLE `tb_detail_setoran` (
  `id` int(11) NOT NULL,
  `id_setoran` int(11) NOT NULL,
  `id_sampah` int(11) NOT NULL,
  `berat_kg` decimal(10,2) NOT NULL,
  `poin` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_nasabah`
--

CREATE TABLE `tb_nasabah` (
  `id` int(11) NOT NULL,
  `nin` char(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('l','p') NOT NULL,
  `alamat` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `no_telephone` char(15) NOT NULL,
  `saldo_poin` int(11) DEFAULT 0,
  `tanggal_daftar` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_remember_tokens`
--

CREATE TABLE `tb_remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(12) NOT NULL,
  `validator_hash` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sampah`
--

CREATE TABLE `tb_sampah` (
  `id` int(11) NOT NULL,
  `jenis_sampah` varchar(50) NOT NULL,
  `harga_per_kg` int(11) NOT NULL,
  `deskripsi` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_setoran`
--

CREATE TABLE `tb_setoran` (
  `id` int(11) NOT NULL,
  `id_nasabah` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `total_poin` int(11) DEFAULT 0,
  `status_transaksi` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`username`) USING BTREE;

--
-- Indexes for table `tb_detail_setoran`
--
ALTER TABLE `tb_detail_setoran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_setoran` (`id_setoran`),
  ADD KEY `id_sampah` (`id_sampah`);

--
-- Indexes for table `tb_nasabah`
--
ALTER TABLE `tb_nasabah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_induk_nasabah` (`nin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tb_remember_tokens`
--
ALTER TABLE `tb_remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tb_sampah`
--
ALTER TABLE `tb_sampah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_setoran`
--
ALTER TABLE `tb_setoran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nasabah` (`id_nasabah`),
  ADD KEY `id_admin` (`id_admin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_detail_setoran`
--
ALTER TABLE `tb_detail_setoran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_nasabah`
--
ALTER TABLE `tb_nasabah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_remember_tokens`
--
ALTER TABLE `tb_remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_sampah`
--
ALTER TABLE `tb_sampah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_setoran`
--
ALTER TABLE `tb_setoran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_detail_setoran`
--
ALTER TABLE `tb_detail_setoran`
  ADD CONSTRAINT `tb_detail_setoran_ibfk_1` FOREIGN KEY (`id_setoran`) REFERENCES `tb_setoran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_detail_setoran_ibfk_2` FOREIGN KEY (`id_sampah`) REFERENCES `tb_sampah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_remember_tokens`
--
ALTER TABLE `tb_remember_tokens`
  ADD CONSTRAINT `tb_remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_admin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_setoran`
--
ALTER TABLE `tb_setoran`
  ADD CONSTRAINT `tb_setoran_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `tb_nasabah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_setoran_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `tb_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
