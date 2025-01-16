<?php
include "session.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'addToCart') {
        $dishName = $_POST['popupDishName'];
        $dishPrice = floatval(str_replace('RM', '', $_POST['popupDishPrice']));

        // Initialize cart if not set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if item exists in the cart
        $existingItem = null;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] === $dishName) {
                $existingItem = &$item;
                break;
            }
        }

        if ($existingItem) {
            // Item exists, increase quantity
            $existingItem['quantity'] += 1;
        } else {
            // Add new item to cart
            $_SESSION['cart'][] = ['name' => $dishName, 'price' => $dishPrice, 'quantity' => 1];
        }

        // Return cart length to update the badge
        echo json_encode(['status' => 'success', 'cartLength' => count($_SESSION['cart'])]);
    }
}
?>
