-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 31, 2022 at 06:28 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

DROP TABLE IF EXISTS `bills`;
CREATE TABLE IF NOT EXISTS `bills` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) UNSIGNED DEFAULT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `total` double NOT NULL,
  `Tax` double DEFAULT NULL,
  `Status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bills_staff_id_index` (`staff_id`),
  KEY `bills_branch_id_index` (`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `staff_id`, `branch_id`, `total`, `Tax`, `Status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 28, NULL, 1, '2022-03-03 15:58:25', '2022-03-15 15:58:25'),
(2, 1, 2, 32, NULL, 1, '2022-03-16 03:29:22', '2022-03-16 03:29:22'),
(3, 1, 1, 74, NULL, 1, '2022-03-16 03:29:56', '2022-03-16 03:29:56'),
(4, 1, 2, 42, NULL, 1, '2022-03-17 09:48:09', '2022-03-17 09:48:09'),
(5, 1, 1, 28, NULL, 1, '2022-06-15 15:58:25', '2022-03-15 15:58:25'),
(6, 1, 1, 75, NULL, 1, '2022-07-16 03:29:56', '2022-03-16 03:29:56'),
(7, 1, 2, 75, NULL, 1, '2023-07-16 03:29:56', '2022-03-16 03:29:56'),
(20, 1, 1, 19, NULL, 1, '2022-09-30 21:00:00', '2022-03-29 07:28:33');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bills_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
