<?php

require_once '../core/Product.php';
require_once '../core/Middleware.php';
Middleware::customer();

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

    <title>All Products - ElectroCart</title>

    <!-- Your existing ElectroCart CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* =========================================
       ALL PRODUCTS HEADER
    ========================================= */

        .all-products-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            text-align: left;
        }

        .all-products-title {
            flex: 1;
        }

        .all-products-title .page-title {
            margin-bottom: 5px;
        }

        .all-products-title p {
            color: #777;
        }


        /* =========================================
       SEARCH BOX
    ========================================= */

        .product-search-box {
            width: 320px;
            flex-shrink: 0;
        }

        .search-wrapper {
            position: relative;
            width: 100%;
        }

        .product-search-input {
            width: 100%;
            height: 48px;
            padding: 0 45px 0 45px;

            border: 1px solid #e5e5e5;
            border-radius: 24px;

            background: #fff;

            font-size: 14px;
            color: #333;

            outline: none;

            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);

            transition: all 0.25s ease;
        }

        .product-search-input::placeholder {
            color: #999;
        }

        .product-search-input:focus {
            border-color: #c96;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.10);
        }

        .search-wrapper>.icon-search {
            position: absolute;

            left: 18px;
            top: 50%;

            transform: translateY(-50%);

            font-size: 17px;
            color: #777;

            pointer-events: none;
        }

        .search-clear {
            position: absolute;

            right: 12px;
            top: 50%;

            transform: translateY(-50%);

            width: 28px;
            height: 28px;

            border: 0;
            border-radius: 50%;

            background: #f3f3f3;

            color: #777;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0;

            cursor: pointer;

            transition: all 0.2s ease;
        }

        .search-clear:hover {
            background: #333;
            color: #fff;
        }


        /* =========================================
       NO SEARCH RESULTS
    ========================================= */

        .no-search-results {
            display: none;

            text-align: center;

            padding: 70px 20px;
        }

        .no-search-results .search-empty-icon {
            font-size: 45px;
            color: #ccc;

            margin-bottom: 20px;
        }

        .no-search-results h3 {
            margin-bottom: 8px;
            font-size: 22px;
        }

        .no-search-results p {
            color: #888;
            margin-bottom: 0;
        }


        /* =========================================
       MOBILE
    ========================================= */

        @media (max-width: 767px) {

            .all-products-header {
                flex-direction: column;
                align-items: stretch;
                gap: 20px;
                text-align: center;
            }

            .product-search-box {
                width: 100%;
                max-width: 100%;
            }

            .product-search-input {
                height: 46px;
            }

        }


        /* =========================================
       SMALL MOBILE
    ========================================= */

        @media (max-width: 480px) {

            .all-products-header {
                gap: 15px;
            }

            .product-search-input {
                height: 44px;
                font-size: 13px;
            }

        }

        /* =========================================
   ALL PRODUCTS IMAGE CONTAINER
========================================= */

        .product-category-section .product-media {
            height: 280px;
            width: 100%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #fff;

            overflow: hidden;
        }

        /* =========================================
   ALL PRODUCTS IMAGE
========================================= */

        .product-category-section .product-media .product-image {
            width: 100%;
            height: 100%;

            object-fit: contain;

            padding: 15px;

            display: block;
        }
    </style>

</head>

<body>

    <?php require_once '../includes/header.php'; ?>
    <main class="main">

        <div class="page-header text-center">
            <div class="container">

                <div class="all-products-header">

                    <div class="all-products-title">

                        <h1 class="page-title mb-1">
                            All Products
                        </h1>

                        <p class="mb-0">
                            Explore all our products by category.
                        </p>

                    </div>

                    <!-- Product Search -->
                    <div class="product-search-box">

                        <div class="search-wrapper">

                            <i class="icon-search"></i>

                            <input type="text" id="productSearch" class="product-search-input"
                                placeholder="Search products..." autocomplete="off">

                            <button type="button" id="clearSearch" class="search-clear" title="Clear search"
                                style="display: none;">
                                <i class="icon-close"></i>
                            </button>

                        </div>

                    </div>

                </div>

            </div>
        </div>
        <div class="page-content">

            <div class="container">
                <div id="noSearchResults" class="no-search-results">

                    <div class="search-empty-icon">
                        <i class="icon-search"></i>
                    </div>

                    <h3>No products found</h3>

                    <p>
                        We couldn't find any product matching your search.
                    </p>

                </div>

                <?php if (empty($groupedProducts)): ?>

                    <div class="text-center py-5">

                        <h3>No products available.</h3>

                        <p>
                            There are currently no products to display.
                        </p>

                    </div>

                <?php else: ?>

                    <?php foreach ($groupedProducts as $categoryName => $categoryProducts): ?>

                        <div class="mb-5 product-category-section"
                            data-category="<?= htmlspecialchars(strtolower($categoryName)) ?>">
                            <!-- Category Name -->
                            <div class="heading heading-center mb-3">

                                <h2 class="title">
                                    <?= htmlspecialchars($categoryName) ?>
                                </h2>

                            </div>


                            <!-- Products -->
                            <div class="row">

                                <?php foreach ($categoryProducts as $product): ?>

                                    <div class="col-6 col-md-4 col-lg-3 product-search-item"
                                        data-product-name="<?= htmlspecialchars(strtolower($product['name'])) ?>">
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
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const searchInput = document.getElementById('productSearch');
            const clearButton = document.getElementById('clearSearch');
            const noResults = document.getElementById('noSearchResults');

            const products = document.querySelectorAll('.product-search-item');
            const categories = document.querySelectorAll('.product-category-section');


            function searchProducts() {

                const searchValue = searchInput.value
                    .trim()
                    .toLowerCase();

                let visibleProducts = 0;


                products.forEach(function (product) {

                    const productName =
                        product.getAttribute('data-product-name') || '';

                    if (
                        searchValue === '' ||
                        productName.includes(searchValue)
                    ) {

                        product.style.display = '';

                        visibleProducts++;

                    } else {

                        product.style.display = 'none';

                    }

                });


                /*
                 * Hide category heading if
                 * all products inside it are hidden.
                 */

                categories.forEach(function (category) {

                    const categoryProducts =
                        category.querySelectorAll('.product-search-item');

                    let categoryHasProduct = false;

                    categoryProducts.forEach(function (product) {

                        if (product.style.display !== 'none') {
                            categoryHasProduct = true;
                        }

                    });

                    if (categoryHasProduct) {

                        category.style.display = '';

                    } else {

                        category.style.display = 'none';

                    }

                });


                /*
                 * Show/hide no-results message
                 */

                if (searchValue !== '' && visibleProducts === 0) {

                    noResults.style.display = 'block';

                } else {

                    noResults.style.display = 'none';

                }


                /*
                 * Show/hide clear button
                 */

                if (searchValue !== '') {

                    clearButton.style.display = 'flex';

                } else {

                    clearButton.style.display = 'none';

                }

            }


            /*
             * Search while typing
             */

            searchInput.addEventListener(
                'input',
                searchProducts
            );


            /*
             * Clear search
             */

            clearButton.addEventListener(
                'click',
                function () {

                    searchInput.value = '';

                    searchInput.focus();

                    searchProducts();

                }
            );

        });

    </script>

</body>

</html>