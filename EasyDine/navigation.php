<?php
include "connectdb.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="navigation.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    
    <script>
        // JavaScript to load the selected category's dishes using AJAX
        function loadCategory(category) {
            const menuContainer = document.getElementById("menu-container"); // Ensure this exists in menu.php

            // Create an XMLHttpRequest to fetch data from menu.php
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // Replace the content of the menu-container with the returned HTML
                    menuContainer.innerHTML = this.responseText;
                }
            };

            xhttp.open("GET", "MainMenu/menu.php?category=" + encodeURIComponent(category), true);
            xhttp.send();
        }
    </script>
    

</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <label class="admin-name">Admin</label>
        </div>

        <hr>

        <?php
        // Fetch categories
        $query = "SELECT category_name FROM food_category";
        $categories = [];
        if ($stmt = $conn->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $categories[] = htmlspecialchars($row['category_name']);
            }
            $stmt->close();
        }
        ?>

        <!-- Category Buttons -->
        <div class="categories">
            <div class="category-buttons">
                <?php foreach ($categories as $category): ?>
                    <button type="button" class="category-button" onclick="loadCategory('<?php echo $category; ?>')">
                        <?php echo $category; ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        
        <hr>
        

        <!-- Toggle Buttons -->
        <div class="category-editor-section">
            <button type="button" class="category-edit" onclick="toggleSection('add-category')">Add Category</button>
            <button type="button" class="category-edit" onclick="toggleSection('delete-category')">Delete Category</button>
            <button type="button" class="category-edit" onclick="toggleSection('edit-category')">Edit Category</button>
        </div>

        <!--- EDIT CATEGORY --->
        <div id="edit-category" class="edit-category" style="display: none;">
            <h3>Edit Category Name</h3>
            <form method="post" action="../editcategory.php" class="category-form">
                <label for="current_category">Select Category to Edit:</label>
                <select name="current_category" id="current_category" required>
                    <option value="" disabled selected>Choose a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                    <?php endforeach; ?>
                </select>
                
                <label for="new_category_name">New Category Name:</label>
                <input type="text" name="new_category_name" id="new_category_name" placeholder="New Category Name" required>
                
                <button type="submit" class="edit-button">Edit Category</button>
            </form>
        </div>

        <!-- Add Category Section -->
        <div id="add-category" class="create-category" style="display: none;">
            <form method="post" action="../addcategory.php">
                <h3>Add New Category</h3>
                <input type="text" name="category_name" placeholder="Category Name" required>
                <button type="submit" name="add">Add Category</button>
            </form>
        </div>

        <!-- Delete Category Section -->
        <div id="delete-category" class="delete-categories" style="display: none;">
            <h3>Delete Category</h3>
            <form method="post" action="../deletecategory.php" class="category-form">
                <label for="category_name">Select Category to Delete:</label>
                <select name="category_name" id="category_name" required>
                    <option value="" disabled selected>Choose a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category;?>"><?php echo $category;?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this category?');">Delete</button>
            </form>
        </div>

        <!--- Reports come here --->
        <div class="report-box">
            <button class="report-button">Report</button>
        </div>
    </div>            



</body>
</html>
