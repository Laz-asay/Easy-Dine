<?php
include "session.php"; // Include session management
include "../connectdb.php";

if (isset($_GET['table']) && !empty($_GET['table'])) {
    $tableNumber = intval($_GET['table']); // Sanitize input

    // Validate table number against the database
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM tablelist WHERE table_number = ?");
    $stmt->bind_param("i", $tableNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // Valid table number, start the session
        $_SESSION['table_number'] = $tableNumber;
        $_SESSION['last_activity'] = time();
        header("Location: mainpage.php");
        exit();
    } else {
        echo "Invalid table number. Please scan the QR code again.";
        exit();
    }
} else {
    echo "Invalid table number. Please scan the QR code again.";
    exit();
}
?>
