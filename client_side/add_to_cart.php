<?php
include "../connectdb.php";

$tableID = isset($_POST['table_id']) ? $_POST['table_id'] : null;
$dishID = isset($_POST['dish_id']) ? $_POST['dish_id'] : null;

if (!$tableID || !$dishID) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

// Check if the dish already exists in the cart for this table
$sql = "SELECT * FROM cart WHERE Table_ID = ? AND Dish_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $tableID, $dishID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update the quantity if the dish already exists
    $row = $result->fetch_assoc();
    $newQuantity = $row['quantity'] + 1;

    $updateSql = "UPDATE cart SET quantity = ?, price = price + (SELECT dish_price FROM dishes WHERE Dish_ID = ?) WHERE Cart_ID = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("iii", $newQuantity, $dishID, $row['Cart_ID']);
    $updateStmt->execute();
} else {
    // Insert a new row if the dish does not exist
    $insertSql = "INSERT INTO cart (quantity, price, Dish_ID, Table_ID) VALUES (1, (SELECT dish_price FROM dishes WHERE Dish_ID = ?), ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("iii", $dishID, $dishID, $tableID);
    $insertStmt->execute();
}

echo json_encode(["success" => true, "message" => "Dish added to cart"]);
exit;
?>
