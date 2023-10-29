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
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `month` date NOT NULL,
  `branchRent` double DEFAULT NULL,
  `electricBill` double DEFAULT NULL,
  `waterBill` double DEFAULT NULL,
  `salaryBill` double DEFAULT NULL,
  `OtherBill` double DEFAULT NULL,
  `Status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_branch_id_index` (`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `branch_id`, `month`, `branchRent`, `electricBill`, `waterBill`, `salaryBill`, `OtherBill`, `Status`, `created_at`, `updated_at`) VALUES
(1, 1, '2022-03-01', 1000, 400, 150, 3000, 1500, 1, '2022-03-16 09:43:48', '2022-03-30 05:54:52'),
(2, 2, '2022-03-01', 1000, 400, 150, 3000, 1500, 1, '2022-03-16 09:43:48', '2022-03-30 05:54:52'),
(4, 1, '2022-04-01', 2000, 100, 150, 3000, 1700, 1, '2022-03-16 09:48:55', '2022-03-16 09:48:55'),
(5, 3, '2022-04-01', 2000, 400, 150, 3000, 1700, 1, '2022-03-16 09:48:55', '2022-03-16 09:48:55'),
(6, 1, '2022-05-01', 2000, 100, 150, 3000, 1700, 1, '2022-03-16 09:48:55', '2022-03-16 09:48:55'),
(7, 1, '2022-06-01', 2000, 100, 150, 3000, 1700, 1, '2022-03-16 09:48:55', '2022-03-16 09:48:55'),
(8, 1, '2022-07-01', 200, 100, 150, 300, 170, 1, '2022-07-16 09:48:55', '2022-03-16 09:48:55'),
(9, 2, '2022-07-01', 200, 100, 150, 300, 170, 1, '2023-07-16 09:48:55', '2022-03-16 09:48:55'),
(10, 2, '2023-06-01', 20, 100, 15, 30, 17, 1, '2022-03-16 09:48:55', '2022-03-16 09:48:55'),
(11, 2, '2023-07-01', 20, 100, 15, 30, 17, 1, '2022-03-16 09:48:55', '2022-03-16 09:48:55'),
(12, 1, '2022-10-01', NULL, NULL, NULL, NULL, NULL, 1, '2022-03-29 07:27:09', '2022-03-29 07:27:09');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
