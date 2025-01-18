<?php
// Start the session to manage session variables
session_start();

// Check if there's an error message in the session and display it
if (isset($_SESSION['error'])) {
    // Store the error message in a JavaScript variable
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);  // Clear the session error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="staff_login2.css">
    <link rel="stylesheet" href="../navigation.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>

    <div class="login-box">
        <h2>Staff Login</h2>
        <form action="login.php" method="POST" class="login-form">
            <div class="login-details-container">
                <label for="staff_name">Staff Name</label>
                <input type="text" name="staff_name" required autofocus class="login-input">

                <label for="password">Password</label>
                <input type="password" name="password" required class="login-input">
            </div>

            <button type="submit" class="login-button">Log In</button>
        </form>
    </div>

    <!-- Notification HTML element -->
    <div id="notification" class="notification" style="display:none;"></div>

    <?php
    // Check if there is an error message and output it in JavaScript
    if (isset($error_message)) {
        echo "<script>
            // Display the notification with the error message
            const notification = document.getElementById('notification');
            notification.textContent = '" . addslashes($error_message) . "';
            notification.style.display = 'block';

            // Make the notification fade out after 3 seconds
            setTimeout(() => {
                notification.classList.add('fade-out');
            }, 3000);
        </script>";
    }
    ?>
</body>
</html>
