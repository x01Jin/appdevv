-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2023 at 09:01 AM
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
-- Database: `appdevdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `deadline` date NOT NULL,
  `employee_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `description`, `start_date`, `deadline`, `employee_name`) VALUES
(7, 'create a system', '2023-09-27', '2023-09-30', 'Spongebob S. Squarepants'),
(8, 'magpahaba ng buhok', '2023-09-28', '2025-12-31', 'Arnaldy Fortin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `program` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `full_name`, `email`, `password`, `program`, `id_number`, `profile_picture`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '$2y$10$i6etcdTh7DbjbhKdmQjAIOA7jM4M0OjKpaXwnz/Q8t5.FAyTViXPO', NULL, NULL, NULL),
(8, 'employee', 'Spongebob S. Squarepants', 'sponge123@email.com', '$2y$10$HDuxN4Y65KZ0Hxi/p08p.OOCEcztA13Mlo5I.ktsKVep6ZMcZ89Oe', 'BSHM', '69-6969-420', NULL),
(9, 'employee', 'patrick p. star', 'patrick@email.com', '$2y$10$TnmTHkCw1wLsX7ZwKTawLewyuiHqc8fziEhCJ2NamAaafdkvIRiZ.', 'BSHM', '92-1098-876', '65140e583ddf9_patrick.jpg'),
(10, 'employee', 'Arnaldy Fortin', 'popoy@email.com', '$2y$10$EEWrhCynCshzzwComYwPyOi5FnxDGvTA/jzaizckC27Ohej.WnPrm', 'BSIT', '12-3456-789', '65157f559c80c_mrclean.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
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
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
