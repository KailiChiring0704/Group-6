<?php
include 'partials/_nav.php';
include 'partials/_dbconnect.php';

if (!isset($_GET['orderId']) || empty($_GET['orderId'])) {
    header("Location: index.php");
    exit();
}

$orderId = $_GET['orderId'];

$orderSql = "SELECT * FROM `orders` WHERE orderId=?";
$orderStmt = $conn->prepare($orderSql);
$orderStmt->bind_param("s", $orderId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
$order = $orderResult->fetch_assoc();

$itemsSql = "SELECT oi.prodId, p.prodName, oi.size, oi.itemQuantity, oi.price FROM `orderitems` oi JOIN `prod` p ON oi.prodId = p.prodId WHERE orderId=?";
$itemsStmt = $conn->prepare($itemsSql);
$itemsStmt->bind_param("s", $orderId);
$itemsStmt->execute();
$itemsResult = $itemsStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - <?= $orderId ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .receipt-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px #ccc;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <h2>Order Receipt</h2>
        <h4>Order ID: <?= htmlspecialchars($orderId) ?></h4>
        <p><strong>Date/Time of Purchase:</strong> <?= $order['orderDate'] ?></p>
        <p><strong>Pickup Name:</strong> <?= htmlspecialchars($order['pickupPersonName']) ?></p>
        <p><strong>Pickup Phone:</strong> <?= htmlspecialchars($order['pickupPersonPhone']) ?></p>
        <p><strong>Pickup Time:</strong> <?= $order['pickupTime'] ?></p>
        <p><strong>Payment Mode:</strong> <?= $order['paymentMode'] == '0' ? 'PayMaya' : ($order['paymentMode'] == '1' ? 'Gcash' : 'Cash') ?></p>

        <h4>Items:</h4>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $itemsResult->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($item['prodName']) ?></td>
                        <td><?= htmlspecialchars($item['size']) ?></td>
                        <td><?= $item['itemQuantity'] ?></td>
                        <td>PHP <?= number_format($item['price'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p><strong>Total Amount:</strong> PHP <?= number_format($order['amount'], 2) ?></p>
        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
    </div>

    <?php include 'partials/_footer.php'; ?>
</body>

</html>