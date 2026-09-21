<?php

require_once '../core/Product.php';

$products = Product::getAllGroupedByCategory();

$groupedProducts = [];

foreach ($products as $product) {

    $categoryName = $product['category_name'];

    $groupedProducts[$categoryName][] = $product;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>All Products - ClothWear</title>

    <!-- Your existing Molla CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <?php require_once '../includes/header.php'; ?>
    <main class="main">

        <div class="page-header text-center">
            <div class="container">

                <h1 class="page-title">
                    All Products
                </h1>

                <p>
                    Explore all our products by category.
                </p>

            </div>
        </div>
        <div class="page-content">

            <div class="container">

                <?php if (empty($groupedProducts)): ?>

                    <div class="text-center py-5">

                        <h3>No products available.</h3>

                        <p>
                            There are currently no products to display.
                        </p>

                    </div>

                <?php else: ?>

                    <?php foreach ($groupedProducts as $categoryName => $categoryProducts): ?>

                        <div class="mb-5">

                            <!-- Category Name -->
                            <div class="heading heading-center mb-3">

                                <h2 class="title">
                                    <?= htmlspecialchars($categoryName) ?>
                                </h2>

                            </div>


                            <!-- Products -->
                            <div class="row">

                                <?php foreach ($categoryProducts as $product): ?>

                                    <div class="col-6 col-md-4 col-lg-3">

                                        <div class="product">

                                            <!-- Product Image -->
                                            <figure class="product-media">

                                                <a href="product-detail.php?id=<?= (int) $product['id'] ?>">

                                                    <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>"
                                                        alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">

                                                </a>

                                            </figure>


                                            <!-- Product Details -->
                                            <div class="product-body">

                                                <div class="product-cat">

                                                    <?= htmlspecialchars($categoryName) ?>

                                                </div>


                                                <h3 class="product-title">

                                                    <a href="product-detail.php?id=<?= (int) $product['id'] ?>">

                                                        <?= htmlspecialchars($product['name']) ?>

                                                    </a>

                                                </h3>


                                                <div class="product-price">

                                                    $<?= number_format(
                                                        $product['price'],
                                                        2
                                                    ) ?>

                                                </div>


                                                <?php if ($product['stock'] > 0): ?>

                                                    <p class="text-success">
                                                        In Stock
                                                    </p>

                                                <?php else: ?>

                                                    <p class="text-danger">
                                                        Out of Stock
                                                    </p>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </div>

    </main>
    <?php require_once '../includes/footer.php'; ?>
    </div>

    <!-- ============================= -->

    <!-- Scroll To Top -->

    <!-- ============================= -->

    <button id="scroll-top" title="Back to Top">
        <i class="icon-arrow-up"></i>

    </button>

    <!-- ============================= -->

    <!-- Plugins JS -->

    <!-- ============================= -->

    <script src="assets/js/jquery.min.js"></script>

    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/jquery.hoverIntent.min.js"></script>

    <script src="assets/js/jquery.waypoints.min.js"></script>

    <script src="assets/js/superfish.min.js"></script>

    <script src="assets/js/owl.carousel.min.js"></script>

    <script src="assets/js/bootstrap-input-spinner.js"></script>

    <script src="assets/js/jquery.elevateZoom.min.js"></script>

    <script src="assets/js/jquery.magnific-popup.min.js"></script>

    <!-- Main JS -->

    <script src="assets/js/main.js"></script>

</body>

</html>