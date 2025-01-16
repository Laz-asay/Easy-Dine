<?php
session_start();

$response = ['status' => 'error'];

if (isset($_POST['itemIndex'])) {
    $itemIndex = $_POST['itemIndex'];

    if (isset($_SESSION['cart'][$itemIndex])) {
        // Increase the quantity of the item
        $_SESSION['cart'][$itemIndex]['quantity']++;

        // Send a success response with the updated quantity
        $response['status'] = 'success';
        $response['newQuantity'] = $_SESSION['cart'][$itemIndex]['quantity'];
    }
}

echo json_encode($response);
exit;
