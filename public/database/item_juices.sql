-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 14, 2022 at 07:27 AM
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
-- Table structure for table `item_juices`
--

DROP TABLE IF EXISTS `item_juices`;
CREATE TABLE IF NOT EXISTS `item_juices` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Shope_id` int(10) UNSIGNED DEFAULT NULL,
  `categories_id` int(10) UNSIGNED DEFAULT NULL,
  `Name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Small_Price` double NOT NULL,
  `Mid_Price` double NOT NULL,
  `Big_Price` double NOT NULL,
  `Gallon_Price` double NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_juices_shope_id_index` (`Shope_id`),
  KEY `item_juices_categories_id_index` (`categories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_juices`
--

INSERT INTO `item_juices` (`id`, `Shope_id`, `categories_id`, `Name`, `Small_Price`, `Mid_Price`, `Big_Price`, `Gallon_Price`, `Status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'تفاح', 4, 6, 9, 25, 1, NULL, NULL),
(2, 1, 1, 'برتقال', 4, 6, 8, 22, 1, NULL, NULL),
(3, 1, 1, 'موز', 4, 6, 8, 23, 1, NULL, NULL),
(4, 1, 1, 'رمان', 5, 7, 9, 27, 1, NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_juices`
--
ALTER TABLE `item_juices`
  ADD CONSTRAINT `item_juices_categories_id_foreign` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_juices_shope_id_foreign` FOREIGN KEY (`Shope_id`) REFERENCES `shopes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
