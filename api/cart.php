<?php
session_start();
header('Content-Type: application/json');
require_once '../models/Cart.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized: Please log in']);
    exit;
}

$cart = new Cart();
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        if (isset($data['user_id'], $data['product_id'], $data['quantity']) && $data['user_id'] == $_SESSION['user_id']) {
            if ($cart->add($data['user_id'], $data['product_id'], $data['quantity'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add to cart: Insufficient stock or invalid product']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing user_id, product_id, quantity, or unauthorized user']);
        }
        break;

    case 'PUT':
        if (isset($data['user_id'], $data['product_id'], $data['quantity'])) {
            if ($cart->update($data['user_id'], $data['product_id'], $data['quantity'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update cart: Insufficient stock or invalid product']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing user_id, product_id, or quantity']);
        }
        break;

    case 'DELETE':
        if (isset($data['user_id'], $data['product_id'])) {
            if ($cart->remove($data['user_id'], $data['product_id'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to remove from cart']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing user_id or product_id']);
        }
        break;

    case 'GET':
        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $items = $cart->getByUser($_GET['user_id']);
            echo json_encode(['success' => true, 'data' => $items]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid or missing user_id']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>