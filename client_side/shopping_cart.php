<?php
session_start();

// Check if the cart is set
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    $cartEmpty = true;
} else {
    $cartEmpty = false;
}

$tableNumber = isset($_SESSION['table_number']) ? $_SESSION['table_number'] : null;
if ($tableNumber === null) {
    echo "No table number found. Please select a table on the previous page.";
    exit;
}

// Calculate total price
$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="shopping_cart.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="top-fixed">
        <div class="top-menu">
            <div class="back-button">
                <a href="mainpage.php">
                    <button>
                        <img src="../images/icon-library/back-60.png">
                    </button>
                </a>
            </div>
            <div class="shopping-cart-header">
                <h1>Shopping Cart</h1>
            </div>
        </div>
    </div>

    <?php if ($cartEmpty): ?>
        <div class="empty-cart-message">
            <h2>Your cart is empty.</h2>
        </div>
    <?php else: ?>
        <div class="cart-container">
            <?php
            // Loop through cart items and display
            foreach ($_SESSION['cart'] as $index => $item) {
                echo "<div class='cart-item' data-index='$index'>";
                echo "<img class='cart-item-image' src='" . $item['image'] . "' alt='" . $item['name'] . "' />";
                echo "<p class='dish-name-cart-display'><strong>" . $item['name'] . "</strong></p>";
                echo "<p class='dish-price-cart-display'>RM" . number_format($item['price'], 2) . " x " . $item['quantity'] . "</p>";

                echo "</div>";
            }
            ?>
        </div>
    <?php endif; ?>

    <div class="bottom-fixed">
        <div class="total-price">
            <div class="jumlah-harga">
                <h1>Total Price</h1>
                <h1 class="final-price">RM<?php echo number_format($totalPrice, 2); ?></h1>
            </div>
        </div>

        <div class="checkout-button">
            <button class="checkout">Checkout</button>
        </div>
    </div>

    <script>

    </script>

</body>
</html>
