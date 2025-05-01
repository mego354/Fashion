<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function register($full_name, $email, $phone_number, $date_of_birth, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = 'INSERT INTO users (full_name, email, phone_number, date_of_birth, password) VALUES (:full_name, :email, :phone_number, :date_of_birth, :password)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':password', $hashed_password);
        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = 'SELECT * FROM users WHERE email = :email';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function emailExists($email) {
        $query = 'SELECT id FROM users WHERE email = :email';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
}
?>