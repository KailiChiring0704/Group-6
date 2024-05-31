<?php
include '_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['createCategory'])) {
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $desc = mysqli_real_escape_string($conn, $_POST["desc"]);

        $sql = "INSERT INTO `categories` (`categoryName`, `categoryDesc`, `categoryCreateDate`) VALUES ('$name', '$desc', current_timestamp())";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('Category created successfully.');
                    window.location=document.referrer;
                    console.log('Created $name');
                  </script>
                  ";
        } else {
            echo "<script>alert('Failed to create category.');
                    window.location=document.referrer;
                  </script>";
        }
    }

    if (isset($_POST['removeCategory'])) {
        $catId = mysqli_real_escape_string($conn, $_POST["catId"]);

        $sql = "DELETE FROM `categories` WHERE `categoryId`='$catId'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('Category removed successfully.');
                    window.location=document.referrer;
                  </script>";
        } else {
            echo "<script>alert('Failed to remove category.');
                    window.location=document.referrer;
                  </script>";
        }
    }

    if (isset($_POST['updateCategory'])) {
        $catId = mysqli_real_escape_string($conn, $_POST["catId"]);
        $catName = mysqli_real_escape_string($conn, $_POST["name"]);
        $catDesc = mysqli_real_escape_string($conn, $_POST["desc"]);

        $sql = "UPDATE `categories` SET `categoryName`='$catName', `categoryDesc`='$catDesc' WHERE `categoryId`='$catId'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('Category updated successfully.');
                    window.location=document.referrer;
                  </script>";
        } else {
            echo "<script>alert('Failed to update category.');
                    window.location=document.referrer;
                  </script>";
        }
    }
}
