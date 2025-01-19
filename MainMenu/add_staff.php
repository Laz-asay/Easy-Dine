<?php
session_start();
include "../connectdb.php";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $staff_number = htmlspecialchars(trim($_POST['staff-number'])); // Treat as a string to preserve leading zeros
    $staff_name = htmlspecialchars(trim($_POST['staff-name']));
    $staff_password = trim($_POST['staff-password']);
    $staff_role = htmlspecialchars(trim($_POST['staff-role']));

    // Check for empty fields
    if (empty($staff_name) || empty($staff_password) || empty($staff_role) || empty($staff_number)) {
        echo "<script>
                alert('Staff name, password, role, and phone number are required.');
                window.location.href = 'staff_management.php'; // Adjust this to your actual page
              </script>";
        exit;
    }

    // Hash the password for security
    $hashed_password = password_hash($staff_password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO staff (phone_number, staff_name, password, staff_role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $staff_number, $staff_name, $hashed_password, $staff_role);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Staff added successfully!');
                    window.location.href = 'staff_management.php'; // Adjust this to your actual page
                  </script>";
        } else {
            echo "<script>
                    alert('Error adding staff: " . $stmt->error . "');
                    window.location.href = 'staff_management.php'; // Adjust this to your actual page
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Error preparing SQL statement: " . $conn->error . "');
                window.location.href = 'staff_management.php'; // Adjust this to your actual page
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request method.');
            window.location.href = 'staff_management.php'; // Adjust this to your actual page
          </script>";
}

$conn->close();
?>
