<?php
/**
 * Get Products - Returns products as JSON for frontend
 * Usage: /api/get-products.php?category=beras&search=premium&sort=popular
 */

require_once 'config.php';

header('Content-Type: application/json');

try {
    // Get query parameters
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'popular';

    // Build SQL query with category join
    $sql = "SELECT p.*, c.name as category_name, c.description as category_description
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE 1=1";

    // Add category filter
    if ($category && $category !== 'all') {
        $category = $conn->real_escape_string($category);
        $sql .= " AND (p.category = '$category' OR c.name = '$category')";
    }

    // Add search filter
    if ($search) {
        $search = $conn->real_escape_string($search);
        $sql .= " AND (p.title LIKE '%$search%' OR p.category LIKE '%$search%' OR p.seller LIKE '%$search%' OR p.product_code LIKE '%$search%')";
    }

    // Add sorting
    switch ($sort) {
        case 'new':
            $sql .= " ORDER BY p.created_at DESC";
            break;
        case 'price_asc':
            $sql .= " ORDER BY p.price ASC";
            break;
        case 'price_desc':
            $sql .= " ORDER BY p.price DESC";
            break;
        case 'popular':
        default:
            $sql .= " ORDER BY p.popular DESC, p.created_at DESC";
            break;
    }

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Database error: " . $conn->error);
    }

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode([
        'success' => true,
        'count' => count($products),
        'data' => $products
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

