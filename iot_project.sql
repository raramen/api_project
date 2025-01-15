-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2025 at 02:28 AM
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
-- Table structure for table `battery_data`
--

CREATE TABLE `battery_data` (
  `id` int(11) NOT NULL,
  `capacity` float NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `battery_data`
--

INSERT INTO `battery_data` (`id`, `capacity`, `created_at`) VALUES
(1, 80, '2025-01-11 02:52:41');

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

-- --------------------------------------------------------

--
-- Table structure for table `speed_data`
--

CREATE TABLE `speed_data` (
  `id` int(11) NOT NULL,
  `value` float NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `speed_data`
--

INSERT INTO `speed_data` (`id`, `value`, `created_at`) VALUES
(1, 50.5, '2025-01-11 02:52:41');

-- --------------------------------------------------------

--
-- Table structure for table `water_level_data`
--

CREATE TABLE `water_level_data` (
  `id` int(11) NOT NULL,
  `level` float NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `water_level_data`
--

INSERT INTO `water_level_data` (`id`, `level`, `created_at`) VALUES
(1, 30.2, '2025-01-11 02:52:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `battery_data`
--
ALTER TABLE `battery_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `speed_data`
--
ALTER TABLE `speed_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `water_level_data`
--
ALTER TABLE `water_level_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `battery_data`
--
ALTER TABLE `battery_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `speed_data`
--
ALTER TABLE `speed_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `water_level_data`
--
ALTER TABLE `water_level_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
