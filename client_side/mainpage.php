<?php 
include "../connectdb.php";
include "session.php";
$totalQuantity = isset($_SESSION['total_quantity']) ? $_SESSION['total_quantity'] : 0;
$tableNumber = isset($_SESSION['table_number']) ? $_SESSION['table_number'] : null;

if ($tableNumber === null) {
    echo "No table number found. Please select a table on the previous page.";
    exit;
}

date_default_timezone_set("Asia/Kuala_Lumpur");
$currentDateTime = date("Y-m-d H:i:s");
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

                // Get the display menu element
                var displayMenu = document.getElementById("displayMenu");

                // Add fade-out animation to the current content
                displayMenu.classList.add("fade-out");

                // Wait for the fade-out animation to complete before fetching and updating content
                displayMenu.addEventListener(
                    "animationend",
                    function handleFadeOut() {
                        // Remove fade-out class after animation
                        displayMenu.classList.remove("fade-out");

                        // Load the category's food menu via AJAX
                        if (categoryName === "") {
                            displayMenu.innerHTML = "";
                            displayMenu.classList.add("fade-in"); // Trigger fade-in animation
                            return;
                        }

                        xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function () {
                            if (this.readyState == 4 && this.status == 200) {
                                // Update the content
                                displayMenu.innerHTML = this.responseText;

                                // Add fade-in animation to new content
                                displayMenu.classList.add("fade-in");

                                // Remove fade-in class after animation
                                displayMenu.addEventListener(
                                    "animationend",
                                    function handleFadeIn() {
                                        displayMenu.classList.remove("fade-in");
                                        displayMenu.removeEventListener("animationend", handleFadeIn);
                                    }
                                );
                            }
                        };
                        xhttp.open("GET", "display_menu.php?q=" + encodeURIComponent(categoryName), true);
                        xhttp.send();

                        // Remove the event listener to avoid multiple triggers
                        displayMenu.removeEventListener("animationend", handleFadeOut);
                    }
                );
            }


            // Automatically load the first category when the page loads
            document.addEventListener("DOMContentLoaded", function() {
                var firstButton = document.querySelector(".card button");
                if (firstButton) {
                    firstButton.click(); // Simulate a click to load the first category
                }
            });

            //popup in display_menu.php cause i hate myself enough to do this


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

            setInterval(() => {
                fetch('get_cart_quantity.php')
                    .then(response => response.json())
                    .then(data => {
                        const cartBadge = document.querySelector('.cart-badge');
                        if (data.total_quantity > 0) {
                            if (!cartBadge) {
                                const badge = document.createElement('span');
                                badge.className = 'cart-badge';
                                badge.textContent = data.total_quantity;
                                document.querySelector('.cart-button').appendChild(badge);
                            } else {
                                cartBadge.textContent = data.total_quantity;
                            }
                        } else if (cartBadge) {
                            cartBadge.remove();
                        }
                    });
            }, 5000);


            function searchDishes() {
                var searchQuery = document.getElementById('searchInput').value;

                // Clear the display menu content if search query is empty
                if (searchQuery === '') {
                    document.getElementById('displayMenu').innerHTML = '';
                    return;
                }

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Update the content with the search results
                        document.getElementById('displayMenu').innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "search_dishes.php?q=" + encodeURIComponent(searchQuery), true);
                xhttp.send();
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
                    <button class="cart-button">
                        <a href="shopping_cart.php">
                            <img src="../images/icon-library/cart-48.png">
                            <?php if ($totalQuantity > 0): ?>
                                <span class="cart-badge"><?php echo $totalQuantity; ?></span>
                            <?php endif; ?>
                        </a>
                    </button>
                </div>
            </div>

            <div class="search-center">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search dishes..." onkeyup="searchDishes()">
                </div>
            </div>

            <!-- END OF TABLE AND CART -->


            <div class="category-content-wrapper">
                <div class="fade-in" id="category-content">
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
            </div>


        </div>

        <div class="display-menu" id="displayMenu">            

        </div>




        <script src="cart.js"></script>

    </body>
</html>