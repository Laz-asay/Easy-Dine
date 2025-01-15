<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="../navigation.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
        crossorigin="anonymous">
    </script>
    
    <script>
        function toggleSection(sectionId) {
            // Get the element by its ID
            var section = document.getElementById(sectionId);

            // Check the current display style and toggle it
            if (section.style.display === "none" || section.style.display === "") {
                section.style.display = "block";  // Show the section
            } else {
                section.style.display = "none";   // Hide the section
            }
        }

        function loadCategory(str) {
            var xhttp;
            if (str == "") {
                document.getElementById("displayMenu").innerHTML = "";
                return;
            }
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                document.getElementById("displayMenu").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "display_menu.php?q=" + str, true);
            xhttp.send();
        }
    </script>
    <script src="script2.js"></script>


    
</head>
<body>

    <!----- SIDEBAR CAUSE INCLUDING ANOTHER PAGE IS FOR LOSERS ----->
    <div class="sidebar">
        <div class="sidebar-header">
            <label class="admin-name">Admin</label>
        </div>

        <hr>

        <?php
        include "../connectdb.php";
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
                <button type="button" class="category-edit" onclick="showPopup('edit-category-popup')">
                    <i class="fa fa-edit"></i>Edit Category
                </button>

                <button type="button" class="category-edit" onclick="showPopup('add-category-popup')">
                    <i class="fa-solid fa-plus"></i>Add Category
                </button>

                <button type="button" class="category-edit" onclick="showPopup('delete-category-popup')">
                    <i class="fa fa-trash"></i>Delete Category
                </button>
            </div>

            <!-- Popup Containers -->

            <!-- Edit Category Popup -->
            <div id="edit-category-popup" class="popup-overlay" onclick="closePopup(event, 'edit-category-popup')">
                <div class="popup-content" onclick="event.stopPropagation()">
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

                        <button type="submit" class="edit-button"><i class="fa fa-edit"></i>Edit Category</button>
                    </form>
                </div>
            </div>

            <!-- Add Category Popup -->
            <div id="add-category-popup" class="popup-overlay" onclick="closePopup(event, 'add-category-popup')">
                <div class="popup-content" onclick="event.stopPropagation()">
                    <h3>Add New Category</h3>
                    <form method="post" action="../addcategory.php">
                        <input type="text" name="category_name" placeholder="Category Name" required>
                        <button type="submit" name="add">Add Category</button>
                    </form>
                </div>
            </div>

            <!-- Delete Category Popup -->
            <div id="delete-category-popup" class="popup-overlay" onclick="closePopup(event, 'delete-category-popup')">
                <div class="popup-content" onclick="event.stopPropagation()">
                    <h3>Delete Category</h3>
                    <form method="post" action="../deletecategory.php" class="category-form">
                        <label for="category_name">Select Category to Delete:</label>
                        <select name="category_name" id="category_name" required>
                            <option value="" disabled selected>Choose a category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this category?');">
                            <i class="fa fa-trash"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        
            <hr>





    </div>     


    <!--------------------------------------------- MAIN CONTENT OVER HERE ---------------------------------->
    <div class="main-content">
        


        <div class="table-management">
            <!------ Add Tables------>
            <div class="table-button">
                <form action="add_table.php" method="POST" id="add-table-form">
                    <label for="number_of_tables">Number of Tables</label>
                    <input type="number" id="number_of_tables" name="number_of_tables" min="1" required>
                    <button type="submit" class="table-edit" id="add-table">Add Tables</button>
                </form> 
            </div>

            <script>
                const numberOfTablesInput = document.getElementById('number_of_tables');

                numberOfTablesInput.addEventListener('input', function () {
                    const value = this.value;
                    const validValue = value.replace(/[^0-9]/g, '');

                    if (validValue && !isNaN(validValue) && validValue > 0) {
                        this.value = Math.floor(validValue);
                    } else {
                        this.value = '';
                    }
                });
            </script>

            <!------ Delete Tables------>
            <div class="table-button">
                <h3>Delete Tables</h3>
                <form method="POST" action="delete_table.php" onsubmit="return confirmDeletion()">
                    <label for="tables_to_delete">Select Tables to Delete:</label><br>
                    <?php
                    include "../connectdb.php";
                    $result = $conn->query("SELECT table_number FROM tablelist");

                    while ($row = $result->fetch_assoc()) {
                        echo "<input type='checkbox' name='tables_to_delete[]' value='" . $row['table_number'] . "'> Table " . $row['table_number'] . "<br>";
                    }
                    ?>
                    <button type="submit" name="delete_tables">Delete Selected Tables</button>
                    <button type="submit" name="delete_all" id="delete_all" onclick="return confirmAllDeletion()">Delete All Tables</button>
                </form>
            </div>

            <script>
                function confirmDeletion() {
                    return confirm("Are you sure you want to delete the selected tables?");
                }

                function confirmAllDeletion() {
                    return confirm("Are you sure you want to delete all tables?");
                }
            </script>

        </div>
                            
        <!----- POPUP BUTTON ---->
        <button class="open-popup" onclick="openForm()">Add Dish</button>

        <!-- -------------------------ADD DISH FORM ----------------------------->
        <!-- Popup Form -->
        <div class="popup-container">
            <div id="form-popup" class="form-popup">
                <div class="form-background">
                    <form action="addmenu.php" method="post" enctype="multipart/form-data" form-container>
                        <h1>Add Dish</h1>
                        <div class="proper-order-form">
                            <input type="text" name="dish_name" id="dish_name" placeholder="Dish Name" required>

                            <div class="file-upload">
                                <div class="custom-file-input">
                                    <label for="dish_image" class="file-label">Upload Dish Image</label>
                                    <input type="file" id="dish_image" name="dish_image" required>
                                </div>
                                <span id="file-name">No file chosen</span> 
                            </div>


                            
                            <input type="number" name="dish_price" id="dish_price" placeholder="Dish Price" step="0.01" min="0" required>

                            <textarea name="dish_desc" id="dish_desc" rows="4" cols="20" placeholder="Dish Description" required></textarea>

                            <select name="dish_category" id="dish_category" required>
                                <option value="" disabled selected>Choose a category</option>
                                <?php
                                $sql = "SELECT category_name FROM food_category"; 
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($row['category_name']) . '">' . htmlspecialchars($row['category_name']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="" disabled>No categories available</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="add-dish-buttons">
                            <input type="hidden" name="dish_availability" value="1" required>
                            <input type="submit" name="submit" value="Submit">
                            <input type="reset" value="Reset">

                            <button class="close-popup" onclick="closeForm()" type="button">Close</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>

        <script>
            function openForm() {
            document.getElementById("form-popup").style.display = "flex";
            }

            function closeForm() {
            document.getElementById("form-popup").style.display = "none";
            }

            document.getElementById("dish_image").addEventListener("change", function() {
                const fileName = document.getElementById("file-name");
                fileName.textContent = this.files[0] ? this.files[0].name : "No file chosen";
            });


        </script>
        <!---------------------- END OF ADD DISH FORM ------------------------->


        <div id="displayMenu">
            
        </div>



    
    </div>    
</body>
</html>
