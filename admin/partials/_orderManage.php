<?php
include '_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updateOrder'])) {
        $orderId = mysqli_real_escape_string($conn, $_POST['orderId']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $paymentMode = mysqli_real_escape_string($conn, $_POST['paymentMode']);
        $orderStatus = mysqli_real_escape_string($conn, $_POST['orderStatus']);

        $sql = "UPDATE orders SET amount = ?, paymentMode = ?, orderStatus = ? WHERE orderId = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("isss", $amount, $paymentMode, $orderStatus, $orderId);
            if ($stmt->execute()) {
                echo "<script>alert('Order updated successfully.');
                        window.location=document.referrer;  
                      </script>";
            } else {
                echo "<script>alert('Failed to update order.');
                       window.location=document.referrer;  
                      </script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Database prepare error.');
                   window.location=document.referrer;  
                  </script>";
        }
    }
}
