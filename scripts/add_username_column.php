<?php
require_once __DIR__ . '/../config/Database.php';

try {
    $database = new Database();
    $db = $database->getPdo();

    // Check if column exists
    $stmt = $db->prepare("SHOW COLUMNS FROM usuarios LIKE 'username'");
    $stmt->execute();
    $result = $stmt->fetch();

    if (!$result) {
        echo "Adding username column...\n";
        $sql = "ALTER TABLE usuarios ADD COLUMN username VARCHAR(50) NOT NULL UNIQUE AFTER apellido";
        $db->exec($sql);
        echo "Column 'username' added successfully.\n";
    } else {
        echo "Column 'username' already exists.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
