<?php
include "../connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_tables']) && isset($_POST['tables_to_delete']) && !empty($_POST['tables_to_delete'])) {
        // Delete selected tables
        $tablesToDelete = $_POST['tables_to_delete'];

        foreach ($tablesToDelete as $tableNumberToDelete) {
            if (filter_var($tableNumberToDelete, FILTER_VALIDATE_INT) && $tableNumberToDelete > 0) {
                // Get the corresponding Table_ID for the table_number
                $stmtSelect = $conn->prepare("SELECT Table_ID FROM tablelist WHERE table_number = ?");
                $stmtSelect->bind_param("i", $tableNumberToDelete);
                $stmtSelect->execute();
                $stmtSelect->bind_result($tableID);
                $stmtSelect->fetch();
                $stmtSelect->close();

                if ($tableID) {
                    // Delete from orderlist based on the Table_ID
                    $stmtDeleteOrders = $conn->prepare("DELETE FROM orderlist WHERE Table_ID = ?");
                    $stmtDeleteOrders->bind_param("i", $tableID);
                    $stmtDeleteOrders->execute();
                    $stmtDeleteOrders->close();
                }

                // Delete the table from tablelist
                $stmtDeleteTable = $conn->prepare("DELETE FROM tablelist WHERE table_number = ?");
                $stmtDeleteTable->bind_param("i", $tableNumberToDelete);
                $stmtDeleteTable->execute();
                $stmtDeleteTable->close();

                // Delete the QR code file
                $qrCodeFilePath = "../images/QR/table_{$tableNumberToDelete}.png";
                if (file_exists($qrCodeFilePath)) {
                    unlink($qrCodeFilePath); // Delete the QR code file
                }
            }
        }

        // After deletion, renumber remaining tables and rename QR codes
        $result = $conn->query("SELECT table_number FROM tablelist ORDER BY table_number");
        $newTableNumber = 1;

        while ($row = $result->fetch_assoc()) {
            $stmtUpdate = $conn->prepare("UPDATE tablelist SET table_number = ? WHERE table_number = ?");
            $stmtUpdate->bind_param("ii", $newTableNumber, $row['table_number']);
            $stmtUpdate->execute();

            // Rename the QR code file to match the new table number
            $oldQrCodeFilePath = "../images/QR/table_{$row['table_number']}.png";
            $newQrCodeFilePath = "../images/QR/table_{$newTableNumber}.png";

            if (file_exists($oldQrCodeFilePath)) {
                rename($oldQrCodeFilePath, $newQrCodeFilePath); // Rename the QR code file
            }

            $newTableNumber++;
            $stmtUpdate->close();
        }

        echo "<script>alert('Selected tables and their orders have been deleted. Table numbers and QR codes have been updated.'); window.location.href = 'menu.php';</script>";
    } elseif (isset($_POST['delete_all'])) {
        // Delete all orders first
        $conn->query("DELETE FROM orderlist");

        // Delete all tables
        $stmt = $conn->prepare("DELETE FROM tablelist");
        if ($stmt->execute()) {
            // Delete all QR codes
            $qrCodeFiles = glob("../images/QR/table_*.png");
            foreach ($qrCodeFiles as $file) {
                unlink($file); // Delete the QR code images
            }

            echo "<script>alert('All tables and their orders have been deleted.'); window.location.href = 'menu.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('No tables selected for deletion.'); window.location.href = 'menu.php';</script>";
    }
}
?>
