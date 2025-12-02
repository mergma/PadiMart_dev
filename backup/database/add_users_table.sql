-- ============================================
-- Add Users Table for Regular User Authentication
-- ============================================
-- Run this file if you already have the database set up
-- and just need to add the users table
-- ============================================

-- Create users table for regular users (non-admins)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','seller','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DONE!
-- ============================================
-- Now you have two types of users:
-- 1. Admins (in 'admins' table) - Can access admin panel
-- 2. Regular Users (in 'users' table) - Can only view products
--
-- Registration creates regular users by default.
-- To create an admin, manually insert into 'admins' table.
-- ============================================

