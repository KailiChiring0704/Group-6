<?php
$server = "db";
$username = "root";
$password = "root";
$database = "billing-system-db";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}


if (isset($_POST['orderId'])) {
    $orderId = $_POST['orderId'];
    $stmt = $conn->prepare("SELECT orderStatus FROM orders WHERE orderId = ?");
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result['orderStatus'] == '1') {
        $updateStmt = $conn->prepare("UPDATE orders SET orderStatus = '6' WHERE orderId = ?");
        $updateStmt->bind_param("s", $orderId);
        $updateStmt->execute();
        echo json_encode(['success' => true, 'message' => 'Order cancelled due to timeout']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not active or already processed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No order ID provided']);
}
