<?php
session_start();
require_once __DIR__ . '/../models/Genre.php';
header('Content-Type: application/json');

$genre = new Genre();
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $input['name'] ?? '';
    if ($genre->create($name)) {
        echo json_encode(['success' => true, 'message' => 'Genre created']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create genre']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $data = $genre->read($_GET['id']);
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        $data = $genre->readAll();
        echo json_encode(['success' => true, 'data' => $data]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = $input['id'] ?? 0;
    $name = $input['name'] ?? '';
    if ($genre->update($id, $name)) {
        echo json_encode(['success' => true, 'message' => 'Genre updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update genre']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $input['id'] ?? 0;
    if ($genre->delete($id)) {
        echo json_encode(['success' => true, 'message' => 'Genre deleted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete genre']);
    }
}
?>