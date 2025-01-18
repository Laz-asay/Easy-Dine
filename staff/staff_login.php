<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="staff_login.css">
        <link rel="stylesheet" href="../navigation.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </head>

    <body>

        <div class="login-container">
            <div class="login-box">
                <h2>Staff Login</h2>
                <form action="login.php" method="POST" class="login-form">
                    <div class="login-details-container">
                        <label for="username">Staff Name</label>
                        <input type="text" name="username" required autofocus class="login-input">

                        <label for="password">Password</label>
                        <input type="password" name="password" required class="login-input">
                    </div>

                    <button type="submit" class="login-button">Log In</button>
                </form>

                <div class="register-option">
                    Don't have account? 
                    <a href="staff_register.php" class="register-link">Register</a>
                </div>

            </div>
        </div>

    </body>
</html>