-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2024 at 06:41 PM
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
-- Database: `votesystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(60) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `photo` varchar(150) DEFAULT NULL,
  `created_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `firstname`, `lastname`, `photo`, `created_on`) VALUES
(1, 'OSAadmin', '$2y$10$fLK8s7ZDnM.1lE7XMP.J6OuPbQ.DPUVKBo7rENnQY7gYq0xAzsKJy', 'OSA', 'OLSHCO', 'olshco.png', '2023-12-08');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `photo` varchar(150) NOT NULL,
  `platform` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `election_id`, `category_id`, `position_id`, `firstname`, `lastname`, `photo`, `platform`) VALUES
(24, 0, 0, 8, 'Santy', 'Balmores', '395667298_864497195002511_6008359017104175884_n.jpg', 'MAMAMOBLUE'),
(25, 0, 0, 8, 'Hannah', 'Reyes', 'hannah.jpg', ''),
(26, 0, 0, 9, 'Marie Loraine', 'Perona', 'marie.jpg', ''),
(27, 0, 0, 9, 'Lyka', 'Refugia', '398230013_228558630077034_8093549086123136836_n.jpg', ''),
(28, 0, 0, 8, 'Charmaine Joyce', 'Coloma', 'cha.jpg', 'Pray');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_by` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `election_id`, `name`, `added_by`, `updated_by`, `created_by`, `updated_at`) VALUES
(1, 1, 'President', 1, NULL, '2023-12-11 04:45:23', NULL),
(2, 4, 'President', 1, NULL, '2023-12-11 07:57:26', NULL),
(3, 1, 'Vice President', 1, NULL, '2023-12-11 09:35:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `election`
--

CREATE TABLE `election` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `voters` varchar(255) NOT NULL,
  `starttime` timestamp NULL DEFAULT NULL,
  `endtime` timestamp NULL DEFAULT NULL,
  `report_path` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - not active, 1 - active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `election`
--

INSERT INTO `election` (`id`, `title`, `voters`, `starttime`, `endtime`, `report_path`, `status`) VALUES
(1, 'JPCS - Junior Philippine Computer Society Election', 'JPCS Students', '2024-03-05 16:00:00', '2024-03-05 17:21:00', '', 1),
(20, 'CSC - College of Student Council Election', 'All Students', '2023-12-11 11:50:00', '2023-12-12 11:50:00', '', 0),
(21, 'YMF - Young Mentor of the Future Election', 'YMF Students', '2023-12-12 06:44:00', '2023-12-13 06:41:00', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `max_vote` int(11) NOT NULL,
  `priority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `description`, `max_vote`, `priority`) VALUES
(8, 'President', 1, 1),
(9, 'Vice President', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sub_admin`
--

CREATE TABLE `sub_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `firstname` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `photo` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_admin`
--

INSERT INTO `sub_admin` (`id`, `username`, `password`, `firstname`, `lastname`, `email`, `photo`, `created_on`) VALUES
(1, 'CSCadmin', '$2y$10$fLK8s7ZDnM.1lE7XMP.J6OuPbQ.DPUVKBo7rENnQY7gYq0xAzsKJy', 'CSC', 'OLSHCO', 'cscadmin@gmail.com', 'csc.jpg', '2023-12-08'),
(3, 'JPCSadmin', '$2a$12$cq4IDbLN/Hxk.cCs.Svsz.g8Xy84WcuxVUv/C8MxVAPy1ijX1FEZ2', 'JPCS', 'OLSHCO', 'jpcsolshco@gmail.com', '', '0000-00-00'),
(4, 'YMFadmin', '$2y$10$uVy.yc.I2oSRilCTlwpMOOm3SMc52K77qW7eqf38SuQf39mLgwEM.', 'YMF', 'OLSHCO', 'ymfolshco@gmail.com', '', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

CREATE TABLE `voters` (
  `id` int(11) NOT NULL,
  `voters_id` varchar(15) NOT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `genPass` varchar(60) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(60) NOT NULL,
  `organization` varchar(50) NOT NULL,
  `yearLvl` int(1) NOT NULL,
  `photo` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`id`, `voters_id`, `password`, `genPass`, `firstname`, `lastname`, `email`, `organization`, `yearLvl`, `photo`) VALUES
(38, '9743685', '$2y$10$q.6ycrvgHl8GCVvV7qA/6eWBdqwxfFfQDqUwvaLeYxNblL196ObFm', 'aJS*{<p$1Y', 'Loraine', 'Perona', 'lorainperona098@gmail.com', 'CSC', 3, ''),
(74, '1690748', '$2y$10$Xj7MhRpaZNBH/034ZlMnaev0IAVsoaihWHQqX5j9WNmqausXHAC7S', 'JaO[K0rtI.', 'Lyka', 'Refugia', 'lebronrefugia@gmail.com', 'JPCS', 3, ''),
(77, '7230489', '$2y$10$m5RC84xZXkNbKCsBkfMVd.1Jp/eRe.X6rZCymL6JUt7ZEekO6PaGm', '4aOM*D+vf!', 'Jericho', 'Liwanag', 'reyeshannahjoy82@gmail.com', 'YMF', 4, ''),
(78, '1506843', '$2y$10$bGuyvN8tVLT.nY07.MjcaOkP1VDpVCpnwMvp7F.ltaqzdAQwdynM2', '8j<Okbqpf9', 'Ivan', 'Talusig', 'ivantalusig@gmail.com', 'CODE-TG', 3, ''),
(80, '7482563', '$2y$10$2TgsIEoEDawmumF/hWaaQ.t648AIGcKq8X/5EXNfqdX3vNzGzi1gi', '0i#K%QtP[5', 'Giselle', 'Coloma', 'gisellecoloma@gmail.com', 'PASOA', 3, ''),
(103, '0658127', '$2y$10$d2Cfoqr5Eb/vw91l9cmYTewbUfzVZUihheo8DBT6nypzxb9mOLWtW', 'ltjBIg_{mp', 'Marian ', 'Lopez', 'marianlopez@gmail.com', 'HMSO', 3, '');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `voters_id` int(11) NOT NULL,
  `org_id` int(1) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `election`
--
ALTER TABLE `election`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_admin`
--
ALTER TABLE `sub_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voters`
--
ALTER TABLE `voters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `election`
--
ALTER TABLE `election`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sub_admin`
--
ALTER TABLE `sub_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `voters`
--
ALTER TABLE `voters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
