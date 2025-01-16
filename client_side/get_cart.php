<?php
include "session.php";

header('Content-Type: application/json');

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

echo json_encode([
    'items' => $cart
]);
?>
