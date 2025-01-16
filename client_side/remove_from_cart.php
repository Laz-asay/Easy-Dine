<?php
include "session.php";

if (isset($_GET['dishName'])) {
    $dishName = $_GET['dishName'];

    // Find and remove the item from the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['name'] === $dishName) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Reindex the array to fix array gaps
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    // Return the total quantity of items in the cart
    echo count($_SESSION['cart']);
} else {
    echo 0; // If no dishName is passed, return 0
}
?>
