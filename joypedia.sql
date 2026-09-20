-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Bulan Mei 2025 pada 18.53
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `joypedia`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `isi` text NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `berita`
--

INSERT INTO `berita` (`id`, `judul`, `kategori`, `deskripsi`, `gambar`, `isi`, `tanggal`) VALUES
(1, 'TEST', 'Teknologi', '', '3060.jpg', 'wqddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddq', '2025-05-16 13:27:57'),
(2, '1111111111', 'Pendidikan', '', 'case1.jpg', '111111111111', '2025-05-16 13:37:33'),
(3, 'TEST', 'Pendidikan', '', 'keyboard.jpg', 'wqddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddq', '2025-05-16 14:20:15'),
(4, 'TEST', 'Olahraga', '', 'EW.jpeg', 'wqddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddq', '2025-05-16 14:23:58'),
(5, 'TEST', 'Teknologi', '', 'GW.jpeg', 'wqddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddq', '2025-05-16 14:24:49'),
(6, 'TEST', 'Olahraga', '', 'GW.jpeg', 'wqddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddq', '2025-05-16 14:25:16'),
(7, 'TESTT', 'Teknologi', '', '3060.jpg', 'JADI GINI LE JADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LEJADI GINI LE', '2025-05-16 15:56:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gambar_berita`
--

CREATE TABLE `gambar_berita` (
  `id` int(11) NOT NULL,
  `berita_id` int(11) NOT NULL,
  `nama_gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `gambar_berita`
--

INSERT INTO `gambar_berita` (`id`, `berita_id`, `nama_gambar`) VALUES
(1, 7, 'case2.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `id` int(11) NOT NULL,
  `berita_id` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `polling`
--

CREATE TABLE `polling` (
  `id` int(11) NOT NULL,
  `berita_id` int(11) DEFAULT NULL,
  `pertanyaan` varchar(255) DEFAULT NULL,
  `opsi1` varchar(100) DEFAULT NULL,
  `opsi2` varchar(100) DEFAULT NULL,
  `opsi3` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `polling_vote`
--

CREATE TABLE `polling_vote` (
  `id` int(11) NOT NULL,
  `polling_id` int(11) DEFAULT NULL,
  `pilihan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin969', 'admin969@gmail.com', 'admin123', 'admin'),
(2, 'Wahyuddin Fakhar', 'wahyuddinfakhar@gmail.com', '$2y$10$ci2G34hShvc5nv0LQanyjuNRu4QvvAYJGJWdyYs9gU0e5JGQt/qpS', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `gambar_berita`
--
ALTER TABLE `gambar_berita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_id` (`berita_id`);

--
-- Indeks untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_id` (`berita_id`);

--
-- Indeks untuk tabel `polling`
--
ALTER TABLE `polling`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_id` (`berita_id`);

--
-- Indeks untuk tabel `polling_vote`
--
ALTER TABLE `polling_vote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `polling_id` (`polling_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `gambar_berita`
--
ALTER TABLE `gambar_berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `polling`
--
ALTER TABLE `polling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `polling_vote`
--
ALTER TABLE `polling_vote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `gambar_berita`
--
ALTER TABLE `gambar_berita`
  ADD CONSTRAINT `gambar_berita_ibfk_1` FOREIGN KEY (`berita_id`) REFERENCES `berita` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `komentar_ibfk_1` FOREIGN KEY (`berita_id`) REFERENCES `berita` (`id`);

--
-- Ketidakleluasaan untuk tabel `polling`
--
ALTER TABLE `polling`
  ADD CONSTRAINT `polling_ibfk_1` FOREIGN KEY (`berita_id`) REFERENCES `berita` (`id`);

--
-- Ketidakleluasaan untuk tabel `polling_vote`
--
ALTER TABLE `polling_vote`
  ADD CONSTRAINT `polling_vote_ibfk_1` FOREIGN KEY (`polling_id`) REFERENCES `polling` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
