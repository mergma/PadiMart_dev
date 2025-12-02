<?php
/**
 * Run database migration to add descriptions and seller information tables
 */

require_once 'config.php';

try {
    // Read the migration file
    $migrationFile = __DIR__ . '/../database/add_descriptions_table.sql';
    $sql = file_get_contents($migrationFile);
    
    if (!$sql) {
        throw new Exception("Could not read migration file");
    }
    
    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            if (!$conn->query($statement)) {
                throw new Exception("Error executing statement: " . $conn->error);
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Migration completed successfully'
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

