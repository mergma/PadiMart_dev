-- ============================================
-- PADI MART - Complete Database Setup
-- ============================================
-- This file will create all necessary tables and sample data
-- Run this file in phpMyAdmin or MySQL command line

-- Create database (optional - uncomment if needed)
-- CREATE DATABASE IF NOT EXISTS padi_mart;
-- USE padi_mart;

-- ============================================
-- 1. CATEGORIES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample categories
INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Makanan', 'Produk makanan dan snack'),
(2, 'Minuman', 'Berbagai jenis minuman'),
(3, 'Kebutuhan Rumah', 'Perlengkapan rumah tangga'),
(4, 'Elektronik', 'Peralatan elektronik');

-- ============================================
-- 2. PRODUCTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(20) NOT NULL UNIQUE,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample products
INSERT INTO `products` (`product_code`, `name`, `category_id`, `price`, `stock`, `description`, `image`) VALUES
('KD_001', 'Indomie Goreng', 1, 3500.00, 100, 'Mi instan rasa goreng original', NULL),
('KD_002', 'Aqua 600ml', 2, 3000.00, 150, 'Air mineral dalam kemasan', NULL),
('KD_003', 'Sabun Lifebuoy', 3, 5000.00, 50, 'Sabun mandi batangan', NULL);

-- ============================================
-- 3. ADMINS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin account
-- Username: admin
-- Password: admin123
INSERT INTO `admins` (`username`, `password`, `email`, `full_name`, `is_active`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@padimart.com', 'Administrator', 1);

-- ============================================
-- SETUP COMPLETE!
-- ============================================
-- Default login credentials:
-- Username: admin
-- Password: admin123
-- 
-- IMPORTANT: Change the default password after first login!
-- ============================================

