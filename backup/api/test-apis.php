<?php
require_once 'config.php';

echo "=== Testing API Endpoints ===\n\n";

// Test 1: Get products
echo "1. Testing GET /get-products.php\n";
$result = $conn->query("SELECT COUNT(*) as count FROM products");
$row = $result->fetch_assoc();
echo "   Products in database: " . $row['count'] . "\n";

// Test 2: Get product descriptions
echo "\n2. Testing GET /manage-descriptions.php\n";
$result = $conn->query("SELECT COUNT(*) as count FROM product_descriptions");
$row = $result->fetch_assoc();
echo "   Product descriptions: " . $row['count'] . "\n";

// Test 3: Get seller information
echo "\n3. Testing GET /manage-sellers.php\n";
$result = $conn->query("SELECT COUNT(*) as count FROM seller_information");
$row = $result->fetch_assoc();
echo "   Sellers in database: " . $row['count'] . "\n";

// Test 4: Get a sample product with details
echo "\n4. Testing GET /get-product-details.php\n";
$result = $conn->query("SELECT id FROM products LIMIT 1");
if ($row = $result->fetch_assoc()) {
    $productId = $row['id'];
    echo "   Sample product ID: " . $productId . "\n";
    
    // Get product details
    $detailResult = $conn->query("SELECT * FROM products WHERE id = $productId");
    $product = $detailResult->fetch_assoc();
    echo "   Product: " . $product['title'] . "\n";
    echo "   Seller: " . $product['seller'] . "\n";
    
    // Get description
    $descResult = $conn->query("SELECT description FROM product_descriptions WHERE product_id = $productId");
    $desc = $descResult->fetch_assoc();
    echo "   Has description: " . ($desc ? 'Yes' : 'No') . "\n";
    
    // Get seller info
    $sellerResult = $conn->query("SELECT * FROM seller_information WHERE seller_name = '" . $conn->real_escape_string($product['seller']) . "'");
    $seller = $sellerResult->fetch_assoc();
    echo "   Has seller info: " . ($seller ? 'Yes' : 'No') . "\n";
    if ($seller) {
        echo "   Seller rating: " . $seller['rating'] . "\n";
    }
}

echo "\n=== All Tests Completed ===\n";
$conn->close();
?>

