<?php
include 'partials/_dbconnect.php';

function getOrderStatusDescription($status)
{
    $statuses = [
        '1' => 'Order Placed',
        '2' => 'Preparing your Order',
        '3' => 'Your order is ready, claim it on the counter',
        '4' => 'Order Received',
        '5' => 'Order Denied',
        '6' => 'Cancelled',
    ];
    return $statuses[$status] ?? 'Unknown Status';
}

$itemModalSql = "SELECT o.*, oi.prodId, oi.itemQuantity, p.prodName, ps.price FROM orders o
                 JOIN orderitems oi ON o.orderId = oi.orderId
                 JOIN prod p ON oi.prodId = p.prodId
                 JOIN prod_sizes ps ON oi.prodId = ps.prodId AND oi.size = ps.size
                 ORDER BY o.orderDate DESC";

$itemModalResult = mysqli_query($conn, $itemModalSql);
$orders = [];

while ($itemModalRow = mysqli_fetch_assoc($itemModalResult)) {
    $orderId = $itemModalRow['orderId'];
    $userId = $itemModalRow['userId'];

    if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
            'orderId' => $orderId,
            'userId' => $userId,
            'orderDate' => $itemModalRow['orderDate'],
            'orderStatus' => $itemModalRow['orderStatus'],
            'amount' => $itemModalRow['amount'],
            'items' => []
        ];
    }
    $orders[$orderId]['items'][] = $itemModalRow;
}

foreach ($orders as $order) {
    $orderId = $order['orderId'];
    $orderStatus = getOrderStatusDescription($order['orderStatus']);
    echo "<div class='modal fade' id='orderItem<?php echo $orderId; ?>' tabindex='-1' role='dialog' aria-labelledby='orderItemLabel<?php echo $orderId; ?>' aria-hidden='true'>";
    echo "<div class='modal-dialog modal-dialog-centered modal-lg' role='document'>";
    echo "<div class='modal-content'>";
    echo "<div class='modal-header'>";
    echo "<h5 class='modal-title' id='orderItemLabel$orderId'>Order Details - #$orderId</h5>";
    echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
    echo "<span aria-hidden='true'>&times;</span>";
    echo "</button></div>";
    echo "<div class='modal-body'>";
    foreach ($order['items'] as $item) {
        echo "<p>{$item['prodName']} - Quantity: {$item['itemQuantity']} - Price: {$item['price']}</p>";
    }
    echo "</div>";
    echo "<div class='modal-footer'>";
    echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
    echo "</div></div></div></div>";
}
