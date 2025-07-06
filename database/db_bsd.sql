-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2025 at 09:00 AM
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
(1, 'budi', 'budi@gmail.com', 'budie', '$2y$10$WyS0wHOrPGE7PzTu7Kks2.uh/7RfxiqGrDQYrFXhSum/i9aX73dMC', '081211213213', 'superadmin', 1, '2025-05-19 10:53:02', '2025-07-06 02:42:15'),
(7, 'admin', 'admin@example.com', 'admin', '$2y$10$o5us4MfszbY2WKnLZ4DNj.t2kQE/8zICUeWpoZ1cs/oDTCOmI8UxS', '089', 'admin', 1, '2025-07-06 05:41:36', NULL),
(8, 'petugas', 'petugas@example.com', 'petugas', '$2y$10$bcTZkF4qPyGmnLx06pWmZus5hEhqeYF5MAqJKEYStsJKmANdfqeP6', '082', 'petugas', 1, '2025-07-06 05:42:23', '2025-07-06 06:28:24');

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

--
-- Dumping data for table `tb_detail_setoran`
--

INSERT INTO `tb_detail_setoran` (`id`, `id_setoran`, `id_sampah`, `berat_kg`, `poin`, `created_at`) VALUES
(1, 5, 3, 3.00, 12000, '2025-07-06 06:44:26'),
(2, 6, 4, 3.00, 6000, '2025-07-06 06:47:53'),
(3, 6, 5, 10.00, 5000, '2025-07-06 06:47:53');

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

--
-- Dumping data for table `tb_nasabah`
--

INSERT INTO `tb_nasabah` (`id`, `nin`, `nama`, `jenis_kelamin`, `alamat`, `email`, `no_telephone`, `saldo_poin`, `tanggal_daftar`, `status`, `created_at`, `updated_at`) VALUES
(4, '2506070001', 'nasabah 1', 'l', 'maron probolinggo', 'nasabah@1.com', '087', 12000, '2025-07-06', 1, '2025-07-06 06:31:37', '2025-07-06 06:44:26'),
(5, '2506070002', 'nasabah 2', 'p', 'paiton probolinggo', 'nasabah@2.com', '085', 11000, '2025-07-05', 1, '2025-07-06 06:38:30', '2025-07-06 06:47:53');

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

--
-- Dumping data for table `tb_sampah`
--

INSERT INTO `tb_sampah` (`id`, `jenis_sampah`, `harga_per_kg`, `deskripsi`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Botol plastik', 4000, 'PET, HDPE, botol plastik campuran, botol sabun, sa', 1, '2025-07-06 06:36:02', '2025-07-06 06:36:17'),
(4, 'Kertas Putih/HVS', 2000, 'Arsip, Buku Tulis tanpa Cover, kertas HVS', 1, '2025-07-06 06:40:20', '2025-07-06 06:40:24'),
(5, 'Koran/Kertas Buram', 500, 'Koran, kertas bertinta, Kertas Campuran, Buku Pela', 1, '2025-07-06 06:42:56', NULL);

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
-- Dumping data for table `tb_setoran`
--

INSERT INTO `tb_setoran` (`id`, `id_nasabah`, `id_admin`, `tanggal_transaksi`, `total_poin`, `status_transaksi`, `created_at`, `updated_at`) VALUES
(5, 4, 8, '2025-07-07', 12000, 1, '2025-07-06 06:44:26', NULL),
(6, 5, 8, '2025-07-06', 11000, 1, '2025-07-06 06:47:52', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_detail_setoran`
--
ALTER TABLE `tb_detail_setoran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_nasabah`
--
ALTER TABLE `tb_nasabah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_remember_tokens`
--
ALTER TABLE `tb_remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_sampah`
--
ALTER TABLE `tb_sampah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_setoran`
--
ALTER TABLE `tb_setoran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
