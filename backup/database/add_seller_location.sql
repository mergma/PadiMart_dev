-- Add seller_location column to products table if it doesn't exist
ALTER TABLE products ADD COLUMN seller_location VARCHAR(255) DEFAULT NULL AFTER origin;
