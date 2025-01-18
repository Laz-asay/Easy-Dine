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

    // Query the database to find the staff member
    $sql = "SELECT * FROM staff WHERE staff_name = '$staff_name'";
    $result = mysqli_query($conn, $sql);
    
    // Check if a matching staff member is found
    if (mysqli_num_rows($result) > 0) {
        // Fetch the staff details
        $row = mysqli_fetch_assoc($result);
        
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Set session variables and redirect to the staff dashboard (or other page)
            $_SESSION['staff_name'] = $row['staff_name'];
            header("Location: staff_dashboard.php");  // Redirect to the dashboard (or other page)
            exit();
        } else {
            // Incorrect password
            $_SESSION['error'] = "Wrong Password.";
            header("Location: staff_login.php");  // Redirect back to the login page
            exit();
        }
    } else {
        // Staff not found
        $_SESSION['error'] = "No staff found with that username.";
        header("Location: staff_login.php");  // Redirect back to the login page
        exit();
    }
    
    // Close the database connection
    mysqli_close($conn);
}
?>
