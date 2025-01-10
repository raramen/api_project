-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2025 at 03:20 PM
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
-- Database: `iot_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `sensor_data`
--

CREATE TABLE `sensor_data` (
  `id` int(11) NOT NULL,
  `speed` float NOT NULL,
  `battery` float NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensor_data`
--

INSERT INTO `sensor_data` (`id`, `speed`, `battery`, `timestamp`) VALUES
(1, 50.5, 80.2, '2024-12-06 22:23:51'),
(2, 50.5, 80, '2024-12-07 22:24:20'),
(3, 50, 79, '2024-12-18 22:24:29'),
(4, 53, 79, '2024-12-18 22:24:47'),
(5, 48, 77, '2024-12-18 22:38:34'),
(6, 50.5, 76, '2024-12-19 09:23:06'),
(7, 43, 75, '2024-12-19 09:23:19'),
(8, 50.5, 80.2, '2024-12-19 09:36:17'),
(9, 50.5, 80.2, '2024-12-19 09:36:28'),
(10, 50.5, 80.2, '2024-12-19 09:36:38'),
(11, 50.5, 80.2, '2024-12-19 09:36:48'),
(12, 50.5, 80.2, '2024-12-20 09:36:58'),
(13, 50.5, 80.2, '2024-12-20 09:44:06'),
(14, 50.5, 80.2, '2024-12-21 09:44:16'),
(15, 50.5, 80.2, '2024-12-21 09:45:04'),
(16, 50, 79, '2024-12-21 13:12:52'),
(17, 60, 90, '2025-01-10 19:05:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
