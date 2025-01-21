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
    <link rel="stylesheet" href="edit_dish.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Edit Dish</title>
</head>
<body>
    <div class="wrapper-container">

        <div class="back-menu">
            <a href="menu.php">
                <button class="back-menu-btn"><i class="fa-solid fa-arrow-left"></i>Back To Menu</button>
            </a>
        </div>

        <h2>Edit Dish: <?php echo htmlspecialchars($dish_name); ?></h2>

        <form action="update_dish.php" method="POST" enctype="multipart/form-data" class="popup">
            <div class="form-template">
                <label for="dish_name">Dish Name:</label>
                <input type="text" name="dish_name" id="dish_name" value="<?php echo htmlspecialchars($dish_name); ?>" placeholder="Dish Name" required>
                
                <div class="choose-file-btn">
                    <label for="dish_image" class="custom-file-label">Upload Image</label>
                    <input type="file" name="dish_image" id="dish_image" class="file-input">
                    <span id="file-name" class="file-name">No file chosen</span>
                </div>



                <label for="dish_price">Dish Price:</label>
                <input type="number" name="dish_price" id="dish_price" value="<?php echo htmlspecialchars($dish_price); ?>" placeholder="Dish Price" step="0.01" min="0" required>
                
                <label for="dish_desc">Dish Description:</label>
                <textarea name="dish_desc" id="dish_desc" rows="4" cols="20" placeholder="Dish Description" required><?php echo htmlspecialchars($dish_desc); ?></textarea>
                
                <label for="dish_category">Select Category:</label>
                <select name="dish_category" id="dish_category" required>
                    <option value="" disabled>Select Category</option>
                    <?php
                    if ($result_category->num_rows > 0) {
                        while ($row = $result_category->fetch_assoc()) {
                            $selected = ($row['category_name'] === $dish_category) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($row['category_name']) . '" ' . $selected . '>' . htmlspecialchars($row['category_name']) . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No categories available</option>';
                    }
                    ?>
                </select>
                
                <input type="hidden" name="dish_availability" value="1" required>
                <div class="form-buttons">
                    <input type="submit" name="submit" value="Update Dish" class="submit-btn">
                    <input type="reset" value="Reset" class="reset-btn">
                </div>

                
            </div>
        </form>
    </div>

    <script>
        const fileInput = document.getElementById('dish_image');
        const fileNameSpan = document.getElementById('file-name');

        fileInput.addEventListener('change', () => {
            const fileName = fileInput.files[0] ? fileInput.files[0].name : 'No file chosen';
            fileNameSpan.textContent = fileName;
        });

    </script>
</body>
</html>
