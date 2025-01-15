<?php
include "../connectdb.php";

$tableID = isset($_GET['table_id']) ? $_GET['table_id'] : null;

if (!$tableID) {
    echo json_encode(["success" => false, "message" => "Table ID is not provided"]);
    exit;
}

// Query to fetch cart items
$sql = "SELECT c.Cart_ID, d.dish_name, d.dish_image, c.quantity, c.price 
        FROM cart c
        INNER JOIN dishes d ON c.Dish_ID = d.Dish_ID
        WHERE c.Table_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tableID);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }
    echo json_encode(["success" => true, "items" => $cartItems]);
} else {
    echo json_encode(["success" => false, "message" => "No items in the cart"]);
}
?>
