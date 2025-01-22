<?php
session_start();
include "../connectdb.php";

$staff_data = [];

// Fetch staff names and roles
$sql = "SELECT Staff_ID, staff_name, staff_role, phone_number FROM staff";
$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    $stmt->bind_result($staff_id,$staff_name, $staff_role, $phone_number);
    while ($stmt->fetch()) {
        $staff_data[] = [
            'name' => htmlspecialchars($staff_name),
            'role' => htmlspecialchars($staff_role),
            'id'  => htmlspecialchars($staff_id),
            'number' => htmlspecialchars($phone_number)
        ];
    }
} else {
    echo "Query execution failed: " . $stmt->error;
}

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="staff_management.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
        <title>Staff Management</title>
    </head>

    <body>


        <div class="full-container">
            <div class="outside-table">
                <a href="menu.php">
                    <button class="back-to-menu"><i class="fa-solid fa-arrow-left"></i>Back to main page</button>
                </a>

                <button class="add-staff-button" onclick="staffPopup()">
                    Add Staff
                </button>
            </div>


            <!-- Add Staff -->
            <div class="add-staff-container" id="add-staff-popup">
                <form class="add-staff-form" action="add_staff.php" method="post">
                    <h2>Add Staff</h2>
                    <label for="staff-name">Staff Name</label>
                    <input type="text" id="staff-name" name="staff-name" required>

                    <label for="staff-password">Password</label>
                    <input type="password" id="staff-password" name="staff-password" required>

                    <label for="staff-role">Role</label>
                    <input type="text" id="staff-role" name="staff-role" required>

                    <label for="staff-number">Phone Number</label>
                    <input type="tel" id="staff-number" name="staff-number">

                    <button type="submit" class="submit-button">Add Staff</button>
                    <button type="button" class="close-button" onclick="closeStaffPopup()">Close</button>
                </form>
            </div>
            <div id="overlay" class="overlay" onclick="closeStaffPopup()"></div>

            <!-- Edit Staff  -->
            <div class="popup-form" id="edit-staff-popup">
                <form class="edit-form" action="edit_staff.php" method="post">
                    <h1>Edit Staff</h1>

                    <!-- Hidden input for staff ID -->
                    <input type="hidden" id="staff-id" name="staff-id" required>

                    <!-- Staff Name -->
                    <label for="edit-staff-name">Staff Name</label>
                    <input type="text" id="edit-staff-name" name="staff-name" required>

                    <!-- Staff Role -->
                    <label for="edit-staff-role">Role</label>
                    <input type="text" id="edit-staff-role" name="staff-role" required>

                    <!-- Staff Number -->
                    <label for="edit-staff-number">Phone Number</label>
                    <input type="tel" id="edit-staff-number" name="staff-number" required pattern="^(\+?\d{1,4}[\s-]?)?(\d{3,4}[\s-]?)?[\d\s-]{7,}$">

                    <!-- Staff Password (optional) -->
                    <label for="edit-staff-password">New Password (Leave blank to keep current)</label>
                    <input type="password" id="edit-staff-password" name="staff-password">

                    <div class="popup-buttons">
                        <input class="save-form" type="submit" value="Save Changes">
                        <button type="button-close" onclick="closeEditPopup()">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Overlay for the Edit Staff Popup -->
            <div id="edit-overlay" class="overlay" onclick="closeEditPopup()"></div>



            <!-- Staff Display Table -->
            <div class="staff-table-container">
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>Staff Id</th>
                            <th>Staff Name</th>
                            <th>Role</th>
                            <th>Phone Number</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staff_data as $staff): ?>
                            <tr>
                                <td><?php echo $staff['id'] ?></td>
                                <td><?php echo $staff['name']; ?></td>
                                <td><?php echo $staff['role']; ?></td>
                                <td><?php echo $staff['number']; ?></td>
                                <td>
                                <button class="edit-staff" 
                                    onclick="openEditPopup('<?php echo $staff['id']; ?>', '<?php echo $staff['name']; ?>', '<?php echo $staff['role']; ?>', '<?php echo $staff['number']; ?>')">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>                                    
                                <form action="delete_staff.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                        <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                                        <button type="submit" class="edit-staff"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <script>
            function staffPopup() {
                document.getElementById("add-staff-popup").style.display = "block";
                document.getElementById("overlay").style.display = "block";
            }

            function closeStaffPopup() {
                document.getElementById("add-staff-popup").style.display = "none";
                document.getElementById("overlay").style.display = "none";
            }

            function openEditPopup(staffId, staffName, staffRole, staffNumber) {
                // Set the form fields with the existing data
                document.getElementById('staff-id').value = staffId;
                document.getElementById('edit-staff-name').value = staffName;
                document.getElementById('edit-staff-role').value = staffRole;
                document.getElementById('edit-staff-number').value = staffNumber;

                // Clear the password field so the user knows it's optional
                document.getElementById('edit-staff-password').value = '';

                // Show the popup and overlay
                document.getElementById('edit-staff-popup').style.display = 'block';
                document.getElementById('edit-overlay').style.display = 'block';
            }


            function closeEditPopup() {
                // Hide the popup and overlay
                document.getElementById('edit-staff-popup').style.display = 'none';
                document.getElementById('edit-overlay').style.display = 'none';
            }

        </script>
        
    </body>
</html>
