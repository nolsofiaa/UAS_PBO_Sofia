-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2026 at 01:23 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_uas_pbo_trpl1b_sofia`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_karyawan`
--

CREATE TABLE `tabel_karyawan` (
  `id_karyawan` int NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `departemen` varchar(50) NOT NULL,
  `hari_kerja_masuk` date NOT NULL,
  `gaji_dasar_per_hari` decimal(15,2) NOT NULL,
  `durasi_kontrak_bulan` int DEFAULT NULL,
  `agensi_penyalur` varchar(100) DEFAULT NULL,
  `tunjangan_kesehatan` decimal(15,2) DEFAULT NULL,
  `opsi_saham_id` varchar(50) DEFAULT NULL,
  `uang_saku_bulanan` decimal(15,2) DEFAULT NULL,
  `sertifikat_kampus_merdeka` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_karyawan`
--

INSERT INTO `tabel_karyawan` (`id_karyawan`, `nama_karyawan`, `departemen`, `hari_kerja_masuk`, `gaji_dasar_per_hari`, `durasi_kontrak_bulan`, `agensi_penyalur`, `tunjangan_kesehatan`, `opsi_saham_id`, `uang_saku_bulanan`, `sertifikat_kampus_merdeka`) VALUES
(1, 'Budi Santoso', 'IT', '2023-01-15', '250000.00', NULL, NULL, '500000.00', 'OPT001', NULL, NULL),
(2, 'Siti Rahayu', 'HRD', '2023-02-01', '200000.00', NULL, NULL, '400000.00', 'OPT002', NULL, NULL),
(3, 'Ahmad Fauzi', 'Finance', '2023-03-10', '275000.00', NULL, NULL, '550000.00', 'OPT003', NULL, NULL),
(4, 'Dewi Lestari', 'Marketing', '2023-04-05', '225000.00', NULL, NULL, '450000.00', 'OPT004', NULL, NULL),
(5, 'Rudi Hermawan', 'IT', '2023-05-20', '300000.00', NULL, NULL, '600000.00', 'OPT005', NULL, NULL),
(6, 'Nina Kurnia', 'HRD', '2023-06-12', '210000.00', NULL, NULL, '420000.00', 'OPT006', NULL, NULL),
(7, 'Andi Wijaya', 'Finance', '2023-07-08', '260000.00', NULL, NULL, '520000.00', 'OPT007', NULL, NULL),
(8, 'Rina Febrianti', 'Marketing', '2023-08-15', '230000.00', NULL, NULL, '460000.00', 'OPT008', NULL, NULL),
(9, 'Doni Pratama', 'IT', '2023-09-01', '280000.00', NULL, NULL, '560000.00', 'OPT009', NULL, NULL),
(10, 'Maya Sari', 'HRD', '2023-10-10', '215000.00', NULL, NULL, '430000.00', 'OPT010', NULL, NULL),
(11, 'Agus Prasetyo', 'IT', '2024-01-02', '200000.00', 6, 'PT Jaya Abadi', '200000.00', NULL, NULL, NULL),
(12, 'Linda Wati', 'HRD', '2024-01-15', '180000.00', 6, 'PT Mitra Sejahtera', '180000.00', NULL, NULL, NULL),
(13, 'Bagus Nugroho', 'Finance', '2024-02-01', '220000.00', 12, 'PT Karya Mandiri', '220000.00', NULL, NULL, NULL),
(14, 'Susi Purnama', 'Marketing', '2024-02-14', '190000.00', 6, 'PT Bina Usaha', '190000.00', NULL, NULL, NULL),
(15, 'Hendra Gunawan', 'IT', '2024-03-01', '210000.00', 12, 'PT Teknologi Nusantara', '210000.00', NULL, NULL, NULL),
(16, 'Rika Damayanti', 'HRD', '2024-03-15', '185000.00', 6, 'PT Sumber Daya Manusia', '185000.00', NULL, NULL, NULL),
(17, 'Fajar Setiawan', 'Finance', '2024-04-01', '225000.00', 12, 'PT Finansial Solutions', '225000.00', NULL, NULL, NULL),
(18, 'Yuni Anggraini', 'Marketing', '2024-04-14', '195000.00', 6, 'PT Marketing Pro', '195000.00', NULL, NULL, NULL),
(19, 'Galih Prakoso', 'IT', '2024-05-01', '215000.00', 12, 'PT Digital Innovasi', '215000.00', NULL, NULL, NULL),
(20, 'Dina Kusuma', 'HRD', '2024-05-15', '190000.00', 6, 'PT People Development', '190000.00', NULL, NULL, NULL),
(21, 'Rizky Ramadhan', 'IT', '2024-06-01', '100000.00', NULL, NULL, NULL, NULL, '2000000.00', 'MSIB-001'),
(22, 'Putri Maharani', 'HRD', '2024-06-03', '90000.00', NULL, NULL, NULL, NULL, '1800000.00', 'MSIB-002'),
(23, 'Dandi Permana', 'Finance', '2024-06-05', '110000.00', NULL, NULL, NULL, NULL, '2200000.00', 'MSIB-003'),
(24, 'Citra Dewi', 'Marketing', '2024-06-07', '95000.00', NULL, NULL, NULL, NULL, '1900000.00', 'MSIB-004'),
(25, 'Eko Prasetyo', 'IT', '2024-06-10', '105000.00', NULL, NULL, NULL, NULL, '2100000.00', 'MSIB-005'),
(26, 'Ratna Sari', 'HRD', '2024-06-12', '92000.00', NULL, NULL, NULL, NULL, '1850000.00', 'MSIB-006'),
(27, 'Bayu Saputra', 'Finance', '2024-06-14', '115000.00', NULL, NULL, NULL, NULL, '2300000.00', 'MSIB-007'),
(28, 'Nadia Rahma', 'Marketing', '2024-06-17', '98000.00', NULL, NULL, NULL, NULL, '1950000.00', 'MSIB-008'),
(29, 'Gilang Pratama', 'IT', '2024-06-19', '108000.00', NULL, NULL, NULL, NULL, '2150000.00', 'MSIB-009'),
(30, 'Tia Anggraini', 'HRD', '2024-06-21', '93000.00', NULL, NULL, NULL, NULL, '1860000.00', 'MSIB-010'),
(31, 'Irfan Hakim', 'IT', '2023-11-01', '290000.00', NULL, NULL, '580000.00', 'OPT011', NULL, NULL),
(32, 'Anisa Fitri', 'HRD', '2023-11-15', '220000.00', NULL, NULL, '440000.00', 'OPT012', NULL, NULL),
(33, 'Hendrik Suwandi', 'Finance', '2023-12-01', '285000.00', NULL, NULL, '570000.00', 'OPT013', NULL, NULL),
(34, 'Sari Wijaya', 'Marketing', '2023-12-15', '240000.00', NULL, NULL, '480000.00', 'OPT014', NULL, NULL),
(35, 'Tony Kurniawan', 'IT', '2024-01-02', '310000.00', NULL, NULL, '620000.00', 'OPT015', NULL, NULL),
(36, 'Ika Puspita', 'HRD', '2024-01-16', '225000.00', NULL, NULL, '450000.00', 'OPT016', NULL, NULL),
(37, 'Arief Budiman', 'Finance', '2024-02-01', '270000.00', NULL, NULL, '540000.00', 'OPT017', NULL, NULL),
(38, 'Diana Kartika', 'Marketing', '2024-02-15', '245000.00', NULL, NULL, '490000.00', 'OPT018', NULL, NULL),
(39, 'Ronald Simanjuntak', 'IT', '2024-03-01', '295000.00', NULL, NULL, '590000.00', 'OPT019', NULL, NULL),
(40, 'Fitri Handayani', 'HRD', '2024-03-15', '230000.00', NULL, NULL, '460000.00', 'OPT020', NULL, NULL),
(41, 'Rizky Maulana', 'IT', '2024-06-01', '205000.00', 6, 'PT Solusi Digital', '205000.00', NULL, NULL, NULL),
(42, 'Putri Amelia', 'HRD', '2024-06-03', '192000.00', 6, 'PT Human Capital', '192000.00', NULL, NULL, NULL),
(43, 'Fahri Abdullah', 'Finance', '2024-06-05', '230000.00', 12, 'PT Keuangan Berkah', '230000.00', NULL, NULL, NULL),
(44, 'Nurul Aini', 'Marketing', '2024-06-07', '198000.00', 6, 'PT Branding Indonesia', '198000.00', NULL, NULL, NULL),
(45, 'Slamet Riyadi', 'IT', '2024-06-10', '212000.00', 12, 'PT Tech Solution', '212000.00', NULL, NULL, NULL),
(46, 'Wulan Sari', 'HRD', '2024-06-12', '188000.00', 6, 'PT HR Management', '188000.00', NULL, NULL, NULL),
(47, 'Heri Susanto', 'Finance', '2024-06-14', '228000.00', 12, 'PT Financial Partners', '228000.00', NULL, NULL, NULL),
(48, 'Tiara Lestari', 'Marketing', '2024-06-17', '196000.00', 6, 'PT Digital Marketing', '196000.00', NULL, NULL, NULL),
(49, 'Yusuf Anwar', 'IT', '2024-06-19', '218000.00', 12, 'PT Coding Academy', '218000.00', NULL, NULL, NULL),
(50, 'Mega Pratiwi', 'HRD', '2024-06-21', '190000.00', 6, 'PT People Solutions', '190000.00', NULL, NULL, NULL),
(51, 'Safira Nuraini', 'IT', '2024-07-01', '102000.00', NULL, NULL, NULL, NULL, '2040000.00', 'MSIB-011'),
(52, 'Aditya Nugraha', 'HRD', '2024-07-03', '94000.00', NULL, NULL, NULL, NULL, '1880000.00', 'MSIB-012'),
(53, 'Citra Kirana', 'Finance', '2024-07-05', '112000.00', NULL, NULL, NULL, NULL, '2240000.00', 'MSIB-013'),
(54, 'Dimas Anggara', 'Marketing', '2024-07-07', '97000.00', NULL, NULL, NULL, NULL, '1940000.00', 'MSIB-014'),
(55, 'Aulia Rachman', 'IT', '2024-07-10', '106000.00', NULL, NULL, NULL, NULL, '2120000.00', 'MSIB-015'),
(56, 'Karina Putri', 'HRD', '2024-07-12', '91000.00', NULL, NULL, NULL, NULL, '1820000.00', 'MSIB-016'),
(57, 'Indra Lesmana', 'Finance', '2024-07-14', '118000.00', NULL, NULL, NULL, NULL, '2360000.00', 'MSIB-017'),
(58, 'Reni Oktaviani', 'Marketing', '2024-07-17', '99000.00', NULL, NULL, NULL, NULL, '1980000.00', 'MSIB-018'),
(59, 'Arif Rahman', 'IT', '2024-07-19', '109000.00', NULL, NULL, NULL, NULL, '2180000.00', 'MSIB-019'),
(60, 'Intan Permatasari', 'HRD', '2024-07-21', '95000.00', NULL, NULL, NULL, NULL, '1900000.00', 'MSIB-020');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_karyawan`
--
ALTER TABLE `tabel_karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_karyawan`
--
ALTER TABLE `tabel_karyawan`
  MODIFY `id_karyawan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
