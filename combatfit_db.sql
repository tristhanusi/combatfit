-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2026 at 05:10 PM
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
-- Database: `combatfit_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `gym_attendance`
--

CREATE TABLE `gym_attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_date` date NOT NULL,
  `session_type` varchar(100) NOT NULL,
  `status` varchar(20) DEFAULT 'Present',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `sex` varchar(10) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) NOT NULL DEFAULT 'Trainee',
  `age` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `membership_tier` varchar(50) DEFAULT NULL,
  `fitness_goal` text DEFAULT NULL,
  `assigned_coach_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `is_first_login` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `birthdate`, `sex`, `contact_number`, `email`, `password`, `created_at`, `role`, `age`, `address`, `membership_tier`, `fitness_goal`, `assigned_coach_id`, `status`, `is_first_login`) VALUES
(39, 'Roland Gabriel Dy', '0000-00-00', '', '', 'roland@combatfit.com', '$2y$10$ByyrR943XrbcHV7EA41YleZ9QJTE17LMtkUvC6fhw1X1FNW/Ux02q', '2026-06-24 07:15:50', 'Trainer', NULL, NULL, 'Staff', 'Head Boxing Coach', NULL, 'Active', 1),
(40, 'Marvin Malunes', '0000-00-00', '', '', 'marvin@combatfit.com', '$2y$10$9FSgoLkWjqkw8SlQfGE9/.oRy493RmqVeYHBiI33XNiVyl9PVvG0e', '2026-06-24 07:16:17', 'Trainer', NULL, NULL, 'Staff', 'Trainer', NULL, 'Active', 1),
(41, 'Jason Canales', '0000-00-00', '', '', 'jason@combatfit.com', '$2y$10$jKfkr1VUCfC3o0yddhM9Vuy6x4UvBLAWZB/uApBVR/Nji3Nr85o/G', '2026-06-24 07:16:52', 'Trainer', NULL, NULL, 'Staff', 'Head Fitness Coach', NULL, 'Active', 1),
(42, 'Aaron Paul', '0000-00-00', '', '', 'aaron@combatfit.com', '$2y$10$aDXVsQhTV6zaEJQPbBU4c.jWuMf1FFnOLO8vF432E.BHzKHYz46Yu', '2026-06-24 07:17:35', 'Trainer', NULL, NULL, 'Staff', 'Fitness Instructor', NULL, 'Active', 1),
(43, 'Tristhan Jerronne Usi', '0000-00-00', '', '', 'tristhan@gmail.com', '$2y$10$fZnzi0GAq.KHBQWyhAk6Mur5UX60BKcDiAcx.O4odKSY.DwXOT9Ie', '2026-06-27 05:10:18', 'Trainee', NULL, 'https://www.facebook.com/', 'Prime', '09161058055', NULL, 'Active', 1),
(44, 'Wendy Ann Capitle', '0000-00-00', '', '', 'wendy@gmail.com', '$2y$10$UnXuEF6Q3ilJFZIlVD6HxehWb6RfGIZgxKglnTwLrZ5o9CWa2Dj6m', '2026-06-27 05:13:01', 'Trainee', NULL, 'https://www.facebook.com/', 'Premium', '09763651298', 39, 'Active', 1),
(46, 'Marlon Rodriguez', '0000-00-00', '', '', 'marlon@combatfit.com', '$2y$10$e4A4FCmLFD3uXaF/dHGncezSXYS3X1vrE0MQLvzwoiELQNneNjuH2', '2026-06-27 05:46:01', 'Trainer', NULL, NULL, 'Staff', 'Muay Thai Instructor', NULL, 'Active', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gym_attendance`
--
ALTER TABLE `gym_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gym_attendance`
--
ALTER TABLE `gym_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gym_attendance`
--
ALTER TABLE `gym_attendance`
  ADD CONSTRAINT `gym_attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
