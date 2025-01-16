<?php
session_start();

if (isset($_POST['dishName'], $_POST['dishDesc'], $_POST['dishPrice'], $_POST['dishImage'])) {
    $itemExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] == $_POST['dishName']) {
            $item['quantity']++;
            $itemExists = true;
            break;
        }
    }

    if (!$itemExists) {
        $dish = [
            'name' => $_POST['dishName'],
            'desc' => $_POST['dishDesc'],
            'price' => (float) $_POST['dishPrice'], // Ensure price is a float
            'image' => $_POST['dishImage'],
            'quantity' => 1 // Default quantity, can be modified later
        ];
        $_SESSION['cart'][] = $dish;
    }

    echo "Item added to cart!";
} else {
    echo "Error: Missing data!";
}
?>
