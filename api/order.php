<?php
session_start();
header('Content-Type: application/json');

require_once '../models/Address.php';
require_once '../models/CreditCard.php';
require_once '../models/Order.php';
require_once '../models/Wishlist.php';
require_once '../models/Cart.php';
require_once '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$user_id = $_SESSION['user_id'];

switch ($action) {
    case 'get_addresses':
        $addressModel = new Address();
        $addresses = $addressModel->readAll($user_id);
        echo json_encode(['success' => true, 'addresses' => $addresses]);
        break;

    case 'add_address':
        $addressModel = new Address();
        $title = $_POST['title'] ?? '';
        $street = $_POST['street'] ?? '';
        $apartment_number = $_POST['apartment_number'] ?? null;
        $city = $_POST['city'] ?? '';
        $country = $_POST['country'] ?? '';
        if (empty($title) || empty($street) || empty($city) || empty($country)) {
            echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
            exit;
        }
        if ($addressModel->create($user_id, $title, $street, $apartment_number, $city, $country)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add address']);
        }
        break;

    case 'update_address':
        $addressModel = new Address();
        $address_id = $_POST['id'] ?? 0;
        $title = $_POST['title'] ?? '';
        $street = $_POST['street'] ?? '';
        $apartment_number = $_POST['apartment_number'] ?? null;
        $city = $_POST['city'] ?? '';
        $country = $_POST['country'] ?? '';
        if (empty($title) || empty($street) || empty($city) || empty($country)) {
            echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
            exit;
        }
        if ($addressModel->update($address_id, $user_id, $title, $street, $apartment_number, $city, $country)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update address']);
        }
        break;

    case 'get_address':
        $addressModel = new Address();
        $address_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $address = $addressModel->read($address_id, $user_id);
        if ($address) {
            echo json_encode($address);
        } else {
            echo json_encode(['success' => false, 'message' => 'Address not found']);
        }
        break;

    case 'get_credit_cards':
        $creditCardModel = new CreditCard();
        $cards = $creditCardModel->readAll($user_id);
        echo json_encode(['success' => true, 'cards' => array_map(function($card) {
            $last4 = substr($card['card_number'], -4);
            return [
                'id' => $card['id'],
                'cardholder_name' => $card['cardholder_name'],
                'card_number' => '•••• •••• •••• ' . $last4,
                'expiry_date' => $card['expiry_date']
            ];
        }, $cards)]);
        break;

    case 'add_credit_card':
        $creditCardModel = new CreditCard();
        $cardholder_name = $_POST['cardholder_name'] ?? '';
        $card_number = $_POST['card_number'] ?? '';
        $expiry_date = $_POST['expiry_date'] ?? '';
        $cvv = $_POST['cvv'] ?? '';
        if (empty($cardholder_name) || empty($card_number) || empty($expiry_date) || empty($cvv)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }
        if (!preg_match('/^\d{16}$/', $card_number)) {
            echo json_encode(['success' => false, 'message' => 'Invalid card number']);
            exit;
        }
        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            echo json_encode(['success' => false, 'message' => 'Invalid CVV']);
            exit;
        }
        if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $expiry_date)) {
            echo json_encode(['success' => false, 'message' => 'Invalid expiry date']);
            exit;
        }
        if ($creditCardModel->create($user_id, $cardholder_name, $card_number, $expiry_date, $cvv)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add card']);
        }
        break;

    case 'get_credit_card':
        $creditCardModel = new CreditCard();
        $card_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $card = $creditCardModel->read($card_id, $user_id);
        if ($card) {
            $last4 = substr($card['card_number'], -4);
            echo json_encode([
                'cardholder_name' => $card['cardholder_name'],
                'card_number' => '•••• •••• •••• ' . $last4,
                'expiry_date' => $card['expiry_date']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Card not found']);
        }
        break;

    case 'get_cart':
        $cartModel = new Cart();
        $cartItems = $cartModel->getByUser($user_id);
        $items = array_map(function($item) {
            return [
                'product_id' => $item['id'],
                'name' => $item['name'],
                'image' => $item['image'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price']
            ];
        }, $cartItems);
        echo json_encode(['success' => true, 'items' => $items]);
        break;

    case 'place_order':
        $input = json_decode(file_get_contents('php://input'), true);
        $orderModel = new Order();
        $wishlistModel = new Wishlist();
        $cartModel = new Cart();

        $db = new Database();
        $conn = $db->connect();
        $conn->beginTransaction();

        try {
            // Validate input
            if (!isset($input['payment_method']) || !in_array($input['payment_method'], ['cash', 'card'])) {
                throw new Exception('Invalid payment method');
            }
            if ($input['payment_method'] === 'card' && empty($input['credit_card_id'])) {
                throw new Exception('Credit card required for card payment');
            }
            if (empty($input['items'])) {
                throw new Exception('No items in order');
            }

            // Calculate total
            $subtotal = array_reduce($input['items'], function($carry, $item) {
                return $carry + ($item['quantity'] * $item['unit_price']);
            }, 0);
            $shipping = isset($input['address_id']) && $input['address_id'] ? 7.99 : 0;
            $total_amount = $subtotal + $shipping;

            // Create order
            $order_id = $orderModel->create(
                $user_id,
                $input['address_id'] ?? null,
                $input['payment_method'],
                $input['credit_card_id'] ?? null,
                $total_amount
            );

            if (!$order_id) {
                throw new Exception('Failed to create order');
            }

            // Create order items and update stock
            foreach ($input['items'] as $item) {
                if (!isset($item['product_id'], $item['quantity'], $item['unit_price']) || $item['quantity'] < 1) {
                    throw new Exception('Invalid item data');
                }
                $orderModel->addOrderItem($order_id, $item['product_id'], $item['quantity'], $item['unit_price']);
                $wishlistModel->remove($user_id, $item['product_id']);
            }

            // Clear cart
            $cartModel->clearByUserId($user_id);

            $conn->commit();
            echo json_encode(['success' => true, 'order_id' => sprintf('ORD-%s-%05d', date('Y'), $order_id)]);
        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    case 'get_orders':
        $orderModel = new Order();
        $orders = $orderModel->getAllByUser($user_id);
    
        foreach ($orders as &$order) {
            $order['items'] = $orderModel->getItemsByOrderId($order['id']);
        }
    
        echo json_encode(['success' => true, 'orders' => $orders]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>