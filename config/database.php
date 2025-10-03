<?php
// Database configuration for Bus Tividi Pariwisata
class Database {
    private $host = 'localhost';
    private $db_name = 'bustividipariwisata';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Create global $pdo variable for non-admin files
try {
    $database = new Database();
    $pdo = $database->getConnection();
} catch(Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>