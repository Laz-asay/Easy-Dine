<?php 

require "../vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

include "../connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numberOfTables = $_POST['number_of_tables'];

    // Validate the input for the number of tables
    if (filter_var($numberOfTables, FILTER_VALIDATE_INT) && $numberOfTables > 0) {
        echo "Number of Tables: $numberOfTables<br><br>";

        // Prepare SQL statement to insert table and QR code information
        $stmt = $conn->prepare("INSERT INTO tablelist (table_number, qr_code_url, qr_code_file) VALUES (?, ?, ?)");

        for ($i = 1; $i <= $numberOfTables; $i++) {
            $tableNumber = $i;

            // Generate the URL for this table, pointing to start_session.php
            $qrCodeUrl = "http://localhost:8050/Food/client_side/start_session.php?table=$tableNumber";

            // Create the QR code using the URL
            $qrCode = new QrCode($qrCodeUrl);
            $writer = new PngWriter();

            // Define the path to save the QR code image
            $qrCodeFilePath = "../images/QR/table_{$tableNumber}.png"; 

            // Save the QR code image to a file
            $writer->write($qrCode)->saveToFile($qrCodeFilePath);

            // Save the table number, QR code URL, and file path into the database
            $stmt->bind_param("iss", $tableNumber, $qrCodeUrl, $qrCodeFilePath);
            $stmt->execute();

            // Display the generated QR code for verification
            echo "<img src='../images/QR/table_{$tableNumber}.png' alt='QR Code for Table $tableNumber'><br>";
            echo "Table $tableNumber QR Code URL: <a href='$qrCodeUrl' target='_blank'>$qrCodeUrl</a><br><br>";
        }

        $stmt->close();

        // Redirect back to the menu page with a success message
        echo
        "<script>
            alert('New tables created successfully!'); 
            window.location.href = 'menu.php';
        </script>";
    } else {
        // Handle invalid input for the number of tables
        echo "Please enter a valid number of tables!";
    }
}
?>
