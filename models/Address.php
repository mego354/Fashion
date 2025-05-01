<?php
require_once __DIR__ . '/../config/database.php';

class Address {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function create($user_id, $title, $street, $apartment_number, $city, $country) {
        $query = 'INSERT INTO addresses (user_id, title, street, apartment_number, city, country) VALUES (:user_id, :title, :street, :apartment_number, :city, :country)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':street', $street);
        $stmt->bindParam(':apartment_number', $apartment_number);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':country', $country);
        return $stmt->execute();
    }

    public function readAll($user_id) {
        $query = 'SELECT * FROM addresses WHERE user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function read($id, $user_id) {
        $query = 'SELECT * FROM addresses WHERE id = :id AND user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update($id, $user_id, $title, $street, $apartment_number, $city, $country) {
        $query = 'UPDATE addresses SET title = :title, street = :street, apartment_number = :apartment_number, city = :city, country = :country WHERE id = :id AND user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':street', $street);
        $stmt->bindParam(':apartment_number', $apartment_number);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':country', $country);
        return $stmt->execute();
    }

    public function delete($id, $user_id) {
        $query = 'DELETE FROM addresses WHERE id = :id AND user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}
?>