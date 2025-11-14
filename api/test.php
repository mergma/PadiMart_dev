<?php
/**
 * Database Connection Test
 * Visit: http://localhost/padi/api/test.php
 */

require_once 'config.php';

echo "<h1>PADI Mart Database Test</h1>";
echo "<hr>";

// Test 1: Database Connection
echo "<h2>1. Database Connection</h2>";
if ($conn->connect_error) {
    echo "<p style='color: red;'>❌ Connection Failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✅ Connected to database: " . DB_NAME . "</p>";
}

// Test 2: Check Tables
echo "<h2>2. Database Tables</h2>";
$tables = ['products', 'users', 'orders'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✅ Table '$table' exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Table '$table' not found</p>";
    }
}

// Test 3: Count Products
echo "<h2>3. Products Data</h2>";
$result = $conn->query("SELECT COUNT(*) as count FROM products");
if ($result) {
    $row = $result->fetch_assoc();
    $count = $row['count'];
    echo "<p style='color: green;'>✅ Found " . $count . " products in database</p>";
    
    if ($count > 0) {
        echo "<h3>Sample Products:</h3>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Title</th><th>Category</th><th>Price</th><th>Seller</th></tr>";
        
        $result = $conn->query("SELECT id, title, category, price, seller FROM products LIMIT 5");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>";
            echo "<td>" . $row['seller'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p style='color: red;'>❌ Error querying products: " . $conn->error . "</p>";
}

// Test 4: API Endpoint Test
echo "<h2>4. API Endpoint Test</h2>";
echo "<p>Test the API endpoints:</p>";
echo "<ul>";
echo "<li><a href='products.php?action=all' target='_blank'>Get All Products</a></li>";
echo "<li><a href='products.php?action=single&id=1' target='_blank'>Get Product #1</a></li>";
echo "</ul>";

// Test 5: Configuration
echo "<h2>5. Configuration</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>DB_HOST</td><td>" . DB_HOST . "</td></tr>";
echo "<tr><td>DB_USER</td><td>" . DB_USER . "</td></tr>";
echo "<tr><td>DB_NAME</td><td>" . DB_NAME . "</td></tr>";
echo "<tr><td>PHP Version</td><td>" . phpversion() . "</td></tr>";
echo "</table>";

echo "<hr>";
echo "<p><strong>Setup Status:</strong> ";
if ($conn->connect_error) {
    echo "<span style='color: red;'>❌ FAILED - Check database connection</span>";
} else {
    $result = $conn->query("SELECT COUNT(*) as count FROM products");
    if ($result && $result->fetch_assoc()['count'] > 0) {
        echo "<span style='color: green;'>✅ READY - Database is configured and has data</span>";
    } else {
        echo "<span style='color: orange;'>⚠️ WARNING - Database connected but no products found. Import padi_mart.sql</span>";
    }
}
echo "</p>";

$conn->close();
?>

