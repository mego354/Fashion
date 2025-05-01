<?php
session_start();
require_once __DIR__ . '/../models/Address.php';
header('Content-Type: application/json');

$address = new Address();
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $input['title'] ?? '';
    $street = $input['street'] ?? '';
    $apartment_number = $input['apartment_number'] ?? '';
    $city = $input['city'] ?? '';
    $country = $input['country'] ?? '';

    if ($address->create($_SESSION['user_id'], $title, $street, $apartment_number, $city, $country)) {
        echo json_encode(['success' => true, 'message' => 'Address created']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create address']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $data = $address->read($_GET['id'], $_SESSION['user_id']);
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        $data = $address->readAll($_SESSION['user_id']);
        echo json_encode(['success' => true, 'data' => $data]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = $input['id'] ?? 0;
    $title = $input['title'] ?? '';
    $street = $input['street'] ?? '';
    $apartment_number = $input['apartment_number'] ?? '';
    $city = $input['city'] ?? '';
    $country = $input['country'] ?? '';

    if ($address->update($id, $_SESSION['user_id'], $title, $street, $apartment_number, $city, $country)) {
        echo json_encode(['success' => true, 'message' => 'Address updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update address']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $input['id'] ?? 0;
    if ($address->delete($id, $_SESSION['user_id'])) {
        echo json_encode(['success' => true, 'message' => 'Address deleted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete address']);
    }
}
?>