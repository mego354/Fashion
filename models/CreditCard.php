<?php
require_once __DIR__ . '/../config/database.php';

class CreditCard {
    private $db;
    private $encryption_key = 'your-secure-encryption-key'; // Replace with a secure key in production

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    private function encrypt($data) {
        return openssl_encrypt($data, 'AES-256-CBC', $this->encryption_key, 0, substr($this->encryption_key, 0, 16));
    }

    private function decrypt($data) {
        return openssl_decrypt($data, 'AES-256-CBC', $this->encryption_key, 0, substr($this->encryption_key, 0, 16));
    }

    public function create($user_id, $cardholder_name, $card_number, $expiry_date, $cvv) {
        $encrypted_card_number = $this->encrypt($card_number);
        $encrypted_cvv = $this->encrypt($cvv);
        $query = 'INSERT INTO credit_cards (user_id, cardholder_name, card_number, expiry_date, cvv) VALUES (:user_id, :cardholder_name, :card_number, :expiry_date, :cvv)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':cardholder_name', $cardholder_name);
        $stmt->bindParam(':card_number', $encrypted_card_number);
        $stmt->bindParam(':expiry_date', $expiry_date);
        $stmt->bindParam(':cvv', $encrypted_cvv);
        return $stmt->execute();
    }

    public function readAll($user_id) {
        $query = 'SELECT id, user_id, cardholder_name, card_number, expiry_date, cvv FROM credit_cards WHERE user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $cards = $stmt->fetchAll();
        foreach ($cards as &$card) {
            $card['card_number'] = $this->decrypt($card['card_number']);
            $card['cvv'] = $this->decrypt($card['cvv']);
        }
        return $cards;
    }

    public function read($id, $user_id) {
        $query = 'SELECT id, user_id, cardholder_name, card_number, expiry_date, cvv FROM credit_cards WHERE id = :id AND user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $card = $stmt->fetch();
        if ($card) {
            $card['card_number'] = $this->decrypt($card['card_number']);
            $card['cvv'] = $this->decrypt($card['cvv']);
        }
        return $card;
    }

    public function update($id, $user_id, $cardholder_name, $card_number, $expiry_date, $cvv) {
        $encrypted_card_number = $this->encrypt($card_number);
        $encrypted_cvv = $this->encrypt($cvv);
        $query = 'UPDATE credit_cards SET cardholder_name = :cardholder_name, card_number = :card_number, expiry_date = :expiry_date, cvv = :cvv WHERE id = :id AND user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':cardholder_name', $cardholder_name);
        $stmt->bindParam(':card_number', $encrypted_card_number);
        $stmt->bindParam(':expiry_date', $expiry_date);
        $stmt->bindParam(':cvv', $encrypted_cvv);
        return $stmt->execute();
    }

    public function delete($id, $user_id) {
        $query = 'DELETE FROM credit_cards WHERE id = :id AND user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}
?>