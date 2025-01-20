<?php
include "../connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dish_name'])) {
        $dish_name = $_POST['dish_name'];

        // Start transaction to ensure atomicity
        $conn->begin_transaction();

        try {
            // 1. Mark the dish as deleted in the `menu` table (soft delete)
            $mark_deleted_sql = "UPDATE menu SET deleted = 1 WHERE dish_name = ?";
            if ($stmt = $conn->prepare($mark_deleted_sql)) {
                $stmt->bind_param("s", $dish_name);
                $stmt->execute();
                $stmt->close();
            } else {
                throw new Exception('Error: Unable to mark dish as deleted in the menu.');
            }

            // 2. Delete from `cart` table (optional, can keep it if necessary)
            $delete_cart_sql = "DELETE FROM cart WHERE Dish_ID IN (SELECT Dish_ID FROM menu WHERE dish_name = ?)";
            if ($stmt = $conn->prepare($delete_cart_sql)) {
                $stmt->bind_param("s", $dish_name);
                $stmt->execute();
                $stmt->close();
            } else {
                throw new Exception('Error: Unable to delete from cart.');
            }

            // 3. Delete related entries in the `reports` table (only if required)
            // You may choose to not delete from reports and keep the reference intact.
            // Comment out this section if you want to keep the report entries.
            // $delete_reports_sql = "DELETE FROM reports WHERE Order_ID IN (SELECT Order_ID FROM orderlist WHERE Dish_ID IN (SELECT Dish_ID FROM menu WHERE dish_name = ?))";
            // if ($stmt = $conn->prepare($delete_reports_sql)) {
            //     $stmt->bind_param("s", $dish_name);
            //     $stmt->execute();
            //     $stmt->close();
            // } else {
            //     throw new Exception('Error: Unable to delete from reports.');
            // }

            // Commit the transaction if everything is successful
            $conn->commit();
            echo "<script>alert('Dish marked as deleted successfully!');</script>";
            header("Location: ../MainMenu/menu.php"); // Redirect to the menu page
            exit();
        } catch (Exception $e) {
            // If an error occurs, rollback the transaction
            $conn->rollback();
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
} else {
    echo "<script>alert('Invalid request method.');</script>";
}

$conn->close();
?>
