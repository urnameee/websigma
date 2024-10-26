-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 26, 2024 at 10:18 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sigma`
--

-- --------------------------------------------------------

--
-- Table structure for table `divisi_ukm`
--

CREATE TABLE `divisi_ukm` (
  `id_divisi` int NOT NULL,
  `id_ukm` int DEFAULT NULL,
  `nama_divisi` varchar(100) NOT NULL,
  `deskripsi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokumentasi_kegiatan`
--

CREATE TABLE `dokumentasi_kegiatan` (
  `id_dokumentasi` int NOT NULL,
  `id_timeline` int DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `foto_path` varchar(255) NOT NULL,
  `ukuran_file` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_pendaftaran`
--

CREATE TABLE `dokumen_pendaftaran` (
  `id_dokumen` int NOT NULL,
  `id_pendaftaran` int DEFAULT NULL,
  `id_jenis_dokumen` int DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_tags`
--

CREATE TABLE `event_tags` (
  `id_tag` int NOT NULL,
  `nama_tag` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fakultas`
--

CREATE TABLE `fakultas` (
  `id_fakultas` int NOT NULL,
  `nama_fakultas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fakultas`
--

INSERT INTO `fakultas` (`id_fakultas`, `nama_fakultas`) VALUES
(1, 'Elektro');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL,
  `hierarki` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `hierarki`) VALUES
(1, 'Presiden Mahasiswa', 1),
(2, 'Wakil Presiden Mahasiswa', 2);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_dokumen`
--

CREATE TABLE `jenis_dokumen` (
  `id_jenis_dokumen` int NOT NULL,
  `nama_jenis` varchar(50) NOT NULL,
  `deskripsi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keanggotaan_ukm`
--

CREATE TABLE `keanggotaan_ukm` (
  `id_keanggotaan` int NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `id_ukm` int DEFAULT NULL,
  `status` enum('anggota','pengurus') DEFAULT 'anggota',
  `id_periode` int DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `tanggal_berakhir` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `keanggotaan_ukm`
--

INSERT INTO `keanggotaan_ukm` (`id_keanggotaan`, `nim`, `id_ukm`, `status`, `id_periode`, `tanggal_bergabung`, `tanggal_berakhir`) VALUES
(1, '43323223', 1, 'anggota', 1, '2024-10-01', '2025-10-01'),
(2, '43323210', 3, 'anggota', 1, '2024-10-01', '2025-10-01'),
(3, '43323210', 2, 'pengurus', 1, '2024-10-01', '2025-10-01');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nim` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `id_program_studi` int DEFAULT NULL,
  `kelas` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `alamat` text,
  `no_whatsapp` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`nim`, `nama_lengkap`, `id_program_studi`, `kelas`, `jenis_kelamin`, `alamat`, `no_whatsapp`, `email`) VALUES
('43323204', 'Aldo Ramadani', 1, 'TI-2C', 'Laki-laki', 'Gunpad', '0812873', 'aldo@gmail.com'),
('43323205', 'Ammar Luqman Arifin', 1, 'TI-2C', 'Perempuan', 'gunpad', '0988412943', 'ammarmmk@gmail.com'),
('43323210', 'Faiz Akmal Nurhakim', 1, 'TI-2c', 'Laki-laki', 'Jatingaleh', '089526861572', 'faiz@gmail.com'),
('43323211', 'Dirga Prayitno', 1, 'TI-2C', 'Laki-laki', 'Jatingaleh', '018230918', 'prayitno@gmail.com'),
('43323212', 'Fathurafi Nadio Busono', 1, 'TI-2C', 'Laki-laki', 'fury anjasmara', '08822148142', 'fathur@yahoo.com'),
('43323223', 'Prabaswara Shafa Azarioma', 1, 'TI-2C', 'Laki-laki', 'Jl. Jatingaleh', '089526861571', 'azshafa@gmail,com');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_ukm`
--

CREATE TABLE `pendaftaran_ukm` (
  `id_pendaftaran` int NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `id_ukm` int DEFAULT NULL,
  `tahap_seleksi` int DEFAULT '1',
  `id_status` int DEFAULT NULL,
  `motivasi` text,
  `id_divisi_pilihan_1` int DEFAULT NULL,
  `id_divisi_pilihan_2` int DEFAULT NULL,
  `cv` text,
  `motivation_letter` text,
  `tanggal_pendaftaran` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `periode_kepengurusan`
--

CREATE TABLE `periode_kepengurusan` (
  `id_periode` int NOT NULL,
  `tahun_mulai` year NOT NULL,
  `tahun_selesai` year NOT NULL,
  `status` enum('aktif','tidak aktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `periode_kepengurusan`
--

INSERT INTO `periode_kepengurusan` (`id_periode`, `tahun_mulai`, `tahun_selesai`, `status`) VALUES
(1, '2024', '2025', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `program_studi`
--

CREATE TABLE `program_studi` (
  `id_program_studi` int NOT NULL,
  `id_fakultas` int DEFAULT NULL,
  `nama_program_studi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `program_studi`
