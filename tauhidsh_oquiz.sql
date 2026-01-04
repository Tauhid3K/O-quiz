-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 04, 2026 at 07:35 PM
-- Server version: 8.0.42-cll-lve
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tauhidsh_oquiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int NOT NULL,
  `attempt_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer` text COLLATE utf8mb4_general_ci,
  `is_correct` tinyint(1) DEFAULT NULL,
  `points_earned` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `attempt_id`, `question_id`, `answer`, `is_correct`, `points_earned`) VALUES
(3, 8, 6, 'C', 1, 2),
(4, 8, 7, 'A', 1, 1),
(5, 10, 6, 'C', 1, 2),
(6, 10, 7, 'A', 1, 1),
(7, 12, 8, '', 0, 0),
(8, 12, 9, '', 0, 0),
(9, 13, 8, '', 0, 0),
(10, 13, 9, '', 0, 0),
(11, 14, 6, 'C', 1, 2),
(12, 14, 7, 'A', 1, 1),
(13, 15, 8, 'A', 1, 1),
(14, 15, 9, 'C', 1, 1),
(15, 16, 8, 'A', 1, 1),
(16, 16, 9, 'C', 1, 1),
(17, 16, 10, 'B', 1, 1),
(18, 17, 8, 'A', 1, 1),
(19, 17, 9, 'C', 1, 1),
(20, 17, 10, 'B', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `attempts`
--

CREATE TABLE `attempts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `attempt_no` int DEFAULT '1',
  `status` enum('in_progress','completed','abandoned') COLLATE utf8mb4_general_ci DEFAULT 'in_progress',
  `started_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` datetime NOT NULL,
  `finished_at` datetime DEFAULT NULL,
  `total_time_seconds` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attempts`
--

INSERT INTO `attempts` (`id`, `user_id`, `quiz_id`, `attempt_no`, `status`, `started_at`, `end_time`, `finished_at`, `total_time_seconds`) VALUES
(8, 2, 1, 1, 'completed', '2025-12-24 02:47:27', '2025-12-23 21:57:27', '2025-12-24 02:47:40', -17987),
(9, 2, 1, 2, 'in_progress', '2025-12-23 21:51:05', '2025-12-23 21:01:05', NULL, NULL),
(10, 4, 1, 1, 'completed', '2025-12-24 14:26:52', '2025-12-24 13:36:52', '2025-12-24 14:27:20', -3572),
(11, 4, 1, 2, 'in_progress', '2025-12-24 14:27:47', '2025-12-24 13:37:47', NULL, NULL),
(12, 8, 2, 1, 'completed', '2025-12-28 20:39:25', '2025-12-28 14:39:25', '2025-12-28 20:39:31', -21594),
(13, 8, 2, 2, 'completed', '2025-12-28 20:39:38', '2025-12-28 14:39:38', '2025-12-28 20:41:12', -21506),
(14, 8, 1, 1, 'completed', '2025-12-28 20:41:32', '2025-12-28 14:51:32', '2025-12-28 20:41:44', -21588),
(15, 9, 2, 1, 'completed', '2025-12-28 21:11:25', '2025-12-28 15:16:25', '2025-12-28 21:11:32', -21593),
(16, 10, 2, 1, 'completed', '2025-12-28 22:01:11', '2025-12-28 16:06:11', '2025-12-28 22:01:24', -21587),
(17, 11, 2, 1, 'completed', '2025-12-29 12:20:26', '2025-12-29 06:25:26', '2025-12-29 12:20:49', -21577);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `question_text` text COLLATE utf8mb4_general_ci NOT NULL,
  `question_type` enum('MCQ','True-False','Short') COLLATE utf8mb4_general_ci NOT NULL,
  `option_a` text COLLATE utf8mb4_general_ci,
  `option_b` text COLLATE utf8mb4_general_ci,
  `option_c` text COLLATE utf8mb4_general_ci,
  `option_d` text COLLATE utf8mb4_general_ci,
  `correct_answer` text COLLATE utf8mb4_general_ci NOT NULL,
  `points` int DEFAULT '1',
  `answer_feedback` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `question_type`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `points`, `answer_feedback`) VALUES
(6, 1, 'what is the value of G', 'MCQ', '9.7', '10.0', '9.8', '9.9', 'C', 2, ''),
(7, 1, 'আলোর গতি কত? ', 'MCQ', '$$3 \\times 10^8$$', '$$3 \\times 10^4$$', '$$3 \\times 10^3$$', '$$3 \\times 10^4$$', 'A', 1, ''),
(8, 2, 'What is the chemical formula for water?', 'MCQ', '$$H_20$$', '$$H_30$$', '$$H0$$', '$$H_20_2$$', 'A', 1, ''),
(9, 2, 'What is the pH value of a neutral solution?', 'MCQ', '8', '9', '7', '5', 'C', 1, ''),
(10, 2, 'What is the chemical symbol for gold?', 'MCQ', 'Ag', 'Au', 'Cl', 'He', 'B', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `subject_id` int DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `total_points` int DEFAULT '0',
  `time_limit_minutes` int DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subject` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `chapter` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `special_exam_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `title`, `description`, `subject_id`, `created_by`, `total_points`, `time_limit_minutes`, `is_published`, `created_at`, `subject`, `chapter`, `special_exam_name`) VALUES
