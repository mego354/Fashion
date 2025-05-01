<?php
session_start();
require_once __DIR__ . '/../models/Product.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$product = new Product();
$method = $_SERVER['REQUEST_METHOD'];

// Handle _method override for POST requests
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

if ($method === 'POST') {
    // Create a new product
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $stock_quantity = $_POST['stock_quantity'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $rating = $_POST['rating'] ?? 1;
    $genre_id = $_POST['genre_id'] ?? 0;
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "../uploads/";
        $filename = uniqid() . '_' . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = 'uploads/' . $filename;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            exit;
        }
    }

    if ($product->create($name, $description, $stock_quantity, $price, $rating, $genre_id, $image)) {
        echo json_encode(['success' => true, 'message' => 'Product created']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create product']);
    }
} elseif ($method === 'GET') {
    if (isset($_GET['id'])) {
        $data = $product->read($_GET['id']);
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        $data = $product->readAll();
        echo json_encode(['success' => true, 'data' => $data]);
    }
} elseif ($method === 'PUT') {
    // Update a product
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $stock_quantity = $_POST['stock_quantity'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $rating = $_POST['rating'] ?? 1;
    $genre_id = $_POST['genre_id'] ?? 0;
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "../uploads/";
        $filename = uniqid() . '_' . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = 'uploads/' . $filename;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            exit;
        }
    }

    if ($product->update($id, $name, $description, $stock_quantity, $price, $rating, $genre_id, $image)) {
        echo json_encode(['success' => true, 'message' => 'Product updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update product']);
    }
} elseif ($method === 'DELETE') {
    // Delete a product
    $id = $_POST['id'] ?? 0;
    if ($product->delete($id)) {
        echo json_encode(['success' => true, 'message' => 'Product deleted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
    }
}
?>