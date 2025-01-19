<?php
include "session.php";

$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['total_quantity'])) {
    $_SESSION['total_quantity'] = $data['total_quantity'];
}
