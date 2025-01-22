<?php
include "session.php"; // Declared session start here
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

// Calculate the total amount for the order
$totalAmount = 0;
foreach ($cart as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}

date_default_timezone_set('Asia/Kuala Lumpur');

// Add 8 hours to the current date and time
$orderDate = new DateTime();
$orderDate->modify('+7 hours');
$orderDate = $orderDate->format('Y-m-d H:i:s'); // Format the datetime

$orderStatus = "Pending"; // Default order status

// Insert the order into the `orderlist` table
$stmtOrder = $conn->prepare("INSERT INTO orderlist (order_date, total_amount, order_status, price, Table_ID) VALUES (?, ?, ?, ?, ?)");
$stmtOrder->bind_param("sdssi", $orderDate, $totalAmount, $orderStatus, $totalAmount, $tableID);

if ($stmtOrder->execute()) {
    // Order inserted successfully
    $orderID = $stmtOrder->insert_id; // Get the Order_ID of the new order

    // Store the Order_ID in the session
    $_SESSION['order_id'] = $orderID;

    // Optional: Insert additional details if needed
    foreach ($cart as $item) {
        $dishName = $item['name'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        // Get Dish_ID from the menu table
        $stmtDish = $conn->prepare("SELECT Dish_ID FROM menu WHERE dish_name = ?");
        $stmtDish->bind_param("s", $dishName);
        $stmtDish->execute();
        $resultDish = $stmtDish->get_result();

        if ($resultDish->num_rows > 0) {
            $dishID = $resultDish->fetch_assoc()['Dish_ID'];

            // Optionally store additional data related to order details if required.
        } else {
            echo "<p>Error: Dish not found. Please contact staff. <a href='mainpage.php'>Go back to menu</a></p>";
            exit;
        }
    }

    // Clear the session cart after checkout
    $_SESSION['cart'] = [];

    // Generate a form to POST order_id to confirmation.php
    echo "<form id='confirmationForm' action='confirmation.php' method='POST'>
            <input type='hidden' name='order_id' value='$orderID'>
        </form>
        <script>document.getElementById('confirmationForm').submit();</script>";
    exit;

} else {
    echo "<p>Error: Unable to place order. Please contact staff. <a href='mainpage.php'>Go back to menu</a></p>";
    exit;
}
?>
