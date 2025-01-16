<?php
include "session.php";

// Check if dishName and dishPrice are passed via GET request
if (isset($_GET['dishName']) && isset($_GET['dishPrice'])) {
    $dishName = $_GET['dishName'];
    $dishPrice = $_GET['dishPrice'];

    // Add the item to the cart (check if it already exists)
    $itemFound = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $dishName) {
            $item['quantity'] += 1; // Increase quantity if item is already in the cart
            $itemFound = true;
            break;
        }
    }

    if (!$itemFound) {
        // If item is not found, add new item to cart
        $_SESSION['cart'][] = ['name' => $dishName, 'price' => $dishPrice, 'quantity' => 1];
    }

    // Return the total quantity of items in the cart
    echo count($_SESSION['cart']);
} else {
    echo 0; // If no valid item is sent, return 0
}
?>
