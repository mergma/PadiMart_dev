-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 02:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `padi_mart`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_code` varchar(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `weight` varchar(100) DEFAULT NULL,
  `seller` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `condition` varchar(50) DEFAULT 'Baru',
  `image` longtext DEFAULT NULL,
  `popular` tinyint(1) DEFAULT 0,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_code`, `title`, `category`, `category_id`, `price`, `weight`, `seller`, `phone`, `origin`, `condition`, `image`, `popular`, `stock`, `created_at`, `updated_at`) VALUES
(4, 'KD_002', 'Low Pressure Steam', 'Modular Turbine', 3, 1500000, '11.5MPA', 'Coal Power Plant', '+10150000', 'MamyTema', 'Bekas - Baik', 'uploads/product_4_1763461917.png', 1, 0, '2025-11-14 02:15:16', '2025-11-18 11:07:35'),
(6, 'KD_003', 'asdasd', 'Modular Turbine', 3, 123, '188 Tonne', 'Porsche, Krupp', '+62 821-1243-8397', 'Berlin', 'Baru', 'uploads/product_1763684782_691fb1ae613ce.png', 0, 1, '2025-11-21 00:26:22', '2025-12-03 01:59:19'),
(7, 'KD_004', 'N3D', 'Modular Turbine', 3, 154000000, '45Ton', 'N(ewBfz', '+628111213863', 'Eswatini', 'Baru', 'uploads/product_1764726995_692f98d3b86cf.png', 0, 5, '2025-12-03 01:56:35', '2025-12-03 01:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `product_descriptions`
--

CREATE TABLE `product_descriptions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_descriptions`
--

INSERT INTO `product_descriptions` (`id`, `product_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 4, NULL, '2025-12-08 02:15:16', '2025-12-08 02:15:16'),
(2, 6, NULL, '2025-12-08 02:15:16', '2025-12-08 02:15:16'),
(3, 7, NULL, '2025-12-08 02:15:16', '2025-12-08 02:15:16');

-- --------------------------------------------------------

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code` (`product_code`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_seller` (`seller`),
  ADD KEY `idx_popular` (`popular`),
  ADD KEY `fk_products_category` (`category_id`),
  ADD KEY `idx_product_code` (`product_code`);

--
-- Indexes for table `product_descriptions`
--
ALTER TABLE `product_descriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_descriptions_product` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_descriptions`
--
ALTER TABLE `product_descriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_descriptions`
--
ALTER TABLE `product_descriptions`
  ADD CONSTRAINT `fk_product_descriptions_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
