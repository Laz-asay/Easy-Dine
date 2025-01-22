<?php
session_start();

// Ensure the staff is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: ../staff/staff_login.php");
    exit();
}

// Include database connection
include("../connectdb.php");

// Check if the order status is being updated
// Check if the order status is being updated
if (isset($_POST['update_status']) && isset($_POST['order_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    // Update the order status in the database if it hasn't been pushed to the report
    $sql_check_status = "SELECT order_status FROM orderlist WHERE Order_ID = ?";
    $stmt_check = $conn->prepare($sql_check_status);
    $stmt_check->bind_param("i", $order_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row = $result_check->fetch_assoc();

    if ($row['order_status'] !== 'Completed') {
        // Update order status only if it's not completed
        $sql_update = "UPDATE orderlist SET order_status = ? WHERE Order_ID = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $new_status, $order_id);
        $stmt_update->execute();
    }
}

// Handle "Push to Report" button
if (isset($_POST['push_to_report'])) {
    $order_id = $_POST['order_id'];

    // Check if the order has already been pushed to the report (Completed)
    $sql_check_report = "SELECT * FROM reports WHERE Order_ID = ?";
    $stmt_check_report = $conn->prepare($sql_check_report);
    $stmt_check_report->bind_param("i", $order_id);
    $stmt_check_report->execute();
    $result_check_report = $stmt_check_report->get_result();

    // Only push to the report if the order has not already been reported
    if (mysqli_num_rows($result_check_report) === 0) {
        // Fetch order details
        $sql_order = "SELECT * FROM orderlist WHERE Order_ID = ?";
        $stmt_order = $conn->prepare($sql_order);
        $stmt_order->bind_param("i", $order_id);
        $stmt_order->execute();
        $result_order = $stmt_order->get_result();
        $order = $result_order->fetch_assoc();
        
        // Check if the order is completed or already pushed to the report
        $is_disabled = ($order['order_status'] == 'Completed') ? 'disabled' : '';
    }
}




// Check if it's an AJAX request
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    // Pagination variables
    $limit = 10; // Number of reports per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Fetch the reports for the requested page
    $sql_report = "SELECT r.Report_ID, r.order_status, r.profit, r.Order_ID, t.table_number 
                   FROM reports r
                   JOIN orderlist o ON r.Order_ID = o.Order_ID
                   JOIN tablelist t ON o.Table_ID = t.Table_ID
                   ORDER BY r.Report_ID DESC
                   LIMIT $limit OFFSET $offset";
    $result_report = mysqli_query($conn, $sql_report);

    // Fetch the total number of reports
    $sql_count = "SELECT COUNT(*) as total_reports FROM reports";
    $result_count = mysqli_query($conn, $sql_count);
    $total_reports = mysqli_fetch_assoc($result_count)['total_reports'];
    $total_pages = ceil($total_reports / $limit);

    // Prepare the response data
    $data = [];
    if (mysqli_num_rows($result_report) > 0) {
        while ($report = mysqli_fetch_assoc($result_report)) {
            $data[] = $report;
        }
    }

    // Return the data as JSON
    echo json_encode(['reports' => $data, 'total_pages' => $total_pages]);
    exit();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="order_dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
    <title>Staff Dashboard</title>

    <script>
        // JavaScript function to confirm before updating the order status
        function confirmStatusChange() {
            return confirm("Are you sure you want to change the order status?");
        }

        // JavaScript function to confirm before pushing the order to the report
        function confirmPushToReport() {
            return confirm("Are you sure you want to push this order to the report? This will prevent any further status changes.");
        }

        function showReportPopup() {
            document.getElementById('reportOverlay').style.display = 'flex';
        }

        // JavaScript function to close the report popup
        function closeReportPopup() {
            document.getElementById('reportOverlay').style.display = 'none';
        }

        document.getElementById('reportOverlay').addEventListener('click', function(e) {
            // Check if the clicked area is the overlay itself (outside the popup)
            if (!document.getElementById('reportPopup').contains(e.target)) {
                closeReportPopup(); // Close the popup if the click is outside the popup
            }
        });
        
    </script>
</head>
<body>

    <div class="welcome-container">
        <div class="welcome-and-back">
            <a href="menu.php">
                <button class="logout"><i class="fa-solid fa-arrow-left"></i></button>
            </a>
            <h1>Welcome <?php echo $_SESSION['admin_username']; ?></h1>
        </div>
    </div>


    <div class="header-container">
        <div class="setting-container">
            <div class="order-and-button">
                <h2>Orders List</h2>
                    <!-- Button to trigger report overlay -->
                    <div class="butang-report">
                        <button class="report-button-show" onclick="showReportPopup()">View Reports</button>

                    </div>
            </div>
        </div>

    </div>

    <div class="table-container">
        <div class="center-order-table">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Order Status</th>
                        <th>Table Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Orders will be dynamically inserted here -->
                </tbody>
            </table>
            
            <div class="order-pagination">
                <!-- Pagination links will be dynamically populated -->
            </div>
        </div>
    </div>





    <!-- Report Popup Overlay -->
    <div id="reportOverlay">
        <div id="reportPopup">
            <button class="closePopupBtn" onclick="closeReportPopup()">Close</button>
            <h2>Report Table</h2>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Order Status</th>
                        <th>Profit</th>
                        <th>Order ID</th>
                        <th>Table Number</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>

            <!-- Pagination controls will be dynamically populated here -->
            <div class="pagination">
                <!-- Pagination links will be inserted here by JavaScript -->
            </div>
        </div>
    </div>


    <div class="chart-container">
        <div class="chart-selector">
            <label for="periodSelect">Select Period: </label>
            <select id="periodSelect" class="select-time" onchange="updateChart(this.value)">
                <option value="day">Day</option>
                <option value="week">Week</option>
                <option value="month">Month</option>
                <option value="year">Year</option>
            </select>
        </div>

        <canvas class="chart-graph" id="profitChart"></canvas>
    </div>




    <script>
        // Initialize the chart with default data (Day view)
        var ctx = document.getElementById('profitChart').getContext('2d');
        var profitChart = new Chart(ctx, {
            type: 'bar', // Type of chart: 'line', 'bar', etc.
            data: {
                labels: [], // Empty initially, will be filled after fetching data
                datasets: [{
                    label: 'Total Profits',
                    data: [], // Empty initially, will be filled after fetching data
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 1)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Profit (RM)'
                        },
                        min: 0, // Set the minimum value for the y-axis to 0
                        max: 10000, // Set the maximum value for the y-axis to 10,000
                        ticks: {
                            stepSize: 1000, // Adjust the step size between tick marks, e.g., every RM1,000
                            callback: function(value, index, values) {
                                return 'RM' + value; // Add 'RM' prefix to the tick values
                            }
                        }
                    }
                }
            }
        });


        // Fetch the profit data from the PHP script and update the chart
        function updateChart(period) {
            fetch('fetch_profit_data.php?period=' + period)
                .then(response => response.json())
                .then(data => {
                    // Update chart with new data
                    profitChart.data.labels = data.labels;
                    profitChart.data.datasets[0].data = data.profits;
                    profitChart.update();
                });
        }

        // Initialize with the 'day' period by default
        updateChart('day');

        function loadReports(page) {
            fetch(`?ajax=1&page=${page}`)
                .then(response => response.json())
                .then(data => {
                    // Update the report table
                    const reportTableBody = document.querySelector("#reportPopup tbody");
                    reportTableBody.innerHTML = ''; // Clear existing rows

                    // Populate the new rows
                    data.reports.forEach(report => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${report.Report_ID}</td>
                            <td>${report.order_status}</td>
                            <td>${report.profit}</td>
                            <td>${report.Order_ID}</td>
                            <td>${report.table_number}</td>
                        `;
                        reportTableBody.appendChild(row);
                    });

                    // Update pagination
                    const pagination = document.querySelector(".pagination");
                    pagination.innerHTML = `
                        <a href="javascript:void(0);" onclick="loadReports(1)">First</a>
                        <a href="javascript:void(0);" onclick="loadReports(${Math.max(1, page - 1)})">Prev</a>
                        <span>Page ${page} of ${data.total_pages}</span>
                        <a href="javascript:void(0);" onclick="loadReports(${Math.min(data.total_pages, page + 1)})">Next</a>
                        <a href="javascript:void(0);" onclick="loadReports(${data.total_pages})">Last</a>
                    `;
                })
                .catch(error => console.error('Error fetching reports:', error));
        }

        // Initially load reports for the first page
        loadReports(1);

        // Update order display based on the status
        function loadOrders(page) {
            fetch(`fetch_orders.php?ajax=1&page=${page}`)
                .then(response => response.json())
                .then(data => {
                    const orderTableBody = document.querySelector(".order-table tbody");
                    orderTableBody.innerHTML = ''; // Clear existing rows

                    // Populate the new rows
                    data.orders.forEach(order => {
                        const disabled = order.order_status === 'Completed' ? 'disabled' : ''; // Disable if Completed
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${order.Order_ID}</td>
                            <td>${order.order_date}</td>
                            <td>${order.total_amount}</td>
                            <td>${order.order_status}</td>
                            <td>${order.table_number}</td>
                            <td>
                                <form method="POST" action="" onsubmit="return confirmStatusChange();">
                                    <input type="hidden" name="order_id" value="${order.Order_ID}">
                                    <select name="order_status" class="order-status-select" id="order-status-${order.Order_ID}">
                                        <option value="Pending" ${order.order_status === 'Pending' ? 'selected' : ''}>Pending</option>
                                        <option value="Completed" ${order.order_status === 'Completed' ? 'selected' : ''}>Done</option>
                                        <option value="Cancelled" ${order.order_status === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                                        <!-- Hidden "Pushed" option, initially not visible -->
                                        <option value="Pushed" ${order.order_status === 'Pushed' ? 'selected' : ''} style="display: none;">Pushed</option>
                                    </select>
                                    <button type="submit" name="update_status" class="status-button" 
                                        ${order.order_status === 'Pushed' ? 'disabled' : ''}>
                                        Update
                                    </button>
                                </form>

                                <form method="POST" action="" onsubmit="return confirmPushToReport();">
                                    <input type="hidden" name="order_id" value="${order.Order_ID}">
                                    <!-- Disable "Push to Report" if status is 'Pending' -->
                                    <button type="submit" name="push_to_report" class="status-button" 
                                            ${order.order_status === 'Pending' || order.order_status === 'Pushed' || order.order_status === 'Cancelled' ? 'disabled' : ''}
                                        onclick="markAsPushed(${order.Order_ID});">
                                        Push to Report
                                    </button>
                                </form>
                            </td>
                        `;
                        orderTableBody.appendChild(row);
                    });

                    // Update pagination
                    const pagination = document.querySelector(".order-pagination");
                    pagination.innerHTML = `
                        <a href="javascript:void(0);" onclick="loadOrders(1)">First</a>
                        <a href="javascript:void(0);" onclick="loadOrders(${Math.max(1, page - 1)})">Prev</a>
                        <span>Page ${page} of ${data.total_pages}</span>
                        <a href="javascript:void(0);" onclick="loadOrders(${Math.min(data.total_pages, page + 1)})">Next</a>
                        <a href="javascript:void(0);" onclick="loadOrders(${data.total_pages})">Last</a>
                    `;
                })
                .catch(error => console.error('Error fetching orders:', error));
        }


    // Load orders for the first page on page load
    loadOrders(1);


    function markAsPushed(orderId) {
        // Select the corresponding order status dropdown
        const statusSelect = document.getElementById(`order-status-${orderId}`);
        
        // Show the "Pushed" option after clicking "Push to Report"
        const pushedOption = statusSelect.querySelector('option[value="Pushed"]');
        if (pushedOption) {
            pushedOption.style.display = 'block'; // Show the "Pushed" option
            statusSelect.value = 'Pushed'; // Set the status to "Pushed"
        }
    }

    </script>


    
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
