<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

$db = (new Database())->connect();
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $query = 'SELECT * FROM products WHERE id = :id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        echo json_encode(['success' => true, 'data' => $product]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing product id']);
}
?>