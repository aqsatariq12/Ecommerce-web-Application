<?php

require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Category.php';
require_once '../core/Product.php';

// Get category ID from URL
$categoryId = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);

// If category ID is missing or invalid
if (!$categoryId) {
    header("Location: index.php");
    exit;
}

// Get category
$category = Category::getById($categoryId);

// If category does not exist
if (!$category) {
    header("Location: index.php");
    exit;
}

// Get all products for this category
$products = Product::getByCategory($categoryId);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>
        <?= htmlspecialchars($category['name']) ?> - ClothWear
    </title>

    <meta name="description" content="Browse <?= htmlspecialchars($category['name']) ?> products at ClothWear.">

    <meta name="author" content="ClothWear">


    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png">

    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/favicon-32x32.png">

    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/icons/favicon-16x16.png">

    <link rel="manifest" href="assets/images/icons/site.html">

    <link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#666666">

    <link rel="shortcut icon" href="assets/images/icons/favicon.ico">

    <meta name="apple-mobile-web-app-title" content="ClothWear">

    <meta name="application-name" content="ClothWear">

    <meta name="msapplication-TileColor" content="#cc9966">

    <meta name="msapplication-config" content="assets/images/icons/browserconfig.xml">

    <meta name="theme-color" content="#ffffff">


    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/plugins/owl-carousel/owl.carousel.css">

    <link rel="stylesheet" href="assets/css/plugins/magnific-popup/magnific-popup.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="assets/css/plugins/nouislider/nouislider.css">

</head>

<body>

    <div class="page-wrapper">

        <?php include '../includes/header.php'; ?>

        <main class="main">


            <!-- ============================= -->
            <!-- Breadcrumb -->
            <!-- ============================= -->

            <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">

                <div class="container d-flex align-items-center">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">
                            <a href="index.php">
                                Home
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="index.php">
                                Categories
                            </a>
                        </li>

                        <li class="breadcrumb-item active" aria-current="page">
                            <?= htmlspecialchars($category['name']) ?>
                        </li>

                    </ol>

                </div>

            </nav>


            <!-- ============================= -->
            <!-- Page Content -->
            <!-- ============================= -->

            <div class="page-content">

                <div class="container">


                    <!-- ============================= -->
                    <!-- Category Heading -->
                    <!-- ============================= -->

<h1 class="page-title text-center mb-3">

    <?= htmlspecialchars($category['name']) ?>

