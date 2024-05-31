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

$itemModalSql = "SELECT o.orderId, o.orderDate, o.amount, oi.prodId, oi.size, o.orderStatus, oi.itemQuantity, p.prodName, ps.price 
                 FROM `orders` o
                 JOIN `orderitems` oi ON o.orderId = oi.orderId
                 JOIN `prod` p ON oi.prodId = p.prodId
                 JOIN `prod_sizes` ps ON oi.prodId = ps.prodId AND oi.size = ps.size
                 WHERE o.userId = $userId
                 ORDER BY o.orderDate DESC";
$itemModalResult = mysqli_query($conn, $itemModalSql);
$orders = [];

while ($itemModalRow = mysqli_fetch_assoc($itemModalResult)) {
    $orderId = $itemModalRow['orderId'];
    $orderStatus = $itemModalRow['orderStatus'];
    if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
            'orderId' => $orderId,
            'orderDate' => $itemModalRow['orderDate'],
            'orderStatus' => $orderStatus,
            'amount' => $itemModalRow['amount'],
            'items' => []
        ];
    }
    $orders[$orderId]['items'][] = $itemModalRow;
}

foreach ($orders as $order) {
    $orderId = $order['orderId'];
    $orderStatus = getOrderStatusDescription($order['orderStatus']);
?>

    <div class="modal fade" id="orderItem<?php echo $orderId; ?>" tabindex="-1" role="dialog" aria-labelledby="orderItemLabel<?php echo $orderId; ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderItemLabel<?php echo $orderId; ?>">Order Details - #<?php echo $orderId; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Order Date: <?php echo date('M d, Y', strtotime($order['orderDate'])); ?></h6>
                    <h6>Order Status: <?php echo $orderStatus; ?></h6>
                    <h6>Total Amount: PHP <?php echo number_format($order['amount'], 2); ?></h6>
                    <div class=" table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item) { ?>
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <img src="img/prod-<?php echo $item['prodId']; ?>.jpg" alt="" width="40" class="img-fluid rounded shadow-sm mr-2">
                                                <span><?php echo $item['prodName']; ?></span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center"><?php echo $item['itemQuantity']; ?> pcs.</td>
                                        <td class="align-middle text-center">PHP <?php echo number_format($item['price'], 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


<?php
}
?>