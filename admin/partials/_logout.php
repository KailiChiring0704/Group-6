<?php
session_start();
if (isset($_SESSION['admin'])) {
    unset($_SESSION['admin']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging out</title>
    <script>
        setTimeout(function() {
            window.location.href = "../index.php";
        }, 3000);
    </script>
    <style>
        body {
            text-align: center;
            padding: 150px;
            font-family: "Montserrat", sans-serif;
            color: #333;
            background-color: #f7f7f7;
        }

        #loader {
            border: 16px solid #f3f3f3;
            border-top: 16px solid #3498db;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div id="loader"></div>
    <p>Logging out, please wait...</p>

</body>

</html>
<?php exit; ?>