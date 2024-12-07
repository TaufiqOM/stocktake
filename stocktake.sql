-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 07, 2024 at 05:20 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stocktake`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `assets_id` int(11) NOT NULL,
  `assets_code` varchar(255) NOT NULL,
  `assets_name` varchar(255) NOT NULL,
  `assets_img` varchar(255) DEFAULT NULL,
  `assets_in` datetime DEFAULT NULL,
  `assets_out` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `kondisi` int(11) DEFAULT NULL,
  `id_lokasi` int(11) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_pic` int(11) DEFAULT NULL,
  `bukti_foto` varchar(255) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`assets_id`, `assets_code`, `assets_name`, `assets_img`, `assets_in`, `assets_out`, `status`, `kondisi`, `id_lokasi`, `id_kategori`, `id_pic`, `bukti_foto`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 'A001', 'Laptop Lenovo', 'lenovo.jpg', '2024-12-01 09:00:00', '2024-12-15 17:00:00', 1, 4, 1, 1, 1, NULL, 1, '2024-12-07 07:57:31', '2024-12-07 16:00:31'),
(2, 'A002', 'Projector Epson', 'projector_epson.jpg', '2024-12-02 10:00:00', '2024-12-20 18:00:00', 1, 2, 1, 1, 2, NULL, 1, '2024-12-07 07:57:31', '2024-12-07 16:18:06'),
(3, 'A003', 'Meja Kerja', 'meja_kerja.jpg', '2024-12-03 08:30:00', '2024-12-17 16:00:00', 0, 1, 3, 4, 1, NULL, 0, '2024-12-07 07:57:31', '2024-12-07 15:17:06'),
(4, 'A004', 'Kursi Kantor', 'kursi_kantor.jpg', '2024-12-04 11:00:00', '2024-12-18 15:30:00', 0, 0, 4, 4, 2, '', 0, '2024-12-07 07:57:31', '2024-12-07 08:06:29'),
(5, 'A005', 'Printer Canon', 'printer_canon.jpg', '2024-12-05 13:00:00', '2024-12-19 14:00:00', 0, 0, 3, 1, 3, '', 0, '2024-12-07 07:57:31', '2024-12-07 08:06:31'),
(6, 'A006', 'Smartphone Samsung', 'smartphone_samsung.jpg', '2024-12-06 12:00:00', '2024-12-21 10:00:00', 0, 0, 2, 1, 2, '', 0, '2024-12-07 07:57:31', '2024-12-07 08:06:33'),
(7, 'A007', 'Monitor LG', 'monitor_lg.jpg', '2024-12-07 09:30:00', '2024-12-22 12:00:00', 0, 0, 4, 1, 1, '', 0, '2024-12-07 07:57:31', '2024-12-07 08:06:34'),
(8, 'A008', 'Kabel HDMI', 'kabel_hdmi.jpg', '2024-12-08 14:00:00', '2024-12-23 11:30:00', 0, 0, 2, 1, 3, '', 0, '2024-12-07 07:57:31', '2024-12-07 08:06:37'),
(9, 'A009', 'Router TP-Link', 'router_tplink.jpg', '2024-12-09 08:00:00', '2024-12-24 13:00:00', 0, 0, 1, 1, 2, '', 0, '2024-12-07 07:57:31', '2024-12-07 08:06:40'),
(10, 'A010', 'Speaker Bluetooth', 'speaker_bluetooth.jpg', '2024-12-10 10:30:00', '2024-12-25 14:30:00', 0, 0, 2, 1, 3, '', 0, '2024-12-07 07:57:31', '2024-12-07 08:06:41');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik', '2024-12-07 08:02:59', '2024-12-07 08:02:59'),
(2, 'Hardware', '2024-12-07 08:03:16', '2024-12-07 08:03:16'),
(3, 'Dokumen', '2024-12-07 08:03:22', '2024-12-07 08:03:22'),
(4, 'Perabotan', '2024-12-07 08:04:51', '2024-12-07 08:04:51');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE `lokasi` (
  `id_lokasi` int(11) NOT NULL,
  `nama_lokasi` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lokasi`
--

INSERT INTO `lokasi` (`id_lokasi`, `nama_lokasi`, `created_at`, `updated_at`) VALUES
(1, 'Kantor IT', '2024-12-07 08:03:59', '2024-12-07 08:03:59'),
(2, 'Kantin', '2024-12-07 08:03:59', '2024-12-07 08:03:59'),
(3, 'Kantor Atas', '2024-12-07 08:03:59', '2024-12-07 08:03:59'),
(4, 'Kantorn HRD', '2024-12-07 08:03:59', '2024-12-07 08:03:59');

-- --------------------------------------------------------

--
-- Table structure for table `pic`
--

CREATE TABLE `pic` (
  `id_pic` int(11) NOT NULL,
  `nama_pic` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pic`
--

INSERT INTO `pic` (`id_pic`, `nama_pic`, `created_at`, `updated_at`) VALUES
(1, 'IT', '2024-12-07 08:06:17', '2024-12-07 08:06:17'),
(2, 'Maintenance', '2024-12-07 08:06:17', '2024-12-07 08:06:17'),
(3, 'HRD', '2024-12-07 08:06:17', '2024-12-07 08:06:17');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, '', '123', '$2y$10$tnswvyco39HKPoqNxvfble.lMltRycn9QZvEURO10x25fsm4TvIcS', '2024-12-07 15:43:43', '2024-12-07 15:55:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`assets_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id_lokasi`);

--
-- Indexes for table `pic`
--
ALTER TABLE `pic`
  ADD PRIMARY KEY (`id_pic`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `assets_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id_lokasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pic`
--
ALTER TABLE `pic`
  MODIFY `id_pic` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
