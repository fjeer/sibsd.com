-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 04:50 PM
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
  `no_telephone` char(15) NOT NULL,
  `role` enum('superadmin','admin','petugas') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id`, `nama`, `email`, `no_telephone`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'budi', 'budi@gmail.com', '081211213213', 'superadmin', 1, '2025-05-19 10:53:02', '2025-05-19 12:08:58'),
(2, 'gamalsinklear', 'gamals@gmail.com', '45821939', 'admin', 1, '2025-05-19 10:55:48', '2025-05-20 11:59:46'),
(3, 'karyawan', 'yawan@gmail.com', '089765432123', 'petugas', 1, '2025-05-20 11:40:10', NULL),
(4, 'samsul', 'samsoel@gimal.kom', '08976543211', 'petugas', 1, '2025-05-20 11:54:59', '2025-05-20 11:57:29');

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
(1, '2520050001', 'Ginanjar Prabroro', 'l', 'Solo Jawa', 'sebelasperseratus@gmail.com', '08262582616', NULL, '2025-05-20', 1, '2025-05-20 13:38:39', '2025-05-20 13:38:39'),
(2, '2520050002', 'Desi', 'p', 'Jl. Malang Raya, Dusun Kaliwates, Kec. Bungurasih, Kab.Pangkalpinang', 'd3ssi@kimal.gom', '0987625431', NULL, '2025-05-20', 1, '2025-05-20 13:52:57', '2025-05-20 14:04:33');

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
(1, 'Plastik', 2000, 'Botol, Sedotan, Styrofoam dll', 1, '2025-05-20 14:43:08', '2025-05-20 14:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `tb_setor_sampah`
--

CREATE TABLE `tb_setor_sampah` (
  `id` int(11) NOT NULL,
  `id_nasabah` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `id_sampah` int(11) NOT NULL,
  `berat_sampah` int(11) NOT NULL,
  `total_poin` int(11) DEFAULT NULL,
  `tanggal_transaksi` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_nasabah`
--
ALTER TABLE `tb_nasabah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_induk_nasabah` (`nin`);

--
-- Indexes for table `tb_sampah`
--
ALTER TABLE `tb_sampah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_setor_sampah`
--
ALTER TABLE `tb_setor_sampah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_setor_sampah_ibfk_1` (`id_admin`),
  ADD KEY `tb_setor_sampah_ibfk_2` (`id_nasabah`),
  ADD KEY `id_sampah` (`id_sampah`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_nasabah`
--
ALTER TABLE `tb_nasabah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_sampah`
--
ALTER TABLE `tb_sampah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_setor_sampah`
--
ALTER TABLE `tb_setor_sampah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_setor_sampah`
--
ALTER TABLE `tb_setor_sampah`
  ADD CONSTRAINT `tb_setor_sampah_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `tb_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_setor_sampah_ibfk_2` FOREIGN KEY (`id_nasabah`) REFERENCES `tb_nasabah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_setor_sampah_ibfk_3` FOREIGN KEY (`id_sampah`) REFERENCES `tb_sampah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
