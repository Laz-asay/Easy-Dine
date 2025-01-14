<?php
include "../connectdb.php";

// Check if dish_name is provided
if (!isset($_GET['dish_name']) || empty($_GET['dish_name'])) {
    echo "Dish name not specified!";
    exit;
}

$dish_name = $_GET['dish_name'];

// Fetch current dish details
$sql = "SELECT dish_image, dish_name, dish_desc, dish_price, dish_category FROM menu WHERE dish_name = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo "Error in preparing query: " . $conn->error;
    exit;
}

$stmt->bind_param("s", $dish_name);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "Dish not found!";
    exit;
}

$stmt->bind_result($dish_image, $dish_name, $dish_desc, $dish_price, $dish_category);
$stmt->fetch();
$stmt->close();

// Fetch available categories for the dropdown
$sql_category = "SELECT category_name FROM food_category";
$result_category = $conn->query($sql_category);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="display_menu.css">
    <title>Edit Dish</title>
</head>
<body>
    <h2>Edit Dish: <?php echo htmlspecialchars($dish_name); ?></h2>

    <form action="update_dish.php" method="POST" enctype="multipart/form-data" class="popup">
        <div class="form-template">
            <input type="text" name="dish_name" id="dish_name" value="<?php echo htmlspecialchars($dish_name); ?>" placeholder="Dish Name" required>
            
            <label for="dish_image">Change Image (Optional):</label>
            <input type="file" name="dish_image" id="dish_image">
            <p>Current Image: <img src="../images/mainmenu/<?php echo htmlspecialchars($dish_image); ?>" alt="<?php echo htmlspecialchars($dish_name); ?>" width="100"></p>
            
            <input type="number" name="dish_price" id="dish_price" value="<?php echo htmlspecialchars($dish_price); ?>" placeholder="Dish Price" step="0.01" min="0" required>
            
            <textarea name="dish_desc" id="dish_desc" rows="4" cols="20" placeholder="Dish Description" required><?php echo htmlspecialchars($dish_desc); ?></textarea>
            
            <label for="dish_category">Select Category:</label>
            <select name="dish_category" id="dish_category" required>
                <option value="" disabled>Select Category</option>
                <?php
                // Populate categories from database
                if ($result_category->num_rows > 0) {
                    while ($row = $result_category->fetch_assoc()) {
                        // Check if this category is the selected one for this dish
                        $selected = ($row['category_name'] === $dish_category) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($row['category_name']) . '" ' . $selected . '>' . htmlspecialchars($row['category_name']) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>No categories available</option>';
                }
                ?>
            </select>
            
            <input type="hidden" name="dish_availability" value="1" required>
            <input type="submit" name="submit" value="Update Dish">
            <input type="reset" value="Reset">
        </div>
    </form>
</body>
</html>
