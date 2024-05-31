<?php require 'partials/_nav.php' ?>
<?php include 'partials/_dbconnect.php'; ?>

<!doctype html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <style>
    .card {

      height: 100%;
    }

    .card-img-top {
      height: 200px;
      object-fit: cover;
    }

    .card-body {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
  </style>
  <title>Home</title>
</head>

<body>

  <div class="container my-4">
    <h1 class="text-center bg-light my-3 p-7">Menu</h1><br>
    <div class="row">
      <?php
      $sql = "SELECT * FROM `categories`";
      $result = mysqli_query($conn, $sql);
      while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['categoryId'];
        $cat = $row['categoryName'];
        $desc = $row['categoryDesc'];
        echo '<div class="col-md-4 mb-4">
                  <div class="card">
                    <img src="img/card-' . $id . '.jpg" class="card-img-top" alt="image for ' . $cat . '">
                    <div class="card-body">
                      <h5 class="card-title"><a href="viewProdList.php?catid=' . $id . '">' . $cat . '</a></h5>
                      <p class="card-text">' . substr($desc, 0, 30) . '...</p>
                      <a href="viewProdList.php?catid=' . $id . '" class="btn btn-primary">View All</a>
                    </div>
                  </div>
                </div>';
      }
      ?>
    </div>
  </div>



  <?php require 'partials/_footer.php'; ?>
  <?php require 'partials/_loginModal.php'; ?>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>

  <script>
    $(document).ready(function() {
      $('.card').hide().fadeIn(1000);

      $('.card').hover(function() {
          $(this).animate({
            'zoom': 1.05
          }, 400);
        },
        function() {
          $(this).animate({
            'zoom': 1
          }, 400);
        });
      <?php if (!$loggedin) : ?>
        $('#loginModal').modal('show');
      <?php endif; ?>
    });
  </script>
</body>

</html>