<?php
/**
 * Manage Product Descriptions
 * GET: Get description for a product
 * POST: Add/Update description for a product
 */

require_once 'config.php';

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            getDescription();
            break;
        case 'POST':
            saveDescription();
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function getDescription() {
    global $conn;
    
    $product_id = intval($_GET['product_id'] ?? 0);
    if (!$product_id) throw new Exception("Product ID required");
    
    $sql = "SELECT * FROM product_descriptions WHERE product_id = $product_id";
    $result = $conn->query($sql);
    
    if (!$result) throw new Exception("Database error: " . $conn->error);
    
    $description = $result->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'data' => $description ?: ['product_id' => $product_id, 'description' => '']
    ]);
}

function saveDescription() {
    global $conn;
    
    $product_id = intval($_POST['product_id'] ?? 0);
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    
    if (!$product_id) throw new Exception("Product ID required");
    
    // Check if description exists
    $checkSql = "SELECT id FROM product_descriptions WHERE product_id = $product_id";
    $result = $conn->query($checkSql);
    
    if (!$result) throw new Exception("Database error: " . $conn->error);
    
    if ($result->num_rows > 0) {
        // Update existing
        $sql = "UPDATE product_descriptions SET description = '$description' WHERE product_id = $product_id";
    } else {
        // Insert new
        $sql = "INSERT INTO product_descriptions (product_id, description) VALUES ($product_id, '$description')";
    }
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Description saved successfully']);
    } else {
        throw new Exception("Error saving description: " . $conn->error);
    }
}

$conn->close();
?>

