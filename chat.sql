-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2025 at 03:59 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

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
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `reply_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `username`, `message`, `timestamp`, `reply_to`) VALUES
(1, 'bramantya', 'asalamualaikum kawan', '2025-05-17 12:30:56', NULL),
(2, 'wahyudd', 'waalaikum salam sobat', '2025-05-17 12:35:06', NULL),
(3, 'bramantya', 'mantap kakak', '2025-05-17 12:36:13', NULL),
(4, 'bramantya', 'ada info lagi ga sihhh teman', '2025-05-17 12:37:28', NULL),
(5, 'wahyudd', 'ada dong', '2025-05-17 12:44:44', NULL),
(6, 'wahyudd', 'ada dong', '2025-05-17 12:44:44', 4),
(7, 'sinta', 'apatuh', '2025-05-17 13:05:43', 5),
(8, 'sinta', 'ada info guys', '2025-05-17 13:12:43', 0),
(9, 'bramantya', 'jangan lupa beli ini guys murahh', '2025-05-17 18:11:18', 0),
(10, 'bramantya', 'ini', '2025-05-17 18:16:26', 0),
(13, 'bramantya', 'dibeli ayo', '2025-05-17 18:53:18', 0),
(14, 'wahyudd', 'BERITA TERKINI] Pemerintah resmi menetapkan tanggal 1 Juni sebagai hari libur nasional dalam rangka memperingati Hari Lahir Pancasila. Seluruh kegiatan belajar mengajar akan diliburkan pada hari tersebut.  https://youtu.be/x7VJaVNrjpg?si=tY3VxzCS0vzA7WHw', '2025-05-17 19:00:19', 0),
(15, 'wahyudd', 'benergasih guys', '2025-05-17 19:00:32', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
