<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';

    $email = $_POST["loginemail"];
    $password = $_POST["loginpassword"];

    $sql = $conn->prepare("SELECT userId, email, password, nickname FROM users WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();
    $num = $result->num_rows;

    if ($num == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['user'] = [
                'loggedin' => true,
                'email' => $email,
                'userId' => $row['userId'],
                'nickname' => $row['nickname']
            ];
            header("location: /index.php?loginsuccess=true");
            exit();
        } else {
            header("location: /index.php?loginsuccess=false&error=Invalid credentials");
            exit();
        }
    } else {
        header("location: /index.php?loginsuccess=false&error=Email not registered");
        exit();
    }
}
