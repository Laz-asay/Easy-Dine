<?php
include "connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_category = $_POST['current_category'];
    $new_category_name = $_POST['new_category_name'];

    // Prevent SQL injection
    $query = "UPDATE food_category SET category_name = ? WHERE category_name = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ss", $new_category_name, $current_category);
        if ($stmt->execute()) {
            echo "Category updated successfully!";
            header("Refresh: 1; URL=../Food/MainMenu/menu.php"); // Redirect after 2 seconds
            exit();
        } else {
            echo "Error updating category: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>