</h1>


                    <p class="text-center mb-5">

                        Browse all products in the
                        <?= htmlspecialchars($category['name']) ?>
                        category.

                    </p>


                    <!-- ============================= -->
                    <!-- Category Information Tabs -->
                    <!-- ============================= -->

                    <div class="product-details-tab mb-5">


                        <!-- Tabs Navigation -->

                        <ul class="nav nav-pills justify-content-center" role="tablist">


                            <!-- Description -->

                            <li class="nav-item">

                                <a class="nav-link active" id="category-desc-link" data-toggle="tab"
                                    href="#category-desc-tab" role="tab" aria-controls="category-desc-tab"
                                    aria-selected="true">

                                    Description

                                </a>

                            </li>


                            <!-- Category Information -->

                            <li class="nav-item">

                                <a class="nav-link" id="category-info-link" data-toggle="tab" href="#category-info-tab"
                                    role="tab" aria-controls="category-info-tab" aria-selected="false">

                                    Category Information

                                </a>

                            </li>


                            <!-- Shipping -->

                            <li class="nav-item">

                                <a class="nav-link" id="category-shipping-link" data-toggle="tab"
                                    href="#category-shipping-tab" role="tab" aria-controls="category-shipping-tab"
                                    aria-selected="false">

                                    Shipping & Returns

                                </a>

                            </li>

                        </ul>


                        <!-- ============================= -->
                        <!-- Tabs Content -->
                        <!-- ============================= -->

                        <div class="tab-content">


                            <!-- ============================= -->
                            <!-- Description Tab -->
                            <!-- ============================= -->

                            <div class="tab-pane fade show active" id="category-desc-tab" role="tabpanel"
                                aria-labelledby="category-desc-link">

                                <div class="product-desc-content">

                                    <h3>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </h3>


                                    <p>

                                        Explore our collection of
                                        <?= htmlspecialchars($category['name']) ?>
                                        products.

                                        Browse our available products and
                                        find the right item for your needs.

                                    </p>


                                    <p>

                                        We offer a variety of products in the
                                        <?= htmlspecialchars($category['name']) ?>
                                        category, with different options
                                        and prices to choose from.

                                    </p>

                                </div>

                            </div>


                            <!-- ============================= -->
                            <!-- Category Information Tab -->
                            <!-- ============================= -->

                            <div class="tab-pane fade" id="category-info-tab" role="tabpanel"
                                aria-labelledby="category-info-link">

                                <div class="product-desc-content">

                                    <h3>

                                        <?= htmlspecialchars($category['name']) ?>
                                        Information

                                    </h3>


                                    <p>

                                        This category currently contains

                                        <strong>
                                            <?= count($products) ?>
                                        </strong>

                                        products.

                                    </p>


                                    <ul>

                                        <li>

                                            Category:
                                            <?= htmlspecialchars($category['name']) ?>

                                        </li>


                                        <li>

                                            Products available:
                                            <?= count($products) ?>

                                        </li>


                                        <li>

                                            All displayed products are
                                            currently active.

                                        </li>

                                    </ul>

                                </div>

                            </div>


                            <!-- ============================= -->
                            <!-- Shipping & Returns Tab -->
                            <!-- ============================= -->

                            <div class="tab-pane fade" id="category-shipping-tab" role="tabpanel"
                                aria-labelledby="category-shipping-link">

                                <div class="product-desc-content">

                                    <h3>
                                        Delivery & Returns
                                    </h3>


                                    <p>

                                        We deliver orders to customers
                                        according to our available
                                        delivery options.

                                    </p>


                                    <p>

                                        If you need to return a product,
                                        please make sure the product meets
                                        our return requirements.

                                    </p>


                                    <p>

                                        For more information about delivery
                                        and returns, please contact our
                                        support team.

                                    </p>

                                </div>

                            </div>


                        </div>

                    </div>


                    <!-- ============================= -->
                    <!-- Product Count -->
                    <!-- ============================= -->

                    <div class="toolbox mb-4">

                        <div class="toolbox-left">

                            <div class="toolbox-info">

                                Showing

                                <span>
                                    <?= count($products) ?>
                                </span>

                                products

                            </div>

                        </div>

                    </div>


                    <!-- ============================= -->
                    <!-- Products -->
                    <!-- ============================= -->

                    <div class="products mb-3">

                        <div class="row">


                            <?php if (empty($products)): ?>


                                <!-- ============================= -->
                                <!-- No Products -->
                                <!-- ============================= -->

                                <div class="col-12 text-center py-5">

                                    <h3>
                                        No products found
                                    </h3>


                                    <p class="text-muted">

                                        There are currently no products
                                        in this category.

                                    </p>


                                    <a href="index.php" class="btn btn-outline-primary">

                                        Continue Shopping

                                    </a>

                                </div>


                            <?php else: ?>


                                <!-- ============================= -->
                                <!-- Product Loop -->
                                <!-- ============================= -->

                                <?php foreach ($products as $product): ?>


                                    <div class="col-6 col-md-4 col-lg-3">


                                        <div class="product product-7 text-center">


                                            <!-- Product Image -->

                                            <figure class="product-media">


                                                <a href="product-detail.php?id=<?= $product['id'] ?>">

                                                    <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>"
                                                        alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">

                                                </a>


                                                <!-- Add To Cart -->

                                                <div class="product-action">

                                                    <a href="#" class="btn-product btn-cart">

                                                        <span>
                                                            add to cart
                                                        </span>

                                                    </a>

                                                </div>


                                            </figure>


                                            <!-- Product Body -->

                                            <div class="product-body">


                                                <!-- Category -->

                                                <div class="product-cat">

                                                    <a href="products.php?category=<?= (int) $product['category_id'] ?>">
                                                        <?= htmlspecialchars($product['category_name']) ?>
                                                    </a>

                                                </div>


                                                <!-- Product Name -->

                                                <h3 class="product-title">

                                                    <a href="product-detail.php?id=<?= $product['id'] ?>">

                                                        <?= htmlspecialchars($product['name']) ?>

                                                    </a>

                                                </h3>


                                                <!-- Price -->

                                                <div class="product-price">

                                                    $<?= number_format($product['price'], 2) ?>

                                                </div>


                                            </div>


                                        </div>


                                    </div>


                                <?php endforeach; ?>


                            <?php endif; ?>


                        </div>

                    </div>


                </div>

            </div>


        </main>


        <?php include '../includes/footer.php'; ?>
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