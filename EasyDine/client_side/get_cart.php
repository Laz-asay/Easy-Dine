<?php
include "session.php";

// Initialize cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Return the cart items as JSON
echo json_encode([
    'items' => $_SESSION['cart']
]);
?>
