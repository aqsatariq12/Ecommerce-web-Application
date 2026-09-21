<?php
require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Category.php';
require_once '../core/Product.php';
require_once '../core/Slider.php';
require_once '../core/Deal.php';

$categories = Category::getActiveCategories();
$products = Product::getNewArrivals();
$sliders = Slider::getActiveSliders();
$deals = Deal::getActiveDeals();
$bestSellingProducts = Product::getBestSelling();
$featuredDeal = $deals[0] ?? null;

$categoryProducts = [];
foreach ($categories as $category) {
    $categoryProducts[$category['id']] = Product::getByCategory($category['id']);
}
include '../includes/header.php';

?>
<!DOCTYPE html>
<html lang="en">


<!-- molla/index-4.html  22 Nov 2019 09:53:08 GMT -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Clothwear - Ecommerce</title>
    <meta name="keywords" content="HTML5 Template">
    <meta name="description" content="Molla - Bootstrap eCommerce Template">
    <meta name="author" content="p-themes">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/icons/favicon-16x16.png">
    <link rel="manifest" href="assets/images/icons/site.html">
    <link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#666666">
    <link rel="shortcut icon" href="assets/images/icons/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="Molla">
    <meta name="application-name" content="Molla">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="msapplication-config" content="assets/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="assets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/plugins/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/plugins/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/plugins/jquery.countdown.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/skins/skin-demo-4.css">
    <link rel="stylesheet" href="assets/css/demos/demo-4.css">

    <style>
        .cta-text {
            color: #212529 !important;
        }

        .cta-text h3,
        .cta-text h4,
        .cta-text p,
        .cta-text span {
            color: #212529 !important;
        }

        .product-action {
            display: flex;
            justify-content: space-around;
        }

        .product-action form {
            display: inline;
            margin: 0;
            padding: 0;
        }

        .product-action form .btn-cart {
            border: 0;
            background: none;
        }

        /* 320px - 375px */
        @media (min-width: 320px) and (max-width: 376px) {
            .mobile-subtitle {
                margin-left: 120px !important;
            }
        }

        @media (min-width: 377px) and (max-width: 426px) {
            .mobile-subtitle {
                margin-left: 180px !important;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">

        <main class="main">
            <div class="intro-slider-container mb-5">

                <div class="intro-slider owl-carousel owl-theme owl-nav-inside owl-light" data-toggle="owl"
                    data-owl-options='{
            "dots": true,
            "nav": false,
            "responsive": {
                "1200": {
                    "nav": true,
                    "dots": false
                }
            }
        }'>

                    <?php foreach ($sliders as $slider): ?>

                        <div class="intro-slide"
                            style="background-image: url(uploads/sliders/<?= htmlspecialchars($slider['image']) ?>);">

                            <div class="container intro-content">

                                <div class="row justify-content-end">

                                    <div class="col-auto col-sm-7 col-md-6 col-lg-5">

                                        <?php if (!empty($slider['subtitle'])): ?>

                                            <h3 class="intro-subtitle text-third mobile-subtitle">
                                                <?= htmlspecialchars($slider['subtitle']) ?>
                                            </h3>

                                        <?php endif; ?>


                                        <h1 class="intro-title mobile-subtitle">
                                            <?= htmlspecialchars($slider['title']) ?>
                                        </h1>


                                        <div class="intro-price mobile-subtitle">

                                            <?php if (!empty($slider['old_price'])): ?>

                                                <sup class="intro-old-price ">
                                                    $<?= number_format($slider['old_price'], 2) ?>
                                                </sup>

                                            <?php endif; ?>

                                            <span class="text-third ">
                                                $<?= number_format($slider['price'], 2) ?>
                                            </span>

                                        </div>


                                        <a href="<?= htmlspecialchars($slider['button_link']) ?>"
                                            class="btn btn-primary btn-round mobile-subtitle">

                                            <span>
                                                <?= htmlspecialchars($slider['button_text']) ?>
                                            </span>

                                            <i class="icon-long-arrow-right"></i>

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

                <span class="slider-loader"></span>

            </div>

            <div class="container">
                <h2 class="title text-center mb-4">Explore Popular Categories</h2><!-- End .title text-center -->

                <div class="cat-blocks-container">
                    <div class="row">

                        <?php foreach ($categories as $category): ?>

                            <div class="col-6 col-sm-4 col-lg-2">

                                <a href="products.php?category=<?= $category['id'] ?>" class="cat-block">

                                    <figure>
                                        <span>
                                            <img src="uploads/categories/<?= htmlspecialchars($category['image']) ?>"
                                                alt="<?= htmlspecialchars($category['name']) ?>">
                                        </span>
                                    </figure>

                                    <h3 class="cat-block-title">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </h3>

                                </a>

                            </div>

                        <?php endforeach; ?>

                    </div>
                </div>
            </div><!-- End .container -->

            <div class="mb-4"></div><!-- End .mb-4 -->

            <div class="container new-arrivals" id="newArrival">
                <div class="heading heading-flex mb-3">
                    <div class="heading-left">
                        <h2 class="title">New Arrivals</h2><!-- End .title -->
                    </div><!-- End .heading-left -->

                    <div class="heading-right">

                        <ul class="nav nav-pills nav-border-anim justify-content-center" role="tablist">

                            <!-- ALL TAB -->
                            <li class="nav-item">

                                <a class="nav-link active" id="new-all-link" data-toggle="tab" href="#new-all-tab"
                                    role="tab" aria-controls="new-all-tab" aria-selected="true">

                                    All

                                </a>

                            </li>


                            <!-- CATEGORY TABS -->
                            <?php foreach ($categories as $category): ?>

                                <li class="nav-item">

                                    <a class="nav-link" id="new-<?= $category['id'] ?>-link" data-toggle="tab"
                                        href="#new-<?= $category['id'] ?>-tab" role="tab"
                                        aria-controls="new-<?= $category['id'] ?>-tab" aria-selected="false">

                                        <?= htmlspecialchars($category['name']) ?>

                                    </a>

                                </li>

                            <?php endforeach; ?>

                        </ul>

                    </div><!-- End .heading-right -->

                </div><!-- End .heading -->

                <div class="tab-content tab-content-carousel just-action-icons-sm">

                    <!-- ALL PRODUCTS -->
                    <div class="tab-pane p-0 fade show active" id="new-all-tab" role="tabpanel"
                        aria-labelledby="new-all-link">

                        <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl"
                            data-owl-options='{
                "nav": true,
                "dots": true,
                "margin": 20,
                "loop": false,
                "responsive": {
                    "0": {
                        "items": 2
                    },
                    "480": {
                        "items": 2
                    },
                    "768": {
                        "items": 3
                    },
                    "992": {
                        "items": 4
                    },
                    "1200": {
                        "items": 5
                    }
                }
            }'>

                            <?php foreach ($products as $product): ?>

                                <div class="product product-2">

                                    <figure class="product-media">

                                        <span class="product-label label-circle label-top">
                                            Top
                                        </span>

                                        <a href="product-detail.php?id=<?= $product['id'] ?>">

                                            <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>"
                                                alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">

                                        </a>


                                        <div class="product-action">
                                            <div>

                                                <?php if ($product['stock'] > 0): ?>

                                                    <form action="add-to-cart.php" method="POST">

                                                        <input type="hidden" name="product_id"
                                                            value="<?= (int) $product['id'] ?>">

                                                        <input type="hidden" name="quantity" value="1">

                                                        <button type="submit" class="btn-product btn-cart" title="Add to cart">

                                                            <span>add to cart</span>

                                                        </button>

                                                    </form>

                                                <?php else: ?>

                                                    <button type="button" class="btn-product btn-cart" title="Out of stock"
                                                        disabled>

                                                        <span>out of stock</span>

                                                    </button>

                                                <?php endif; ?>
                                            </div>
                                            <div>

                                                <a href="product-detail.php?id=<?= (int) $product['id'] ?>"
                                                    class="btn-product" title="View Details">

                                                    <i class="icon-eye"></i>

                                                </a>
                                            </div>

                                        </div>

                                    </figure>


                                    <div class="product-body">

                                        <div class="product-cat">

                                            <a href="products.php?category=<?= $product['category_id'] ?>">

                                                <?= htmlspecialchars($product['category_name']) ?>

                                            </a>

                                        </div>


                                        <h3 class="product-title">

                                            <a href="product-detail.php?id=<?= $product['id'] ?>">

                                                <?= htmlspecialchars($product['name']) ?>

                                            </a>

                                        </h3>


                                        <div class="product-price">

                                            $<?= number_format($product['price'], 2) ?>

                                        </div>




                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>


                    <!-- CATEGORY TABS -->
                    <?php foreach ($categories as $category): ?>

                        <div class="tab-pane p-0 fade" id="new-<?= $category['id'] ?>-tab" role="tabpanel"
                            aria-labelledby="new-<?= $category['id'] ?>-link">

                            <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl"
                                data-owl-options='{
                    "nav": true,
                    "dots": true,
                    "margin": 20,
                    "loop": false,
                    "responsive": {
                        "0": {
                            "items": 2
                        },
                        "480": {
                            "items": 2
                        },
                        "768": {
                            "items": 3
                        },
                        "992": {
                            "items": 4
                        },
                        "1200": {
                            "items": 5
                        }
                    }
                }'>

                                <?php if (!empty($categoryProducts[$category['id']])): ?>

                                    <?php foreach ($categoryProducts[$category['id']] as $product): ?>

                                        <div class="product product-2">

                                            <figure class="product-media">

                                                <span class="product-label label-circle label-top">
                                                    Top
                                                </span>

                                                <a href="product-detail.php?id=<?= $product['id'] ?>">

                                                    <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>"
                                                        alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">

                                                </a>

                                                

                                                <div class="product-action">

                                                    <?php if ($product['stock'] > 0): ?>

                                                        <form action="add-to-cart.php" method="POST">

                                                            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">

                                                            <input type="hidden" name="quantity" value="1">

                                                            <button type="submit" class="btn-product btn-cart" title="Add to cart">

                                                                <span>add to cart</span>

                                                            </button>

                                                        </form>

                                                    <?php else: ?>

                                                        <button type="button" class="btn-product btn-cart" title="Out of stock"
                                                            disabled>

                                                            <span>out of stock</span>

                                                        </button>

                                                    <?php endif; ?>


                                                    <a href="product-detail.php?id=<?= (int) $product['id'] ?>" class="btn-product"
                                                        title="View Details">

                                                        <i class="icon-eye"></i>

                                                    </a>

                                                </div>

                                            </figure>


                                            <div class="product-body">

                                                <div class="product-cat">

                                                    <a href="products.php?category=<?= $product['category_id'] ?>">

                                                        <?= htmlspecialchars($product['category_name']) ?>

                                                    </a>

                                                </div>


                                                <h3 class="product-title">

                                                    <a href="product-detail.php?id=<?= $product['id'] ?>">

                                                        <?= htmlspecialchars($product['name']) ?>

                                                    </a>

                                                </h3>


                                                <div class="product-price">

                                                    $<?= number_format($product['price'], 2) ?>

                                                </div>




                                            </div>

                                        </div>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <div class="text-center w-100 py-5">

                                        <p class="text-muted">
                                            No products available in this category.
                                        </p>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

                <!-- End .tab-content -->
            </div><!-- End .container -->

            <div class="mb-6"></div><!-- End .mb-6 -->
            <?php if ($featuredDeal): ?>

                <div class="container">

                    <div class="cta cta-border mb-5"
                        style="background-image: url('uploads/deals/<?= htmlspecialchars($featuredDeal['background_image']) ?>');">

                        <!-- PRODUCT IMAGE -->
                        <img src="uploads/products/<?= htmlspecialchars($featuredDeal['product_image']) ?>"
                            alt="<?= htmlspecialchars($featuredDeal['product_name']) ?>" class="cta-img">


                        <div class="row justify-content-center">

                            <div class="col-md-12">

                                <div class="cta-content">


                                    <!-- TEXT -->
                                    <div class="cta-text text-right">

                                        <?php if (!empty($featuredDeal['subtitle'])): ?>

                                            <p>
                                                <?= htmlspecialchars($featuredDeal['subtitle']) ?>

                                                <br>

                                                <strong>
                                                    <?= htmlspecialchars($featuredDeal['title']) ?>
                                                </strong>
                                            </p>

                                        <?php endif; ?>

                                    </div>


                                    <!-- BUTTON -->
                                    <a href="product-detail.php?id=<?= $featuredDeal['product_id'] ?>"
                                        class="btn btn-primary btn-round">

                                        <span>
                                            <?= htmlspecialchars($featuredDeal['button_text']) ?>
                                            -
                                            $<?= number_format($featuredDeal['product_price'], 2) ?>
                                        </span>

                                        <i class="icon-long-arrow-right"></i>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endif; ?>
            <div class="container" id="dealsOutlet">

                <div class="heading text-center mb-3">

                    <h2 class="title">
                        Deals & Outlet
                    </h2>

                    <p class="title-desc">
                        Today’s deal and more
                    </p>

                </div>


                <div class="row">

                    <?php foreach ($deals as $deal): ?>

                        <div class="col-lg-6 deal-col">

                            <div class="deal"
                                style="background-image: url('uploads/deals/<?= htmlspecialchars($deal['background_image']) ?>');">

                                <!-- DEAL TOP -->
                                <div class="deal-top">

                                    <h2>
                                        <?= htmlspecialchars($deal['title']) ?>
                                    </h2>

                                    <h4>
                                        <?= htmlspecialchars($deal['subtitle']) ?>
                                    </h4>

                                </div>
                                <!-- End .deal-top -->


                                <!-- DEAL CONTENT -->
                                <div class="deal-content">

                                    <h3 class="product-title">

                                        <a href="product-detail.php?id=<?= $deal['product_id'] ?>">

                                            <?= htmlspecialchars($deal['product_name']) ?>

                                        </a>

                                    </h3>


                                    <div class="product-price">

                                        <span class="new-price">

                                            $<?= number_format($deal['product_price'], 2) ?>

                                        </span>


                                        <?php if (!empty($deal['old_price'])): ?>

                                            <span class="old-price">

                                                Was $<?= number_format($deal['old_price'], 2) ?>

                                            </span>

                                        <?php endif; ?>

                                    </div>
                                    <!-- End .product-price -->


                                    <a href="product-detail.php?id=<?= $deal['product_id'] ?>" class="btn btn-link">

                                        <span>
                                            <?= htmlspecialchars($deal['button_text']) ?>
                                        </span>

                                        <i class="icon-long-arrow-right"></i>

                                    </a>

                                </div>
                                <!-- End .deal-content -->


                                <!-- DEAL BOTTOM -->
                                <div class="deal-bottom">

                                    <?php if (!empty($deal['countdown_until'])): ?>

                                        <div class="deal-countdown"
                                            data-until="<?= htmlspecialchars($deal['countdown_until']) ?>">
                                        </div>

                                    <?php endif; ?>

                                </div>
                                <!-- End .deal-bottom -->

                            </div>
                            <!-- End .deal -->

                        </div>
                        <!-- End .col-lg-6 -->

                    <?php endforeach; ?>

                </div>
                <!-- End .row -->


                <div class="more-container text-center mt-1 mb-5">

                    <a href="products.php" class="btn btn-outline-dark-2 btn-round btn-more">

                        <span>
                            Shop more Outlet deals
                        </span>

                        <i class="icon-long-arrow-right"></i>

                    </a>

                </div>
                <!-- End .more-container -->

            </div>
            <!-- End .container -->

            <div class="container">
                <hr class="mb-0">
                <div class="owl-carousel mt-5 mb-5 owl-simple" data-toggle="owl" data-owl-options='{
                        "nav": false, 
                        "dots": false,
                        "margin": 30,
                        "loop": false,
                        "responsive": {
                            "0": {
                                "items":2
                            },
                            "420": {
                                "items":3
                            },
                            "600": {
                                "items":4
                            },
                            "900": {
                                "items":5
                            },
                            "1024": {
                                "items":6
                            }
                        }
                    }'>
                    <div class="brand">
                        <img src="assets/images/brands/1.png" alt="Brand Name">
                    </div>

                    <div class="brand">
                        <img src="assets/images/brands/2.png" alt="Brand Name">
                    </div>

                    <div class="brand">
                        <img src="assets/images/brands/3.png" alt="Brand Name">
                    </div>

                    <div class="brand">
                        <img src="assets/images/brands/4.png" alt="Brand Name">
                    </div>

                    <div class="brand">
                        <img src="assets/images/brands/5.png" alt="Brand Name">
                    </div>

                    <div class="brand">
                        <img src="assets/images/brands/6.png" alt="Brand Name">
                    </div>
                </div><!-- End .owl-carousel -->
            </div><!-- End .container -->

            <div class="bg-light pt-5 pb-6">
                <div class="container trending-products" id="trendingProducts">
                    <div class="heading heading-flex mb-3">
                        <div class="heading-left">
                            <h2 class="title">Trending Products</h2><!-- End .title -->
                        </div><!-- End .heading-left -->

                        <div class="heading-right">
                            <ul class="nav nav-pills nav-border-anim justify-content-center" role="tablist">

                                <li class="nav-item">

                                    <a class="nav-link active" id="trending-best-selling-link" data-toggle="tab"
                                        href="#trending-best-selling-tab" role="tab"
                                        aria-controls="trending-best-selling-tab" aria-selected="true">

                                        Best Selling

                                    </a>

                                </li>

                            </ul>
                        </div><!-- End .heading-right -->
                    </div><!-- End .heading -->




                    <div class="tab-content tab-content-carousel just-action-icons-sm">
                        <div class="tab-pane p-0 fade show active" id="trending-best-selling-tab" role="tabpanel"
                            aria-labelledby="trending-best-selling-link">

                            <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow"
                                data-toggle="owl" data-owl-options='{
            "nav": true,
            "dots": true,
            "margin": 20,
            "loop": false,
            "responsive": {
                "0": {
                    "items": 2
                },
                "480": {
                    "items": 2
                },
                "768": {
                    "items": 3
                },
                "992": {
                    "items": 4
                },
                "1200": {
                    "items": 5
                }
            }
        }'>

                                <?php if (!empty($bestSellingProducts)): ?>

                                    <?php foreach ($bestSellingProducts as $product): ?>

                                        <div class="product product-2">

                                            <!-- PRODUCT IMAGE -->
                                            <figure class="product-media">

                                                <span class="product-label label-circle label-top">
                                                    Best Selling
                                                </span>

                                                <a href="product-detail.php?id=<?= $product['id'] ?>">

                                                    <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>"
                                                        alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">

                                                </a>
                                                


                                                <!-- PRODUCT ACTION -->
                                                <div class="product-action">

                                                    <a href="#" class="btn-product btn-cart" title="Add to cart">

                                                        <span>
                                                            add to cart
                                                        </span>

                                                    </a>

                                                                                                    <a href="product-detail.php?id=<?= (int) $product['id'] ?>"
                                                    class="btn-product" title="View Details">

                                                    <i class="icon-eye"></i>

                                                </a>

                                                </div>

                                            </figure>


                                            <!-- PRODUCT BODY -->
                                            <div class="product-body">

                                                <!-- CATEGORY -->
                                                <div class="product-cat">

                                                    <a href="products.php?category=<?= $product['category_id'] ?>">

                                                        <?= htmlspecialchars($product['category_name']) ?>

                                                    </a>

                                                </div>


                                                <!-- PRODUCT NAME -->
                                                <h3 class="product-title">

                                                    <a href="product-detail.php?id=<?= $product['id'] ?>">

                                                        <?= htmlspecialchars($product['name']) ?>

                                                    </a>

                                                </h3>


                                                <!-- PRICE -->
                                                <div class="product-price">

                                                    $<?= number_format($product['price'], 2) ?>

                                                </div>


                                                <!-- RATINGS -->
                                                <div class="ratings-container">

                                                    <div class="ratings">

                                                        <div class="ratings-val" style="width: 100%;">
                                                        </div>

                                                    </div>

                                                    <span class="ratings-text">

                                                        <?= (int) $product['total_sold'] ?> sold

                                                    </span>

                                                </div>

                                            </div>

                                        </div>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <div class="text-center w-100 py-5">

                                        <p class="text-muted">
                                            No best-selling products available.
                                        </p>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div><!-- .End .tab-pane -->
                    </div><!-- End .tab-content -->
                    <!-- End .col-xl-4-5col -->
                    <!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End .bg-light pt-5 pb-6 -->

            <div class="mb-5"></div><!-- End .mb-5 -->

            <div class="mb-4"></div><!-- End .mb-4 -->

            <div class="container">
                <hr class="mb-0">
            </div><!-- End .container -->

            <div class="icon-boxes-container bg-transparent">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="icon-box icon-box-side">
                                <span class="icon-box-icon text-dark">
                                    <i class="icon-rocket"></i>
                                </span>
                                <div class="icon-box-content">
                                    <h3 class="icon-box-title">Free Shipping</h3><!-- End .icon-box-title -->
                                    <p>Orders $50 or more</p>
                                </div><!-- End .icon-box-content -->
                            </div><!-- End .icon-box -->
                        </div><!-- End .col-sm-6 col-lg-3 -->

                        <div class="col-sm-6 col-lg-3">
                            <div class="icon-box icon-box-side">
                                <span class="icon-box-icon text-dark">
                                    <i class="icon-rotate-left"></i>
                                </span>

                                <div class="icon-box-content">
                                    <h3 class="icon-box-title">Free Returns</h3><!-- End .icon-box-title -->
                                    <p>Within 30 days</p>
                                </div><!-- End .icon-box-content -->
                            </div><!-- End .icon-box -->
                        </div><!-- End .col-sm-6 col-lg-3 -->

                        <div class="col-sm-6 col-lg-3">
                            <div class="icon-box icon-box-side">
                                <span class="icon-box-icon text-dark">
                                    <i class="icon-info-circle"></i>
                                </span>

                                <div class="icon-box-content">
                                    <h3 class="icon-box-title">Get 20% Off 1 Item</h3><!-- End .icon-box-title -->
                                    <p>when you sign up</p>
                                </div><!-- End .icon-box-content -->
                            </div><!-- End .icon-box -->
                        </div><!-- End .col-sm-6 col-lg-3 -->

                        <div class="col-sm-6 col-lg-3">
                            <div class="icon-box icon-box-side">
                                <span class="icon-box-icon text-dark">
                                    <i class="icon-life-ring"></i>
                                </span>

                                <div class="icon-box-content">
                                    <h3 class="icon-box-title">We Support</h3><!-- End .icon-box-title -->
                                    <p>24/7 amazing services</p>
                                </div><!-- End .icon-box-content -->
                            </div><!-- End .icon-box -->
                        </div><!-- End .col-sm-6 col-lg-3 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End .icon-boxes-container -->
        </main><!-- End .main -->
        <?php include '../includes/footer.php'; ?>
    </div><!-- End .page-wrapper -->
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>


    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/bootstrap-input-spinner.js"></script>
    <script src="assets/js/jquery.plugin.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/jquery.countdown.min.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/demos/demo-4.js"></script>

</body>


<!-- molla/index-4.html  22 Nov 2019 09:54:18 GMT -->

</html>