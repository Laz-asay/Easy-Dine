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
                        <a href="shopping_cart.php"><img src="../images/icon-library/cart-48.png"></a>
                    </button>
                </div>
            </div>
            <!-- TABLE AND CART -->


            <div class="scroll-dish">
                <div class="card"><h2>Main Dish</h2></div>
                <div class="card"><h2>Side Dish</h2></div>
                <div class="card"><h2>Beverage</h2></div>
            </div>
        </div>

        <div class="display-menu">            
                <?php 
                $sql = "SELECT * FROM menu WHERE dish_availability = 'available'"; 
                $result = $conn->query($sql);
        

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $dishName = htmlspecialchars($row['dish_name']);
                        $dishImage = "../images/mainmenu/" . $row['dish_image'];
                        $dishPrice = htmlspecialchars($row['dish_price']);
                        $dishDesc = htmlspecialchars($row['dish_desc']);
                ?>

                <div class="menu-box">
                    <div class="img-and-name">
                        <img src="<?php echo $dishImage; ?>" alt="<?php echo $dishName; ?>" class="dish-image">
                            <div class="name-and-price">
                                <h2><?php echo $dishName; ?></h2>
                                <p class="dish-price">RM<?php echo $dishPrice; ?></p>
                            </div>
                    </div>

                    <p class="dish-desc"><?php echo $dishDesc; ?></p>
                    <button class="add-to-cart">
                        <img src="../images/icon-library/plus-60.png" alt="Add to Cart">
                    </button>
                </div>

                <?php
                    }
                } else {
                    echo "<p>No available Main Dishes at the moment.</p>";
                }

                $conn->close();
                ?>          
        </div>
    </body>
</html>
