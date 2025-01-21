<?php 

require "../vendor/autoload.php";
include "../connectdb.php";

// Fetching all tables and their QR codes
$sql = "SELECT table_number, qr_code FROM tablelist"; 
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="order_dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

    <title>Available Tables</title>
    <link rel="stylesheet" href="show_tables.css"> <!-- Link to external CSS -->
</head>
<body>
    <div class="back-menu">
        <a href="menu.php">
            <button class="logout"><i class="fa-solid fa-arrow-left"></i></button>
        </a>
    </div>

    <div class="container">
        <h1>Available Tables</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Table Number</th>
                        <th>QR Code</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['table_number']; ?></td>
                            <td><img src="<?php echo $row['qr_code']; ?>" alt="QR Code for Table <?php echo $row['table_number']; ?>"></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tables found.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2025 Easy-Dine. All Rights Reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
