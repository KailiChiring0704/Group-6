<?php
include '_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['removeUser'])) {
        $Id = $_POST["Id"];
        $sql = "DELETE FROM `users` WHERE `userId`='$Id'";
        $result = mysqli_query($conn, $sql);
        echo "<script>alert('Removed');
            window.location=document.referrer;
            </script>";
    }

    if (isset($_POST['createUser'])) {
        $nickname = $_POST["nickname"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $userType = $_POST["userType"];
        $password = $_POST["password"];
        $cpassword = $_POST["cpassword"];

        $existSql = "SELECT * FROM `users` WHERE email = '$email'";
        $result = mysqli_query($conn, $existSql);
        $numExistRows = mysqli_num_rows($result);
        if ($numExistRows > 0) {
            echo "<script>alert('Email Already Exists');
                    window.location=document.referrer;
                </script>";
        } else {
            if (($password == $cpassword)) {
                $hash = crypt($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users` ( `nickname`, `firstName`, `lastName`, `email`, `phone`, `userType`, `password`, `joinDate`) VALUES ('$nickname', '$firstName', '$lastName', '$email', '$phone', '$userType', '$hash', current_timestamp())";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    echo "<script>alert('Success');
                            window.location=document.referrer;
                        </script>";
                } else {
                    echo "<script>alert('Failed');
                            window.location=document.referrer;
                        </script>";
                }
            } else {
                echo "<script>alert('Passwords do not match');
                    window.location=document.referrer;
                </script>";
            }
        }
    }
    if (isset($_POST['editUser'])) {
        $id = $_POST["userId"];
        $nickname = $_POST["nickname"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $phone = $_POST["phone"];
        $userType = $_POST["userType"];
        $userType = $_POST["userType"];

        $sql = "UPDATE `users` SET `nickname`='$nickname',`firstName`='$firstName', `lastName`='$lastName',  `phone`='$phone', `userType`='$userType' WHERE `userId`='$id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('update successfully');
                window.location=document.referrer;
                </script>";
        } else {
            echo "<script>alert('failed');
                window.location=document.referrer;
                </script>";
        }
    }

    if (isset($_POST['updateProfilePhoto'])) {
        $id = $_POST["userId"];
        $check = getimagesize($_FILES["userimage"]["tmp_name"]);
        if ($check !== false) {
            $newfilename = "person-" . $id . ".jpg";

            $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/1128-tea-cafe/img/';
            $uploadfile = $uploaddir . $newfilename;

            if (move_uploaded_file($_FILES['userimage']['tmp_name'], $uploadfile)) {
                echo "<script>alert('success');
                        window.location=document.referrer;
                    </script>";
            } else {
                echo "<script>alert('failed');
                        window.location=document.referrer;
                    </script>";
            }
        } else {
            echo '<script>alert("Please select an image file to upload.");
            window.location=document.referrer;
                </script>';
        }
    }

    if (isset($_POST['removeProfilePhoto'])) {
        $id = $_POST["userId"];
        $filename = $_SERVER['DOCUMENT_ROOT'] . "/1128-tea-cafe/img/person-" . $id . ".jpg";
        if (file_exists($filename)) {
            unlink($filename);
            echo "<script>alert('Removed');
                window.location=document.referrer;
            </script>";
        } else {
            echo "<script>alert('no photo available.');
                window.location=document.referrer;
            </script>";
        }
    }
}
