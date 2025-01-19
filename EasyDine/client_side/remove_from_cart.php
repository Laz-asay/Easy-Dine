<?php
session_start();

$dishName = isset($_GET['dishName']) ? $_GET['dishName'] : '';

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['name'] === $dishName) {
            unset($_SESSION['cart'][$index]);
            break;
        }
    }

    // Re-index the array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Calculate the total quantity of items in the cart
$totalQuantity = array_reduce($_SESSION['cart'], function ($carry, $item) {
    return $carry + $item['quantity'];
}, 0);

echo $totalQuantity;
?>
