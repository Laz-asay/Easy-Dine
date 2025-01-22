<?php 
include "../connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dish_name = htmlspecialchars($_POST['dish_name']);
    $dish_price = htmlspecialchars($_POST['dish_price']);
    $dish_desc = htmlspecialchars($_POST['dish_desc']);
    $dish_category = htmlspecialchars($_POST['dish_category']);
    $dish_image = $_FILES['dish_image']; //receive image file

    if (isset($dish_image) && $dish_image['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']; // only allow these types of file
        $fileType = $dish_image['type'];
        
        if (in_array($fileType, $allowedTypes)) {
            $targetDir = "../images/mainmenu/"; //target folder directory to store images
            $fileName = uniqid() . "_" . basename($dish_image['name']); // store name of file here to link to database
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($dish_image['tmp_name'], $targetFile)) {
                $sql = "INSERT INTO menu (dish_name, dish_image, dish_price, dish_desc, dish_category) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("ssdss", $dish_name, $fileName, $dish_price, $dish_desc, $dish_category);

                    if ($stmt->execute()) {
                        echo "<script>alert('New record created successfully!');</script>";
                        echo "<meta http-equiv=\"refresh\" content=\"1;URL=menu.php\">";
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Error preparing statement: " . $conn->error;
                }
            } else {
                echo "Error uploading the image file.";
            }
        } else {
            echo "Invalid file type. Only JPG and PNG files are allowed.";
        }
    } else {
        echo "No image uploaded or there was an upload error.";
    }
}
$conn->close();
?>
