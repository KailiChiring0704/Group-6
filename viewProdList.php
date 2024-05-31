<?php require 'partials/_nav.php' ?>

<?php
include 'partials/_dbconnect.php';
$id = $_GET['catid'];
$sql = "SELECT * FROM `categories` WHERE categoryId = $id";
$result = mysqli_query($conn, $sql);
$catname = "Category";
$catdesc = "Category description";
if ($row = mysqli_fetch_assoc($result)) {
    $catname = $row['categoryName'];
    $catdesc = $row['categoryDesc'];
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" crossorigin="anonymous">
    <title id="title"><?php echo htmlspecialchars($catname); ?></title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
    <style>
        .jumbotron {
            padding: 2rem 1rem;
        }

        #cont {
            min-height: 570px;
        }

        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-body {
            flex-grow: 1;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card .btn {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        @media (max-width: 767px) {
            .card .btn {
                margin-top: 0.5rem;
                margin-bottom: 0.1rem;
                width: 100%;
            }
        }

        .btn-group {
            display: block;
        }

        .btn-group .btn {
            width: 100%;
            margin: 0.25rem 0;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-icon-left {
            display: inline-flex;
            align-items: center;
        }

        .btn-icon-left .fa-icon {
            margin-right: 0.5rem;
        }

        .btn .fa-icon,
        .btn .fas {
            margin-right: 0.5rem;
        }
    </style>
</head>

<body>
    <div>&nbsp;
        <?php require 'partials/_backToAllCat.php' ?>
    </div>
    <div class="container my-3" id="cont">
        <div class="col-lg-4 text-center bg-light my-3 category-title-container" style="margin: auto;">
            <h2 class="text-center"><span id="catTitle"><?php echo htmlspecialchars($catname); ?></span></h2>
        </div>
        <div class="col-lg-4 text-center bg-light my-3 category-title-container" style="margin: auto;">
            <p class="text-center"><span id="catDesc"><?php echo htmlspecialchars($catdesc); ?></span></p>
        </div>
        &nbsp;
        <div class="row">
            <?php
            $id = $_GET['catid'];
            $sql = "SELECT p.prodId, p.prodName, p.prodDesc, ps.size, ps.price
            FROM `prod` p
            JOIN `prod_sizes` ps ON p.prodId = ps.prodId
            WHERE p.prodCategoryId = $id";
            $result = mysqli_query($conn, $sql);
            $noResult = true;

            $products = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $noResult = false;
                $products[$row['prodId']]['prodName'] = $row['prodName'];
                $products[$row['prodId']]['prodDesc'] = $row['prodDesc'];
                $products[$row['prodId']]['sizes'][$row['size']] = $row['price'];
            }

            foreach ($products as $prodId => $prodData) {
                echo '<div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="card">
                <img src="img/prod-' . $prodId . '.jpg" class="card-img-top" alt="image for ' . $prodData['prodName'] . '">
                <div class="card-body">
                    <h5 class="card-title">' . substr($prodData['prodName'], 0, 100) . '</h5>';
                foreach ($prodData['sizes'] as $size => $price) {
                    echo '<h6 style="color: #ff0000">' . $size . ' - PHP ' . $price . '.00 </h6>';
                }
                echo '<p class="card-text">' . substr($prodData['prodDesc'], 0, 35) . '...</p>
                <div class="btn-group" role="group" aria-label="Product actions">';
                if ($loggedin) {
                    echo '<button type="button" class="btn btn-primary" onclick="initializeModal(' . $prodId . ', \'' . htmlspecialchars(json_encode($prodData['sizes']), ENT_QUOTES, 'UTF-8') . '\')" data-toggle="modal" data-target="#addToCartModal"><i class="fas fa-shopping-cart fa-icon"></i>Add to cart</button>';
                    echo '<a href="viewProd.php?prodid=' . $prodId . '" class="btn btn-secondary"><i class="fas fa-info-circle fa-icon"></i>More Information</a>';
                } else {
                    echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#loginModal"><i class="fas fa-shopping-cart fa-icon"></i>Add to cart</button>';
                    echo '<a href="viewProd.php?prodid=' . $prodId . '" class="btn btn-secondary"><i class="fas fa-info-circle fa-icon"></i>More Information</a>';
                }
                echo '</div>
            </div>
        </div>
    </div>';
            }
            if ($noResult) {
                echo '<div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <p class="display-4">No items available in this category.</p>
                        <p class="lead">We will update soon.</p>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
    <?php require 'partials/_addToCartModal.php'; ?>
    <?php require 'partials/_footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>


    <script>
        function updateTotalPrice(pricePerUnit) {
            let quantity = $('#quantityInput').val() || 1;
            let total = quantity * pricePerUnit;
            $('#totalPriceDisplay').val('PHP ' + total.toFixed(2));
            $('#totalPrice').val(total.toFixed(2));
        }

        $(document).on('change', 'input[name="size"]', function() {
            let pricePerUnit = $(this).data('price');
            updateTotalPrice(pricePerUnit);
        });

        $('#quantityInput').on('input change', function() {
            let pricePerUnit = $('input[name="size"]:checked').data('price');
            updateTotalPrice(pricePerUnit);
        });

        function initializeModal(productId, sizesJson) {
            var sizes = JSON.parse(sizesJson);
            var buttonsHtml = '';
            var defaultSize = 'Large';
            var defaultPrice = 0;

            for (var size in sizes) {
                buttonsHtml += `<label class="btn btn-secondary ${size === defaultSize ? 'active' : ''}">
                                <input type="radio" name="size" value="${size}" autocomplete="off" data-price="${sizes[size]}" ${size === defaultSize ? 'checked' : ''}> ${size} - PHP ${sizes[size]}
                            </label>`;
                if (size === defaultSize) {
                    defaultPrice = sizes[size];
                }

                $('#addToCartModal .btn-group').html(buttonsHtml);
                $('#addToCartItemId').val(productId);
                $('#addToCartModal').modal('show');

                $('#quantityInput').val(1);
                updateTotalPrice(defaultPrice);
            }
        }
    </script>


</body>

</html>