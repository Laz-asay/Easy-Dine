<?php
session_start();
include "../connectdb.php";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the staff ID
    if (isset($_POST['staff_id'])) {
        $staff_id = intval($_POST['staff_id']); // Ensure the ID is an integer

        // Check if the staff ID is valid
        if ($staff_id > 0) {
            // Prepare the SQL statement to delete the staff member
            $sql = "DELETE FROM staff WHERE staff_id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $staff_id);

                if ($stmt->execute()) {
                    echo "<script>
                    alert('Staff member deleted successfully!');
                    window.location.href = 'staff_management.php'; // Adjust this to your actual page
                    </script>";
                } else {
                    echo "<script>
                    alert('Error deleting staff member: " . $stmt->error . "');
                    window.location.href = 'your_staff_list_page.php'; // Adjust this to your actual page
                    </script>";       
                }

                $stmt->close();
            } else {
                echo "<script>
                alert('Error preparing SQL statement: " . $conn->error . "');
                window.location.href = 'your_staff_list_page.php'; // Adjust this to your actual page
                </script>";
            }
        } else {
            echo "<script>
            alert('Invalid Staff ID');
            window.location.href = 'your_staff_list_page.php'; // Adjust this to your actual page
            </script>"; 
        }
    } else {
        echo "Staff ID not provided.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
