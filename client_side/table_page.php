<?php
include "session.php"; // Include session management

// Reset session for table_number only
if (isset($_GET['reset_session'])) {
    // Unset only the 'table_number' session variable
    if (isset($_SESSION['table_number'])) {
        unset($_SESSION['table_number']); // Clears only the table_number session variable
    }
    header("Location: table_page.php"); // Redirects to refresh the page
    exit();
}

// Handle table selection
if (isset($_POST['table_number'])) {
    $_SESSION['table_number'] = $_POST['table_number']; 
    header("Location: mainpage.php"); // Redirect to the main page
    exit();
}

include "../connectdb.php";
$sql = "SELECT table_number FROM tablelist";
$result = $conn->query($sql);

if ($result) {
    $tableNumbers = [];
    while ($row = $result->fetch_assoc()) {
        $tableNumbers[] = $row['table_number'];
    }
} else {
    echo "Error fetching table numbers.";
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Table</title>
</head>
<body>
    <h1>Select Your Table</h1>

    <!-- Table Selection Form -->
    <form method="POST" action="table_page.php">
        <label for="table_number">Choose a Table:</label>
        <select name="table_number" id="table_number" required>
            <option value="">Select a table</option>
            <?php
            // Populate the dropdown with table numbers from the database
            foreach ($tableNumbers as $tableNumber) {
                echo "<option value=\"$tableNumber\">Table $tableNumber</option>";
            }
            ?>
        </select>
        <button type="submit">Select Table</button>
    </form>

    <!-- Session Reset Button -->
    <br><br>
    <a href="table_page.php?reset_session=true">
        <button>Reset Table Selection</button>
    </a>

</body>
</html>
