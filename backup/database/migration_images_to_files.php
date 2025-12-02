<?php
/**
 * Migration Script: Convert Base64 Images to Files
 * This script converts all base64-encoded images in the database to actual files
 * and updates the database to store file paths instead
 */

require_once '../api/config.php';

// Configuration
$uploadsDir = '../uploads/';
$defaultImage = 'uploads/default.jpg';

// Create uploads directory if it doesn't exist
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
    echo "✓ Created uploads directory\n";
}

// Copy default image if it doesn't exist
$defaultImageSource = '../img/PADI MART.png';
$defaultImageDest = $uploadsDir . 'default.jpg';
if (!file_exists($defaultImageDest) && file_exists($defaultImageSource)) {
    copy($defaultImageSource, $defaultImageDest);
    echo "✓ Created default image\n";
}

// Start migration
echo "\n=== Starting Image Migration ===\n\n";

try {
    // Get all products with base64 images
    $sql = "SELECT id, title, image FROM products WHERE image IS NOT NULL AND image != ''";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Error fetching products: " . $conn->error);
    }
    
    $totalProducts = $result->num_rows;
    $converted = 0;
    $skipped = 0;
    $errors = 0;
    
    echo "Found {$totalProducts} products to process\n\n";
    
    while ($row = $result->fetch_assoc()) {
        $productId = $row['id'];
        $productTitle = $row['title'];
        $imageData = $row['image'];
        
        // Skip if image is already a file path
        if (strpos($imageData, 'uploads/') === 0 || strpos($imageData, 'http') === 0) {
            echo "⊘ Product #{$productId} ({$productTitle}): Already using file path\n";
            $skipped++;
            continue;
        }
        
        // Skip if image is empty or too short to be valid base64
        if (strlen($imageData) < 100) {
            echo "⊘ Product #{$productId} ({$productTitle}): Image data too short, using default\n";
            $updateSql = "UPDATE products SET image = '$defaultImage' WHERE id = $productId";
            $conn->query($updateSql);
            $skipped++;
            continue;
        }
        
        try {
            // Decode base64 image
            $imageContent = base64_decode($imageData);
            
            if ($imageContent === false) {
                throw new Exception("Failed to decode base64");
            }
            
            // Detect image type
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($imageContent);
            
            // Determine file extension
            $extension = 'jpg';
            switch ($mimeType) {
                case 'image/jpeg':
                    $extension = 'jpg';
                    break;
                case 'image/png':
                    $extension = 'png';
                    break;
                case 'image/gif':
                    $extension = 'gif';
                    break;
                case 'image/webp':
                    $extension = 'webp';
                    break;
                default:
                    $extension = 'jpg';
            }
            
            // Generate unique filename
            $filename = 'product_' . $productId . '_' . time() . '.' . $extension;
            $filepath = $uploadsDir . $filename;
            $dbPath = 'uploads/' . $filename;
            
            // Save image to file
            if (file_put_contents($filepath, $imageContent) === false) {
                throw new Exception("Failed to write file");
            }
            
            // Update database with file path
            $dbPath = $conn->real_escape_string($dbPath);
            $updateSql = "UPDATE products SET image = '$dbPath' WHERE id = $productId";
            
            if (!$conn->query($updateSql)) {
                throw new Exception("Database update failed: " . $conn->error);
            }
            
            echo "✓ Product #{$productId} ({$productTitle}): Converted to {$filename}\n";
            $converted++;
            
        } catch (Exception $e) {
            echo "✗ Product #{$productId} ({$productTitle}): Error - " . $e->getMessage() . "\n";
            
            // Set default image on error
            $updateSql = "UPDATE products SET image = '$defaultImage' WHERE id = $productId";
            $conn->query($updateSql);
            $errors++;
        }
    }
    
    // Generate product codes for products that don't have them
    echo "\n=== Generating Product Codes ===\n\n";
    
    $sql = "SELECT id FROM products WHERE product_code IS NULL OR product_code = '' ORDER BY id";
    $result = $conn->query($sql);
    
    $codeCounter = 1;
    while ($row = $result->fetch_assoc()) {
        $productId = $row['id'];
        $productCode = 'KD_' . str_pad($codeCounter, 3, '0', STR_PAD_LEFT);
        
        $updateSql = "UPDATE products SET product_code = '$productCode' WHERE id = $productId";
        if ($conn->query($updateSql)) {
            echo "✓ Product #{$productId}: Assigned code {$productCode}\n";
            $codeCounter++;
        }
    }
    
    // Summary
    echo "\n=== Migration Summary ===\n";
    echo "Total Products: {$totalProducts}\n";
    echo "Converted: {$converted}\n";
    echo "Skipped: {$skipped}\n";
    echo "Errors: {$errors}\n";
    echo "\n✓ Migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "\n✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

$conn->close();
?>

