<?php
include("../connectdb.php");

// Check if it's an AJAX request
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    // Pagination variables
    $limit = 10; // Number of orders per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Fetch orders with pagination
    $sql_orders = "SELECT o.Order_ID, o.order_date, o.total_amount, o.order_status, t.table_number 
                   FROM orderlist o 
                   JOIN tablelist t ON o.Table_ID = t.Table_ID
                   WHERE (o.order_status != 'Completed' OR o.order_date >= NOW() - INTERVAL 24 HOUR)
                   ORDER BY o.Order_ID DESC
                   LIMIT $limit OFFSET $offset";
    $result_orders = mysqli_query($conn, $sql_orders);

    // Fetch total number of orders
    $sql_count = "SELECT COUNT(*) as total_orders 
                  FROM orderlist o 
                  JOIN tablelist t ON o.Table_ID = t.Table_ID
                  WHERE (o.order_status != 'Completed' OR o.order_date >= NOW() - INTERVAL 24 HOUR)";
    $result_count = mysqli_query($conn, $sql_count);
    $total_orders = mysqli_fetch_assoc($result_count)['total_orders'];
    $total_pages = ceil($total_orders / $limit);

    // Prepare response data
    $orders = [];
    if (mysqli_num_rows($result_orders) > 0) {
        while ($order = mysqli_fetch_assoc($result_orders)) {
            $orders[] = $order;
        }
    }

    // Return data as JSON
    echo json_encode(['orders' => $orders, 'total_pages' => $total_pages]);
    exit();
}
?>
