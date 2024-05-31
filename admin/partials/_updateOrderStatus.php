<?php
include '_dbconnect.php';

header('Content-Type: application/json');

$dateToday = (new DateTime("now", new DateTimeZone('Asia/Manila')))->format('Y-m-d');
function getNewQueueNumber($conn, $dateToday)
{
    $stmt = $conn->prepare("SELECT MAX(queueNumber) as maxQueue FROM queue WHERE dateAdded = ?");
    $stmt->bind_param("s", $dateToday);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $currentMaxQueue = $result ? $result['maxQueue'] : 0;
    $stmt->close();
    return $currentMaxQueue + 1;
}

function updateAnalytics($orderId, $conn, $dateToday)
{

    $orderQuery = "SELECT SUM(price * itemQuantity) AS order_total FROM orderitems WHERE orderId = ?";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $orderTotal = $result['order_total'] ?? 0;

    $checkQuery = "SELECT * FROM analytics WHERE date = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $dateToday);
    $checkStmt->execute();
    $exists = $checkStmt->get_result()->num_rows > 0;
    $checkStmt->close();

    if ($exists) {
        $analyticsUpdate = "UPDATE analytics SET total_orders = total_orders + 1, daily_revenue = daily_revenue + ?, weekly_revenue = weekly_revenue + ?, monthly_revenue = monthly_revenue + ? WHERE date = ?";
        $stmt = $conn->prepare($analyticsUpdate);
        $stmt->bind_param("ddds", $orderTotal, $orderTotal, $orderTotal, $dateToday);
    } else {
        $analyticsInsert = "INSERT INTO analytics (date, total_orders, daily_revenue, weekly_revenue, monthly_revenue) VALUES (?, 1, ?, ?, ?)";
        $stmt = $conn->prepare($analyticsInsert);
        $stmt->bind_param("sddd", $dateToday, $orderTotal, $orderTotal, $orderTotal);
    }

    if (!$stmt->execute()) {
        $stmt->close();
        return false;
    }
    $stmt->close();
    return true;
}

if (isset($_POST['orderId']) && isset($_POST['newStatus'])) {
    $orderId = $_POST['orderId'];
    $newStatus = $_POST['newStatus'];

    $conn->begin_transaction();

    try {
        $analyticsUpdated = updateAnalytics($orderId, $conn, $dateToday);
        if (!$analyticsUpdated) {
            throw new Exception("Failed to update analytics.");
        }

        if ($newStatus == '2') {
            $queueNumber = getNewQueueNumber($conn, $dateToday);
            $stmt = $conn->prepare("INSERT INTO queue (orderId, queueNumber, dateAdded) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $orderId, $queueNumber, $dateToday);
            $stmt->execute();
            $stmt->close();
        } elseif ($newStatus == '3') {
            $stmt = $conn->prepare("DELETE FROM queue WHERE orderId = ?");
            $stmt->bind_param("s", $orderId);
            $stmt->execute();
            $stmt->close();
        }

        $stmt = $conn->prepare("UPDATE orders SET orderStatus = ? WHERE orderId = ?");
        $stmt->bind_param("ss", $newStatus, $orderId);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Order and analytics updated successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to update order status: ' . $e->getMessage()]);
    }
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
