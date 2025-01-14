<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="display_menu.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </head>

    <body>
        <?php
            include "../connectdb.php";



            if (!isset($_GET['q']) || empty($_GET['q'])) {
                echo "Category not specified!";
                exit;
            }

            $sql = "SELECT dish_image, dish_name, dish_desc, dish_price FROM menu WHERE dish_category = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                echo "Error in preparing query: " . $conn->error;
                exit;
            }
            $stmt->bind_param("s", $_GET['q']);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 0) {
                echo "No dishes found in this category!";
                exit;
            }

            $stmt->bind_result($dish_image,$dish_name, $dish_desc, $dish_price);

            ?>

            <table cellpadding='10' cellspacing='0' class='menu-table'>
                <tr>
                    <th>Dish Image</th>
                    <th>Dish Name</th>
                    <th>Description</th>
                    <th>Price (RM)</th>
                    <th>Action</th>
                </tr>

                <?php while ($stmt->fetch()) {?>
                    <tr>
                        <td><img src="../images/mainmenu/<?php echo htmlspecialchars($dish_image); ?>" alt="<?php echo htmlspecialchars($dish_name); ?>" width="100"></td>
                        <td><?php echo htmlspecialchars($dish_name); ?></td>
                        <td><?php echo htmlspecialchars($dish_desc); ?></td>
                        <td><?php echo htmlspecialchars($dish_price); ?></td>
                        <td>
                            <!-- Edit Form -->
                            <form action="edit_dish.php" method="GET" style="display:inline;">
                                <input type="hidden" name="dish_name" value="<?php echo htmlspecialchars($dish_name); ?>">
                                <button type="submit" class="edit-menu">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                            </form>
                            
                            <!-- Delete Form -->
                            <form action="delete_dish.php" method="POST" style="display:inline;">
                                <input type="hidden" name="dish_name" value="<?php echo htmlspecialchars($dish_name); ?>">
                                <button type="submit" class="delete-menu" 
                                    onclick="return confirm('Are you sure you want to delete this dish?');"> 
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                <?php } ?>
            </table>

            <?php
            $stmt->close();

        ?>
    </body>
</html>


