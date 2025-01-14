<?php 

require "../vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

include "../connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numberOfTables = $_POST['number_of_tables'];

    if (filter_var($numberOfTables, FILTER_VALIDATE_INT) && $numberOfTables > 0) {
        echo "Number of Tables: $numberOfTables<br><br>";

        $stmt = $conn->prepare("INSERT INTO tablelist (table_number, qr_code) VALUES (?, ?)");

        for ($i = 1; $i <= $numberOfTables; $i++) {
            $tableNumber = $i;

            $qrCodeUrl = "http://localhost:8050/table_page.php?table_number=$tableNumber";
            $qrCode = new QrCode($qrCodeUrl);

            $writer = new PngWriter();
            
            $qrCodeFilePath = "../images/QR/table_{$tableNumber}.png"; 
            
            $writer->write($qrCode)->saveToFile($qrCodeFilePath);

            $stmt->bind_param("is", $tableNumber, $qrCodeFilePath);
            $stmt->execute();

            echo "<img src='../images/QR/table_{$tableNumber}.png' alt='QR Code for Table $tableNumber'><br>";
        }

        $stmt->close();

        echo
        "<script>
            alert('New tables created successfully!'); window.location.href = 'menu.php';
        </script>";
    } else {
        echo "Please enter a valid number of tables!";
    }
}
?>
