-- Add product descriptions and seller information tables
-- Run this migration to add editable descriptions and seller info

USE padi_mart;

-- Create product_descriptions table
CREATE TABLE IF NOT EXISTS product_descriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL UNIQUE,
  description LONGTEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create seller_information table
CREATE TABLE IF NOT EXISTS seller_information (
  id INT AUTO_INCREMENT PRIMARY KEY,
  seller_name VARCHAR(255) NOT NULL UNIQUE,
  contact_email VARCHAR(255),
  contact_phone VARCHAR(20),
  location VARCHAR(255),
  description LONGTEXT,
  rating DECIMAL(3,2) DEFAULT 4.0,
  review_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_seller_name (seller_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default seller information for existing sellers
INSERT IGNORE INTO seller_information (seller_name, contact_email, contact_phone, location, description, rating, review_count) VALUES
('Tani Jaya', 'tani.jaya@example.com', '+6281234567890', 'Tabalong, Kalimantan Selatan', 'Petani beras organik berpengalaman dengan komitmen terhadap kualitas dan keberlanjutan.', 4.5, 25),
('Organik Nusantara', 'organik@example.com', '+6281234567891', 'Tabalong, Kalimantan Selatan', 'Produsen kacang tanah organik berkualitas tinggi dengan sertifikasi internasional.', 4.3, 18),
('Seni Lokal', 'seni.lokal@example.com', '+6281234567892', 'Tabalong, Kalimantan Selatan', 'Pengrajin kerajinan tangan tradisional yang melestarikan budaya lokal.', 4.7, 32),
('Koperasi Tani Maju', 'koperasi@example.com', '+628987654321', 'Tabalong, Kalimantan Selatan', 'Koperasi petani yang menyediakan pupuk organik berkualitas untuk pertanian berkelanjutan.', 4.2, 15),
('Balai Benih Unggul', 'benih@example.com', '+628112233445', 'Tabalong, Kalimantan Selatan', 'Penyedia benih padi unggul dengan hasil panen yang terbukti meningkat.', 4.6, 28),
('Bengkel Alat Tani', 'bengkel@example.com', '+628556677889', 'Tabalong, Kalimantan Selatan', 'Produsen alat pertanian berkualitas dengan garansi dan layanan purna jual terbaik.', 4.4, 22),
('Pusat Edukasi Pertanian', 'edukasi@example.com', '+628667788990', 'Tabalong, Kalimantan Selatan', 'Lembaga edukasi pertanian yang menyediakan panduan lengkap untuk petani modern.', 4.8, 35),
('Industri Olahan Lokal', 'olahan@example.com', '+628223344556', 'Tabalong, Kalimantan Selatan', 'Industri pengolahan gandum dengan standar kebersihan dan kualitas internasional.', 4.1, 12),
('Pengrajin Bambu Lokal', 'bambu@example.com', '+628334455667', 'Tabalong, Kalimantan Selatan', 'Pengrajin anyaman bambu tradisional dengan desain modern yang menarik.', 4.5, 20),
('Usaha Keripik Mama', 'keripik@example.com', '+628445566778', 'Tabalong, Kalimantan Selatan', 'Usaha keluarga yang memproduksi keripik singkong dengan resep rahasia turun temurun.', 4.6, 30);

