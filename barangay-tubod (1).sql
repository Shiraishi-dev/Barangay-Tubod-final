-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2025 at 06:27 PM
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
-- Database: `barangay-tubod`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `image`, `start_date`, `end_date`) VALUES
(12, 'Feeding Program', 'asdasd', 'event_68c82b8f843aa0.30211947.jpg', '2025-09-23', '2025-09-25'),
(13, 'Cleaning Program', 'Help us and feed our community, contribute', 'event_68c82c0b84bbd0.18159576.jpg', '2025-09-08', '2025-09-18');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image`, `created_at`) VALUES
(4, 'Suspended Classes', 'K-11 suspended class from Monday to Wednesday, August 27-30', '1757950385_536138655_24759808540278756_1557332686037241442_n.jpg', '2025-09-15 15:33:05'),
(5, 'Suspended Classes', 'K-12 suspended class from Monday to Wednesday, August 27-30', '1757950387_536138655_24759808540278756_1557332686037241442_n.jpg', '2025-09-15 15:33:07');

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE `users_info` (
  `user_id` int(11) NOT NULL,
  `user_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `id_number` varchar(50) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_info`
--

INSERT INTO `users_info` (`user_id`, `user_type`, `id_number`, `first_name`, `middle_name`, `last_name`, `username`, `password`, `email`, `mobile_number`, `created_at`) VALUES
(1, 'user', NULL, 'Juan', 'Santos', 'Dela Cruz', 'juandelacruz', '123', 'juan.delacruz@example.com', NULL, '2025-09-11 15:56:03'),
(2, 'admin', 'ADM-0001', 'Maria', 'Lopez', 'Santos', 'adminmaria', '$2y$10$kA9jZ2h8P3fjL5C4sWjqU.xkppd5oB1Q9tJX7U7lyuDKH4Z9M5LkC', 'admin.maria@example.com', NULL, '2025-09-11 15:56:14'),
(3, 'user', NULL, 'kim', 'lazagas', 'escobido', 'kimnutsu', '123', 'kimescobido@gmail.com', NULL, '2025-09-11 16:06:56'),
(4, 'user', NULL, 'shiraishi', 'Asuna', 'Manjiro', 'Nero', '$2y$10$dy0VWeBy4DDktd6.q1DtiuEy7/XFQTMHqOMOyWzhfTUEZWLBwh//O', 'kimescobido30@gmail.com', '0952679235', '2025-09-11 16:36:46'),
(5, 'admin', 'AMD-002', 'shiraishi', 'Asuna', 'Manjiro', 'admin', '$2y$10$zGt9YWenyo3GgIU07J.yzu4GPlHBeuaGHbtQNoIvgKvhSmZYw.h9C', 'kimescobido0@gmail.com', '09917398085', '2025-09-11 17:25:19'),
(6, 'admin', 'AMD-004', 'Kim', 'Lazagas', 'Escobido', 'admin1', '$2y$10$oza1iqAt4.Dw3SukJbxzteSdxg.l8A3VCEvBV/HT7sjQKE7OZKCMa', 'asdada@gmail.com', '012313', '2025-09-11 17:26:42'),
(8, 'user', NULL, 'asda', 'dada', 'dada', 'Nero1', '$2y$10$czjgvW9RCy49HYwHS7o.2eXRSEMTkYfxauIvZ6N7wt4d3.Hq7uddq', 'asdadas@gmail.com', '012313', '2025-09-11 17:31:01'),
(9, 'user', NULL, 'Kim', 'dada', 'Manjiro', 'Nero2', '$2y$10$G16a.l7DSgx7flkE9POl6uXsmlfwXoE7pTFjWqGxkY0aYNjxra.cu', 'kimlazagas.escobido@my.smciligan.edu.ph', '21314134213', '2025-09-11 17:33:51'),
(10, 'admin', NULL, 'sadasda', 'dada', 'dadada', 'Nero3', '$2y$10$HquVmPJ7NOviji1M7kfiTOXWazrsFrC2b1taIwmxsJIo9NoPVao4y', 'kimescobido303@gmail.com', '541243123412', '2025-09-11 17:34:14');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_events`
--

CREATE TABLE `volunteer_events` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `volunteer_events`
--
ALTER TABLE `volunteer_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users_info`
--
ALTER TABLE `users_info`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `volunteer_events`
--
ALTER TABLE `volunteer_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `volunteer_events`
--
ALTER TABLE `volunteer_events`
  ADD CONSTRAINT `volunteer_events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_info` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `volunteer_events_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
