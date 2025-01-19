<?php
include "../connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dish_name'])) {
        $dish_name = $_POST['dish_name'];

        // Start the deletion process from both the `cart` and `menu` tables
        // First, delete from the cart table
        $delete_cart_sql = "DELETE FROM cart WHERE Dish_ID IN (SELECT Dish_ID FROM menu WHERE dish_name = ?)";
        if ($stmt = $conn->prepare($delete_cart_sql)) {
            $stmt->bind_param("s", $dish_name);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "<script>alert('Error: Unable to delete from cart.');</script>";
            exit();
        }

        // Now, delete from the menu table
        $delete_menu_sql = "DELETE FROM menu WHERE dish_name = ?";
        if ($stmt = $conn->prepare($delete_menu_sql)) {
            $stmt->bind_param("s", $dish_name);
            if ($stmt->execute()) {
                // Success message
                echo "<script>alert('Dish deleted successfully from both menu and order list!');</script>";
                header("Location: ../MainMenu/menu.php"); // Redirect to the menu page
                exit();
            } else {
                echo "<script>alert('Error: Unable to delete the dish from the menu.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error: Unable to prepare the delete statement for the menu.');</script>";
        }
    }
} else {
    echo "<script>alert('Invalid request method.');</script>";
}

$conn->close();
?>
