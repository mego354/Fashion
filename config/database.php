<?php
class Database {
    private $host = 'localhost';     // EDIT e.g. 'localhost'
    private $dbname = 'ecommerce';   // EDIT e.g. 'ecommerce'
    private $username = 'root';      // EDIT e.g. 'root'
    private $password = ''; // EDIT e.g. ''
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;
    }
}
?>