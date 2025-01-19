<?php
include "session.php";
include "../connectdb.php";
$tableNumber = isset($_SESSION['table_number']) ? $_SESSION['table_number'] : null;


if (isset($_GET['name'])) {
    $dishName = $_GET['name'];

    $stmt = $conn->prepare("SELECT * FROM menu WHERE dish_name = ?");
    $stmt->bind_param("s", $dishName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $dishImage = "../images/mainmenu/" . $row['dish_image'];
        $dishPrice = htmlspecialchars($row['dish_price']);
        $dishDesc = htmlspecialchars($row['dish_desc']);
    } else {
        echo "<p>Dish not found.</p>";
        exit;
    }

    $stmt->close();
} else {
    echo "<p>No dish selected.</p>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dish Details</title>
    <link rel="stylesheet" href="dish_details.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>
    <div class="page-container">
        <div class="dish-details">  
            <img src="<?php echo $dishImage; ?>" alt="<?php echo htmlspecialchars($dishName); ?>" class="dish-image">

            <div class="dish-and-price">
                <div class="dish-name">
                    <h2><?php echo htmlspecialchars($dishName); ?></h2>
                </div>
                <h2>RM<?php echo $dishPrice; ?></h2>
            </div>

            <div class="dish-desc">
                <p><?php echo $dishDesc; ?></p>
            </div>

            <div class="bottom-fixed">
                <!-- Quantity Selector -->
                <div class="quantity-selector">
                    <button type="button" onclick="decreaseQuantity()">-</button>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" class="quantity-input">
                    <button type="button" onclick="increaseQuantity()">+</button>
                </div>

                <!-- Add to Cart Form -->
                <div class="action-buttons">
                    <form action="add_to_cart.php" method="POST" class="action-butang">
                        <input type="hidden" name="dish_name" value="<?php echo htmlspecialchars($dishName); ?>">
                        <input type="hidden" name="dish_price" value="<?php echo $dishPrice; ?>">
                        <input type="number" name="quantity" id="hidden-quantity" value="1" hidden>
                        <button type="submit">Add to Cart</button>
                    </form>

                    <!-- Back Button -->
                    <a href="mainpage.php" class="action-butang">
                        <button>Back To Menu</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const quantityInput = document.getElementById('quantity');
        const hiddenQuantityInput = document.getElementById('hidden-quantity');

        function increaseQuantity() {
            let current = parseInt(quantityInput.value);
            quantityInput.value = current + 1;
            hiddenQuantityInput.value = quantityInput.value;
        }

        function decreaseQuantity() {
            let current = parseInt(quantityInput.value);
            if (current > 1) {
                quantityInput.value = current - 1;
                hiddenQuantityInput.value = quantityInput.value;
            }
        }

        // Sync manual input changes to hidden input
        quantityInput.addEventListener('input', () => {
            hiddenQuantityInput.value = quantityInput.value;
        });
    </script>
</body>
</html>

