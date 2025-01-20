<?php
include "session.php";
include "../connectdb.php";

if (isset($_GET['q'])) {
    $categoryName = $_GET['q'];

    $stmt = $conn->prepare("SELECT * FROM menu WHERE dish_category = ? AND deleted = 0");
    $stmt->bind_param("s", $categoryName);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<div class="menu-grid">';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dishName = htmlspecialchars($row['dish_name']);
            $dishImage = "../images/mainmenu/" . $row['dish_image'];
            $dishPrice = htmlspecialchars($row['dish_price']);

            echo '
                <div class="menu-box">
                    <form action="dish_details.php" method="GET">
                        <input type="hidden" name="name" value="' . $dishName . '">
                        <button type="submit" class="menu-box-btn">
                            <div class="img-and-name">
                                <img src="' . $dishImage . '" alt="' . $dishName . '" class="dish-image">
                                <div class="name-and-price">
                                    <h2 class="dish-name">' . $dishName . '</h2>
                                    <p class="dish-price">RM' . $dishPrice . '</p>
                                </div>
                            </div>
                        </button>
                    </form>
                </div>
            ';
        }
    } else {
        echo "<p>No available dishes in this category.</p>";
    }

    echo '</div>';
    $stmt->close();
}

$conn->close();
?>
