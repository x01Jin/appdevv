-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2023 at 11:05 AM
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
  `status` enum('ongoing','finished') NOT NULL DEFAULT 'ongoing',
  `completion_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `description`, `start_date`, `deadline`, `student_name`, `student_id`, `status`, `completion_date`) VALUES
(10, 'create stuff', '2023-09-30', '2023-10-07', 'Arnaldy Fortin', '12-3456-789', 'finished', '2023-10-06'),
(26, 'kalayaan', '2023-10-08', '2023-10-09', 'employee1', '11-1111-111', 'ongoing', '2023-10-08'),
(27, 'katipunan', '2023-10-10', '2023-10-11', 'employee1', '11-1111-111', 'finished', '2023-10-11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `role` enum('adviser','student','headoffice') NOT NULL DEFAULT 'student',
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
('adviser', 'Ms. Administrator', 'admin@admin.com', '$2y$10$zxysxERJOxqVibfSe9ltDer/DzTaW6nltjfbB.Sz7LbTaEcuz58Ei', 'Admin', '00-0000-000', '651fed5084197_administrator.jpg'),
('student', 'employee1', 'emp1@email.com', '$2y$10$qF0XXoeJYIXn.Lp.uxvBruoV.Egl.32HM7xU1X1QR3SiNJ9qi3GFm', 'emp', '11-1111-111', '6521e72607f5e_emp1.jpg'),
('headoffice', 'Mr. Marlo', 'Marlo@reg.com', '$2y$10$DjAEDkHFIAJuFfZZItyFwuWDEmB1OfrzuX4YdTWY8Y6F5MFRdvJZK', 'Registrar', '11-2222-333', '6531041397834_marlo.jpg'),
('student', 'Arnaldy', 'popoy@email.com', '$2y$10$EEWrhCynCshzzwComYwPyOi5FnxDGvTA/jzaizckC27Ohej.WnPrm', 'BSIT', '12-3456-789', '652feb9745184_download.jpg'),
('student', 'employee2', 'emp2@email.com', '$2y$10$p8KwrrcvA7Ai.fT2i/PjV.qhMcj.A4UDXFiN6wX0U/9dn696.0HjW', 'emp', '22-2222-222', '../../profile_pictures/default.jpg'),
('student', 'employee3', 'emp3@email.com', '$2y$10$1uKB67ZICW39zCJLHj8lP.rV7tNJe/KZPdbu9O3MO8j9PspDinf8e', 'bsbs', '98-5466-344', '../../profile_pictures/default.jpg');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
