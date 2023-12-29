-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2023 at 04:28 PM
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
-- Database: `car-wash`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `phone` varchar(256) NOT NULL,
  `timestamp` date NOT NULL DEFAULT current_timestamp(),
  `delete_on` text DEFAULT '0',
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `timestamp`, `delete_on`, `deleted`) VALUES
(45, 'Bilal', '00910', '2023-12-24', '2023-12-24', 1),
(46, 'Zia', '09090', '2023-12-24', '2023-12-27', 1),
(47, 'Ahmed', '0909', '2023-12-24', '2023-12-27', 1),
(48, 'Bilal', '1091029', '2024-01-01', '2023-12-27', 1),
(49, 'Bilal Zahid', '03132979209', '2024-03-01', '2023-12-27', 1),
(50, 'Bilal', '9898', '0000-00-00', '2023-12-27', 1),
(51, 'Bilal', '9889', '0000-00-00', '2023-12-27', 1),
(52, 'ahsdk', '0909', '0000-00-00', '0', 0),
(53, 'asduiad', 'aslda', '0000-00-00', '2023-12-27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `halls`
--

CREATE TABLE IF NOT EXISTS `halls` (
  `id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `halls`
--

INSERT INTO `halls` (`id`, `count`, `date`) VALUES
(19, 3, '2023-12-27');

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `id` int(11) NOT NULL,
  `hall_1` int(10) NOT NULL,
  `hall_2` int(11) DEFAULT 0,
  `hall_3` int(10) NOT NULL DEFAULT 0,
  `hall_4` int(10) NOT NULL DEFAULT 0,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(250) NOT NULL DEFAULT 'customer',
  `timestamp` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`id`, `hall_1`, `hall_2`, `hall_3`, `hall_4`, `customer_id`, `customer_name`, `timestamp`) VALUES
(16, 7, 7, 1, 6, 29, 'customer', '2023-12-30'),
(18, 0, 0, 0, 0, 24, 'ahmed', '2024-12-03'),
(19, 34, 10, 0, 0, 29, 'customer', '2023-12-04'),
(20, 3, 5, 0, 4, 31, 'customer', '2023-12-05'),
(21, 8, 9, 7, 8, 24, 'ahmed', '2023-12-05'),
(23, 6, 8, 8, 0, 29, 'customer', '2023-12-05'),
(24, 0, 0, 6, 7, 29, 'customer', '2023-12-07'),
(25, 33, 44, 44, 33, 30, 'ahmed', '2023-12-08'),
(27, 1, 0, 0, 1, 30, 'ahmed', '2023-12-06'),
(28, 0, 0, 0, 0, 24, 'ahmed', '2023-12-07');

-- --------------------------------------------------------

--
-- Table structure for table `records_2`
--

CREATE TABLE `records_2` (
  `id` int(10) NOT NULL,
  `hall_1` int(10) DEFAULT NULL,
  `hall_2` int(10) DEFAULT NULL,
  `hall_3` int(10) DEFAULT NULL,
  `hall_4` int(10) DEFAULT NULL,
  `hall_5` int(10) DEFAULT NULL,
  `hall_6` int(10) DEFAULT NULL,
  `hall_7` int(10) DEFAULT NULL,
  `hall_8` int(10) DEFAULT NULL,
  `hall_9` int(10) DEFAULT NULL,
  `hall_10` int(10) DEFAULT NULL,
  `number_of_halls` int(20) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `customer_name` varchar(250) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `records_2`
--

INSERT INTO `records_2` (`id`, `hall_1`, `hall_2`, `hall_3`, `hall_4`, `hall_5`, `hall_6`, `hall_7`, `hall_8`, `hall_9`, `hall_10`, `number_of_halls`, `customer_id`, `customer_name`, `date`) VALUES
(215, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 0, '', '2023-12-24'),
(216, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 4, 0, '', '0000-00-00'),
(217, 0, 0, 0, 2, 0, 0, 0, 0, 0, 0, 4, 47, 'Ahmed', '0000-00-00'),
(222, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 53, 'asduiad', '2023-12-27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(27, 'Admin', 'Admin', '0'),
(28, 'User', 'User', '1'),
(29, 'Zia', 'bfdsdds', '0'),
(30, 'ahmed', 'hfghdgdd', '1'),
(31, 'Manager', 'Manager', '2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `halls`
--
ALTER TABLE `halls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `records_2`
--
ALTER TABLE `records_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `halls`
--
ALTER TABLE `halls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `records_2`
--
ALTER TABLE `records_2`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
