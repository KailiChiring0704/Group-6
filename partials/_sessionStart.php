<?php
session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['loggedin'] === true) {
    $loggedin = true;
    $userId = $_SESSION['user']['userId'];
    $email = $_SESSION['user']['email'];
    $nickname = $_SESSION['user']['nickname'];
} else {
    $loggedin = false;
    $userId = 0;
    $email = null;
    $nickname = null;
}
