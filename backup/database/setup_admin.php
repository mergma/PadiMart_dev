<?php
/**
 * Setup Admin Authentication System
 * Run this file once to create the admins table and default admin account
 */

require_once '../api/config.php';

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Setup Admin - PADI MART</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 2rem; }
        .container { max-width: 800px; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        pre { background: #f8f9fa; padding: 1rem; border-radius: 4px; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔐 Setup Admin Authentication System</h1>
        <hr>
";

$errors = [];
$success = [];

// Step 1: Create admins table
echo "<h3>Step 1: Creating admins table...</h3>";
$sql = "CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($sql) === TRUE) {
    echo "<p class='success'>✅ Admins table created successfully!</p>";
    $success[] = "Admins table created";
} else {
    if (strpos($conn->error, 'already exists') !== false) {
        echo "<p class='success'>✅ Admins table already exists!</p>";
        $success[] = "Admins table exists";
    } else {
        echo "<p class='error'>❌ Error creating table: " . $conn->error . "</p>";
        $errors[] = "Failed to create admins table";
    }
}

// Step 2: Check if default admin exists
echo "<h3>Step 2: Creating default admin account...</h3>";
$checkAdmin = $conn->query("SELECT id FROM admins WHERE username = 'admin'");

if ($checkAdmin && $checkAdmin->num_rows > 0) {
    echo "<p class='success'>✅ Default admin account already exists!</p>";
    $success[] = "Admin account exists";
} else {
    // Create default admin account
    // Password: admin123 (hashed with bcrypt)
    $defaultPassword = password_hash('admin123', PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("INSERT INTO admins (username, password, email, full_name) VALUES (?, ?, ?, ?)");
    $username = 'admin';
    $email = 'admin@padimart.com';
    $fullName = 'Administrator';
    
    $stmt->bind_param("ssss", $username, $defaultPassword, $email, $fullName);
    
    if ($stmt->execute()) {
        echo "<p class='success'>✅ Default admin account created successfully!</p>";
        $success[] = "Admin account created";
    } else {
        echo "<p class='error'>❌ Error creating admin account: " . $stmt->error . "</p>";
        $errors[] = "Failed to create admin account";
    }
    $stmt->close();
}

// Summary
echo "<hr><h3>📋 Setup Summary</h3>";

if (count($errors) === 0) {
    echo "<div class='alert alert-success'>";
    echo "<h4>✅ Setup completed successfully!</h4>";
    echo "<p><strong>Default Login Credentials:</strong></p>";
    echo "<pre>";
    echo "Username: admin\n";
    echo "Password: admin123\n";
    echo "</pre>";
    echo "<p class='text-danger'><strong>⚠️ IMPORTANT: Please change the default password immediately after first login!</strong></p>";
    echo "<p><a href='../login.php' class='btn btn-primary'>Go to Login Page</a></p>";
    echo "</div>";
} else {
    echo "<div class='alert alert-danger'>";
    echo "<h4>❌ Setup encountered errors:</h4>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
    echo "</div>";
}

if (count($success) > 0) {
    echo "<div class='alert alert-info'>";
    echo "<h5>Successful steps:</h5>";
    echo "<ul>";
    foreach ($success as $item) {
        echo "<li>$item</li>";
    }
    echo "</ul>";
    echo "</div>";
}

echo "<hr>";
echo "<p><small>You can safely delete this file (database/setup_admin.php) after setup is complete.</small></p>";

echo "
    </div>
</body>
</html>";

$conn->close();
?>

