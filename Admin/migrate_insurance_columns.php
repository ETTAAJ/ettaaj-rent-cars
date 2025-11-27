<?php
/**
 * Migration script to add insurance columns to cars table
 * Run this once via browser: http://localhost/ettaaj-rent-cars/Admin/migrate_insurance_columns.php
 * Or via command line: php migrate_insurance_columns.php
 */

require_once 'config.php';

// Check if admin is logged in
if (empty($_SESSION['admin_logged_in'])) {
    die("Access denied. Please log in first.");
}

$columns = [
    'insurance_basic_price' => "DECIMAL(10,2) DEFAULT NULL",
    'insurance_smart_price' => "DECIMAL(10,2) DEFAULT NULL",
    'insurance_premium_price' => "DECIMAL(10,2) DEFAULT NULL",
    'insurance_basic_deposit' => "DECIMAL(10,2) DEFAULT NULL",
    'insurance_smart_deposit' => "DECIMAL(10,2) DEFAULT NULL",
    'insurance_premium_deposit' => "DECIMAL(10,2) DEFAULT NULL"
];

$added = [];
$skipped = [];
$errors = [];

try {
    // Get existing columns
    $stmt = $pdo->query("SHOW COLUMNS FROM cars");
    $existingColumns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existingColumns[] = $row['Field'];
    }

    // Add columns that don't exist
    $position = 'discount';
    foreach ($columns as $columnName => $definition) {
        if (in_array($columnName, $existingColumns)) {
            $skipped[] = $columnName;
        } else {
            try {
                $sql = "ALTER TABLE `cars` ADD COLUMN `$columnName` $definition AFTER `$position`";
                $pdo->exec($sql);
                $added[] = $columnName;
                $position = $columnName; // Next column goes after this one
            } catch (PDOException $e) {
                $errors[] = "$columnName: " . $e->getMessage();
            }
        }
    }

    // Output results
    echo "<!DOCTYPE html><html><head><title>Migration Results</title>";
    echo "<style>body{font-family:Arial;padding:20px;background:#36454F;color:#fff;}";
    echo ".success{color:#10b981;}.error{color:#ef4444;}.info{color:#FFD700;}</style></head><body>";
    echo "<h1>Insurance Columns Migration</h1>";
    
    if (!empty($added)) {
        echo "<p class='success'>✓ Added columns: " . implode(', ', $added) . "</p>";
    }
    
    if (!empty($skipped)) {
        echo "<p class='info'>⊘ Skipped (already exist): " . implode(', ', $skipped) . "</p>";
    }
    
    if (!empty($errors)) {
        echo "<p class='error'>✗ Errors:</p><ul>";
        foreach ($errors as $error) {
            echo "<li class='error'>$error</li>";
        }
        echo "</ul>";
    }
    
    if (empty($added) && empty($errors)) {
        echo "<p class='info'>All columns already exist. No changes needed.</p>";
    }
    
    echo "<p><a href='index.php' style='color:#FFD700;'>← Back to Admin Panel</a></p>";
    echo "</body></html>";

} catch (Exception $e) {
    die("Migration failed: " . $e->getMessage());
}
?>

