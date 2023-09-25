-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2023 at 07:16 PM
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
  `employee_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `deadline` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `description`, `employee_id`, `start_date`, `deadline`) VALUES
(1, 'kababalaghan', 2, '2023-09-24', '2023-09-30'),
(2, 'create a systemf', 3, '2023-09-24', '2023-09-30'),
(3, 'mag kape ka', 4, '2023-09-28', '2023-09-29'),
(4, 'create stuff', 5, '2023-09-25', '2023-09-30'),
(5, 'mag push up for a week 50 reps per day', 4, '2023-09-25', '2023-10-01'),
(6, 'lastogan mo si bogart', 6, '2023-09-26', '2023-10-02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `program` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `profile_picture` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `username`, `email`, `password`, `program`, `id_number`, `profile_picture`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '$2y$10$i6etcdTh7DbjbhKdmQjAIOA7jM4M0OjKpaXwnz/Q8t5.FAyTViXPO', NULL, NULL, NULL),
(2, 'employee', 'alex', 'exg1082@gmail.com', '$2y$10$D/ib0IrFeh88ipnDaakdxuMeNQOY7qvb45.lE/7yJ3CbRSwsH1hnC', NULL, NULL, NULL),
(3, 'employee', 'kalbo', 'kalbo@cisco.com', '$2y$10$u84.gpg69paxSx4gdPXk0eRHA6LOxajWz3wEAHhNfoSUMqO.nugn2', NULL, NULL, NULL),
(4, 'employee', 'darreil', 'dar@email.com', '$2y$10$6NAD1GQKuXv/Kanf7VJq5OevYAgFNORnFGaWG6psV9aqq8OTY0aqi', NULL, NULL, NULL),
(5, 'employee', 'edward', 'ed@ward.com', '$2y$10$mE2opvOSlfY7793MMHmpju2uh2UTwd04yu9Ay2PwLGNHE93RztZEm', NULL, NULL, NULL),
(6, 'employee', 'daniel', 'daniel@malastog.com', '$2y$10$I/GNFMhecsGg1JIJMYtZyeiP4EUgNEZ4lQYI1t161hk38mToMsMUm', NULL, NULL, NULL),
(7, 'admin', 'danyel2', 'ferrari@malastog.com', '$2y$10$s.NmV7pdFXJN1mJE2DHwieXK3ltHnM9MMrF4wq4hBu0gDX9zsMhda', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
