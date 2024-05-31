<?php
include '_dbconnect.php';

if (isset($_POST['orderId'])) {
    $orderId = $_POST['orderId'];
    $stmt = $conn->prepare("SELECT orderStatus FROM orders WHERE orderId = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $status = $result->fetch_assoc();

    echo json_encode([
        'orderStatus' => $status['orderStatus']
    ]);
}
