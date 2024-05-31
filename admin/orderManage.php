<?php
function getOrderStatusDescription($status)
{
    $statuses = [
        '1' => 'Order Placed',
        '2' => 'Preparing Order',
        '3' => 'Ready for Pickup',
        '4' => 'Order Received',
        '5' => 'Deny Order',
        '6' => 'Cancel Order'
    ];
    return $statuses[$status] ?? 'Unknown Status';
}

include 'partials/_dbconnect.php';

$sql = "SELECT orders.*, users.email FROM orders JOIN users ON orders.userId = users.userId ORDER BY orders.orderDate DESC";
$result = mysqli_query($conn, $sql);

$statuses = [
    '1' => 'Order Placed',
    '2' => 'Preparing Order',
    '3' => 'Ready for Pickup',
    '4' => 'Order Received',
    '5' => 'Deny Order',
    '6' => 'Cancel Order'
];
?>


<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<style>
    .tooltip.show {
        top: -62px !important;
    }

    .table-wrapper .btn {
        float: right;
        color: #333;
        background-color: #fff;
        border-radius: 3px;
        border: none;
        outline: none !important;
        margin-left: 10px;
    }

    .table-wrapper .btn:hover {
        color: #333;
        background: #f2f2f2;
    }

    .table-wrapper .btn.btn-primary {
        color: #fff;
        background: #03A9F4;
    }

    .table-wrapper .btn.btn-primary:hover {
        background: #03a3e7;
    }

    .table-title .btn {
        font-size: 13px;
        border: none;
    }

    .table-title .btn i {
        float: left;
        font-size: 21px;
        margin-right: 5px;
    }

    .table-title .btn span {
        float: left;
        margin-top: 2px;
    }

    .table-title {
        color: #fff;
        background: #4b5366;
        padding: 16px 25px;
        margin: -20px -25px 10px;
        border-radius: 3px 3px 0 0;
    }

    .table-title h2 {
        margin: 5px 0 0;
        font-size: 24px;
    }

    table.table tr th,
    table.table tr td {
        border-color: #e9e9e9;
        padding: 12px 15px;
        vertical-align: middle;
    }

    table.table tr th:first-child {
        width: 60px;
    }

    table.table tr th:last-child {
        width: 80px;
    }

    table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #fcfcfc;
    }

    table.table-striped.table-hover tbody tr:hover {
        background: #f5f5f5;
    }

    table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
    }

    table.table td a {
        font-weight: bold;
        color: #566787;
        display: inline-block;
        text-decoration: none;
    }

    table.table td a:hover {
        color: #2196F3;
    }

    table.table td a.view {
        width: 30px;
        height: 30px;
        color: #2196F3;
        border: 2px solid;
        border-radius: 30px;
        text-align: center;
    }

    table.table td a.view i {
        font-size: 22px;
        margin: 2px 0 0 1px;
    }

    table.table .avatar {
        border-radius: 50%;
        vertical-align: middle;
        margin-right: 10px;
    }

    table {
        counter-reset: section;
    }

    .count:before {
        counter-increment: section;
        content: counter(section);
    }
</style>

<div class="container" style="margin-top:98px;background: aliceblue;">
    <div class="table-wrapper">
        <div class="table-title" style="border-radius: 14px;">
            <div class="row">
                <div class="col-sm-6">
                    <h2>Order Details</h2>
                </div>
                <div class="col-sm-6">
                    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search orders..." class="form-control" style="width: auto; display: inline-block; margin-top: 10px;">
                    <a onclick="location.reload()" class="btn btn-primary"><i class="material-icons">&#xE863;</i> <span>Refresh List</span></a>
                    <a href="#" onclick="window.print()" class="btn btn-info"><i class="material-icons">&#xE24D;</i> <span>Print</span></a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover text-center" id="NoOrder">
                <thead style="background-color: rgb(111 202 203);">
                    <tr>
                        <th>Order Id</th>
                        <th>User Email</th>
                        <th>Amount</th>
                        <th>Payment Mode</th>
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT orders.*, users.email FROM orders JOIN users ON orders.userId = users.userId ORDER BY orders.orderDate DESC";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $orderId = htmlspecialchars($row['orderId']);
                        $email = htmlspecialchars($row['email']);
                        $amount = htmlspecialchars($row['amount']);
                        $orderStatus = $row['orderStatus'];
                        $orderStatusDesc = $statuses[$orderStatus] ?? 'Unknown Status';
                        $paymentMode = ($row['paymentMode'] == 2) ? "Cash" : "Online";
                        $orderDate = date('F j, Y', strtotime($row['orderDate']));
                        echo "<tr>
                                <td>" . $orderId . "</td>
                                <td>" . $email . "</td>
                                <td>PHP " . $amount . ".00</td>
                                <td>" . $paymentMode . "</td>
                                <td>" . $orderDate . "</td>
                                <td>" . $orderStatusDesc . "</td>
                                <td><button class='btn btn-info' data-toggle='modal' data-target='#orderModal" . $orderId . "'>View</button></td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php

if (isset($result)) {
    mysqli_data_seek($result, 0);
    while ($row = mysqli_fetch_assoc($result)) {
        $orderId = $row['orderId'];
        $email = $row['email'];
        $amount = $row['amount'];
        $paymentMode = $row['paymentMode'];
        $orderStatus = $row['orderStatus'];
        $orderStatusDesc = getOrderStatusDescription($orderStatus);
        $orderDate = date('F j, Y', strtotime($row['orderDate']));
?>
        <div class="modal fade" id="orderModal<?= $orderId; ?>" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel<?= $orderId; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderModalLabel<?= $orderId; ?>">Order Details - #<?= $orderId; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="/admin/partials/_orderManage.php">
                            <div>
                                <label>Email:</label>
                                <input type="email" class="form-control" name="email" value="<?= $email; ?>" readonly>
                            </div>
                            <div>
                                <label>Amount:</label>
                                <input type="number" class="form-control" name="amount" value="<?= $amount; ?>" readonly>
                            </div>
                            <div>
                                <label>Payment Mode:</label>
                                <select class="form-control" name="paymentMode" readonly>
                                    <option value="0" <?= $paymentMode == 0 ? 'selected' : ''; ?>>Gcash</option>
                                    <option value="1" <?= $paymentMode == 1 ? 'selected' : ''; ?>>Paymaya</option>
                                    <option value="2" <?= $paymentMode == 2 ? 'selected' : ''; ?>>Cash</option>
                                </select>
                            </div>
                            <div>
                                <label>Order Status:</label>
                                <select class="form-control" name="orderStatus">
                                    <?php foreach ($statuses as $key => $status) : ?>
                                        <option value="<?= $key ?>" <?= $key == $orderStatus ? 'selected' : ''; ?>>
                                            <?= $status ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <input type="hidden" name="orderId" value="<?= $orderId; ?>">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="updateOrder">Update Order</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>
<script>
    function searchTable() {
        let input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("NoOrder");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            if (td.length > 0) { // ensures that TH rows are not affected
                let rowContainsFilterText = false;
                for (let j = 0; j < td.length; j++) {
                    if (td[j].textContent || td[j].innerText) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            rowContainsFilterText = true;
                            break;
                        }
                    }
                }
                if (!rowContainsFilterText) {
                    tr[i].style.display = "none";
                } else {
                    tr[i].style.display = "";
                }
            }
        }
    }
</script>