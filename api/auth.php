<?php
session_start();
require_once __DIR__ . '/../models/User.php';
header('Content-Type: application/json');

$user = new User();
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($input['action'])) {
        if ($input['action'] === 'register') {
            $full_name = $input['full_name'] ?? '';
            $email = $input['email'] ?? '';
            $phone_number = $input['phone_number'] ?? '';
            $date_of_birth = $input['date_of_birth'] ?? '';
            $password = $input['password'] ?? '';
            $confirm_password = $input['confirm_password'] ?? '';

            if ($password !== $confirm_password) {
                echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
               (exit);
            }

            if ($user->emailExists($email)) {
                echo json_encode(['success' => false, 'message' => 'Email already exists']);
                exit;
            }

            if ($user->register($full_name, $email, $phone_number, $date_of_birth, $password)) {
                echo json_encode(['success' => true, 'message' => 'Registration successful']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Registration failed']);
            }
        } elseif ($input['action'] === 'login') {
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';

            $user_data = $user->login($email, $password);
            if ($user_data) {
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['user_email'] = $user_data['email'];
                echo json_encode([
                    'success' => true,
                    'message' => 'Login successful',
                    'user_id' => $user_data['id'] // Include user_id in response
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Logged out']);
}
?>