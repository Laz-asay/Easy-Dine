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

        // Find the current highest table_number in the database
        $result = $conn->query("SELECT MAX(table_number) AS max_table_number FROM tablelist");
        $row = $result->fetch_assoc();
        $maxTableNumber = $row['max_table_number'] ?? 0; // Default to 0 if no tables exist

        // Prepare SQL statement to insert table and QR code information
        $stmt = $conn->prepare("INSERT INTO tablelist (table_number, qr_code) VALUES (?, ?)");

        for ($i = 1; $i <= $numberOfTables; $i++) {
            $tableNumber = $maxTableNumber + $i; // Increment from the current highest table number

            // Generate the URL for this table, pointing to start_session.php
            $qrCodeUrl = "http://localhost/Food/client_side/start_session.php?table=$tableNumber";

            // Create the QR code using the URL
            $qrCode = new QrCode($qrCodeUrl);
            $writer = new PngWriter();

            // Define the path to save the QR code image
            $qrCodeFilePath = "../images/QR/table_{$tableNumber}.png"; 

            // Save the QR code image to a file
            $writer->write($qrCode)->saveToFile($qrCodeFilePath);

            // Save the table number and QR code URL into the database
            $stmt->bind_param("is", $tableNumber, $qrCodeFilePath);
            $stmt->execute();
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