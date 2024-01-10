-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2024 at 01:23 PM
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
  `student_name` varchar(255) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `status` enum('requested','ongoing','finished') NOT NULL DEFAULT 'requested',
  `completion_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `description`, `start_date`, `deadline`, `student_name`, `student_id`, `status`, `completion_date`) VALUES
(86, 'Help in encoding of TES', '0000-00-00', '0000-00-00', '', 'unassigned', 'requested', NULL),
(87, 'Help in encoding of ISO', '0000-00-00', '0000-00-00', '', 'unassigned', 'requested', NULL),
(88, 'Help organizing papers', '0000-00-00', '0000-00-00', '', 'unassigned', 'requested', NULL),
(89, 'Request personal Help', '0000-00-00', '0000-00-00', '', 'unassigned', 'requested', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `role` enum('adviser','student','headoffice','placeholder') NOT NULL DEFAULT 'student',
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `program` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`role`, `full_name`, `email`, `password`, `program`, `id_number`, `profile_picture`) VALUES
('adviser', 'Ms. Adviser', 'adviser@adviser.com', '$2y$10$BqK4ApNJ/rgccP7CuHUISujfjUFcULZzN46bi7aO2WCOVxlJVebhG', 'Admin', '00-0000-000', '65341dc304b28_administrator.jpg'),
('student', 'employee1', 'emp1@email.com', '$2y$10$xUgb9rZ4v5doeaa/trbAluhGGlgpCAQlI/RDbydsNH.DEvR/58pCe', 'BSCS', '10-8210-821', '659e85d191492_default.jpg'),
('headoffice', 'Mr. Marlo', 'marlo@reg.com', '$2y$10$OUVFQBByMdqt4ax7inGu..RZsJQpRv2lKfJ8Uno6flDy6OJDgJT7G', 'Registrar', '11-2222-333', '6531041397834_marlo.jpg'),
('student', 'Clarence Fuertes', 'clarence@email.com', '$2y$10$Gb5S64vr.amA9ELDUYf5E.ywjsg2eFs7t.8/3dbmDJFC0Iy0WjLha', 'BSCS', '69-6699-420', NULL),
('placeholder', 'PlaceHolder', 'place@holder.com', 'placeholder', NULL, 'unassigned', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_ibfk_1` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
