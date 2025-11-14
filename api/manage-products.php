<?php
/**
 * Manage Products - Handle add, edit, delete operations
 * POST: Add new product
 * PUT: Update product
 * DELETE: Delete product
 */

require_once 'config.php';

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'POST':
            addProduct();
            break;
        case 'PUT':
            updateProduct();
            break;
        case 'DELETE':
            deleteProduct();
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

function addProduct() {
    global $conn;
    
    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $category = $conn->real_escape_string($_POST['category'] ?? '');
    $price = intval($_POST['price'] ?? 0);
    $weight = $conn->real_escape_string($_POST['weight'] ?? '');
    $seller = $conn->real_escape_string($_POST['seller'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $origin = $conn->real_escape_string($_POST['origin'] ?? '');
    $condition = $conn->real_escape_string($_POST['condition'] ?? 'Baru');
    $popular = isset($_POST['popular']) ? 1 : 0;
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
    }
    
    $image = $conn->real_escape_string($image);
    
    $sql = "INSERT INTO products (title, category, price, weight, seller, phone, origin, `condition`, image, popular) 
            VALUES ('$title', '$category', $price, '$weight', '$seller', '$phone', '$origin', '$condition', '$image', $popular)";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Product added successfully', 'id' => $conn->insert_id]);
    } else {
        throw new Exception("Error adding product: " . $conn->error);
    }
}

function updateProduct() {
    global $conn;
    
    parse_str(file_get_contents("php://input"), $data);
    
    $id = intval($data['id'] ?? 0);
    if (!$id) throw new Exception("Product ID required");
    
    $updates = [];
    $fields = ['title', 'category', 'price', 'weight', 'seller', 'phone', 'origin', 'condition', 'popular'];
    
    foreach ($fields as $field) {
        if (isset($data[$field])) {
            $fieldName = ($field === 'condition') ? '`condition`' : $field;
            if ($field === 'price') {
                $updates[] = "$fieldName = " . intval($data[$field]);
            } elseif ($field === 'popular') {
                $updates[] = "$fieldName = " . (isset($data[$field]) ? 1 : 0);
            } else {
                $updates[] = "$fieldName = '" . $conn->real_escape_string($data[$field]) . "'";
            }
        }
    }
    
    if (empty($updates)) throw new Exception("No fields to update");
    
    $sql = "UPDATE products SET " . implode(", ", $updates) . " WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    } else {
        throw new Exception("Error updating product: " . $conn->error);
    }
}

function deleteProduct() {
    global $conn;
    
    parse_str(file_get_contents("php://input"), $data);
    
    $id = intval($data['id'] ?? 0);
    if (!$id) throw new Exception("Product ID required");
    
    $sql = "DELETE FROM products WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
    } else {
        throw new Exception("Error deleting product: " . $conn->error);
    }
}

$conn->close();
?>

