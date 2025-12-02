<?php
/**
 * Manage Seller Information
 * GET: Get seller info or all sellers
 * POST: Add/Update seller information
 */

require_once 'config.php';

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            getSellerInfo();
            break;
        case 'POST':
            saveSellerInfo();
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

function getSellerInfo() {
    global $conn;
    
    $seller_name = isset($_GET['seller_name']) ? $conn->real_escape_string($_GET['seller_name']) : '';
    
    if ($seller_name) {
        // Get specific seller
        $sql = "SELECT * FROM seller_information WHERE seller_name = '$seller_name'";
        $result = $conn->query($sql);
        
        if (!$result) throw new Exception("Database error: " . $conn->error);
        
        $seller = $result->fetch_assoc();
        
        echo json_encode([
            'success' => true,
            'data' => $seller ?: null
        ]);
    } else {
        // Get all sellers
        $sql = "SELECT * FROM seller_information ORDER BY seller_name ASC";
        $result = $conn->query($sql);
        
        if (!$result) throw new Exception("Database error: " . $conn->error);
        
        $sellers = [];
        while ($row = $result->fetch_assoc()) {
            $sellers[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'count' => count($sellers),
            'data' => $sellers
        ]);
    }
}

function saveSellerInfo() {
    global $conn;
    
    $seller_name = $conn->real_escape_string($_POST['seller_name'] ?? '');
    $contact_email = $conn->real_escape_string($_POST['contact_email'] ?? '');
    $contact_phone = $conn->real_escape_string($_POST['contact_phone'] ?? '');
    $location = $conn->real_escape_string($_POST['location'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $rating = floatval($_POST['rating'] ?? 4.0);
    $review_count = intval($_POST['review_count'] ?? 0);
    
    if (!$seller_name) throw new Exception("Seller name required");
    
    // Check if seller exists
    $checkSql = "SELECT id FROM seller_information WHERE seller_name = '$seller_name'";
    $result = $conn->query($checkSql);
    
    if (!$result) throw new Exception("Database error: " . $conn->error);
    
    if ($result->num_rows > 0) {
        // Update existing
        $sql = "UPDATE seller_information SET 
                contact_email = '$contact_email',
                contact_phone = '$contact_phone',
                location = '$location',
                description = '$description',
                rating = $rating,
                review_count = $review_count
                WHERE seller_name = '$seller_name'";
    } else {
        // Insert new
        $sql = "INSERT INTO seller_information (seller_name, contact_email, contact_phone, location, description, rating, review_count) 
                VALUES ('$seller_name', '$contact_email', '$contact_phone', '$location', '$description', $rating, $review_count)";
    }
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Seller information saved successfully']);
    } else {
        throw new Exception("Error saving seller info: " . $conn->error);
    }
}

$conn->close();
?>

