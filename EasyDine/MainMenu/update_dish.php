<?php
include "../connectdb.php";

if (!isset($_POST['dish_name']) || empty($_POST['dish_name']) || !isset($_POST['dish_desc']) || !isset($_POST['dish_price']) || !isset($_POST['dish_category'])) {
    echo "All fields are required!";
    exit;
}

$dish_name = $_POST['dish_name'];
$dish_desc = $_POST['dish_desc'];
$dish_price = $_POST['dish_price'];
$dish_category = $_POST['dish_category'];

// Handle image upload (if new image is uploaded)
if (isset($_FILES['dish_image']) && $_FILES['dish_image']['error'] == 0) {
    $target_dir = "../images/mainmenu/";
    $target_file = $target_dir . basename($_FILES['dish_image']['name']);
    
    // Move the uploaded file
    if (move_uploaded_file($_FILES['dish_image']['tmp_name'], $target_file)) {
        $dish_image = basename($_FILES['dish_image']['name']);
    } else {
        echo "Error uploading image!";
        exit;
    }
} else {
    // If no new image is uploaded, keep the old image
    $sql = "SELECT dish_image FROM menu WHERE dish_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dish_name);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($dish_image);
    $stmt->fetch();
    $stmt->close();
}

// update the dish
$sql = "UPDATE menu SET dish_desc = ?, dish_price = ?, dish_category = ?, dish_image = ? WHERE dish_name = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo "Error in preparing query: " . $conn->error;
    exit;
}

$stmt->bind_param("sdsss", $dish_desc, $dish_price, $dish_category, $dish_image, $dish_name);
if ($stmt->execute()) {
    echo "Dish updated successfully!";
    sleep(2); //tido jap
    header("Location: ../MainMenu/menu.php"); // Correct syntax for header function
    exit();
} 
else {
    echo "Error updating dish!";
}

$stmt->close();
?>
