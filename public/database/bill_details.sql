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
-- Table structure for table `bill_details`
--

DROP TABLE IF EXISTS `bill_details`;
CREATE TABLE IF NOT EXISTS `bill_details` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Bill_id` int(10) UNSIGNED DEFAULT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
  `size` tinyint(4) DEFAULT NULL,
  `count` tinyint(4) DEFAULT NULL,
  `price` double NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bill_details_bill_id_index` (`Bill_id`),
  KEY `bill_details_item_id_index` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_details`
--

INSERT INTO `bill_details` (`id`, `Bill_id`, `item_id`, `size`, `count`, `price`, `Status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 2, 9, 1, '2022-03-15 15:58:25', '2022-03-15 15:58:25'),
(2, 1, 1, 1, 2, 5, 1, '2022-03-15 15:58:25', '2022-03-15 15:58:25'),
(3, 2, 1, 3, 3, 9, 1, '2022-03-16 03:29:22', '2022-03-16 03:29:22'),
(4, 2, 1, 1, 1, 5, 1, '2022-03-16 03:29:22', '2022-03-16 03:29:22'),
(5, 3, 2, 3, 3, 10, 1, '2022-03-16 03:29:56', '2022-03-16 03:29:56'),
(6, 3, 2, 2, 1, 8, 1, '2022-03-16 03:29:56', '2022-03-16 03:29:56'),
(7, 3, 1, 3, 4, 9, 1, '2022-03-16 03:29:56', '2022-03-16 03:29:56'),
(8, 4, 1, 3, 3, 9, 1, '2022-03-17 09:48:09', '2022-03-17 09:48:09'),
(9, 4, 1, 1, 3, 5, 1, '2022-03-17 09:48:09', '2022-03-17 09:48:09'),
(10, 5, 1, 3, 2, 9, 1, '2022-06-15 15:58:25', '2022-03-15 15:58:25'),
(11, 5, 1, 1, 2, 5, 1, '2022-06-15 15:58:25', '2022-03-15 15:58:25'),
(12, 6, 2, 3, 3, 10, 1, '2022-07-16 03:29:56', '2022-03-16 03:29:56'),
(13, 6, 2, 2, 1, 8, 1, '2022-07-16 03:29:56', '2022-03-16 03:29:56'),
(14, 6, 1, 3, 4, 9, 1, '2022-07-16 03:29:56', '2022-03-16 03:29:56'),
(15, 7, 2, 3, 3, 10, 1, '2022-07-16 03:29:56', '2022-03-16 03:29:56'),
(16, 7, 2, 2, 1, 8, 1, '2022-07-16 03:29:56', '2022-03-16 03:29:56'),
(17, 7, 1, 3, 4, 9, 1, '2022-07-16 03:29:56', '2022-03-16 03:29:56'),
(21, 20, 1, 3, 1, 9, 1, '2022-09-30 21:00:00', '2022-03-29 07:28:33'),
(22, 20, 1, 1, 1, 5, 1, '2022-09-30 21:00:00', '2022-03-29 07:28:33'),
(23, 20, 1, 1, 1, 5, 1, '2022-09-30 21:00:00', '2022-03-29 07:28:33');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill_details`
--
ALTER TABLE `bill_details`
  ADD CONSTRAINT `bill_details_bill_id_foreign` FOREIGN KEY (`Bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bill_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
