<?php
$showAlert = false;
$showError = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';
    $nickname = $_POST["nickname"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $existSql = "SELECT * FROM `users` WHERE email = '$email'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if ($numExistRows > 0) {
        $showError = "Email already exists";
        header("Location: /index.php?signupsuccess=false&error=$showError");
    } else {
        if (($password == $cpassword)) {
            $hash = crypt($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` ( `nickname`, `firstName`, `lastName`, `email`, `phone`, `userType`, `password`, `joinDate`) VALUES ('$nickname', '$firstName', '$lastName', '$email', '$phone', '0', '$hash', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $showAlert = true;
                header("Location: /index.php?signupsuccess=true");
            }
        } else {
            $showError = "Passwords do not match";
            header("Location: /index.php?signupsuccess=false&error=$showError");
        }
    }
}
