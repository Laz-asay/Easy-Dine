<?php
session_start();
include "../connectdb.php";

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='mainpage.php'>Go back to menu</a></p>";
    exit;
}

// Check if Table_Number is set
if (!isset($_SESSION['table_number'])) {
    echo "<p>Table number not assigned. Please contact staff. <a href='mainpage.php'>Go back to menu</a></p>";
    exit;
}

$tableNumber = $_SESSION['table_number'];

// Map table_number to Table_ID in the `tablelist` table
$stmtTable = $conn->prepare("SELECT Table_ID FROM tablelist WHERE table_number = ?");
$stmtTable->bind_param("i", $tableNumber);
$stmtTable->execute();
$resultTable = $stmtTable->get_result();

if ($resultTable->num_rows === 0) {
    echo "<p>Error: Table number does not exist. Please contact staff. <a href='mainpage.php'>Go back to menu</a></p>";
    exit;
}

$tableID = $resultTable->fetch_assoc()['Table_ID'];
$cart = $_SESSION['cart'];

// Loop through cart and insert into the `cart` table
foreach ($cart as $item) {
    $dishName = $item['name'];
    $quantity = $item['quantity'];
    $price = $item['price'];

    // Get Dish_ID from menu table
    $stmtDish = $conn->prepare("SELECT Dish_ID FROM menu WHERE dish_name = ?");
    $stmtDish->bind_param("s", $dishName);
    $stmtDish->execute();
    $resultDish = $stmtDish->get_result();

    if ($resultDish->num_rows > 0) {
        $dishID = $resultDish->fetch_assoc()['Dish_ID'];

        // Insert into cart table
        $stmtCart = $conn->prepare("INSERT INTO cart (quantity, price, Dish_ID, Table_ID) VALUES (?, ?, ?, ?)");
        $stmtCart->bind_param("idii", $quantity, $price, $dishID, $tableID);
        $stmtCart->execute();
    } else {
        echo "<p>Error: Dish not found. Please contact staff. <a href='mainpage.php'>Go back to menu</a></p>";
        exit;
    }
}

// Clear the session cart after checkout
$_SESSION['cart'] = [];

// Redirect to confirmation page
header("Location: confirmation.php");
exit;
?>
