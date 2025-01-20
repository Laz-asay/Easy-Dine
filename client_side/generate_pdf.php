<?php
require "../fpdf.php"; // Include the FPDF library
include "../connectdb.php"; // Include your database connection

$orderID = $_POST['order_id'] ?? null;

if ($orderID) {
    $query = "SELECT orderlist.order_date, orderlist.total_amount, orderlist.order_status, 
              tablelist.table_number 
              FROM orderlist 
              JOIN tablelist ON orderlist.Table_ID = tablelist.Table_ID 
              WHERE orderlist.Order_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $orderData = $result->fetch_assoc();

    if ($orderData) {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Order Report', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Order Date:', 0, 0);
        $pdf->Cell(0, 10, $orderData['order_date'], 0, 1);
        $pdf->Cell(50, 10, 'Table Number:', 0, 0);
        $pdf->Cell(0, 10, $orderData['table_number'], 0, 1);
        $pdf->Cell(50, 10, 'Total Amount:', 0, 0);
        $pdf->Cell(0, 10, number_format($orderData['total_amount'], 2), 0, 1);

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="Order_' . $orderID . '.pdf"');
        $pdf->Output("I");
        exit;
    } else {
        echo "Order not found.";
    }
} else {
    echo "Invalid order ID.";
}
