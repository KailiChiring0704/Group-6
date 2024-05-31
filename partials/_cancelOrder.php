<?php
require '_dbconnect.php';

if (isset($_POST['orderId'])) {
    $orderId = $_POST['orderId'];

    echo "Received orderId: " . $orderId;

    $stmt = $conn->prepare("UPDATE orders SET orderStatus = '6' WHERE orderId = ?");
    if ($stmt === false) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    } else {
        $stmt->bind_param("s", $orderId);
        if ($stmt->execute()) {
            echo "Order #$orderId cancelled successfully.";
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    echo "Invalid request.";
}
