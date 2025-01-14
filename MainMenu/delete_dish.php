<?php
include "../connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dish_name'])) {
        $dish_name = $_POST['dish_name'];
        $sql = "DELETE FROM menu WHERE dish_name = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $dish_name);
            if ($stmt->execute()) {
                echo "<script>alert('Dish deleted successfully!');</script>";
                sleep(2); //tido jap
                header("Location: ../MainMenu/menu.php"); // Correct syntax for header function
                exit();
            } else {
                echo "<script>alert('Error: Unable to delete the dish.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error: Unable to prepare the statement.');</script>";
        }
    } else {
        echo "<script>alert('Dish name not provided.');</script>";
    }
}

$conn->close();
?>
