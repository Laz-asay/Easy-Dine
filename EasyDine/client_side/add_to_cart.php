<?php
include "session.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dishName = $_POST['dish_name'];
    $dishPrice = $_POST['dish_price'];
    $quantity = intval($_POST['quantity']); // Get quantity from the form

    // Add the dish to the cart (stored in session)
    $cartItem = [
        'name' => $dishName,
        'price' => $dishPrice,
        'quantity' => $quantity,
    ];

    // Check if the item is already in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $dishName) {
            $item['quantity'] += $quantity; // Increment the quantity
            $found = true;
            break;
        }
    }

    // Add new item to the cart if not already present
    if (!$found) {
        $_SESSION['cart'][] = $cartItem;
    }

    // Redirect back to the main page
    header("Location: mainpage.php");
    exit;
}
?>
