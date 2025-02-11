<?php
// fetch_profit_data.php
// fetch_profit_data.php

session_start();
include("../connectdb.php");

$period = isset($_GET['period']) ? $_GET['period'] : 'day';
$current_date = date('Y-m-d');

// Adjust the SQL query based on the period
switch ($period) {
    case 'week':
        $start_date = date('Y-m-d', strtotime('-1 week', strtotime($current_date)));
        break;
    case 'month':
        $start_date = date('Y-m-d', strtotime('-1 month', strtotime($current_date)));
        break;
    case 'year':
        $start_date = date('Y-m-d', strtotime('-1 year', strtotime($current_date)));
        break;
    case 'day':
    default:
        $start_date = date('Y-m-d', strtotime('-1 day', strtotime($current_date)));
        break;
}

// Adjust SQL to link with `orderlist` and fetch `order_date` from there
$sql = "SELECT DATE(o.order_date) AS date, SUM(r.profit) AS total_profit 
        FROM reports r
        JOIN orderlist o ON r.Order_ID = o.Order_ID
        WHERE o.order_date >= ?
        GROUP BY DATE(o.order_date)
        ORDER BY date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $start_date);
$stmt->execute();
$result = $stmt->get_result();

$labels = [];
$profits = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['date'];
    $profits[] = (float)$row['total_profit'];
}

// Send the data as JSON
echo json_encode([
    'labels' => $labels,
    'profits' => $profits
]);

mysqli_close($conn);

?>
