-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2020 at 05:44 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hris`
--

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE `training` (
  `recid_training` int(11) NOT NULL,
  `crt_by` varchar(11) NOT NULL,
  `crt_date` datetime NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `jenis_training` enum('Teknis','Non Teknis') NOT NULL,
  `kategori` enum('Umum','Khusus') NOT NULL,
  `recid_mtlevel` int(11) DEFAULT NULL,
  `recid_legal` int(11) NOT NULL,
  `judul_training` varchar(50) NOT NULL,
  `lembaga` varchar(50) NOT NULL,
  `trainer` varchar(50) NOT NULL,
  `tgl_m_training` date NOT NULL,
  `tgl_a_training` date NOT NULL,
  `jml_jam` int(11) NOT NULL,
  `tempat_training` varchar(35) NOT NULL,
  `biaya` int(11) NOT NULL,
  `scan_brosur` text NOT NULL,
  `alasan_pengajuan` text NOT NULL,
  `kompetensi` varchar(100) NOT NULL,
  `sertifikat` enum('Bersertifikat','Tidak Bersertifikat') NOT NULL,
  `tna` enum('Ya','Tidak') NOT NULL DEFAULT 'Ya',
  `evaluasi` enum('Ya','Tidak') NOT NULL DEFAULT 'Ya',
  `atasan` int(11) DEFAULT NULL,
  `email_evaluasi` enum('0','1') NOT NULL,
  `close_evaluasi` enum('0','1') NOT NULL,
  `acc_hc` date DEFAULT NULL,
  `acc_direksi` date DEFAULT NULL,
  `scan_direksi` text NOT NULL,
  `poin` int(11) NOT NULL,
  `mdf_by` varchar(11) NOT NULL,
  `mdf_date` datetime NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `training`
--

INSERT INTO `training` (`recid_training`, `crt_by`, `crt_date`, `tgl_pengajuan`, `jenis_training`, `kategori`, `recid_mtlevel`, `recid_legal`, `judul_training`, `lembaga`, `trainer`, `tgl_m_training`, `tgl_a_training`, `jml_jam`, `tempat_training`, `biaya`, `scan_brosur`, `alasan_pengajuan`, `kompetensi`, `sertifikat`, `tna`, `evaluasi`, `atasan`, `email_evaluasi`, `close_evaluasi`, `acc_hc`, `acc_direksi`, `scan_direksi`, `poin`, `mdf_by`, `mdf_date`, `note`) VALUES
(1, '1189', '2020-07-08 11:23:07', '0000-00-00', 'Teknis', 'Umum', NULL, 4094, 'Tes Training', '', 'anysah', '2020-07-08', '2020-07-08', 0, 'Kantor', 0, '', '', '', 'Tidak Bersertifikat', 'Ya', 'Ya', NULL, '0', '0', NULL, NULL, '', 0, '', '0000-00-00 00:00:00', ''),
(2, '1189', '2020-07-08 11:23:44', '0000-00-00', 'Teknis', 'Umum', NULL, 4095, 'Tes Training', '', 'anysah', '2020-07-08', '2020-07-08', 0, 'Kantor', 0, '', '', '', 'Tidak Bersertifikat', 'Ya', 'Ya', NULL, '0', '0', NULL, NULL, '', 0, '', '0000-00-00 00:00:00', ''),
(3, '1189', '2020-07-08 03:59:43', '0000-00-00', 'Teknis', 'Umum', NULL, 0, 'Tes Training', '', 'anysah', '2020-07-08', '2020-07-08', 0, 'Kantor', 0, '', '', '', 'Tidak Bersertifikat', 'Ya', 'Ya', NULL, '0', '0', NULL, NULL, '', 0, '', '0000-00-00 00:00:00', ''),
(4, '1189', '2020-07-08 04:10:41', '2020-07-08', 'Non Teknis', 'Umum', NULL, 0, 'Tes Training4', 'IT', 'anysah', '2020-07-08', '2020-07-08', 2, 'Kantor', 100000, 'bd534e74bd5fb70fdedd447981473f6c.pdf', 'test 4', '', 'Bersertifikat', 'Ya', 'Ya', NULL, '0', '0', NULL, NULL, '', 0, '', '0000-00-00 00:00:00', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `training`
--
ALTER TABLE `training`
  ADD PRIMARY KEY (`recid_training`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `training`
--
ALTER TABLE `training`
  MODIFY `recid_training` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
