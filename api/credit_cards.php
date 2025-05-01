<?php
session_start();
require_once __DIR__ . '/../models/CreditCard.php';
header('Content-Type: application/json');

$credit_card = new CreditCard();
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardholder_name = $input['cardholder_name'] ?? '';
    $card_number = $input['card_number'] ?? '';
    $expiry_date = $input['expiry_date'] ?? '';
    $cvv = $input['cvv'] ?? '';

    if ($credit_card->create($_SESSION['user_id'], $cardholder_name, $card_number, $expiry_date, $cvv)) {
        echo json_encode(['success' => true, 'message' => 'Credit card added']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add credit card']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $data = $credit_card->read($_GET['id'], $_SESSION['user_id']);
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        $data = $credit_card->readAll($_SESSION['user_id']);
        echo json_encode(['success' => true, 'data' => $data]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = $input['id'] ?? 0;
    $cardholder_name = $input['cardholder_name'] ?? '';
    $card_number = $input['card_number'] ?? '';
    $expiry_date = $input['expiry_date'] ?? '';
    $cvv = $input['cvv'] ?? '';

    if ($credit_card->update($id, $_SESSION['user_id'], $cardholder_name, $card_number, $expiry_date, $cvv)) {
        echo json_encode(['success' => true, 'message' => 'Credit card updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update credit card']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $input['id'] ?? 0;
    if ($credit_card->delete($id, $_SESSION['user_id'])) {
        echo json_encode(['success' => true, 'message' => 'Credit card deleted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete credit card']);
    }
}
?>