--

INSERT INTO `program_studi` (`id_program_studi`, `id_fakultas`, `nama_program_studi`) VALUES
(1, 1, 'Teknologi Rekayasa Komputer');

-- --------------------------------------------------------

--
-- Table structure for table `status_pendaftaran`
--

CREATE TABLE `status_pendaftaran` (
  `id_status` int NOT NULL,
  `nama_status` varchar(50) NOT NULL,
  `deskripsi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `struktur_organisasi_ukm`
--

CREATE TABLE `struktur_organisasi_ukm` (
  `id_struktur` int NOT NULL,
  `id_ukm` int DEFAULT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `id_jabatan` int DEFAULT NULL,
  `id_periode` int DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_berakhir` date DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `struktur_organisasi_ukm`
--

INSERT INTO `struktur_organisasi_ukm` (`id_struktur`, `id_ukm`, `nim`, `id_jabatan`, `id_periode`, `tanggal_mulai`, `tanggal_berakhir`, `foto_path`) VALUES
(1, 3, '43323211', 1, 1, '2024-10-01', '2025-10-01', 'dirga1.jpg'),
(2, 2, '43323205', 2, 1, '2024-10-01', '2024-10-01', 'dirga1.jpg'),
(3, 2, '43323211', 1, 1, '2024-10-01', '2024-10-01', 'dirga1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `timeline_tags`
--

CREATE TABLE `timeline_tags` (
  `id_timeline` int NOT NULL,
  `id_tag` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timeline_ukm`
--

CREATE TABLE `timeline_ukm` (
  `id_timeline` int NOT NULL,
  `id_ukm` int DEFAULT NULL,
  `judul_kegiatan` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `tanggal_kegiatan` date DEFAULT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ukm`
--

CREATE TABLE `ukm` (
  `id_ukm` int NOT NULL,
  `nama_ukm` varchar(100) NOT NULL,
  `deskripsi` text,
  `visi` text,
  `misi` text,
  `tanggal_berdiri` date DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `banner_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ukm`
--

INSERT INTO `ukm` (`id_ukm`, `nama_ukm`, `deskripsi`, `visi`, `misi`, `tanggal_berdiri`, `logo_path`, `banner_path`) VALUES
(1, 'BEM', 'Badan Eksekutif Mahasiswa', '\r\n“Mewujudkan KBM polines yang solid dan dinamis demi terciptanya sinergisitas mahasiswa”\r\n\r\n', '1. Meningkatkan keimanan dan ketakwaan kepada Tuhan yang Maha Esa\r\n\r\n    Memperkuat hubungan dengan Tuhan\r\n    Menjaga keseimbangan spiritual dan dunia\r\n    Meningkatkan kesadaran sosial dan lingkungan\r\n    Meningkatkan toleransi dan kerukunan antar umat beragama\r\n    Menjadikan keimanan dan ketakwaan sebagai landasan moral dan etika dalam hidup\r\n', '2000-01-01', 'logo-bem.png', 'cover-bem.png'),
(2, 'PCC', 'Politecnik Computer Club', '\r\n“Mewujudkan KBM polines yang solid dan dinamis demi terciptanya sinergisitas mahasiswa”', '1. Meningkatkan keimanan dan ketakwaan kepada Tuhan yang Maha Esa\r\n    Memperkuat hubungan dengan Tuhan\r\n    Menjaga keseimbangan spiritual dan dunia\r\n    Meningkatkan kesadaran sosial dan lingkungan\r\n    Meningkatkan toleransi dan kerukunan antar umat beragama\r\n    Menjadikan keimanan dan ketakwaan sebagai landasan moral dan etika dalam hidup\r\n2. Meningkatkan keimanan dan ketakwaan kepada Tuhan yang Maha Esa\r\n    Memperkuat hubungan dengan Tuhan\r\n    Menjaga keseimbangan spiritual dan dunia\r\n    Meningkatkan kesadaran sosial dan lingkungan\r\n    Meningkatkan toleransi dan kerukunan antar umat beragama\r\n    Menjadikan keimanan dan ketakwaan sebagai landasan moral dan etika dalam hidup', '2024-10-01', 'logo-pcc.png', 'cover-pcc.jpg'),
(3, 'mancing', 'mancing adalah dunia hiburan yg halal dan nikmat', 'foya', 'foyafoya', '2024-10-25', 'logo-mancing.png', 'cover-mancing.jpg'),
(4, 'SP', 'Sport Polines', 'foya', 'foya', '2024-10-26', 'logo-sp.png', 'cover-sp.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE `user_login` (
  `id_login` int NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','admin_ukm','super_admin') DEFAULT 'mahasiswa',
  `nim_reference` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`id_login`, `username`, `password`, `role`, `nim_reference`) VALUES
(2, '43323223', '123', 'mahasiswa', '43323223'),
(3, '43323210', '123', 'mahasiswa', '43323210'),
(4, '43323205', '123', 'mahasiswa', '43323205'),
(7, 'admin', 'admin', 'super_admin', NULL),
(8, 'bem', 'bem', 'admin_ukm', NULL),
(9, '43323212', '123', 'mahasiswa', NULL),
(10, '43323211', '123', 'mahasiswa', NULL),
(11, '43323204', '123', 'mahasiswa', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `divisi_ukm`
--
ALTER TABLE `divisi_ukm`
  ADD PRIMARY KEY (`id_divisi`),
  ADD KEY `id_ukm` (`id_ukm`);

--
-- Indexes for table `dokumentasi_kegiatan`
--
ALTER TABLE `dokumentasi_kegiatan`
  ADD PRIMARY KEY (`id_dokumentasi`),
  ADD KEY `id_timeline` (`id_timeline`),
  ADD KEY `idx_dokumentasi_judul` (`judul`);

--
-- Indexes for table `dokumen_pendaftaran`
--
ALTER TABLE `dokumen_pendaftaran`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `id_pendaftaran` (`id_pendaftaran`),
  ADD KEY `id_jenis_dokumen` (`id_jenis_dokumen`);

--
-- Indexes for table `event_tags`
--
ALTER TABLE `event_tags`
  ADD PRIMARY KEY (`id_tag`);

--
-- Indexes for table `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id_fakultas`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `jenis_dokumen`
--
ALTER TABLE `jenis_dokumen`
  ADD PRIMARY KEY (`id_jenis_dokumen`);

--
-- Indexes for table `keanggotaan_ukm`
--
ALTER TABLE `keanggotaan_ukm`
  ADD PRIMARY KEY (`id_keanggotaan`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_periode` (`id_periode`),
  ADD KEY `idx_keanggotaan_ukm` (`id_ukm`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nim`),
  ADD KEY `idx_mahasiswa_program_studi` (`id_program_studi`);

--
-- Indexes for table `pendaftaran_ukm`
--
ALTER TABLE `pendaftaran_ukm`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_status` (`id_status`),
  ADD KEY `id_divisi_pilihan_1` (`id_divisi_pilihan_1`),
  ADD KEY `id_divisi_pilihan_2` (`id_divisi_pilihan_2`),
  ADD KEY `idx_pendaftaran_ukm` (`id_ukm`);

--
-- Indexes for table `periode_kepengurusan`
--
ALTER TABLE `periode_kepengurusan`
  ADD PRIMARY KEY (`id_periode`);

--
-- Indexes for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id_program_studi`),
  ADD KEY `id_fakultas` (`id_fakultas`);

--
-- Indexes for table `status_pendaftaran`
--
ALTER TABLE `status_pendaftaran`
  ADD PRIMARY KEY (`id_status`);

--
-- Indexes for table `struktur_organisasi_ukm`
--
ALTER TABLE `struktur_organisasi_ukm`
  ADD PRIMARY KEY (`id_struktur`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_jabatan` (`id_jabatan`),
  ADD KEY `id_periode` (`id_periode`),
  ADD KEY `idx_struktur_organisasi_ukm` (`id_ukm`);

--
-- Indexes for table `timeline_tags`
--
ALTER TABLE `timeline_tags`
  ADD PRIMARY KEY (`id_timeline`,`id_tag`),
  ADD KEY `id_tag` (`id_tag`);

--
-- Indexes for table `timeline_ukm`
--
ALTER TABLE `timeline_ukm`
  ADD PRIMARY KEY (`id_timeline`),
  ADD KEY `idx_timeline_ukm` (`id_ukm`),
  ADD KEY `idx_timeline_status` (`status`);

--
-- Indexes for table `ukm`
--
ALTER TABLE `ukm`
  ADD PRIMARY KEY (`id_ukm`);

--
-- Indexes for table `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`id_login`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_nim_mahasiswa` (`nim_reference`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `divisi_ukm`
--
ALTER TABLE `divisi_ukm`
  MODIFY `id_divisi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dokumentasi_kegiatan`
--
ALTER TABLE `dokumentasi_kegiatan`
  MODIFY `id_dokumentasi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dokumen_pendaftaran`
--
ALTER TABLE `dokumen_pendaftaran`
  MODIFY `id_dokumen` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_tags`
--
ALTER TABLE `event_tags`
  MODIFY `id_tag` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id_fakultas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jenis_dokumen`
--
ALTER TABLE `jenis_dokumen`
  MODIFY `id_jenis_dokumen` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keanggotaan_ukm`
--
ALTER TABLE `keanggotaan_ukm`
  MODIFY `id_keanggotaan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pendaftaran_ukm`
--
ALTER TABLE `pendaftaran_ukm`
  MODIFY `id_pendaftaran` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `periode_kepengurusan`
--
ALTER TABLE `periode_kepengurusan`
  MODIFY `id_periode` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id_program_studi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `status_pendaftaran`
--
ALTER TABLE `status_pendaftaran`
  MODIFY `id_status` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `struktur_organisasi_ukm`
--
ALTER TABLE `struktur_organisasi_ukm`
  MODIFY `id_struktur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `timeline_ukm`
--
ALTER TABLE `timeline_ukm`
  MODIFY `id_timeline` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ukm`
--
ALTER TABLE `ukm`
  MODIFY `id_ukm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `id_login` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `divisi_ukm`
--
ALTER TABLE `divisi_ukm`
  ADD CONSTRAINT `divisi_ukm_ibfk_1` FOREIGN KEY (`id_ukm`) REFERENCES `ukm` (`id_ukm`);

--
-- Constraints for table `dokumentasi_kegiatan`
--
ALTER TABLE `dokumentasi_kegiatan`
  ADD CONSTRAINT `dokumentasi_kegiatan_ibfk_1` FOREIGN KEY (`id_timeline`) REFERENCES `timeline_ukm` (`id_timeline`);

--
-- Constraints for table `dokumen_pendaftaran`
--
ALTER TABLE `dokumen_pendaftaran`
  ADD CONSTRAINT `dokumen_pendaftaran_ibfk_1` FOREIGN KEY (`id_pendaftaran`) REFERENCES `pendaftaran_ukm` (`id_pendaftaran`),
  ADD CONSTRAINT `dokumen_pendaftaran_ibfk_2` FOREIGN KEY (`id_jenis_dokumen`) REFERENCES `jenis_dokumen` (`id_jenis_dokumen`);

--
-- Constraints for table `keanggotaan_ukm`
--
ALTER TABLE `keanggotaan_ukm`
  ADD CONSTRAINT `keanggotaan_ukm_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`),
  ADD CONSTRAINT `keanggotaan_ukm_ibfk_2` FOREIGN KEY (`id_ukm`) REFERENCES `ukm` (`id_ukm`),
  ADD CONSTRAINT `keanggotaan_ukm_ibfk_3` FOREIGN KEY (`id_periode`) REFERENCES `periode_kepengurusan` (`id_periode`);

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_program_studi`) REFERENCES `program_studi` (`id_program_studi`);

--
-- Constraints for table `pendaftaran_ukm`
--
ALTER TABLE `pendaftaran_ukm`
  ADD CONSTRAINT `pendaftaran_ukm_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`),
  ADD CONSTRAINT `pendaftaran_ukm_ibfk_2` FOREIGN KEY (`id_ukm`) REFERENCES `ukm` (`id_ukm`),
  ADD CONSTRAINT `pendaftaran_ukm_ibfk_3` FOREIGN KEY (`id_status`) REFERENCES `status_pendaftaran` (`id_status`),
  ADD CONSTRAINT `pendaftaran_ukm_ibfk_4` FOREIGN KEY (`id_divisi_pilihan_1`) REFERENCES `divisi_ukm` (`id_divisi`),
  ADD CONSTRAINT `pendaftaran_ukm_ibfk_5` FOREIGN KEY (`id_divisi_pilihan_2`) REFERENCES `divisi_ukm` (`id_divisi`);

--
-- Constraints for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD CONSTRAINT `program_studi_ibfk_1` FOREIGN KEY (`id_fakultas`) REFERENCES `fakultas` (`id_fakultas`);

--
-- Constraints for table `struktur_organisasi_ukm`
--
ALTER TABLE `struktur_organisasi_ukm`
  ADD CONSTRAINT `struktur_organisasi_ukm_ibfk_1` FOREIGN KEY (`id_ukm`) REFERENCES `ukm` (`id_ukm`),
  ADD CONSTRAINT `struktur_organisasi_ukm_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`),
  ADD CONSTRAINT `struktur_organisasi_ukm_ibfk_3` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`),
  ADD CONSTRAINT `struktur_organisasi_ukm_ibfk_4` FOREIGN KEY (`id_periode`) REFERENCES `periode_kepengurusan` (`id_periode`);

--
-- Constraints for table `timeline_tags`
--
ALTER TABLE `timeline_tags`
  ADD CONSTRAINT `timeline_tags_ibfk_1` FOREIGN KEY (`id_timeline`) REFERENCES `timeline_ukm` (`id_timeline`) ON DELETE CASCADE,
  ADD CONSTRAINT `timeline_tags_ibfk_2` FOREIGN KEY (`id_tag`) REFERENCES `event_tags` (`id_tag`) ON DELETE CASCADE;

--
-- Constraints for table `timeline_ukm`
--
ALTER TABLE `timeline_ukm`
  ADD CONSTRAINT `timeline_ukm_ibfk_1` FOREIGN KEY (`id_ukm`) REFERENCES `ukm` (`id_ukm`);

--
-- Constraints for table `user_login`
--
ALTER TABLE `user_login`
  ADD CONSTRAINT `fk_nim_mahasiswa` FOREIGN KEY (`nim_reference`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
