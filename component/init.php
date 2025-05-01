<?php
// init.php
session_start();

// Initialize cart and wishlist if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}
?>