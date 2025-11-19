-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 01:27 AM
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
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `full_name`, `created_at`, `last_login`, `is_active`) VALUES
(1, 'admin', '$2y$10$CqZvx0sxiMOewQZ4Mnd6D.0vpg7Fwgfn2jt6hwGdfHn6m83z2KDq.', 'admin@padimart.com', 'Administrator', '2025-11-18 11:11:09', '2025-11-19 00:14:22', 1),
(2, 'angel', '$2y$10$BjLBT5fPhSW7lXtS2JjDFeXqxD.BnPVJc/7Yx7vWENZabFysVPkry', 'angel@gmail.com', 'michelle angelina', '2025-11-19 00:10:53', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Camilan & Olahan', 'Kategori Camilan & Olahan', '2025-11-18 10:31:57', '2025-11-18 10:31:57'),
(2, 'Pupuk', 'Kategori Pupuk', '2025-11-18 10:31:57', '2025-11-18 10:31:57'),
(3, 'Modular Turbine', 'RAHHHHH 80MMF EZSZZZ', '2025-11-18 11:07:19', '2025-11-18 11:07:19'),
(4, 'fdfscas', '2423423', '2025-11-19 00:14:56', '2025-11-19 00:14:56');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(4, 'KD_002', 'Low Pressure Steam', 'Modular Turbine', 3, 1500000, '11.5MPA', 'Coal Power Plant', '+10150000', 'MamyTema', 'Bekas - Baik', 'uploads/product_4_1763461917.png', 1, 0, '2025-11-14 02:15:16', '2025-11-18 11:07:35');

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
(2, 4, 'buh', '2025-11-18 04:22:40', '2025-11-18 04:22:40');

-- --------------------------------------------------------

--
-- Table structure for table `seller_information`
--

CREATE TABLE `seller_information` (
  `id` int(11) NOT NULL,
  `seller_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 4.00,
  `review_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seller_information`
--

INSERT INTO `seller_information` (`id`, `seller_name`, `contact_email`, `contact_phone`, `location`, `description`, `rating`, `review_count`, `created_at`, `updated_at`) VALUES
(1, 'Tani Jaya', 'tani.jaya@example.com', '+6281234567890', 'Tabalong, Kalimantan Selatan', 'Updated seller description - 11/18/2025, 11:21:20 AM', 4.70, 30, '2025-11-18 04:07:48', '2025-11-18 04:21:20'),
(2, 'Organik Nusantara', 'organik@example.com', '+6281234567891', 'Tabalong, Kalimantan Selatan', 'Produsen kacang tanah organik berkualitas tinggi dengan sertifikasi internasional.', 4.30, 18, '2025-11-18 04:07:48', '2025-11-18 04:07:48'),
(3, 'Seni Lokal', 'seni.lokal@example.com', '+6281234567892', 'Tabalong, Kalimantan Selatan', 'Pengrajin kerajinan tangan tradisional yang melestarikan budaya lokal.', 4.70, 32, '2025-11-18 04:07:48', '2025-11-18 04:07:48'),
(4, 'Koperasi Tani Maju', 'koperasi@example.com', '+628987654321', 'Tabalong, Kalimantan Selatan', 'Koperasi petani yang menyediakan pupuk organik berkualitas untuk pertanian berkelanjutan.', 4.20, 15, '2025-11-18 04:07:48', '2025-11-18 04:07:48'),
(5, 'Balai Benih Unggul', 'benih@example.com', '+628112233445', 'Tabalong, Kalimantan Selatan', 'Penyedia benih padi unggul dengan hasil panen yang terbukti meningkat.', 4.60, 28, '2025-11-18 04:07:48', '2025-11-18 04:07:48'),
(6, 'Bengkel Alat Tani', 'bengkel@example.com', '+628556677889', 'Tabalong, Kalimantan Selatan', 'Produsen alat pertanian berkualitas dengan garansi dan layanan purna jual terbaik.', 4.40, 22, '2025-11-18 04:07:48', '2025-11-18 04:07:48'),
(7, 'Pusat Edukasi Pertanian', 'edukasi@example.com', '+628667788990', 'Tabalong, Kalimantan Selatan', 'Lembaga edukasi pertanian yang menyediakan panduan lengkap untuk petani modern.', 4.80, 35, '2025-11-18 04:07:48', '2025-11-18 04:07:48'),
(8, 'Industri Olahan Lokal', 'olahan@example.com', '+628223344556', 'Tabalong, Kalimantan Selatan', 'Industri pengolahan gandum dengan standar kebersihan dan kualitas internasional.', 4.10, 12, '2025-11-18 04:07:48', '2025-11-18 04:07:48'),
(9, 'Pengrajin Bambu Lokal', 'bambu@example.com', '+628334455667', 'Tabalong, Kalimantan Selatan', 'Pengrajin anyaman bambu tradisional dengan desain modern yang menarik.', 4.50, 20, '2025-11-18 04:07:48', '2025-11-18 04:07:48'),
(10, 'Usaha Keripik Mama', 'keripik@example.com', '+628445566778', 'Tabalong, Kalimantan Selatan', 'Usaha keluarga yang memproduksi keripik singkong dengan resep rahasia turun temurun.', 4.60, 30, '2025-11-18 04:07:48', '2025-11-18 04:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','seller','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_product_id` (`product_id`);

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
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `seller_information`
--
ALTER TABLE `seller_information`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `seller_name` (`seller_name`),
  ADD KEY `idx_seller_name` (`seller_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_descriptions`
--
ALTER TABLE `product_descriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `seller_information`
--
ALTER TABLE `seller_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_descriptions`
--
ALTER TABLE `product_descriptions`
  ADD CONSTRAINT `product_descriptions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
