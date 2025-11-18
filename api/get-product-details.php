<?php
/**
 * Get Product Details with Description and Seller Info
 * GET: Get product with description and seller information
 */

require_once 'config.php';

header('Content-Type: application/json');

try {
    $product_id = intval($_GET['product_id'] ?? 0);
    
    if (!$product_id) throw new Exception("Product ID required");
    
    // Get product
    $productSql = "SELECT * FROM products WHERE id = $product_id";
    $productResult = $conn->query($productSql);
    
    if (!$productResult) throw new Exception("Database error: " . $conn->error);
    
    $product = $productResult->fetch_assoc();
    if (!$product) throw new Exception("Product not found");
    
    // Get description
    $descSql = "SELECT description FROM product_descriptions WHERE product_id = $product_id";
    $descResult = $conn->query($descSql);
    $description = $descResult ? $descResult->fetch_assoc() : null;
    
    // Get seller info
    $sellerSql = "SELECT * FROM seller_information WHERE seller_name = '" . $conn->real_escape_string($product['seller']) . "'";
    $sellerResult = $conn->query($sellerSql);
    $sellerInfo = $sellerResult ? $sellerResult->fetch_assoc() : null;
    
    // Combine data
    $product['description'] = $description['description'] ?? '';
    $product['seller_info'] = $sellerInfo ?: [
        'seller_name' => $product['seller'],
        'contact_phone' => $product['phone'],
        'location' => $product['origin'],
        'rating' => 4.0,
        'review_count' => 0
    ];
    
    echo json_encode([
        'success' => true,
        'data' => $product
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>

