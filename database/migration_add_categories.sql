-- Migration: Add Categories Table and Update Products Schema
-- This migration adds category management and file-based image storage

USE padi_mart;

-- Step 1: Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 2: Add product_code column to products table
ALTER TABLE products 
ADD COLUMN IF NOT EXISTS product_code VARCHAR(20) UNIQUE AFTER id;

-- Step 3: Add category_id column to products table
ALTER TABLE products 
ADD COLUMN IF NOT EXISTS category_id INT AFTER category;

-- Step 4: Add stock column for inventory management (optional, default 0)
ALTER TABLE products 
ADD COLUMN IF NOT EXISTS stock INT DEFAULT 0 AFTER popular;

-- Step 5: Modify image column to store file paths instead of base64
-- Note: This will be handled by the migration script to preserve existing images
-- ALTER TABLE products MODIFY COLUMN image VARCHAR(255) DEFAULT 'uploads/default.jpg';

-- Step 6: Insert default categories from existing product categories
INSERT IGNORE INTO categories (name, description) 
SELECT DISTINCT category, CONCAT('Kategori ', category) 
FROM products 
WHERE category IS NOT NULL AND category != '' AND category NOT IN (SELECT name FROM categories);

-- Step 7: Update category_id based on existing category names
UPDATE products p 
INNER JOIN categories c ON p.category = c.name 
SET p.category_id = c.id
WHERE p.category_id IS NULL;

-- Step 8: Add foreign key constraint
-- First check if constraint doesn't exist
SET @constraint_exists = (
    SELECT COUNT(*) 
    FROM information_schema.TABLE_CONSTRAINTS 
    WHERE CONSTRAINT_SCHEMA = 'padi_mart' 
    AND TABLE_NAME = 'products' 
    AND CONSTRAINT_NAME = 'fk_products_category'
);

SET @sql = IF(@constraint_exists = 0,
    'ALTER TABLE products ADD CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL',
    'SELECT "Foreign key already exists"'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Step 9: Create index on product_code for faster lookups
CREATE INDEX IF NOT EXISTS idx_product_code ON products(product_code);

-- Display migration results
SELECT 'Migration completed successfully!' AS Status;
SELECT COUNT(*) AS 'Total Categories' FROM categories;
SELECT COUNT(*) AS 'Total Products' FROM products;
SELECT COUNT(*) AS 'Products with Category ID' FROM products WHERE category_id IS NOT NULL;

