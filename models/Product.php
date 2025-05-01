<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function create($name, $description, $stock_quantity, $price, $rating, $genre_id, $image) {
        $query = 'INSERT INTO products (name, description, stock_quantity, price, rating, genre_id, image) 
                  VALUES (:name, :description, :stock_quantity, :price, :rating, :genre_id, :image)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':genre_id', $genre_id);
        $stmt->bindParam(':image', $image);
        return $stmt->execute();
    }

    public function readAll() {
        $query = 'SELECT p.*, g.name as genre_name FROM products p JOIN genres g ON p.genre_id = g.id';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function read($id) {
        $query = 'SELECT p.*, g.name as genre_name FROM products p JOIN genres g ON p.genre_id = g.id WHERE p.id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update($id, $name, $description, $stock_quantity, $price, $rating, $genre_id, $image = null) {
        $query = 'UPDATE products SET name = :name, description = :description, stock_quantity = :stock_quantity, price = :price, rating = :rating, genre_id = :genre_id';
        if ($image !== null) {
            $query .= ', image = :image';
        }
        $query .= ' WHERE id = :id';
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':genre_id', $genre_id);
        if ($image !== null) {
            $stmt->bindParam(':image', $image);
        }
        if (!$stmt->execute()) {
            error_log("Update failed: " . print_r($stmt->errorInfo(), true));
            return false;
        }
        return true;
    }
    
    public function delete($id) {
        $query = 'DELETE FROM products WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        if (!$stmt->execute()) {
            error_log("Delete failed: " . print_r($stmt->errorInfo(), true));
            return false;
        }
        return true;
    }

    public function getTopRatedProducts($limit = 7) {
        $query = "SELECT p.*, g.name as genre_name 
                  FROM products p 
                  JOIN genres g ON p.genre_id = g.id 
                  ORDER BY p.rating DESC 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getNewProducts($limit = 7) {
        $query = "SELECT p.*, g.name as genre_name 
                  FROM products p 
                  JOIN genres g ON p.genre_id = g.id 
                  ORDER BY p.created_at DESC 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getProductsByGenreSorted($genre_id) {
        $query = "SELECT p.*, g.name as genre_name 
                  FROM products p 
                  JOIN genres g ON p.genre_id = g.id 
                  WHERE g.id = :genre_id 
                  ORDER BY p.rating DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':genre_id', $genre_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = 'SELECT * FROM products WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Get price range for dynamic filter
    public function getPriceRange() {
        $query = "SELECT MIN(price) as min_price, MAX(price) as max_price FROM products";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFilteredProducts($filters, $page = 1, $perPage = 8) {
        $query = "SELECT p.*, g.name as genre_name FROM products p JOIN genres g ON p.genre_id = g.id WHERE 1=1";
        $countQuery = "SELECT COUNT(*) as total FROM products p JOIN genres g ON p.genre_id = g.id WHERE 1=1";
        $params = [];

        // Category/Genre filter (support for multiple categories)
        if (!empty($filters['genres']) && is_array($filters['genres'])) {
            $genreParams = [];
            $i = 0;
            foreach ($filters['genres'] as $genre_id) {
                $paramName = ':genre_id_' . $i;
                $genreParams[] = $paramName;
                $params[$paramName] = $genre_id;
                $i++;
            }
            $query .= " AND p.genre_id IN (" . implode(', ', $genreParams) . ")";
            $countQuery .= " AND p.genre_id IN (" . implode(', ', $genreParams) . ")";
        }

        // Price filter
        if (isset($filters['price_min']) && $filters['price_min'] > 0) {
            $query .= " AND p.price >= :price_min";
            $countQuery .= " AND p.price >= :price_min";
            $params[':price_min'] = $filters['price_min'];
        }
        
        if (isset($filters['price_max']) && $filters['price_max'] > 0) {
            $query .= " AND p.price <= :price_max";
            $countQuery .= " AND p.price <= :price_max";
            $params[':price_max'] = $filters['price_max'];
        }

        // Sorting
        switch ($filters['sort']) {
            case 'name_asc':
                $query .= " ORDER BY p.name ASC";
                break;
            case 'name_desc':
                $query .= " ORDER BY p.name DESC";
                break;
            case 'price_low':
                $query .= " ORDER BY p.price ASC";
                break;
            case 'price_high':
                $query .= " ORDER BY p.price DESC";
                break;
            case 'newest':
                $query .= " ORDER BY p.created_at DESC";
                break;
            case 'rating_high':
                $query .= " ORDER BY p.rating DESC";
                break;
            case 'rating_low':
                $query .= " ORDER BY p.rating ASC";
                break;
            default:
                $query .= " ORDER BY p.rating DESC";
        }

        // Pagination
        $offset = ($page - 1) * $perPage;
        $query .= " LIMIT :offset, :perPage";

        // Get total count
        $stmt = $this->db->prepare($countQuery);
        foreach ($params as $key => $value) {
            if (strpos($key, 'genre_id_') !== false) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        $stmt->execute();
        $totalProducts = $stmt->fetchColumn();

        // Get products
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            if (strpos($key, 'genre_id_') !== false) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'products' => $products,
            'total' => $totalProducts
        ];
    }
}
?>
