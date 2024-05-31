<?php
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin']['loggedin'] === true) {
    $loggedin = true;
    $userId = $_SESSION['admin']['userId'];
    $email = $_SESSION['admin']['email'];
    $nickname = $_SESSION['admin']['nickname'];
} else {
    $loggedin = false;
    $userId = 0;
    $email = null;
    $nickname = null;
}
