<?php 
include "connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = htmlspecialchars(trim($_POST['category_name'])); 

    // Check for duplicate entry
    $check_sql = "SELECT COUNT(*) FROM food_category WHERE category_name = ?";
    $stmt = $conn->prepare($check_sql);

    if ($stmt) {
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            // Category already exists
            echo "<script>
                alert('Category already exists!');
                window.location.href = 'MainMenu/menu.php';
            </script>";
            exit();
        }
    } else {
        echo "Error preparing duplicate check query: " . $conn->error;
        exit();
    }

    // Insert new category if no duplicate is found
    $sql = "INSERT INTO food_category (category_name) VALUES (?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $category_name);
        if ($stmt->execute()) {
            echo "<script>
                alert('New category added successfully!');
                window.location.href = 'MainMenu/menu.php';
            </script>";
            exit();
        } else {
            echo "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
    header("Location: MainMenu/menu.php");
    exit();
}
?>
