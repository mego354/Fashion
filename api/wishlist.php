<?php
session_start();
header('Content-Type: application/json');
require_once '../models/Wishlist.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized: Please log in']);
    exit;
}

$wishlist = new Wishlist();
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        if (isset($data['user_id'], $data['product_id'])) {
            if ($wishlist->add($data['user_id'], $data['product_id'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add to wishlist']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing user_id or product_id']);
        }
        break;

    case 'DELETE':
        if (isset($data['user_id'], $data['product_id'])) {
            if ($wishlist->remove($data['user_id'], $data['product_id'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to remove from wishlist']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing user_id or product_id']);
        }
        break;

    case 'GET':
        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $items = $wishlist->getByUser($_GET['user_id']);
            echo json_encode(['success' => true, 'data' => $items]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing or invalid user_id']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>