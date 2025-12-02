<?php
require_once 'config.php';

echo "=== Database Tables ===\n";
$result = $conn->query("SHOW TABLES");
while($row = $result->fetch_row()) {
    echo "- " . $row[0] . "\n";
}

echo "\n=== Product Descriptions Table ===\n";
$result = $conn->query("SELECT COUNT(*) as count FROM product_descriptions");
$row = $result->fetch_assoc();
echo "Records: " . $row['count'] . "\n";

echo "\n=== Seller Information Table ===\n";
$result = $conn->query("SELECT COUNT(*) as count FROM seller_information");
$row = $result->fetch_assoc();
echo "Records: " . $row['count'] . "\n";

echo "\n=== Sample Sellers ===\n";
$result = $conn->query("SELECT seller_name, contact_email, rating FROM seller_information LIMIT 3");
while($row = $result->fetch_assoc()) {
    echo "- " . $row['seller_name'] . " (" . $row['contact_email'] . ") - Rating: " . $row['rating'] . "\n";
}

$conn->close();
?>

