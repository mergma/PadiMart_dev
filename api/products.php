<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($method) {
    case 'GET':
        if ($action === 'all') {
            getAllProducts();
        } elseif ($action === 'single' && isset($_GET['id'])) {
            getProductById($_GET['id']);
        } else {
            getAllProducts();
        }
        break;
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

function getAllProducts() {
    global $conn;
    $sql = "SELECT * FROM products ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    if ($result) {
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $products]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error fetching products']);
    }
}

function getProductById($id) {
    global $conn;
    $id = intval($id);
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        echo json_encode(['success' => true, 'data' => $result->fetch_assoc()]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
}

function addProduct() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    
    $title = $conn->real_escape_string($data['title'] ?? '');
    $category = $conn->real_escape_string($data['category'] ?? '');
    $price = intval($data['price'] ?? 0);
    $weight = $conn->real_escape_string($data['weight'] ?? '');
    $seller = $conn->real_escape_string($data['seller'] ?? '');
    $phone = $conn->real_escape_string($data['phone'] ?? '');
    $origin = $conn->real_escape_string($data['origin'] ?? '');
    $condition = $conn->real_escape_string($data['condition'] ?? 'Baru');
    $image = $conn->real_escape_string($data['image'] ?? '');
    $popular = isset($data['popular']) ? 1 : 0;
    
    $sql = "INSERT INTO products (title, category, price, weight, seller, phone, origin, condition, image, popular) 
            VALUES ('$title', '$category', $price, '$weight', '$seller', '$phone', '$origin', '$condition', '$image', $popular)";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Product added', 'id' => $conn->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error adding product: ' . $conn->error]);
    }
}

function updateProduct() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    $id = intval($data['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }

    $updates = [];
    $fields = ['title', 'category', 'price', 'weight', 'seller', 'phone', 'origin', 'condition', 'image', 'popular'];

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

    if (empty($updates)) {
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        return;
    }

    $sql = "UPDATE products SET " . implode(", ", $updates) . " WHERE id = $id";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Product updated']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error updating product']);
    }
}

function deleteProduct() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = intval($data['id'] ?? 0);
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Product ID required']);
        return;
    }
    
    $sql = "DELETE FROM products WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Product deleted']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error deleting product']);
    }
}
?>

