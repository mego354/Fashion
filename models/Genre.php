<?php
require_once __DIR__ . '/../config/database.php';

class Genre {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function create($name) {
        $query = 'INSERT INTO genres (name) VALUES (:name)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        return $stmt->execute();
    }

    public function readAll() {
        $query = 'SELECT * FROM genres';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function read($id) {
        $query = 'SELECT * FROM genres WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update($id, $name) {
        $query = 'UPDATE genres SET name = :name WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = 'DELETE FROM genres WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>