<?php 

include "../connectdb.php";

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


        <div class="displayed-menu" id="cartItemsContainer">
            <p>Loading cart items...</p>
        </div>


        <div class="bottom-fixed">
            <div class="total-price">
                <div class="jumlah-harga">
                    <h1>Total Price</h1>
                    <?php echo "<h1>RM20</h1>"; ?>
                </div>
            </div>

            <div class="checkout-button">
                <button class="checkout">Checkout</button>
            </div>
        </div>


        <script>
            // Fetch and display cart items
            document.addEventListener("DOMContentLoaded", function () {
                const tableID = new URLSearchParams(window.location.search).get("table_id");
                if (!tableID) {
                    document.getElementById("cartItemsContainer").innerHTML = "<p>No table selected.</p>";
                    return;
                }

                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        const response = JSON.parse(this.responseText);

                        if (response.success) {
                            const items = response.items;
                            let html = "";

                            items.forEach(item => {
                                html += `
                                    <div class="cart-item">
                                        <img src="${item.dish_image}" alt="${item.dish_name}" class="dish-image">
                                        <div class="item-details">
                                            <h2>${item.dish_name}</h2>
                                            <p>Quantity: ${item.quantity}</p>
                                            <p>Price: RM${parseFloat(item.price).toFixed(2)}</p>
                                        </div>
                                    </div>
                                `;
                            });

                            document.getElementById("cartItemsContainer").innerHTML = html;
                        } else {
                            document.getElementById("cartItemsContainer").innerHTML = `<p>${response.message}</p>`;
                        }
                    }
                };

                xhttp.open("GET", "shopping_cart_backend.php?table_id=" + encodeURIComponent(tableID), true);
                xhttp.send();
            });
        </script>

    </body>
</html>