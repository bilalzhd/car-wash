-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2023 at 11:07 AM
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

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `phone` varchar(256) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `timestamp`) VALUES
(32, 'ziaullah', '0101010111', '2023-12-08 14:10:22'),
(33, 'ahmed', '000', '2023-12-08 20:45:17');

-- --------------------------------------------------------

--
-- Table structure for table `halls`
--

CREATE TABLE `halls` (
  `count` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `halls`
--

INSERT INTO `halls` (`count`, `date`) VALUES
(4, '2023-12-08');

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
  `hall_1` int(10) NOT NULL DEFAULT 0,
  `hall_2` int(10) NOT NULL DEFAULT 0,
  `hall_3` int(10) NOT NULL DEFAULT 0,
  `hall_4` int(10) NOT NULL DEFAULT 0,
  `hall_5` int(10) NOT NULL DEFAULT 0,
  `hall_6` int(10) NOT NULL DEFAULT 0,
  `hall_7` int(10) NOT NULL DEFAULT 0,
  `hall_8` int(10) NOT NULL DEFAULT 0,
  `hall_9` int(10) NOT NULL DEFAULT 0,
  `hall_10` int(10) NOT NULL DEFAULT 0,
  `number_of_halls` int(20) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `customer_name` varchar(250) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `records_2`
--

INSERT INTO `records_2` (`id`, `hall_1`, `hall_2`, `hall_3`, `hall_4`, `hall_5`, `hall_6`, `hall_7`, `hall_8`, `hall_9`, `hall_10`, `number_of_halls`, `customer_id`, `customer_name`, `date`) VALUES
(2, 10, 101, 10, 10, 0, 0, 0, 0, 0, 0, 4, 24, 'Customer Name', '2023-12-07'),
(3, 2, 11, 10, 20, 0, 0, 0, 0, 0, 0, 4, 32, 'Customer Name', '2023-12-08'),
(5, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 4, 33, 'Customer Name', '2023-12-08'),
(6, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 4, 33, 'Customer Name', '2023-12-09'),
(7, 2, 1, 1, 1, 0, 0, 0, 0, 0, 0, 4, 32, 'Customer Name', '2023-12-09');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `records_2`
--
ALTER TABLE `records_2`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
