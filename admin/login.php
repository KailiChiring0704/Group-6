<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <title>Login</title>
    <link rel="icon" href="/1128-tea-cafe/img/logo.jpg" type="image/x-icon">

    <style>
        body {
            width: 100%;
            height: calc(100%);
        }

        main#main {
            width: 100%;
            height: calc(100%);
            background: white;
        }

        #login-right {
            position: absolute;
            right: 0;
            width: 40%;
            height: calc(100%);
            background: #EFEFEF;
            display: flex;
            align-items: center;
        }

        #login-left {
            position: absolute;
            left: 0;
            width: 60%;
            height: calc(100%);
            background: #FFFFFF;
            display: flex;
            align-items: center;
        }

        #login-left img {
            width: 100%;
            height: auto;
            opacity: 0.7;
        }

        #login-right .card {
            margin: auto;

        }
    </style>
</head>

<body>
    <main id="main" class=" bg-dark">
        <div id="login-left">
            <img src="..\img\About.png" alt="About Us">
        </div>
        <div id="login-right">
            <div class="card col-md-8">
                <br>
                <div class="card-header" style="background-color: rgb(999 999 999);">
                    <h1>Admin Login</h1>
                    <h5>1128 Tea & Cafe</h5>
                </div>
                <div class=" card-body">
                    <form action="partials/_handleLogin.php" method="post">
                        <div class="form-group">
                            <label for="email" class="control-label"><b>Email</b></label>
                            <input type="text" id="email" name="email" class="form-control" placeholder="Enter your admin email here">
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label"><b>Password</b></label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter password">
                        </div>
                        <center><button type="submit" class="btn-sm btn-block btn-wave col-md-4 btn-primary">Login</button></center>
                    </form>
                </div>
            </div>
        </div>
    </main>


    <?php
    if (isset($_GET['loginsuccess']) && $_GET['loginsuccess'] == "false") {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning!</strong> Invalid Credentials
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                </div>';
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
</body>

</html>