(1, 'Model Test', NULL, NULL, 3, 0, 10, 0, '2025-12-20 17:15:07', 'Physics', NULL, NULL),
(2, 'Test', NULL, NULL, 5, 0, 5, 0, '2025-12-28 08:55:09', 'Chemistry', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int NOT NULL,
  `attempt_id` int NOT NULL,
  `user_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `score` int NOT NULL,
  `total_possible` int NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `attempted_on` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `attempt_id`, `user_id`, `quiz_id`, `score`, `total_possible`, `percentage`, `attempted_on`) VALUES
(2, 8, 2, 1, 3, 3, 100.00, '2025-12-24 02:47:40'),
(3, 10, 4, 1, 3, 3, 100.00, '2025-12-24 14:27:20'),
(4, 12, 8, 2, 0, 2, 0.00, '2025-12-28 20:39:31'),
(5, 13, 8, 2, 0, 2, 0.00, '2025-12-28 20:41:12'),
(6, 14, 8, 1, 3, 3, 100.00, '2025-12-28 20:41:44'),
(7, 15, 9, 2, 2, 2, 100.00, '2025-12-28 21:11:32'),
(8, 16, 10, 2, 3, 3, 100.00, '2025-12-28 22:01:24'),
(9, 17, 11, 2, 3, 3, 100.00, '2025-12-29 12:20:49');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('student','teacher','admin') COLLATE utf8mb4_general_ci DEFAULT 'student',
  `email_verified` tinyint(1) DEFAULT '0',
  `verification_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('active','blocked') COLLATE utf8mb4_general_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `created_at`, `role`, `email_verified`, `verification_token`, `status`) VALUES
(1, 'Tauhid Shahriar', 'ts.ek.3902@gmail.com', '$2y$10$fNRS4JnFQuRAR6Yz0XGWW.vU/SZvL/OrjVeNKcZU/le39cqQekKsm', '2025-12-13 19:04:39', 'admin', 1, NULL, 'active'),
(2, 'Sourov Hasan', 'sourovhasan@gmail.com', '$2y$10$2hYcXAR403.Q19zAyPvNOOkRPdFLrG.kEmRHWy0wqkPgi6vtdwyU.', '2025-12-19 19:39:31', 'student', 0, NULL, 'active'),
(3, 'Shahriar ', 'smilysourov5000@gmail.com', '$2y$10$4GTjgbHBreVf/IFY2WltWuXGcKLJhLdL.xBUkb3K3Iuynf8LXhr/W', '2025-12-19 19:40:13', 'teacher', 0, NULL, 'active'),
(4, 'Niaz', 'intekhabhossain.niaz@gmail.com', '$2y$10$ONxFbU65fWdGdiSRmycvbuNjpUvWfNbAt5qcqO3uVOB9r33PRThfK', '2025-12-24 13:26:28', 'student', 0, NULL, 'active'),
(5, 'Teacher', 'teacher@gmail.com', '$2y$10$VEkw6I.qqEcVDSGi86fcxObGX04o8sfYV6qOFD4XvOXI9JoC5Mj7i', '2025-12-28 08:54:34', 'teacher', 0, NULL, 'active'),
(6, 'Nafis Islam', 'nafis@gmail.com', '$2y$10$mO61rQcHVm1V0MhL8rdnGudkdRPNtUXSqWv4Ob0aIf41IOu.XM852', '2025-12-28 09:09:22', 'student', 0, NULL, 'active'),
(7, 'Hadi', 'hadi@gmail.com', '$2y$10$yXfYm4rXSAq1u7ht/B157ubUarhFEhjLeCDtbt.OoEoandeYyVzT.', '2025-12-28 13:19:15', 'student', 0, NULL, 'active'),
(8, 'Arman Hossain', 'arman@gmail.com', '$2y$10$k8AS9CGRpPzKXYKONuD.gOa28qTcpgmSmXERPBvOUjIrdQoidzk5y', '2025-12-28 14:39:04', 'student', 0, NULL, 'active'),
(9, 'Tauhid Nibir', 'nibir@gmail.com', '$2y$10$lAezXVe.tTlzghJuFOXaX.ygkQF5lANj5nfuWrFegyJN2HOqDhdE6', '2025-12-28 15:11:12', 'student', 0, NULL, 'active'),
(10, 'Abdullah As Sakib', 'sakib@gmail.com', '$2y$10$2ETGz3T14Kqmv8CZn5l1FOwwbo1xe2JB9G2lUe1BuvxL4FDQS35zC', '2025-12-28 16:01:01', 'student', 0, NULL, 'active'),
(11, 'Sohan', 'sohan@gmail.com', '$2y$10$w8xjgzoGf5Hzcv6Hmg1MG.rVt8tgKMA1g65hRQ/oWS52/hJsBtru6', '2025-12-29 06:19:31', 'student', 0, NULL, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attempt_id` (`attempt_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `idx_attempt_question` (`attempt_id`,`question_id`);

--
-- Indexes for table `attempts`
--
ALTER TABLE `attempts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_quiz_attempt` (`user_id`,`quiz_id`,`attempt_no`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `idx_user_quiz` (`user_id`,`quiz_id`),
  ADD KEY `idx_started_at` (`started_at`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `idx_quiz_type` (`quiz_id`,`question_type`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attempt_id` (`attempt_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `idx_user_quiz` (`user_id`,`quiz_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD KEY `idx_role_email` (`role`,`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `attempts`
--
ALTER TABLE `attempts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attempts`
--
ALTER TABLE `attempts`
  ADD CONSTRAINT `attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attempts_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_3` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
