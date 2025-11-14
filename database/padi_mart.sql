-- PADI Mart Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS padi_mart;
USE padi_mart;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  price INT NOT NULL,
  weight VARCHAR(100),
  seller VARCHAR(255),
  phone VARCHAR(20),
  origin VARCHAR(255),
  `condition` VARCHAR(50) DEFAULT 'Baru',
  image LONGTEXT,
  popular TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_category (category),
  INDEX idx_seller (seller),
  INDEX idx_popular (popular)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create users table (for future authentication)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'seller', 'user') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create orders table (for future use)
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  product_id INT,
  quantity INT NOT NULL,
  total_price INT NOT NULL,
  status VARCHAR(50) DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id),
  INDEX idx_user_id (user_id),
  INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO products (title, category, price, weight, seller, phone, origin, `condition`, popular) VALUES
('Beras Premium Tabalong', 'Beras', 75000, '5kg', 'Tani Jaya', '+6281234567890', 'Tabalong, Kalimantan Selatan', 'Baru', 1),
('Kacang Tanah Organik', 'Camilan & Olahan', 45000, '500g', 'Organik Nusantara', '+6281234567891', 'Tabalong, Kalimantan Selatan', 'Baru', 0),
('Kerajinan Tangan Tradisional', 'Kerajinan & Oleh-oleh', 150000, '1kg', 'Seni Lokal', '+6281234567892', 'Tabalong, Kalimantan Selatan', 'Baru', 1);

