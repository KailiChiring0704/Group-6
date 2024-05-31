<?php
header('Content-Type: application/json');

$server = "db";
$username = "root";
$password = "root";
$database = "billing-system-db";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}
try {
    $dateToday = new DateTime("now", new DateTimeZone('Asia/Manila'));
    $today = $dateToday->format('Y-m-d');
    $startOfWeek = $dateToday->modify('monday this week')->format('Y-m-d');
    $startOfMonth = $dateToday->modify('first day of this month')->format('Y-m-d');

    $orderQuery = "SELECT SUM(price * itemQuantity) AS order_total FROM orderitems WHERE orderId = ?";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $orderTotal = $result['order_total'] ?? 0;
    $stmt->close();

    $query = "SELECT * FROM analytics WHERE date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $exists = $stmt->get_result()->num_rows > 0;
    $stmt->close();

    if ($exists) {
        $updateQuery = "UPDATE analytics SET total_orders = total_orders + 1, daily_revenue = daily_revenue + ?, weekly_revenue = IF(date >= ?, weekly_revenue + ?, weekly_revenue), monthly_revenue = IF(date >= ?, monthly_revenue + ?, monthly_revenue) WHERE date = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("dddsdd", $orderTotal, $startOfWeek, $orderTotal, $startOfMonth, $orderTotal, $today);
    } else {
        $insertQuery = "INSERT INTO analytics (date, total_orders, daily_revenue, weekly_revenue, monthly_revenue) VALUES (?, 0, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sddd", $today, $orderTotal, $orderTotal, $orderTotal);
    }

    if (!$stmt->execute()) {
        $stmt->close();
        throw new Exception("Failed to update analytics.");
    }
    if (!$result) {
        throw new Exception('No data available for today.');
    }
    $query = "SELECT * FROM analytics WHERE date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $response = [
        'daily_revenue' => $result['daily_revenue'],
        'weekly_revenue' => $result['weekly_revenue'],
        'monthly_revenue' => $result['monthly_revenue'],
        'total_orders' => $result['total_orders'],
        'labels' => [],
        'revenue_over_time' => []
    ];

    $query = "SELECT date, daily_revenue FROM analytics ORDER BY date DESC LIMIT 5";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        array_unshift($response['labels'], $row['date']);
        array_unshift($response['revenue_over_time'], $row['daily_revenue']);
    }

    echo json_encode(['success' => true, 'data' => $response]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
