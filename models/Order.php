<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function create($user_id, $address_id, $payment_method, $credit_card_id, $total_amount) {
        $query = 'INSERT INTO orders (user_id, address_id, payment_method, credit_card_id, total_amount, status) 
                  VALUES (:user_id, :address_id, :payment_method, :credit_card_id, :total_amount, "pending")';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindValue(':address_id', $address_id, $address_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindValue(':credit_card_id', $credit_card_id, $credit_card_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindParam(':total_amount', $total_amount);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function addOrderItem($order_id, $product_id, $quantity, $unit_price) {
        // Update product stock
        $query = 'UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE id = :product_id AND stock_quantity >= :quantity';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':product_id', $product_id);
        if (!$stmt->execute() || $stmt->rowCount() == 0) {
            throw new Exception('Insufficient stock or product not found');
        }

        // Insert order item
        $query = 'INSERT INTO order_items (order_id, product_id, quantity, unit_price) 
                  VALUES (:order_id, :product_id, :quantity, :unit_price)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':unit_price', $unit_price);
        return $stmt->execute();
    }

    public function getAllByUser($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getItemsByOrderId($order_id) {
        $stmt = $this->db->prepare("
            SELECT oi.product_id, oi.quantity, oi.unit_price, p.name, p.image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>