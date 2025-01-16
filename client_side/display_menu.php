<?php
session_start();
include "../connectdb.php";

if (isset($_GET['q'])) {
    $categoryName = $_GET['q'];

    $stmt = $conn->prepare("SELECT * FROM menu WHERE dish_category = ? AND dish_availability = 'available'");
    $stmt->bind_param("s", $categoryName);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<div class="menu-grid">';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dishName = htmlspecialchars($row['dish_name']);
            $dishImage = "../images/mainmenu/" . $row['dish_image'];
            $dishPrice = htmlspecialchars($row['dish_price']);
            $dishDesc = htmlspecialchars($row['dish_desc']);
            echo '  
                    <div class="menu-box" onclick="openPopup(\'' . $dishName . '\', \'' . $dishDesc . '\', \'' . $dishPrice . '\', \'' . $dishImage . '\')">
                        <div class="img-and-name">
                            <img src="' . $dishImage . '" alt="' . $dishName . '" class="dish-image">
                            <div class="name-and-price">
                                <h2 class="dish-name">' . $dishName . '</h2>
                                <p class="dish-price">RM' . $dishPrice . '</p>
                            </div>
                        </div>
                    </div>
                  ';
        }
        echo '</div>';
    } else {
        echo "<p>No available dishes in this category.</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!-- POP POP POP UP  -->
<div id="dishPopup" class="popup-modal">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <img id="popupDishImage" src="" alt="Dish Image" class="popup-dish-image">
        <h2 id="popupDishName"></h2>
        <p id="popupDishDesc"></p>
        <p id="popupDishPrice"></p>
        <button class="add-to-cart" onclick="addToCart()">
            <img src="../images/icon-library/plus-60.png">
        </button>
    </div>
</div>
