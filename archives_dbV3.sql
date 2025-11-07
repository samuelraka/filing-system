-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2025 at 05:07 AM
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
-- Database: `archives_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `arsip_aktif`
--

CREATE TABLE `arsip_aktif` (
  `id_arsip` int(11) NOT NULL,
  `id_subsub` int(11) NOT NULL,
  `nomor_berkas` int(11) NOT NULL,
  `jumlah_item` int(11) DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `arsip_aktif`
--

INSERT INTO `arsip_aktif` (`id_arsip`, `id_subsub`, `nomor_berkas`, `jumlah_item`, `keterangan`, `created_at`) VALUES
(1, 1, 1, 3, 'Testing', '2025-11-06 22:36:31'),
(4, 1, 2, 1, 'asfasfafas', '2025-11-07 00:25:37');

-- --------------------------------------------------------

--
-- Table structure for table `arsip_inaktif`
--

CREATE TABLE `arsip_inaktif` (
  `id_arsip` int(11) NOT NULL,
  `id_subsub` int(11) NOT NULL,
  `nomor_berkas` int(11) NOT NULL,
  `jumlah_item` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `arsip_inaktif`
--

INSERT INTO `arsip_inaktif` (`id_arsip`, `id_subsub`, `nomor_berkas`, `jumlah_item`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, '2025-11-07 09:14:01', '2025-11-07 09:40:35'),
(2, 2, 2, 1, '2025-11-07 09:41:31', '2025-11-07 09:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `arsip_statis`
--

CREATE TABLE `arsip_statis` (
  `id_arsip_statis` int(11) NOT NULL,
  `id_subsub` int(11) NOT NULL,
  `jenis_arsip` varchar(150) NOT NULL,
  `tahun` year(4) NOT NULL,
  `jumlah` int(11) DEFAULT 0,
  `tingkat_perkembangan` enum('Asli','Salinan','Lengkap') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `arsip_statis`
--

INSERT INTO `arsip_statis` (`id_arsip_statis`, `id_subsub`, `jenis_arsip`, `tahun`, `jumlah`, `tingkat_perkembangan`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 'Surat Keputusan', '2022', 3, 'Lengkap', 'Catatan Nomor 1', '2025-11-06 21:22:45', '2025-11-06 21:22:45'),
(2, 2, 'Laporan', '2025', 2, 'Asli', 'Contoh 2', '2025-11-06 21:25:52', '2025-11-06 21:25:52');

-- --------------------------------------------------------

--
-- Table structure for table `arsip_vital`
--

CREATE TABLE `arsip_vital` (
  `id_arsip` int(11) NOT NULL,
  `jenis_arsip` varchar(255) NOT NULL,
  `tingkat_perkembangan` varchar(100) NOT NULL,
  `kurun_tahun` varchar(50) NOT NULL,
  `media` varchar(100) NOT NULL,
  `jumlah` int(11) DEFAULT 1,
  `jangka_simpan` varchar(100) NOT NULL,
  `lokasi_simpan` varchar(150) NOT NULL,
  `metode_perlindungan` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `arsip_vital`
--

INSERT INTO `arsip_vital` (`id_arsip`, `uraian_arsip`, `unit_kerja`, `kurun_waktu`, `media`, `jumlah`, `jangka_simpan`, `lokasi_simpan`, `metode_perlindungan`, `keterangan`, `created_at`) VALUES
(1, 'Testing', 'TI', '2022-2025', 'Kertas', 3, '5', 'Ruang Arsip 1`', 'Enkripsi', 'Testing', '2025-11-05 18:36:34'),
(2, 'Testing 2', 'TI', '2022-2025', 'Digital', 3, '5', 'Ruang Arsip 2', 'Laminasi', 'Penting', '2025-11-06 21:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `item_arsip`
--

CREATE TABLE `item_arsip` (
  `id_item` int(11) NOT NULL,
  `id_arsip` int(11) NOT NULL,
  `nomor_item` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan_skaad` varchar(100) DEFAULT NULL,
  `uraian_singkat` text NOT NULL,
  `uraian_informasi` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_arsip`
--

INSERT INTO `item_arsip` (`id_item`, `id_arsip`, `nomor_item`, `tanggal`, `keterangan_skaad`, `uraian_singkat`, `uraian_informasi`, `file_path`, `created_at`) VALUES
(1, 1, 1, '2024-01-02', 'Rahasia', 'Test', 'Test', '1762468591_Application Form(Samuel Raka Yustianto).pdf', '2025-11-06 22:36:31'),
(2, 1, 2, '2024-01-02', 'Rahasia', 'Testing Ke 2', 'Testing Informasi 2', '1762469000_99-File Utama Naskah-453-1-10-20220715.pdf', '2025-11-06 22:43:20'),
(4, 1, 3, '2024-02-01', 'Rahasia', 'asdasd', 'asdasda', '1762470224_14701-Article Text-44223-1-10-20240306.pdf', '2025-11-06 23:03:44'),
(7, 4, 1, '2025-05-01', 'Terbuka', 'ALSKdaldnalsdnasd', 'asdsafafasf', '1762475137_Paper22359.pdf', '2025-11-07 00:25:37');

-- --------------------------------------------------------

--
-- Table structure for table `item_arsip_inaktif`
--

CREATE TABLE `item_arsip_inaktif` (
  `id_item` int(11) NOT NULL,
  `id_arsip` int(11) NOT NULL,
  `nomor_item` int(11) NOT NULL,
  `kategori_arsip` varchar(100) NOT NULL,
  `kurun_waktu` date NOT NULL,
  `jangka_simpan` date NOT NULL,
  `nomor_boks` varchar(50) NOT NULL,
  `lokasi_simpan` varchar(100) NOT NULL,
  `tingkat_perkembangan` varchar(50) NOT NULL,
  `uraian_singkat` varchar(255) NOT NULL,
  `uraian_informasi` text NOT NULL,
  `keterangan` text DEFAULT NULL,
  `file_path` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_arsip_inaktif`
--

INSERT INTO `item_arsip_inaktif` (`id_item`, `id_arsip`, `nomor_item`, `kategori_arsip`, `kurun_waktu`, `jangka_simpan`, `nomor_boks`, `lokasi_simpan`, `tingkat_perkembangan`, `uraian_singkat`, `uraian_informasi`, `keterangan`, `file_path`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Pajak', '2022-02-01', '2025-02-01', 'SAMS-002', 'Gudang A', 'Asli', 'asdadasd', 'asdadasda', 'asdasdadads', '1762481641_Offering_A_Product_Recommendation_System_in_E-comm.pdf', '2025-11-07 09:14:01', '2025-11-07 09:14:01'),
(2, 1, 2, 'Pajak', '2022-02-13', '2025-02-13', 'SAMS-003', 'Gudang A', 'Asli', 'sdadasdasdsasd', 'dsfdsfsdfsdfadsasasd', 'asdasdadafdfsfsdf', '1762483160_artificial-intelligence-and-its-drastic-impact-on-ecommerce-progress.pdf', '2025-11-07 09:39:20', '2025-11-07 09:39:20'),
(3, 1, 3, 'Keuangan', '2022-03-15', '2025-03-15', 'SABS-001', 'Gudang B', 'Asli', 'ASDASSFSDF', 'ASDASDWSFSFD', 'ASDADASDADCC', '1762483235_Hybrid_content_and_collaborative_filteri.pdf', '2025-11-07 09:40:35', '2025-11-07 09:40:35'),
(4, 2, 1, 'Keuangan', '2022-03-17', '2025-03-17', 'SABS-002', 'Gudang B', 'Asli', 'ASDADADAXZXZ', 'ZCXZCZCASDADSADS', 'ASDADADADS', '1762483291_FLORA+-+VOLUME.+2,+NOMOR.+1,+TAHUN+2025+HAL+139-151.pdf', '2025-11-07 09:41:31', '2025-11-07 09:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `pokok_masalah`
--

CREATE TABLE `pokok_masalah` (
  `id_pokok` int(11) NOT NULL,
  `kode_pokok` varchar(50) NOT NULL,
  `topik_pokok` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pokok_masalah`
--

INSERT INTO `pokok_masalah` (`id_pokok`, `kode_pokok`, `topik_pokok`, `created_at`, `updated_at`) VALUES
(1, 'PM-001', 'Contoh Pokok Masalah 1', '2025-11-01 14:37:49', '2025-11-01 15:34:45'),
(3, 'PM-002', 'Contoh Pokok Masalah 2', '2025-11-01 23:36:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `profil`
--

CREATE TABLE `profil` (
  `id_profil` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil`
--

INSERT INTO `profil` (`id_profil`, `id_user`, `id_unit`, `created_at`, `updated_at`) VALUES
(1, 7, 2, '2025-10-24 00:16:18', NULL),
(2, 8, 1, '2025-10-24 00:17:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_masalah`
--

CREATE TABLE `sub_masalah` (
  `id_sub` int(11) NOT NULL,
  `id_pokok` int(11) NOT NULL,
  `kode_sub` varchar(10) NOT NULL,
  `topik_sub` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_masalah`
--

INSERT INTO `sub_masalah` (`id_sub`, `id_pokok`, `kode_sub`, `topik_sub`, `created_at`, `updated_at`) VALUES
(1, 1, 'SM-001', 'Contoh Sub Masalah 1', '2025-11-01 15:55:44', '2025-11-01 23:23:35'),
(3, 3, 'SM-002', 'Contoh Sub Masalah 2', '2025-11-02 05:30:38', NULL),
(4, 1, 'SM-003', 'Contoh Sub Masalah 3', '2025-11-02 05:31:54', '2025-11-05 22:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `sub_sub_masalah`
--

CREATE TABLE `sub_sub_masalah` (
  `id_subsub` int(11) NOT NULL,
  `id_sub` int(11) NOT NULL,
  `kode_subsub` varchar(10) NOT NULL,
  `topik_subsub` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_sub_masalah`
--

INSERT INTO `sub_sub_masalah` (`id_subsub`, `id_sub`, `kode_subsub`, `topik_subsub`, `created_at`, `updated_at`) VALUES
(1, 1, 'SSM-001', 'Contoh Sub Sub Masalah 1', '2025-11-02 05:13:47', '2025-11-02 05:30:12'),
(2, 4, 'SSM-002', 'Contoh Sub Sub Masalah 2', '2025-11-02 05:30:52', '2025-11-05 22:56:06');

-- --------------------------------------------------------

--
-- Table structure for table `unit_pengolah`
--

CREATE TABLE `unit_pengolah` (
  `id_unit` int(11) NOT NULL,
  `kode_unit` varchar(10) NOT NULL,
  `nama_unit` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit_pengolah`
--

INSERT INTO `unit_pengolah` (`id_unit`, `kode_unit`, `nama_unit`, `created_at`, `updated_at`) VALUES
(1, 'UP-001	', 'Unit A', '2025-10-22 10:28:56', NULL),
(2, 'UP-002	', 'Unit B', '2025-10-22 10:32:14', NULL),
(3, 'UP-003', 'Unit C', '2025-10-22 11:45:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','user') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `email`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin Utama', 'superadmin@poltekes.ac.id', 'superadmin', 'Superadmin123', 'superadmin', '2025-10-21 21:47:05', '2025-10-22 10:47:11'),
(2, 'Admin Arsip', 'admin@poltekes.ac.id', 'admin', '12345', 'admin', '2025-10-21 21:47:05', '2025-10-22 07:40:13'),
(3, 'User Staf Umum', 'samuelngampus@gmail.com', 'Samuel_Raka_Yustianto', 'Samuelraka_190303', 'user', '2025-10-21 21:47:05', '2025-11-01 13:34:16'),
(4, 'Super Admin Kedua', 'superadmin2@poltekes.ac.id', 'superadmin2', '$2y$10$FssyoN1P0B2X7H1olcBaVucVjW7BMMtP7y63J5QfZpHu9K8hN2kzC', 'superadmin', '2025-10-21 23:15:08', NULL),
(5, 'Admin Arsip Kedua', 'admin2@poltekes.ac.id', 'admin2', '$2y$10$FssyoN1P0B2X7H1olcBaVucVjW7BMMtP7y63J5QfZpHu9K8hN2kzC', 'admin', '2025-10-21 23:15:08', NULL),
(6, 'User Staf Umum Kedua', 'user2@poltekes.ac.id', 'stafumum2', '$2y$10$FssyoN1P0B2X7H1olcBaVucVjW7BMMtP7y63J5QfZpHu9K8hN2kzC', 'user', '2025-10-21 23:15:08', NULL),
(7, 'Samuel Raka Yustianto', 'samuelrakayustianto@gmail.com', 'usrZZRYC', '$2y$10$AuwLNOY4dFSXNcoiZZfmuOZQdoYfl9EuYLX6xtr1AfX4YFHL0XdEe', 'admin', '2025-10-24 00:16:18', NULL),
(8, 'Pengguna 2', 'azza@gmail.com', 'usr0XX2K', '$2y$10$p8VKUL6kLs2nEI5BbFhAQO16FT.9d1MlSUNc8dXXQWhDTCUw033uO', 'user', '2025-10-24 00:17:08', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arsip_aktif`
--
ALTER TABLE `arsip_aktif`
  ADD PRIMARY KEY (`id_arsip`),
  ADD KEY `id_subsub` (`id_subsub`);

--
-- Indexes for table `arsip_inaktif`
--
ALTER TABLE `arsip_inaktif`
  ADD PRIMARY KEY (`id_arsip`),
  ADD KEY `id_subsub` (`id_subsub`);

--
-- Indexes for table `arsip_statis`
--
ALTER TABLE `arsip_statis`
  ADD PRIMARY KEY (`id_arsip_statis`),
  ADD KEY `fk_subsub_arsipstatis` (`id_subsub`);

--
-- Indexes for table `arsip_vital`
--
ALTER TABLE `arsip_vital`
  ADD PRIMARY KEY (`id_arsip`);

--
-- Indexes for table `item_arsip`
--
ALTER TABLE `item_arsip`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_arsip` (`id_arsip`);

--
-- Indexes for table `item_arsip_inaktif`
--
ALTER TABLE `item_arsip_inaktif`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_arsip` (`id_arsip`);

--
-- Indexes for table `pokok_masalah`
--
ALTER TABLE `pokok_masalah`
  ADD PRIMARY KEY (`id_pokok`);

--
-- Indexes for table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id_profil`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_unit` (`id_unit`);

--
-- Indexes for table `sub_masalah`
--
ALTER TABLE `sub_masalah`
  ADD PRIMARY KEY (`id_sub`),
  ADD KEY `id_pokok` (`id_pokok`);

--
-- Indexes for table `sub_sub_masalah`
--
ALTER TABLE `sub_sub_masalah`
  ADD PRIMARY KEY (`id_subsub`),
  ADD KEY `id_sub` (`id_sub`);

--
-- Indexes for table `unit_pengolah`
--
ALTER TABLE `unit_pengolah`
  ADD PRIMARY KEY (`id_unit`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arsip_aktif`
--
ALTER TABLE `arsip_aktif`
  MODIFY `id_arsip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `arsip_inaktif`
--
ALTER TABLE `arsip_inaktif`
  MODIFY `id_arsip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `arsip_statis`
--
ALTER TABLE `arsip_statis`
  MODIFY `id_arsip_statis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `arsip_vital`
--
ALTER TABLE `arsip_vital`
  MODIFY `id_arsip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `item_arsip`
--
ALTER TABLE `item_arsip`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `item_arsip_inaktif`
--
ALTER TABLE `item_arsip_inaktif`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pokok_masalah`
--
ALTER TABLE `pokok_masalah`
  MODIFY `id_pokok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sub_masalah`
--
ALTER TABLE `sub_masalah`
  MODIFY `id_sub` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sub_sub_masalah`
--
ALTER TABLE `sub_sub_masalah`
  MODIFY `id_subsub` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `unit_pengolah`
--
ALTER TABLE `unit_pengolah`
  MODIFY `id_unit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arsip_aktif`
--
ALTER TABLE `arsip_aktif`
  ADD CONSTRAINT `arsip_aktif_ibfk_1` FOREIGN KEY (`id_subsub`) REFERENCES `sub_sub_masalah` (`id_subsub`) ON DELETE CASCADE;

--
-- Constraints for table `arsip_inaktif`
--
ALTER TABLE `arsip_inaktif`
  ADD CONSTRAINT `arsip_inaktif_ibfk_1` FOREIGN KEY (`id_subsub`) REFERENCES `sub_sub_masalah` (`id_subsub`);

--
-- Constraints for table `arsip_statis`
--
ALTER TABLE `arsip_statis`
  ADD CONSTRAINT `fk_subsub_arsipstatis` FOREIGN KEY (`id_subsub`) REFERENCES `sub_sub_masalah` (`id_subsub`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `item_arsip`
--
ALTER TABLE `item_arsip`
  ADD CONSTRAINT `item_arsip_ibfk_1` FOREIGN KEY (`id_arsip`) REFERENCES `arsip_aktif` (`id_arsip`) ON DELETE CASCADE;

--
-- Constraints for table `item_arsip_inaktif`
--
ALTER TABLE `item_arsip_inaktif`
  ADD CONSTRAINT `item_arsip_inaktif_ibfk_1` FOREIGN KEY (`id_arsip`) REFERENCES `arsip_inaktif` (`id_arsip`);

--
-- Constraints for table `profil`
--
ALTER TABLE `profil`
  ADD CONSTRAINT `profil_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `profil_ibfk_2` FOREIGN KEY (`id_unit`) REFERENCES `unit_pengolah` (`id_unit`) ON DELETE CASCADE;

--
-- Constraints for table `sub_masalah`
--
ALTER TABLE `sub_masalah`
  ADD CONSTRAINT `sub_masalah_ibfk_1` FOREIGN KEY (`id_pokok`) REFERENCES `pokok_masalah` (`id_pokok`) ON DELETE CASCADE;

--
-- Constraints for table `sub_sub_masalah`
--
ALTER TABLE `sub_sub_masalah`
  ADD CONSTRAINT `sub_sub_masalah_ibfk_1` FOREIGN KEY (`id_sub`) REFERENCES `sub_masalah` (`id_sub`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
