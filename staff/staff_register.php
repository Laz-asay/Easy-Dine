<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="staff_register.css">
        <link rel="stylesheet" href="../navigation.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <title>Staff Register</title>
    </head>

    <body>
        <div class="register-container">
            <div class="register-box">
                <h2>Staff Register</h2>
                <form action="register.php" method="POST" class="register-form">
                    <div class="register-details-container">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" required autofocus class="register-input">

                        <label for="username">Username</label>
                        <input type="text" name="username" required class="register-input">

                        <label for="password">Password</label>
                        <input type="password" name="password" required class="register-input">

                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" required class="register-input">
                    </div>

                    <button type="submit" class="register-button">Register</button>
                </form>

                <div class="login-option">
                        Already a staff? 
                        <a href="staff_login.php" class="login-link">Login</a>
                </div>
            </div>
        </div>
    </body>
</html>
