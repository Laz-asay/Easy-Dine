<?php

include "session.php";

$totalQuantity = isset($_SESSION['total_quantity']) ? $_SESSION['total_quantity'] : 0;
echo json_encode(['total_quantity' => $totalQuantity]);
