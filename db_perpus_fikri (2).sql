-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 05, 2026 at 09:47 AM
-- Server version: 8.0.30
-- PHP Version: 8.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpus_fikri`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int NOT NULL,
  `id_kategori` int DEFAULT NULL,
  `kode_buku` varchar(20) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `stok` int DEFAULT '0',
  `lokasi_rak` varchar(50) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `id_kategori`, `kode_buku`, `judul`, `penulis`, `penerbit`, `stok`, `lokasi_rak`, `cover`) VALUES
(17, 1, 'BK001', 'Dilan 1990', 'Pidi Baiq', 'Pastel Books', 10, 'Rak A1', 'cover_1775379544.png'),
(18, 1, 'BK002', 'Perahu Kertas', 'Dee Lestari', 'Bentang Pustaka', 7, 'Rak A1', 'cover_1775379788.jpg'),
(19, 1, 'BK003', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 12, 'Rak A1', 'cover_1775379799.jpg'),
(20, 1, 'BK004', 'Pulang-Pergi', 'Tere Liye', 'Republika', 9, 'Rak A2', 'cover_1775379894.jpg'),
(21, 1, 'BK005', 'Cantik Itu Luka', 'Eka Kurniawan', 'Republika', 9, 'Rak A2', 'cover_1775379880.jpg'),
(22, 1, 'BK006', 'Hujan', 'Tere Liye', 'Gramedia', 7, 'Rak A2', 'cover_1775379979.jpg'),
(23, 2, 'BK007', 'Pemrograman Web Dasar', 'Abdul Kadir', 'Andi Offset', 10, 'Rak B1', 'cover_1775379990.jpg'),
(24, 2, 'BK008', 'Belajar HTML & CSS', 'Jubilee Enterprise', 'Elex Media Komputindo', 8, 'Rak B1', 'https://covers.openlibrary.org/b/id/10958342-L.jpg'),
(25, 2, 'BK009', 'Dasar Pemrograman JavaScript', 'Wahana Komputer', 'Andi Offset', 7, 'Rak B2', 'https://covers.openlibrary.org/b/id/10600055-L.jpg'),
(26, 3, 'BK010', 'Fisika SMA Kelas X', 'Marthen Kanginan', 'Erlangga', 10, 'Rak C1', 'https://covers.openlibrary.org/b/id/8235080-L.jpg'),
(27, 3, 'BK011', 'Biologi SMA', 'Campbell Reece', 'Erlangga', 6, 'Rak C1', 'https://covers.openlibrary.org/b/id/8235110-L.jpg'),
(28, 3, 'BK012', 'Kimia SMA', 'Unggul Sudarmo', 'Erlangga', 8, 'Rak C2', 'https://covers.openlibrary.org/b/id/8091016-L.jpg'),
(29, 4, 'BK013', 'Sejarah Indonesia', 'Sartono Kartodirdjo', 'Gramedia', 6, 'Rak D1', 'cover_1775380434.jpg'),
(30, 4, 'BK014', 'Indonesia dalam Arus Sejarah', 'Taufik Abdullah', 'Ichtiar Baru', 4, 'Rak D1', 'cover_1775380424.jpg'),
(31, 5, 'BK015', 'Strategi Belajar Mengajar', 'Wina Sanjaya', 'Kencana', 5, 'Rak E1', 'cover_1775380407.jpg'),
(32, 5, 'BK016', 'Psikologi Pendidikan', 'Slameto', 'Rineka Cipta', 4, 'Rak E1', 'cover_1775380396.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(1, 'Fiksi'),
(2, 'Teknologi'),
(3, 'Sains'),
(4, 'Sejarah'),
(5, 'Pendidikan'),
(7, 'Dongeng');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `id_user` int NOT NULL,
  `id_buku` int NOT NULL,
  `tgl_pengajuan` datetime DEFAULT CURRENT_TIMESTAMP,
  `tgl_pinjam` date DEFAULT NULL,
  `tgl_harus_kembali` date DEFAULT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `status` enum('menunggu','dipinjam','dikembalikan','ditolak') DEFAULT 'menunggu',
  `denda` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `kode_transaksi`, `id_user`, `id_buku`, `tgl_pengajuan`, `tgl_pinjam`, `tgl_harus_kembali`, `tgl_kembali`, `status`, `denda`) VALUES
(1, 'TRX-1775306840-2', 2, 32, '2026-04-04 19:47:20', '2026-04-04', '2026-04-11', '2026-04-21', 'dikembalikan', 10000),
(2, 'TRX-1775306840-2', 2, 31, '2026-04-04 19:47:20', '2026-04-04', '2026-04-11', NULL, 'dipinjam', 0),
(3, 'TRX-1775314225-2', 2, 18, '2026-04-04 21:50:25', '2026-04-04', '2026-04-11', NULL, 'dipinjam', 0),
(4, 'TRX-1775379433-2', 2, 32, '2026-04-05 15:57:13', '2026-04-05', '2026-04-12', NULL, 'dipinjam', 0),
(5, 'TRX-1775379433-2', 2, 29, '2026-04-05 15:57:13', '2026-04-05', '2026-04-12', NULL, 'dipinjam', 0),
(6, 'TRX-1775379433-2', 2, 30, '2026-04-05 15:57:13', '2026-04-05', '2026-04-12', NULL, 'dipinjam', 0),
(7, 'TRX-1775380574-2', 2, 32, '2026-04-05 16:16:14', NULL, NULL, NULL, 'menunggu', 0),
(8, 'TRX-1775380574-2', 2, 17, '2026-04-05 16:16:14', NULL, NULL, NULL, 'menunggu', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('admin','siswa') DEFAULT 'siswa',
  `nis` varchar(20) DEFAULT NULL,
  `kelas` varchar(20) DEFAULT NULL,
  `angkatan` varchar(20) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `nama`, `role`, `nis`, `kelas`, `angkatan`, `status`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Perpus', 'admin', NULL, NULL, NULL, 'aktif', '2026-04-04 12:22:04'),
(2, 'fikri', '$2y$12$WpU0ogvo7cZBO.BBsajXLORdz2IKK4g/YXrMRkS0VcVZZtR13lJKS', 'fikri', 'siswa', '123', 'XII RPL 1', '2023', 'aktif', '2026-04-04 12:39:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_buku` (`kode_buku`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
