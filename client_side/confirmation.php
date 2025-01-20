<?php
include "session.php"; // Declared session start here
include "../connectdb.php";
require "../fpdf.php";

// Retrieve the Order_ID from POST request
$orderID = $_POST['order_id'] ?? null;

if ($orderID) {
    // Query to fetch the order details and corresponding table number
    $query = "
        SELECT 
            o.order_date, 
            o.total_amount, 
            o.order_status, 
            o.price, 
            t.table_number
        FROM 
            orderlist o
        INNER JOIN 
            tablelist t ON o.Table_ID = t.Table_ID
        WHERE 
            o.Order_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the order data
    $order_data = $result->fetch_assoc();
} else {
    echo "Error: Order ID not found.";
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="confirmation.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>

    <div class="thank-you">        <h1>Thank You!</h1></div>

    <?php date_default_timezone_set('Asia/Kuala Lumpur'); ?>
    <?php echo date("Y-m-d H:i:s"); ?>

    <div class="report-container">
        <?php if (!empty($order_data)): ?>
            <div class="order-details">
                <p>Your order has been placed successfully.</p>
            </div>
            <div class="report-table-container">
                <table class="report-table">
                    <tr>
                        <th>Order Date</th>
                        <td><?php echo htmlspecialchars($order_data['order_date']); ?></td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td>RM<?php echo htmlspecialchars($order_data['total_amount']); ?></td>
                    </tr>
                    <tr>
                        <th>Table Number</th>
                        <td><?php echo htmlspecialchars($order_data['table_number']); ?></td>
                    </tr>
                </table>
            </div>
        <?php else: ?>
            <p>No details available for this order.</p>
        <?php endif; ?>
    </div>

    <div class="confirmation-container">

        <div class="buttons">
            <a href="mainpage.php" class="back-to-menu"><button class="back-menu">Back to Menu</button> </a>
            <button type="button" class="print-report" onclick="generatePDF(<?php echo htmlspecialchars($orderID); ?>)">Print Report</button>
        </div>

    </div>

    <script>
    function generatePDF(orderID) {
        fetch('generate_pdf.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ order_id: orderID }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.blob(); // Get the PDF as a blob
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `Order_${orderID}.pdf`;
            document.body.appendChild(link);
            link.click();
            link.remove();
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    }
    </script>

    
</body>
</html>
