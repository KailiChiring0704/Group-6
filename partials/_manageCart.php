<?php
error_log(print_r($_POST, true));

ob_start();
include '_sessionStart.php';
if (!session_id()) session_start();

include '_dbconnect.php';
error_log("Script is executing.");

function generateOrderId($conn)
{
    do {
        $uniqueCode = substr(md5(uniqid(rand(), true)), 0, 5);
        $query = "SELECT COUNT(*) FROM `orders` WHERE orderId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $uniqueCode);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->fetch_row()[0];
    } while ($exists > 0);

    return $uniqueCode;
}

function handleCheckout($conn, $userId)
{
    $paymentMode = $_POST["paymentMode"];
    $totalPrice = $_POST["totalPrice"];

    $conn->begin_transaction();
    try {
        $orderId = generateOrderId($conn);
        $insertOrderStmt = $conn->prepare("INSERT INTO `orders` (orderId, userId, amount, paymentMode, orderStatus, orderDate) VALUES (?, ?, ?, ?, '1', NOW())");
        $insertOrderStmt->bind_param("siss", $orderId, $userId, $totalPrice, $paymentMode);
        $insertOrderStmt->execute();

        if ($insertOrderStmt->affected_rows > 0) {
            $conn->commit();
            $_SESSION['status'] = "Thanks for ordering with us. Your order id is $orderId.";

            $cartItemsSql = "SELECT vc.*, ps.price FROM viewcart vc
                 JOIN prod_sizes ps ON vc.prodId = ps.prodId AND vc.size = ps.size
                 WHERE vc.userId=?";
            $cartStmt = $conn->prepare($cartItemsSql);
            $cartStmt->bind_param("i", $userId);
            $cartStmt->execute();
            $cartItemsResult = $cartStmt->get_result();

            $insertItemStmt = $conn->prepare("INSERT INTO orderitems (orderId, prodId, size, itemQuantity, price) VALUES (?, ?, ?, ?, ?)");

            while ($cartItem = $cartItemsResult->fetch_assoc()) {
                $insertItemStmt->bind_param("sisid", $orderId, $cartItem['prodId'], $cartItem['size'], $cartItem['itemQuantity'], $cartItem['price']);
                $insertItemStmt->execute();
                if ($insertItemStmt->affected_rows == 0) {
                    throw new Exception("Failed to insert order item.");
                }
            }
            $deleteCartStmt = $conn->prepare("DELETE FROM viewcart WHERE userId=?");
            $deleteCartStmt->bind_param("i", $userId);
            $deleteCartStmt->execute();
            if ($deleteCartStmt->affected_rows == 0) {
                throw new Exception("Failed to clear cart items after order placement.");
            }

            $conn->commit();

            $_SESSION['status'] = "Order placed successfully. Your order ID is $orderId.";
            header("Location: /viewOrder.php");
            exit();
        } else {
            throw new Exception("Order insertion failed.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error placing the order: " . $e->getMessage();
        error_log("Checkout error: " . $e->getMessage());
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user']['userId'];

    if (isset($_POST['addToCart'])) {
        $itemId = $_POST["itemId"];
        $size = $_POST["size"];
        $quantity = $_POST["quantity"];


        $existSql = "SELECT * FROM `viewcart` WHERE prodId = '$itemId' AND `userId`='$userId' AND `size`='$size'";
        $result = mysqli_query($conn, $existSql);
        $numExistRows = mysqli_num_rows($result);

        if ($numExistRows > 0) {
            $row = mysqli_fetch_assoc($result);
            $newQuantity = $row['itemQuantity'] + $quantity;
            $updateSql = "UPDATE `viewcart` SET `itemQuantity`='$newQuantity' WHERE prodId = '$itemId' AND `userId`='$userId' AND `size`='$size'";
            $updateResult = mysqli_query($conn, $updateSql);
        } else {
            $sql = "INSERT INTO `viewcart` (`userId`, `prodId`, `size`, `itemQuantity`, `addedDate`) VALUES ('$userId', '$itemId', '$size', '$quantity', current_timestamp())";
            $result = mysqli_query($conn, $sql);
        }

        if (isset($updateResult) && $updateResult || isset($result) && $result) {
            $_SESSION['status'] = 'Item Added to Cart.';
        } else {
            $_SESSION['status'] = 'Unable to Add Item.';
        }
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
    }

    if (isset($_POST['removeItem'])) {
        $cartItemId = $_POST["cartItemId"];
        $size = $_POST["size"];
        $sql = "DELETE FROM `viewcart` WHERE `cartItemId`='$cartItemId' AND `userId`='$userId' AND `size`='$size'";
        if ($conn->query($sql)) {
            $_SESSION['status'] = 'Item Removed.';
        } else {
            $_SESSION['status'] = 'Failed to Remove Item.';
            error_log("Failed to remove item: " . $conn->error);
        }
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
    }


    if (isset($_POST['checkout'])) {
        handleCheckout($conn, $userId);
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $cartItemId = $_POST['cartItemId'];
        $qty = $_POST['quantity'];
        $updatesql = "UPDATE `viewcart` SET `itemQuantity` = ? WHERE `cartItemId` = ? AND `userId` = ?";
        $stmt = $conn->prepare($updatesql);
        $stmt->bind_param("iii", $qty, $cartItemId, $userId);
        $updateresult = $stmt->execute();
        if ($updateresult) {
            echo json_encode(['status' => 'success', 'message' => 'Quantity updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        }
        $stmt->close();
        ob_end_flush();
        exit();
    }
}
