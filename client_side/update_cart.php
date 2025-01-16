<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'];
    $index = $data['index'];

    if (isset($_SESSION['cart'][$index])) {
        if ($action === 'reduce') {
            // Decrease quantity or remove if quantity is 1
            if ($_SESSION['cart'][$index]['quantity'] > 1) {
                $_SESSION['cart'][$index]['quantity']--;
            } else {
                array_splice($_SESSION['cart'], $index, 1);
            }
        } elseif ($action === 'delete') {
            // Remove item from the cart
            array_splice($_SESSION['cart'], $index, 1);
        }

        echo json_encode(['status' => 'success']);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
exit;
?>
