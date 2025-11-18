<?php
// ============================================
// PADI MART - Database Configuration Template
// ============================================
// 
// INSTRUCTIONS:
// 1. Copy this file and rename it to "config.php"
// 2. Update the database credentials below with your own
// 3. Save the file
//
// NOTE: The real config.php file is ignored by Git for security
// ============================================

// Database Configuration
// Update these values with your database credentials
define('DB_HOST', 'localhost');        // Usually 'localhost' for XAMPP
define('DB_USER', 'root');             // Your MySQL username (default: 'root' for XAMPP)
define('DB_PASS', '');                 // Your MySQL password (default: empty for XAMPP)
define('DB_NAME', 'padi_mart');        // Database name

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS headers (but NOT Content-Type - let each file set its own)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>

