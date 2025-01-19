<?php
$errorMessage = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "Unknown error occurred.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>
<body>
    <h1>Error</h1>
    <p><?php echo $errorMessage; ?></p>
    <a href="start_session.php">Click here to scan the QR code again.</a>
</body>
</html>
