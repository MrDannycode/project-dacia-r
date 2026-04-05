<?php
require 'config/database.php';
$sql = file_get_contents('migrations/02_stiri_schema.sql');
try {
    $conn->exec($sql);
    echo "Migration successful!\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
