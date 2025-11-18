<?php
/**
 * Manage Categories - Handle category CRUD operations
 * GET: Get all categories
 * POST: Add new category
 * PUT: Update category
 * DELETE: Delete category
 */

require_once 'config.php';

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            getCategories();
            break;
        case 'POST':
            addCategory();
            break;
        case 'PUT':
            updateCategory();
            break;
        case 'DELETE':
            deleteCategory();
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

function getCategories() {
    global $conn;
    
    $sql = "SELECT * FROM categories ORDER BY name ASC";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Error fetching categories: " . $conn->error);
    }
    
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $categories]);
}

function addCategory() {
    global $conn;
    
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    
    if (empty($name)) {
        throw new Exception("Category name is required");
    }
    
    // Check if category already exists
    $checkSql = "SELECT id FROM categories WHERE name = '$name'";
    $result = $conn->query($checkSql);
    
    if ($result->num_rows > 0) {
        throw new Exception("Category name already exists");
    }
    
    $sql = "INSERT INTO categories (name, description) VALUES ('$name', '$description')";
    
    if ($conn->query($sql)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Category added successfully',
            'id' => $conn->insert_id
        ]);
    } else {
        throw new Exception("Error adding category: " . $conn->error);
    }
}

function updateCategory() {
    global $conn;
    
    // Parse PUT data
    parse_str(file_get_contents("php://input"), $data);
    
    $id = intval($data['id'] ?? 0);
    $name = $conn->real_escape_string($data['name'] ?? '');
    $description = $conn->real_escape_string($data['description'] ?? '');
    
    if (!$id) {
        throw new Exception("Category ID is required");
    }
    
    if (empty($name)) {
        throw new Exception("Category name is required");
    }
    
    // Check if category name already exists for different category
    $checkSql = "SELECT id FROM categories WHERE name = '$name' AND id != $id";
    $result = $conn->query($checkSql);
    
    if ($result->num_rows > 0) {
        throw new Exception("Category name already exists");
    }
    
    $sql = "UPDATE categories SET name = '$name', description = '$description' WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
    } else {
        throw new Exception("Error updating category: " . $conn->error);
    }
}

function deleteCategory() {
    global $conn;
    
    parse_str(file_get_contents("php://input"), $data);
    
    $id = intval($data['id'] ?? 0);
    
    if (!$id) {
        throw new Exception("Category ID is required");
    }
    
    // Check if category is used by any products
    $checkSql = "SELECT COUNT(*) as count FROM products WHERE category_id = $id";
    $result = $conn->query($checkSql);
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        throw new Exception("Cannot delete category: It is used by " . $row['count'] . " product(s)");
    }
    
    $sql = "DELETE FROM categories WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
    } else {
        throw new Exception("Error deleting category: " . $conn->error);
    }
}

$conn->close();
?>

