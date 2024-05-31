<?php
include 'partials/_dbconnect.php';
session_start();

$systemName = "Default System Name";
$categories = [];
$userId = 0;
$count = 0;

if (isset($_SESSION['user']) && $_SESSION['user']['loggedin'] === true) {
  $loggedin = true;
  $userId = $_SESSION['user']['userId'];
  $email = $_SESSION['user']['email'];
  $nickname = $_SESSION['user']['nickname'];
} else {
  $loggedin = false;
  $email = $nickname = '';
}

if ($result = mysqli_query($conn, "SELECT * FROM `sitedetail`")) {
  if ($row = mysqli_fetch_assoc($result)) {
    $systemName = $row['systemName'];
  }
} else {
  $error = mysqli_error($conn);
}

$categoryQuery = "SELECT categoryName, categoryId FROM `categories`";
if ($result = mysqli_query($conn, $categoryQuery)) {
  while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
  }
} else {
  $error = mysqli_error($conn);
}

if ($loggedin) {
  $countsql = "SELECT SUM(`itemQuantity`) AS itemCount FROM `viewcart` WHERE `userId`=?";
  if ($stmt = mysqli_prepare($conn, $countsql)) {
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    $count = $count ?: 0;
  }
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php"><?= htmlspecialchars($systemName) ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Menu</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php foreach ($categories as $category) : ?>
            <a class="dropdown-item" href="viewProdList.php?catid=<?= $category['categoryId'] ?>"><?= htmlspecialchars($category['categoryName']) ?></a>
          <?php endforeach; ?>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="viewOrder.php">Orders</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="about.php">About</a>
      </li>
    </ul>
    <a href="viewCart.php" class="btn btn-secondary mx-2" title="My Cart">
      <i class="fas fa-shopping-cart"></i> Cart (<span id="cartCount"><?= $count ?></span>)
    </a>
    <?php if ($loggedin) : ?>
      <ul class="navbar-nav mr-2">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown">Welcome <?= htmlspecialchars($nickname) ?></a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
            <a class="dropdown-item" href="partials/_logout.php">Logout</a>
          </div>
        </li>
      </ul>
      <div class="text-center image-size-small position-relative">
        <a href="viewProfile.php"><img src="img/person-<?= $userId ?>.jpg" class="rounded-circle" onError="this.src = 'img/profilePic.jpg'" style="width:40px; height:40px"></a>
      </div>
    <?php else : ?>
      <button type="button" class="btn btn-success mx-2" data-toggle="modal" data-target="#loginModal">Login</button>
      <button type="button" class="btn btn-success mx-2" data-toggle="modal" data-target="#signupModal">SignUp</button>
    <?php endif; ?>
  </div>
</nav>

<?php
include 'partials/_loginModal.php';
include 'partials/_signupModal.php';
?>