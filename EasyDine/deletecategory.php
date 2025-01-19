<?php
include "connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = htmlspecialchars($_POST['category_name']);

    // Check if the category exists
    $checkQuery = "SELECT * FROM food_category WHERE category_name = ?";
    if ($stmt = $conn->prepare($checkQuery)) {
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If category exists, delete it
            $deleteQuery = "DELETE FROM food_category WHERE category_name = ?";
            if ($deleteStmt = $conn->prepare($deleteQuery)) {
                $deleteStmt->bind_param("s", $category_name);
                if ($deleteStmt->execute()) {
                    echo "Category deleted successfully!";
                    header("Location: MainMenu/menu.php");
                } else {
                    echo "Error deleting category: " . $deleteStmt->error;
                }
                $deleteStmt->close();
            }
        } else {
            echo "Category not found!";
        }

        $stmt->close();
    }

    // Redirect back to the main menu
    header("Location: MainMenu/menu.php");
    exit();
} else {
    echo "Invalid request!";
    header("Location: MainMenu/menu.php");
    exit();
}
?>
