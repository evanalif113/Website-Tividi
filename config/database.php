<?php
// Database configuration for Bus Tividi Pariwisata (Hosting Version)
class Database
{
    private $host = 'localhost';
    private $db_name = 'tividitr1_tividi';     // ganti sesuai nama database di cPanel
    private $username = 'tividitr1_admin';      // ganti sesuai nama user MySQL di cPanel
    private $password = '4H6qE8RdM8SghIOB';         // ganti sesuai password MySQL yang kamu buat
    private $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Create global $pdo variable for non-admin files
try {
    $database = new Database();
    $pdo = $database->getConnection();
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
