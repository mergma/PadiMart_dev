<?php
/**
 * Manage Products - Handle add, edit, delete operations
 * POST: Add new product
 * PUT: Update product
 * DELETE: Delete product
 */

require_once 'config.php';

header('Content-Type: application/json');

// Configuration
$uploadsDir = '../uploads/';
$defaultImage = 'uploads/default.jpg';

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

/**
 * Generate unique product code
 */
function generateProductCode($conn) {
    $sql = "SELECT product_code FROM products WHERE product_code LIKE 'KD_%' ORDER BY product_code DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $lastCode = $row['product_code'];
        $lastNumber = intval(substr($lastCode, 3));
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    return 'KD_' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}

/**
 * Handle image upload and return file path
 */
function handleImageUpload($fileInput) {
    global $uploadsDir, $defaultImage;

    if (!isset($fileInput) || $fileInput['error'] !== UPLOAD_ERR_OK) {
        return $defaultImage;
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $fileInput['type'];

    if (!in_array($fileType, $allowedTypes)) {
        throw new Exception("Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.");
    }

    // Validate file size (max 5MB)
    if ($fileInput['size'] > 5 * 1024 * 1024) {
        throw new Exception("File size too large. Maximum 5MB allowed.");
    }

    // Generate unique filename
    $extension = pathinfo($fileInput['name'], PATHINFO_EXTENSION);
    $filename = 'product_' . time() . '_' . uniqid() . '.' . $extension;
    $filepath = $uploadsDir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($fileInput['tmp_name'], $filepath)) {
        throw new Exception("Failed to upload image");
    }

    return 'uploads/' . $filename;
}

function addProduct() {
    global $conn;

    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $category = $conn->real_escape_string($_POST['category'] ?? '');
    $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $price = intval($_POST['price'] ?? 0);
    $weight = $conn->real_escape_string($_POST['weight'] ?? '');
    $seller = $conn->real_escape_string($_POST['seller'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $origin = $conn->real_escape_string($_POST['origin'] ?? '');
    $condition = $conn->real_escape_string($_POST['condition'] ?? 'Baru');
    $popular = isset($_POST['popular']) ? 1 : 0;
    $stock = intval($_POST['stock'] ?? 0);

    // Generate product code
    $productCode = generateProductCode($conn);

    // Handle image upload
    $imagePath = handleImageUpload($_FILES['image'] ?? null);

    // If category_id is provided, get category name from it
    if ($category_id) {
        $catSql = "SELECT name FROM categories WHERE id = $category_id";
        $catResult = $conn->query($catSql);
        if ($catResult && $catRow = $catResult->fetch_assoc()) {
            $category = $catRow['name'];
        }
    }

    $categoryIdSql = $category_id ? $category_id : 'NULL';

    $sql = "INSERT INTO products (product_code, title, category, category_id, price, weight, seller, phone, origin, `condition`, image, popular, stock)
            VALUES ('$productCode', '$title', '$category', $categoryIdSql, $price, '$weight', '$seller', '$phone', '$origin', '$condition', '$imagePath', $popular, $stock)";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Product added successfully', 'id' => $conn->insert_id, 'product_code' => $productCode]);
    } else {
        throw new Exception("Error adding product: " . $conn->error);
    }
}

function updateProduct() {
    global $conn;

    // For PUT requests with FormData, PHP doesn't populate $_POST
    // We need to parse multipart/form-data manually
    $data = [];

    // Try to get data from $_POST first (if available)
    if (!empty($_POST)) {
        $data = $_POST;
    } else {
        // Parse multipart form data manually for PUT requests
        $boundary = '';
        if (isset($_SERVER['CONTENT_TYPE'])) {
            preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
            $boundary = $matches[1] ?? '';
        }

        if ($boundary) {
            $input = file_get_contents('php://input');
            $parts = explode('--' . $boundary, $input);

            foreach ($parts as $part) {
                if (empty($part) || $part === '--' || $part === "--\r\n") continue;

                // Split headers from content
                $split = explode("\r\n\r\n", $part, 2);
                if (count($split) !== 2) continue;

                $headers = $split[0];
                $content = rtrim($split[1], "\r\n");

                // Extract field name
                if (preg_match('/name="([^"]+)"/', $headers, $matches)) {
                    $fieldName = $matches[1];
                    $data[$fieldName] = $content;
                }
            }
        }
    }

    $id = intval($data['id'] ?? 0);
    if (!$id) throw new Exception("Product ID required");

    $updates = [];
    $fields = ['title', 'category', 'price', 'weight', 'seller', 'phone', 'origin', 'condition', 'popular', 'stock', 'category_id'];

    foreach ($fields as $field) {
        if (isset($data[$field])) {
            $fieldName = ($field === 'condition') ? '`condition`' : $field;
            if ($field === 'price' || $field === 'stock') {
                $updates[] = "$fieldName = " . intval($data[$field]);
            } elseif ($field === 'popular') {
                $updates[] = "$fieldName = " . (isset($data[$field]) ? 1 : 0);
            } elseif ($field === 'category_id') {
                $catId = !empty($data[$field]) ? intval($data[$field]) : 'NULL';
                $updates[] = "$fieldName = $catId";

                // Update category name if category_id is provided
                if ($catId !== 'NULL') {
                    $catSql = "SELECT name FROM categories WHERE id = $catId";
                    $catResult = $conn->query($catSql);
                    if ($catResult && $catRow = $catResult->fetch_assoc()) {
                        $updates[] = "category = '" . $conn->real_escape_string($catRow['name']) . "'";
                    }
                }
            } else {
                $updates[] = "$fieldName = '" . $conn->real_escape_string($data[$field]) . "'";
            }
        }
    }

    // Handle image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Get old image path to delete it
        $oldImageSql = "SELECT image FROM products WHERE id = $id";
        $oldImageResult = $conn->query($oldImageSql);
        if ($oldImageResult && $oldImageRow = $oldImageResult->fetch_assoc()) {
            $oldImage = $oldImageRow['image'];
            // Delete old image file if it's not the default
            if ($oldImage && $oldImage !== 'uploads/default.jpg' && file_exists('../' . $oldImage)) {
                unlink('../' . $oldImage);
            }
        }

        // Upload new image
        $imagePath = handleImageUpload($_FILES['image']);
        $updates[] = "image = '$imagePath'";
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

    // Get image path to delete the file
    $imageSql = "SELECT image FROM products WHERE id = $id";
    $imageResult = $conn->query($imageSql);
    if ($imageResult && $imageRow = $imageResult->fetch_assoc()) {
        $imagePath = $imageRow['image'];
        // Delete image file if it's not the default
        if ($imagePath && $imagePath !== 'uploads/default.jpg' && file_exists('../' . $imagePath)) {
            unlink('../' . $imagePath);
        }
    }

    $sql = "DELETE FROM products WHERE id = $id";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
    } else {
        throw new Exception("Error deleting product: " . $conn->error);
    }
}

$conn->close();
?>

