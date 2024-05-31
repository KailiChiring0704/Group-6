<?php
$orderId = $row['orderId'];

$orderSql = "SELECT * FROM `orders` WHERE `orderId` = ?";
$orderStmt = $conn->prepare($orderSql);
$orderStmt->bind_param("s", $orderId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
$orderRow = $orderResult->fetch_assoc();

$orderItemsSql = "SELECT oi.*, p.prodName FROM `orderitems` oi JOIN `prod` p ON oi.prodId = p.prodId WHERE oi.orderId = ?";
$orderItemsStmt = $conn->prepare($orderItemsSql);
$orderItemsStmt->bind_param("s", $orderId);
$orderItemsStmt->execute();
$orderItemsResult = $orderItemsStmt->get_result();
?>

<div class="modal fade" id="receiptModal<?php echo $orderId; ?>" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel<?php echo $orderId; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel<?php echo $orderId; ?>">Order Receipt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>Order ID: <?php echo $orderId; ?></h6>
                <h6>Date: <?php echo $orderRow['orderDate']; ?></h6>
                <h6>Payment Mode: <?php echo $orderRow['paymentMode']; ?></h6>
                <h6>Pickup By: <?php echo $orderRow['pickupPersonName']; ?> at <?php echo $orderRow['pickupTime']; ?></h6>
                <h6>Phone: <?php echo $orderRow['pickupPersonPhone']; ?></h6>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($itemRow = $orderItemsResult->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$itemRow['prodName']}</td>
                                    <td>{$itemRow['itemQuantity']}</td>
                                    <td>{$itemRow['price']}</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <!-- Total amount -->
                <h5>Total: <?php echo $orderRow['amount']; ?></h5>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <!-- Close button -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- Print button (optional) -->
                <button type="button" class="btn btn-primary" onclick="window.print();">Print Receipt</button>
            </div>
        </div>
    </div>
</div>