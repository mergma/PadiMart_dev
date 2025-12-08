<?php
    
    $dsn = "mysql:host=localhost";
    $username = "root";
    $password = "";
    
    try {
        $con = new PDO($dsn, $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database if it doesn't exist
        $con->exec("CREATE DATABASE IF NOT EXISTS padi_mart");
        
        // Select the database
        $con->exec("USE padi_mart");
        
        // Create categories table
        $con->exec("CREATE TABLE IF NOT EXISTS categories (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL UNIQUE,
            description LONGTEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Create admins table
        $con->exec("CREATE TABLE IF NOT EXISTS admins (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            full_name VARCHAR(100) DEFAULT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            last_login TIMESTAMP NULL DEFAULT NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        
        // Create users table
        $con->exec("CREATE TABLE IF NOT EXISTS users (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Create products table
        $con->exec("CREATE TABLE IF NOT EXISTS products (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            product_code VARCHAR(20) DEFAULT NULL UNIQUE,
            title VARCHAR(255) NOT NULL,
            category VARCHAR(100) NOT NULL,
            category_id INT DEFAULT NULL,
            price INT NOT NULL,
            weight VARCHAR(100) DEFAULT NULL,
            seller VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            origin VARCHAR(255) DEFAULT NULL,
            seller_location VARCHAR(255) DEFAULT NULL,
            `condition` VARCHAR(50) DEFAULT 'Baru',
            image LONGTEXT DEFAULT NULL,
            popular TINYINT(1) DEFAULT 0,
            stock INT DEFAULT 0,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            KEY idx_category (category),
            KEY idx_seller (seller),
            KEY idx_popular (popular),
            KEY fk_products_category (category_id),
            KEY idx_product_code (product_code),
            FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Create product_descriptions table
        $con->exec("CREATE TABLE IF NOT EXISTS product_descriptions (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            description LONGTEXT DEFAULT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            KEY fk_product_descriptions_product (product_id),
            FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Add seller_location column to products if it doesn't exist
        try {
            $con->exec("ALTER TABLE products ADD COLUMN seller_location VARCHAR(255) DEFAULT NULL AFTER origin");
        } catch (Exception $e) {
            // Column likely already exists, ignore error
        }
        
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit();
    }
?>
