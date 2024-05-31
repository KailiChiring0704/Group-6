<?php
include '_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['createItem'])) {
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $description = mysqli_real_escape_string($conn, $_POST["description"]);
        $categoryId = mysqli_real_escape_string($conn, $_POST["categoryId"]);

        $sql = "INSERT INTO `prod` (`prodName`, `prodDesc`, `prodCategoryId`, `prodPubDate`) VALUES ('$name', '$description', '$categoryId', current_timestamp())";
        $result = mysqli_query($conn, $sql);
        $prodId = $conn->insert_id;

        if ($result) {
            foreach ($_POST['sizes'] as $index => $size) {
                $price = mysqli_real_escape_string($conn, $_POST['prices'][$index]);
                $size = mysqli_real_escape_string($conn, $size);
                $sql_size = "INSERT INTO `prod_sizes` (`prodId`, `size`, `price`) VALUES ('$prodId', '$size', '$price')";
                mysqli_query($conn, $sql_size);
            }
            echo "<script>alert('Item created successfully.'); window.location=document.referrer;</script>";
        } else {
            echo "<script>alert('Failed to create item.'); window.location=document.referrer;</script>";
        }
    }

    if (isset($_POST['removeItem'])) {
        $prodId = $_POST["prodId"];
        $sql = "DELETE FROM `prod` WHERE `prodId`='$prodId'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $filename = $_SERVER['DOCUMENT_ROOT'] . "/1128-tea-cafe/img/prod-" . $prodId . ".jpg";
            if (file_exists($filename)) {
                unlink($filename);
            }
            echo "<script>alert('Item removed successfully.'); window.location=document.referrer;</script>";
        } else {
            echo "<script>alert('Failed to remove item.'); window.location=document.referrer;</script>";
        }
    }

    if (isset($_POST['updateItem'])) {
        $prodId = $_POST["prodId"];
        $prodName = mysqli_real_escape_string($conn, $_POST["name"]);
        $prodDesc = mysqli_real_escape_string($conn, $_POST["desc"]);
        $categoryId = mysqli_real_escape_string($conn, $_POST["category"]);

        $sql = "UPDATE `prod` SET `prodName`='$prodName', `prodDesc`='$prodDesc', `prodCategoryId`='$categoryId' WHERE `prodId`='$prodId'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            foreach ($_POST['sizes'] as $index => $size) {
                $price = mysqli_real_escape_string($conn, $_POST['prices'][$index]);
                $size = mysqli_real_escape_string($conn, $size);
            }

            echo "<script>alert('Item updated successfully.'); window.location=document.referrer;</script>";
        } else {
            echo "<script>alert('Failed to update item.'); window.location=document.referrer;</script>";
        }
    }
}
