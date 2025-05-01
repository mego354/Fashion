<?php
require_once __DIR__ . '/../config/database.php';

class Wishlist {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function add($user_id, $product_id) {
        $query = 'INSERT INTO wishlists (user_id, product_id) VALUES (:user_id, :product_id)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        return $stmt->execute();
    }

    public function remove($user_id, $product_id) {
        $query = 'DELETE FROM wishlists WHERE user_id = :user_id AND product_id = :product_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        return $stmt->execute();
    }

    public function getByUser($user_id) {
        $query = 'SELECT w.product_id, p.name, p.price, p.image 
                  FROM wishlists w 
                  JOIN products p ON w.product_id = p.id 
                  WHERE w.user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>