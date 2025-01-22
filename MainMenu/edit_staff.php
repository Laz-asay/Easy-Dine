<?php
session_start();
include "../connectdb.php";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $staff_id = $_POST['staff-id'];
    $staff_name = htmlspecialchars(trim($_POST['staff-name']));
    $staff_role = htmlspecialchars(trim($_POST['staff-role']));
    $staff_number = htmlspecialchars(trim($_POST['staff-number']));
    $staff_password = trim($_POST['staff-password']); // Password field

    // Check for empty fields (except password)
    if (empty($staff_name) || empty($staff_role) || empty($staff_number)) {
        echo "<script>
                alert('Staff name, role, and phone number are required.');
                window.location.href = 'staff_management.php';
              </script>";
        exit;
    }

    // Prepare the SQL query to update the staff information
    $sql = "UPDATE staff SET staff_name = ?, staff_role = ?, phone_number = ?";

    // If a new password is provided, hash it and include it in the update query
    if (!empty($staff_password)) {
        $hashed_password = password_hash($staff_password, PASSWORD_DEFAULT);
        $sql .= ", password = ?";
    }
    
    // Add the WHERE clause to update the correct staff member
    $sql .= " WHERE Staff_ID = ?";

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters for the SQL statement
        if (!empty($staff_password)) {
            $stmt->bind_param("ssssi", $staff_name, $staff_role, $staff_number, $hashed_password, $staff_id);
        } else {
            $stmt->bind_param("sssi", $staff_name, $staff_role, $staff_number, $staff_id);
        }

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>
                    alert('Staff updated successfully!');
                    window.location.href = 'staff_management.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error updating staff: " . $stmt->error . "');
                    window.location.href = 'staff_management.php';
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Error preparing SQL statement: " . $conn->error . "');
                window.location.href = 'staff_management.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request method.');
            window.location.href = 'staff_management.php';
          </script>";
}

$conn->close();
?>
