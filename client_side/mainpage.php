<?php
include "../connectdb.php";
include "session.php";
$tableNumber = isset($_SESSION['table_number']) ? $_SESSION['table_number'] : null;

if ($tableNumber === null) {
    echo "No table number found. Please select a table on the previous page.";
    exit;
}

// Initialize cart if it doesn't exist in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $dishName = $_POST['dishName'];
    $dishPrice = $_POST['dishPrice'];

    // Check if the item is already in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] == $dishName) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = ['name' => $dishName, 'price' => $dishPrice, 'quantity' => 1];
    }

    // Redirect to update the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Remove item from cart
if (isset($_POST['action']) && $_POST['action'] == 'remove') {
    $index = $_POST['index'];
    unset($_SESSION['cart'][$index]);

    // Reindex array
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    // Redirect to update the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="mainpage.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

        <script>
            function openCart() {
                var cartPopup = document.getElementById("cartPopup");
                cartPopup.style.display = "block";
            }

            function closeCart() {
                var cartPopup = document.getElementById("cartPopup");
                cartPopup.style.display = "none";
            }
        </script>

    </head>
    <body>
        <div class="head-menu">
            <!-- TABLE AND CART -->
            <div class="top-menu">
                <!-- Table List -->
                <div class="table-number">
                    <h1>Table <span class="table-number-colour"><?php echo htmlspecialchars($tableNumber); ?></span> </h1>
                    <a>Let's get your order</a>
                </div>

                <div class="shopping-cart">
                    <button class="cart-button" onclick="openCart()">
                        <img src="../images/icon-library/cart-48.png">
                        <span id="cartQuantity" class="cart-badge"><?php echo count($_SESSION['cart']); ?></span>
                    </button>
                </div>
            </div>
            <!-- END OF TABLE AND CART -->

            <?php 
                $sql = "SELECT * FROM food_category ORDER BY Category_ID ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo '<div class="card-container">';
                    $firstCategory = true; 
                    while ($row = $result->fetch_assoc()) {
                        $categoryName = htmlspecialchars($row['category_name']); 
                        $activeClass = $firstCategory ? 'active' : ''; 
                        echo '<div class="card">
                                <button class="' . $activeClass . '" onclick="loadCategory(\'' . $categoryName . '\', this)">' . $categoryName . '</button>
                            </div>';
                        $firstCategory = false; 
                    }
                    echo '</div>';
                } else {
                    echo "<p>No categories found</p>";
                }
            ?>
        </div>

        <div class="display-menu" id="displayMenu">             
            <!-- Menu content loaded by AJAX -->
        </div>

        <!-- Cart Popup -->
        <div id="cartPopup" class="cart-popup-modal">
            <div class="cart-popup-content">
                <button class="close" onclick="closeCart()">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
                <h2>Your Cart</h2>
                <div id="cartItems">
                    <?php
                        if (count($_SESSION['cart']) > 0) {
                            $total = 0;
                            foreach ($_SESSION['cart'] as $index => $item) {
                                $total += $item['price'] * $item['quantity'];
                                echo "<div class='cart-item'>
                                        <span>{$item['name']} x {$item['quantity']}</span>
                                        <span>RM" . number_format($item['price'] * $item['quantity'], 2) . "</span>
                                        <form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
                                            <input type='hidden' name='action' value='remove'>
                                            <input type='hidden' name='index' value='{$index}'>
                                            <button type='submit'>Remove</button>
                                        </form>
                                      </div>";
                            }
                            echo "<div class='cart-total'>
                                    <h3>Total: <span>RM" . number_format($total, 2) . "</span></h3>
                                  </div>";
                        } else {
                            echo "<p>Your cart is empty.</p>";
                        }
                    ?>
                </div>
                <button class="checkout-button" onclick="checkout()">Checkout</button>
            </div>
        </div>

        <script>
            // Update the cart quantity dynamically
            document.getElementById('cartQuantity').textContent = <?php echo count($_SESSION['cart']); ?>;
        </script>
    </body>
</html>
