<?php
include "session.php";


// Initialize the cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];

// Handle item removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $removeName = $_POST['remove_item'];

    // Remove the item from the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['name'] === $removeName) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
            break;
        }
    }

    // Redirect to avoid form resubmission
    header("Location: shopping_cart.php");
    exit;
}

$totalQuantity = 0; // Initialize total quantity

// Calculate the total quantity of items in the cart
foreach ($cart as $item) {
    $totalQuantity += $item['quantity'];
}

$_SESSION['total_quantity'] = $totalQuantity; // Store total quantity in session


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="shopping_cart.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="cart-container">
        <h1>Shopping Cart</h1>

        <?php if (empty($cart)): ?>
            <div class="cart-empty">
                <p class="empty-cart">Your cart is empty. </p>
                <a href="mainpage.php" class="back-to-menu">Go back to menu</a>
            </div>
        <?php else: ?>
            <div class="swipe-remove">
                <p class="swipe-info">(Swipe box to the left to remove)</p>
            </div>
            <?php

            $total = 0;
            foreach ($cart as $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>  
                <div class="cart-item" data-name="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="item-info">
                        <p><strong><?php echo htmlspecialchars($item['name']); ?></strong></p>
                        <p>RM<?php echo number_format($item['price'], 2); ?> x <?php echo htmlspecialchars($item['quantity']); ?> = RM<?php echo number_format($subtotal, 2); ?></p>
                    </div>
                    <div class="remove-overlay">Swipe to Remove</div>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary">
                <p>Total: RM<?php echo number_format($total, 2); ?></p>
            </div>

            <div class="cart-filled-options">
                <div class="cart-buttons">
                    <div class="cart-buttons-vertical">
                        <a href="mainpage.php">
                            <button class="checkout-btn">
                                Continue Browsing   
                            </button>
                        </a>
                        <form action="checkout.php" method="POST" style="display: inline;">
                            <button type="submit" class="checkout-btn">Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>




    <form id="removeForm" action="shopping_cart.php" method="POST" style="display: none;">
        <input type="hidden" name="remove_item" id="removeItemInput">
    </form>

    <script>
        document.querySelectorAll('.cart-item').forEach(item => {
            let startX; // Starting X-coordinate of the touch
            let currentX; // Current X-coordinate during the swipe
            let threshold = 220; // Minimum distance to trigger removal
            let swipeSpeed = 0.3; // Transition duration for swipe animation (seconds)

            item.addEventListener('touchstart', e => {
                startX = e.touches[0].clientX;
                item.style.transition = `transform ${swipeSpeed}s ease-out`;
            });

            item.addEventListener('touchmove', e => {
                currentX = e.touches[0].clientX;
                const diffX = startX - currentX;

                if (diffX > 0) {
                    // Swipe to the left
                    item.style.transform = `translateX(-${Math.min(diffX, threshold)}px)`;
                }
            });

            item.addEventListener('touchend', () => {
                const diffX = startX - currentX;

                if (diffX > threshold) {
                    // If swipe distance exceeds threshold, remove item
                    item.style.transition = `transform ${swipeSpeed}s ease-out`; // Reset transition
                    item.style.transform = `translateX(-100%)`; // Swipe completely out

                    // Delay removal form submission to allow animation to complete
                    setTimeout(() => {
                        const itemName = item.getAttribute('data-name');
                        document.getElementById('removeItemInput').value = itemName;
                        document.getElementById('removeForm').submit();
                    }, swipeSpeed * 1000); // Convert seconds to milliseconds
                } else {
                    // Reset position if swipe is insufficient
                    item.style.transition = `transform ${swipeSpeed}s ease-out`;
                    item.style.transform = `translateX(0)`;
                }
            });
        });

        const totalQuantity = <?php echo json_encode($totalQuantity); ?>;
        fetch('update_cart_quantity.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ total_quantity: totalQuantity })
        });


    </script>

</body>
</html>
