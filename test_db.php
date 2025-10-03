<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    echo "Koneksi database berhasil!<br>";
    
    // Test query sederhana
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tabel yang ada:<br>";
    foreach ($tables as $table) {
        echo "- " . $table . "<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    echo "test";
}
?>