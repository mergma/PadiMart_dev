<?php
/**
 * Migration Runner for PADI MART Database
 * This script runs all necessary migrations to upgrade the database schema
 */

require_once '../api/config.php';

echo "=== PADI MART Database Migration ===\n\n";

// Step 1: Run SQL migration
echo "Step 1: Running SQL migration (add categories table and update products schema)...\n";

$sqlFile = __DIR__ . '/migration_add_categories.sql';
if (!file_exists($sqlFile)) {
    die("Error: migration_add_categories.sql not found!\n");
}

$sql = file_get_contents($sqlFile);
$statements = array_filter(array_map('trim', explode(';', $sql)));

$successCount = 0;
$errorCount = 0;

foreach ($statements as $statement) {
    if (empty($statement)) continue;
    
    if ($conn->query($statement)) {
        $successCount++;
        echo "✓ Executed: " . substr($statement, 0, 50) . "...\n";
    } else {
        $errorCount++;
        echo "✗ Error: " . $conn->error . "\n";
        echo "  Statement: " . substr($statement, 0, 100) . "...\n";
    }
}

echo "\nSQL Migration Results: $successCount successful, $errorCount errors\n\n";

if ($errorCount > 0) {
    echo "Warning: Some SQL statements failed. Please review the errors above.\n";
    echo "You may need to manually fix these issues before proceeding.\n\n";
}

// Step 2: Run image migration
echo "Step 2: Converting base64 images to files...\n";

$imageMigrationFile = __DIR__ . '/migration_images_to_files.php';
if (!file_exists($imageMigrationFile)) {
    die("Error: migration_images_to_files.php not found!\n");
}

// Check if uploads directory exists
if (!is_dir('../uploads')) {
    mkdir('../uploads', 0755, true);
    echo "✓ Created uploads directory\n";
}

// Check if default image exists
if (!file_exists('../uploads/default.jpg')) {
    if (file_exists('../img/PADI MART.png')) {
        copy('../img/PADI MART.png', '../uploads/default.jpg');
        echo "✓ Copied default image\n";
    } else {
        echo "⚠ Warning: Default image not found. Please add uploads/default.jpg manually.\n";
    }
}

// Run image migration
include $imageMigrationFile;

echo "\n=== Migration Complete ===\n";
echo "Please verify the following:\n";
echo "1. Check that the categories table was created\n";
echo "2. Check that products table has new columns (product_code, category_id, stock)\n";
echo "3. Check that images were converted to files in uploads/ directory\n";
echo "4. Test the admin panel at admin.php\n";
echo "\nBackup files created:\n";
echo "- admin_old_backup.php (old admin interface)\n";
echo "\nYou can delete backup files once you've verified everything works.\n";
?>

