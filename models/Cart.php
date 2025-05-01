<?php
require_once __DIR__ . '/../config/database.php';

class Cart {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function add($user_id, $product_id, $quantity) {
        // Check stock and product existence
        $query = 'SELECT stock_quantity FROM products WHERE id = :product_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $product = $stmt->fetch();

        if (!$product || $product['stock_quantity'] < $quantity) {
            return false; // Not enough stock or product not found
        }

        $query = 'INSERT INTO carts (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity) ON DUPLICATE KEY UPDATE quantity = quantity + :quantity';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        return $stmt->execute();
    }

    public function update($user_id, $product_id, $quantity) {
        // Check stock
        $query = 'SELECT stock_quantity FROM products WHERE id = :product_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $product = $stmt->fetch();

        if (!$product || $product['stock_quantity'] < $quantity) {
            return false; // Not enough stock
        }

        $query = 'UPDATE carts SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        return $stmt->execute();
    }

    public function remove($user_id, $product_id) {
        $query = 'DELETE FROM carts WHERE user_id = :user_id AND product_id = :product_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        return $stmt->execute();
    }

    public function getByUser($user_id) {
        $query = 'SELECT p.*, c.quantity FROM carts c JOIN products p ON c.product_id = p.id WHERE c.user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clearByUserId($user_id) {
        $query = 'DELETE FROM carts WHERE user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}
?>