<?php
/**
 * Test Update Product Functionality
 * This script tests if the update product function works correctly
 */

require_once 'config.php';

header('Content-Type: application/json');

try {
    // Get first product to test with
    $sql = "SELECT id, title FROM products LIMIT 1";
    $result = $conn->query($sql);
    
    if (!$result || $result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'No products found to test',
            'status' => 'ERROR'
        ]);
        exit;
    }
    
    $product = $result->fetch_assoc();
    $testId = $product['id'];
    $originalTitle = $product['title'];
    
    // Test 1: Check if database connection works
    $testResult = [
        'database_connection' => 'OK',
        'test_product_id' => $testId,
        'original_title' => $originalTitle
    ];
    
    // Test 2: Try a simple update
    $testTitle = 'TEST_UPDATE_' . time();
    $sql = "UPDATE products SET title = '" . $conn->real_escape_string($testTitle) . "' WHERE id = $testId";
    
    if ($conn->query($sql)) {
        $testResult['update_test'] = 'OK';
        
        // Verify update
        $sql = "SELECT title FROM products WHERE id = $testId";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        if ($row['title'] === $testTitle) {
            $testResult['verification'] = 'OK';
            
            // Restore original title
            $sql = "UPDATE products SET title = '" . $conn->real_escape_string($originalTitle) . "' WHERE id = $testId";
            $conn->query($sql);
            $testResult['restore'] = 'OK';
        } else {
            $testResult['verification'] = 'FAILED - Title not updated';
        }
    } else {
        $testResult['update_test'] = 'FAILED - ' . $conn->error;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Update test completed',
        'status' => 'OK',
        'tests' => $testResult
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'status' => 'ERROR'
    ]);
}

$conn->close();
?>

