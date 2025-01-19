<?php
// Start the session
session_start();

// Include database connection
include("../connectdb.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $staff_name = mysqli_real_escape_string($conn, $_POST['staff_name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the username is an admin
    $sql_admin = "SELECT * FROM admin WHERE username = '$staff_name'";
    $result_admin = mysqli_query($conn, $sql_admin);

    // Check if a matching admin is found
    if (mysqli_num_rows($result_admin) > 0) {
        // Fetch the admin details
        $row_admin = mysqli_fetch_assoc($result_admin);

        // Compare the entered password directly with the stored password (plain text comparison for admin)
        if ($password == $row_admin['password']) {
            // Set session variables for admin and redirect to the admin page
            $_SESSION['admin_username'] = $row_admin['username'];
            header("Location: ../MainMenu/menu.php");  // Redirect to the admin page
            exit();
        } else {
            // Incorrect password for admin
            $_SESSION['error'] = "Wrong Password for Admin.";
            header("Location: staff_login.php");  // Redirect back to the login page
            exit();
        }
    } else {
        // Admin not found, check for staff
        $sql_staff = "SELECT * FROM staff WHERE staff_name = '$staff_name'";
        $result_staff = mysqli_query($conn, $sql_staff);

        // Check if a matching staff member is found
        if (mysqli_num_rows($result_staff) > 0) {
            // Fetch the staff details
            $row_staff = mysqli_fetch_assoc($result_staff);

            // Verify the password for staff (using password_verify for hashed password)
            if (password_verify($password, $row_staff['password'])) {
                // Set session variables for staff and redirect to the staff dashboard
                $_SESSION['staff_name'] = $row_staff['staff_name'];
                header("Location: staff_dashboard.php");  // Redirect to the staff dashboard
                exit();
            } else {
                // Incorrect password for staff
                $_SESSION['error'] = "Wrong Password for Staff.";
                header("Location: staff_login.php");  // Redirect back to the login page
                exit();
            }
        } else {
            // Neither staff nor admin found
            $_SESSION['error'] = "No staff or admin found with that username.";
            header("Location: staff_login.php");  // Redirect back to the login page
            exit();
        }
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
