-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2023 at 01:22 AM
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
  `employee_name` varchar(255) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `status` enum('ongoing','finished') NOT NULL DEFAULT 'ongoing',
  `completion_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `description`, `start_date`, `deadline`, `employee_name`, `employee_id`, `status`, `completion_date`) VALUES
(10, 'create stuff', '2023-09-30', '2023-10-07', 'Arnaldy Fortin', '12-3456-789', 'finished', '2023-10-06'),
(17, 'adsadzzxcdfgnnhhnhn', '2023-10-07', '2023-10-14', 'Arnaldy Fortin', '12-3456-789', 'ongoing', NULL),
(25, 'o9o9o9o9o9o', '2023-10-08', '2023-10-24', 'Arnaldy Fortin', '12-3456-789', '', NULL),
(26, 'kalayaan', '2023-10-08', '2023-10-09', 'employee1', '11-1111-111', 'finished', '2023-10-08'),
(27, 'katipunan', '2023-10-10', '2023-10-11', 'employee1', '11-1111-111', 'ongoing', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
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
('admin', 'Mr. Admin', 'admin@admin.com', '$2y$10$Dqk0dPDeIimXO4zk2OEGN./zh3NbQSmZ8VIROOXG8LmBQBJEaS2C.', 'Admin', '00-0000-000', '651fed5084197_administrator.jpg'),
('employee', 'employee1', 'emp1@email.com', '$2y$10$qF0XXoeJYIXn.Lp.uxvBruoV.Egl.32HM7xU1X1QR3SiNJ9qi3GFm', 'emp', '11-1111-111', '6521e72607f5e_emp1.jpg'),
('employee', 'Arnaldy Fortin', 'popoy@email.com', '$2y$10$EEWrhCynCshzzwComYwPyOi5FnxDGvTA/jzaizckC27Ohej.WnPrm', 'BSIT', '12-3456-789', '65202bba1bd7d_saitama.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_ibfk_1` (`employee_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
