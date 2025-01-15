<?php 
    include "../connectdb.php";
    
    $tableNumber = isset($_GET['table_number']) ? $_GET['table_number'] : null;
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
                if (categoryName == "") {
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
            document.addEventListener("DOMContentLoaded", function () {
                var firstButton = document.querySelector(".card button");
                if (firstButton) {
                    firstButton.click(); // Simulate a click on the first category
                }
            });

        </script>
    </head>
    <body>
        <div class="head-menu">
            <!-- TABLE AND CART -->
            <div class="top-menu">
                <div class="table-number">
                    <h1>Table <span class="table-number-colour"><?php echo htmlspecialchars($tableNumber); ?></span></h1>
                    <a>Let's get your order</a>
                </div>
                <div class="shopping-cart">
                    <button class="cart-button">
                        <a href="shopping_cart.php"><img src="../images/icon-library/cart-48.png"></a>
                    </button>
                </div>
            </div>
            <!-- Categories -->
            <?php 
                $firstCategoryName = null;

                $sql = "SELECT * FROM food_category ORDER BY Category_ID ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo '<div class="card-container">';
                    while ($row = $result->fetch_assoc()) {
                        $categoryName = htmlspecialchars($row['category_name']);
                        if (!$firstCategoryName) {
                            $firstCategoryName = $categoryName; // Capture the first category name
                        }
                        echo '<div class="card">
                                <button onclick="loadCategory(\'' . $categoryName . '\', this)">' . $categoryName . '</button>
                            </div>';
                    }
                    echo '</div>';

                } else {
                    echo "<p>No categories found</p>";
                }
            ?>

        </div>

        <div id="displayMenu" class="display-menu">
            <p>Loading Menu...</p>
        </div>

        <script src="cart.js"></script>

    </body>
</html>
