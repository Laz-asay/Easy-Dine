<?php
include "../connectdb.php";

if (isset($_GET['q'])) {
    $categoryName = $_GET['q'];

    $stmt = $conn->prepare("SELECT * FROM menu WHERE dish_category = ? AND dish_availability = 'available'");
    $stmt->bind_param("s", $categoryName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dishName = htmlspecialchars($row['dish_name']);
            $dishImage = "../images/mainmenu/" . $row['dish_image'];
            $dishPrice = htmlspecialchars($row['dish_price']);
            $dishDesc = htmlspecialchars($row['dish_desc']);
?>
                <div class="menu-box">
                    <div class="img-and-details">
                        <img src="<?php echo $dishImage; ?>" alt="<?php echo $dishName; ?>" class="dish-image">
                        <div class="details">
                            <div class="name-and-price">
                                <h2 class="dish-name"><?php echo $dishName; ?></h2>
                                <p class="dish-price">RM<?php echo $dishPrice; ?></p>
                            </div>
                            <p class="dish-desc"><?php echo $dishDesc; ?></p>
                        </div>
                    </div>
                    <button class="add-to-cart">
                        <img src="../images/icon-library/plus-60.png" alt="Add to Cart">
                    </button>
                </div>
<?php

        }
    } else {
        echo "<p>No available dishes in this category.</p>";
    }

    $stmt->close();
}


$conn->close();
?>
    