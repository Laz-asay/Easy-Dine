<?php
include "../connectdb.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dish_name'])) {
        $dish_name = $_POST['dish_name'];
        
        // JavaScript prompt will handle the confirmation
        echo "
        <script>
            if (confirm('Deleting this dish will also remove it from the cart. Do you want to proceed?')) {
                window.location.href = 'delete_dish_confirm.php?dish_name=" . urlencode($dish_name) . "';
            } else {
                window.location.href = '../MainMenu/menu.php'; // Redirect to menu if cancelled
            }
        </script>";
    } else {
        echo "<script>alert('Dish name not provided.');</script>";
    }
}

$conn->close();
?>
