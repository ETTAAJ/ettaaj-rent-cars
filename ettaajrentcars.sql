-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2025 at 09:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;


/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ettaajrentcars`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$D0VU799vTZQWZFLQPaP/NOegnqfbZNv2l74axQpRPj.SrUbhhfIhe', '2025-11-14 20:06:47');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `bags` int(11) DEFAULT NULL,
  `gear` enum('Manual','Automatic') DEFAULT NULL,
  `fuel` enum('Diesel','Petrol') DEFAULT NULL,
  `price_day` decimal(10,2) DEFAULT NULL,
  `price_week` decimal(10,2) DEFAULT NULL,
  `price_month` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `name`, `image`, `seats`, `bags`, `gear`, `fuel`, `price_day`, `price_week`, `price_month`) VALUES
(21, 'Dacia Logan', 'Dacia Logan.jpg', 5, 2, 'Manual', 'Diesel', 300.00, 1800.00, 6000.00),
(22, 'Fiat 500 Convertible', 'Fiat 500 Convertible.jpg', 4, 1, 'Automatic', 'Petrol', 400.00, 2400.00, 8000.00),
(23, 'Hyundai Accent', 'Hyundai Accent.jpg', 5, 2, 'Automatic', 'Petrol', 400.00, 2400.00, 8000.00),
(24, 'Dacia Duster', 'Dacia Duster.jpg', 5, 3, 'Automatic', 'Diesel', 450.00, 2700.00, 9000.00),
(26, 'Renault Clio 5', 'Renault Clio 5 (1).jpg', 5, 2, 'Manual', 'Diesel', 370.00, 2220.00, 7400.00),
(27, 'Renault Clio 5', 'Renault Clio 5.jpg', 5, 2, 'Automatic', 'Petrol', 400.00, 2400.00, 8000.00),
(28, 'Renault Mégane 4', 'Renault Mgane 4.jpg', 5, 2, 'Automatic', 'Diesel', 700.00, 4200.00, 14000.00),
(29, 'Volkswagen Golf 8', 'Volkswagen Golf 8.jpg', 5, 2, 'Automatic', 'Diesel', 900.00, 5400.00, 18000.00),
(30, 'Volkswagen T-Roc', 'Volkswagen T-Roc.jpg', 5, 3, 'Automatic', 'Diesel', 900.00, 5400.00, 18000.00),
(31, 'Audi Q3', 'Audi Q3.jpg', 5, 2, 'Automatic', 'Petrol', 1000.00, 6000.00, 20000.00),
(32, 'Cupra Leon', 'Cupra Leon.jpg', 5, 2, 'Automatic', 'Diesel', 1000.00, 6000.00, 20000.00),
(36, 'Mercedes-Benz A-Class', 'Mercedes-Benz A-Class.jpg', 5, 2, 'Automatic', 'Diesel', 1200.00, 7200.00, 24000.00),
(37, 'Mercedes-Benz C220 AMG', 'Mercedes-Benz C220 AMG.jpg', 5, 3, 'Automatic', 'Diesel', 1800.00, 10800.00, 36000.00),
(39, 'Audi Q8 S-Line', 'Audi Q8 S-Line.jpg', 5, 4, 'Automatic', 'Petrol', 2300.00, 13800.00, 46000.00),
(42, 'Porsche Macan GTS', 'Porsche Macan GTS.jpg', 5, 4, 'Automatic', 'Petrol', 2800.00, 16800.00, 56000.00),
(43, 'BMW 420d Pack M Convertible', 'BMW 420d Pack M Convertible.jpg', 4, 2, 'Automatic', 'Petrol', 3200.00, 19200.00, 64000.00),
(44, 'Porsche Cayenne Coupé', 'Porsche Cayenne Coup.jpg', 5, 4, 'Automatic', 'Petrol', 3800.00, 22800.00, 76000.00),
(45, 'Range Rover Sport', 'Range Rover Sport.jpg', 5, 4, 'Automatic', 'Petrol', 4000.00, 24000.00, 80000.00),
(46, 'Range Rover Vogue', 'Range Rover Vogue.jpg', 5, 5, 'Automatic', 'Petrol', 5300.00, 31800.00, 106000.00),
(47, 'Mercedes-Benz S-Class', 'Mercedes-Benz S-Class.jpg', 5, 3, 'Automatic', 'Petrol', 6500.00, 39000.00, 130000.00),
(48, 'Mercedes-Benz G63 AMG', 'Mercedes-Benz G63 AMG.jpg', 5, 4, 'Automatic', 'Petrol', 13000.00, 78000.00, 260000.00);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
