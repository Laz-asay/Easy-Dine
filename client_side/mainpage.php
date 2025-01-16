<?php 
include "../connectdb.php";
include "session.php";
$tableNumber = isset($_SESSION['table_number']) ? $_SESSION['table_number'] : null;

if ($tableNumber === null) {
    echo "No table number found. Please select a table on the previous page.";
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
            function loadCategory(categoryName, buttonElement) {
                var xhttp;

                // Clear the active state from all buttons
                var buttons = document.querySelectorAll(".card button");
                buttons.forEach(function(button) {
                    button.classList.remove("active");
                });

                // Add the active class to the clicked button
                buttonElement.classList.add("active");

                // Load the category's food menu via AJAX
                if (categoryName === "") {
                    document.getElementById("displayMenu").innerHTML = "";
                    return;
                }
                xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("displayMenu").innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "display_menu.php?q=" + encodeURIComponent(categoryName), true);
                xhttp.send();
            }

            // Automatically load the first category when the page loads
            document.addEventListener("DOMContentLoaded", function() {
                var firstButton = document.querySelector(".card button");
                if (firstButton) {
                    firstButton.click(); // Simulate a click to load the first category
                }
            });

            //popup in display_menu.php cause i hate myself enough to do this
            function openPopup(dishName, dishDesc, dishPrice, dishImage) {
                var popup = document.getElementById('dishPopup');
                
                // Set the content of the popup
                document.getElementById('popupDishName').textContent = dishName;
                document.getElementById('popupDishDesc').textContent = dishDesc;
                document.getElementById('popupDishPrice').textContent = 'RM' + dishPrice;
                document.getElementById('popupDishImage').src = dishImage;

                // Show the popup and apply the fade-in effect
                popup.style.display = "block";
                popup.classList.add('fade-in');
                
                // Remove the fade-in class after the animation is complete
                setTimeout(function() {
                    popup.classList.remove('fade-in');
                }, 500); // Match the animation duration
            }


            window.onclick = function(event) {
                if (event.target == document.getElementById('dishPopup')) {
                    closePopup();
                }
            }

            function closePopup() {
                var popup = document.getElementById('dishPopup');
                
                // Add the fade-out class to trigger the animation
                popup.classList.add('fade-out');
                
                // Wait for the animation to complete before hiding the popup
                setTimeout(function() {
                    popup.style.display = "none"; // Hide the popup after animation
                    popup.classList.remove('fade-out'); // Remove the fade-out class for future use
                }, 500); // Timeout duration should match the animation duration (500ms)
            }


            /////////////////////////////////////////////// CART ITEM /////////////////////////////////////////////////////////

            // Array to store cart items (you may initialize this globally if not already defined)
            let cart = [];

            function addToCart() {
                // Get the item details from the popup
                const dishName = document.getElementById("popupDishName").textContent;
                const dishPrice = parseFloat(document.getElementById("popupDishPrice").textContent.replace("RM", ""));

                // Check if the item is already in the cart
                const existingItem = cart.find(item => item.name === dishName);

                if (existingItem) {
                    // If the item is already in the cart, increase its quantity
                    existingItem.quantity += 1;
                } else {
                    // Otherwise, add the new item to the cart
                    cart.push({ name: dishName, price: dishPrice, quantity: 1 });
                }

                // Update the cart badge with the total quantity of items
                const totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);
                updateCartQuantity(totalQuantity);

                // Show notification
                showNotification(`${dishName} has been added to your cart.`);

                // Close the dish popup
                closePopup();
            }

            function openCart() {
                const cartPopup = document.getElementById("cartPopup");
                const cartItemsContainer = document.getElementById("cartItems");
                const cartTotal = document.getElementById("cartTotal");

                // Clear the current cart display
                cartItemsContainer.innerHTML = "";

                // If the cart is empty, display a message
                if (cart.length === 0) {
                    cartItemsContainer.innerHTML = "<p>Your cart is empty.</p>";
                    cartTotal.textContent = "RM0.00";
                } else {
                    // Otherwise, display the cart items
                    let total = 0;

                    cart.forEach((item, index) => {
                        total += item.price * item.quantity;

                        const cartItem = document.createElement("div");
                        cartItem.className = "cart-item";
                        cartItem.innerHTML = `
                            <span>${item.name} x ${item.quantity}</span>
                            <span>RM${(item.price * item.quantity).toFixed(2)}</span>
                            <button onclick="removeFromCart(${index})">Remove</button>
                        `;
                        cartItemsContainer.appendChild(cartItem);
                    });

                    // Update the total
                    cartTotal.textContent = `RM${total.toFixed(2)}`;
                }

                // Display the popup
                cartPopup.style.display = "block";
            }

            function closeCart() {
                document.getElementById("cartPopup").style.display = "none";
            }

            function updateCartQuantity(quantity) {
                const cartBadge = document.getElementById('cartQuantity');

                if (quantity > 0) {
                    cartBadge.textContent = quantity; // Set the quantity
                    cartBadge.style.display = "flex"; // Make sure it's visible
                } else {
                    cartBadge.style.display = "none"; // Hide the badge if the cart is empty
                }
            }

            document.addEventListener("DOMContentLoaded", function () {
                const cart = JSON.parse(sessionStorage.cart || "[]");
                updateCartQuantity(cart.length);
            });

            function removeFromCart(index) {
                // Remove the item at the specified index
                cart.splice(index, 1);

                // Refresh the cart display
                openCart();
            }

            function checkout() {
                if (cart.length === 0) {
                    alert("Your cart is empty. Add some items before checking out.");
                    return;
                }

                // You can handle checkout logic here (e.g., send the cart data to the server)
                alert("Thank you for your order!");
                cart = []; // Clear the cart
                closeCart(); // Close the cart popup
            }

            // Function to show a notification at the bottom of the page
            function showNotification(dishName) {
                // Create a div for the notification
                const notification = document.createElement('div');
                notification.classList.add('notification');
                notification.textContent = `${dishName} has been added to your cart.`;

                // Append the notification to the body
                document.body.appendChild(notification);

                // Trigger the animation by adding the 'show' class
                setTimeout(() => {
                    notification.classList.add('show');
                }, 10); // Slight delay to allow animation to trigger

                // Remove the notification after 3 seconds
                setTimeout(() => {
                    notification.classList.remove('show');
                    // Remove the notification from the DOM after it fades out
                    setTimeout(() => {
                        notification.remove();
                    }, 500); // Wait for fade-out to complete before removing
                }, 3000); // 3 seconds for the notification to stay visible
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
                        <span id="cartQuantity" class="cart-badge">0</span>
                    </button>
                </div>
            </div>
            <!-- END OF TABLE AND CART -->


            <?php 
                $sql = "SELECT * FROM food_category ORDER BY Category_ID ASC"; // Order by category_name
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

        </div>

        <!-- Cart Popup -->
        <div id="cartPopup" class="cart-popup-modal">
            <div class="cart-popup-content">
                <button class="close" onclick="closeCart()">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
                <h2>Your Cart</h2>
                <div id="cartItems">
                    <!-- Items will be displayed here dynamically -->
                </div>
                <div class="cart-total">
                    <h3>Total: <span id="cartTotal">RM0.00</span></h3>
                </div>
                <button class="checkout-button" onclick="checkout()">Checkout</button>
            </div>
        </div>


        <script src="cart.js"></script>

    </body>
</html>
