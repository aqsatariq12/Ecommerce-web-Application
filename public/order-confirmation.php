<?php 
require_once '../core/Middleware.php';
Middleware::customer();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Order Confirmation - Molla</title>

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Demo 4 CSS -->
    <link rel="stylesheet" href="assets/css/demos/demo-4.css">
    <!-- Icons -->
    <link rel="stylesheet" href="assets/css/plugins/animate/animate.min.css">
    <link rel="stylesheet" href="assets/css/plugins/owl-carousel/owl.carousel.css">
</head>

<body>

    <div class="page-wrapper">

        <!-- ================= HEADER INCLUDE START ================= -->
         <?php include '../includes/header.php'; ?>
        <!-- ================= HEADER INCLUDE END =================== -->

        <main class="main">

            <!-- Page Header -->
            <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
                <div class="container">
                    <h1 class="page-title">Order Confirmation<span>Shop</span></h1>
                </div>
            </div>

            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order Confirmation</li>
                    </ol>
                </div>
            </nav>

            <div class="page-content">
                <div class="cart">
                    <div class="container">
                        <div class="row">

                            <!-- Left Side: Order Items & Customer Details -->
                            <div class="col-lg-9">
                                
                                <!-- Success Alert Banner -->
                                <div class="text-center p-4 mb-4" style="background-color: #f9f9f9; border-radius: 4px; border: 1px solid #e0e0e0;">
                                    <div class="mb-2">
                                        <i class="icon-check" style="font-size: 40px; color: #c96;"></i>
                                    </div>
                                    <h3 class="title mb-1" style="font-size: 22px;">Thank You For Your Order!</h3>
                                    <p class="mb-0 text-muted">Your order has been placed and is currently being processed.</p>
                                </div>

                                <!-- Ordered Products Table -->
                                <table class="table table-cart table-mobile">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="product-col">
                                                <div class="product">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="assets/images/products/table/product-1.jpg" alt="Product image">
                                                        </a>
                                                    </figure>
                                                    <h3 class="product-title">
                                                        <a href="#">Beige knitted elastic runner shoes</a>
                                                    </h3>
                                                </div>
                                            </td>
                                            <td class="price-col">$84.00</td>
                                            <td class="quantity-col">1</td>
                                            <td class="total-col">$84.00</td>
                                        </tr>
                                        <tr>
                                            <td class="product-col">
                                                <div class="product">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="assets/images/products/table/product-2.jpg" alt="Product image">
                                                        </a>
                                                    </figure>
                                                    <h3 class="product-title">
                                                        <a href="#">Blue utility pinafore denim dress</a>
                                                    </h3>
                                                </div>
                                            </td>
                                            <td class="price-col">$76.00</td>
                                            <td class="quantity-col">1</td>
                                            <td class="total-col">$76.00</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Customer Billing & Shipping Info -->
                                <div class="row mt-4 mb-4">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <div class="card card-dashboard">
                                            <div class="card-body">
                                                <h3 class="card-title" style="font-size: 16px; border-bottom: 1px solid #ebebeb; padding-bottom: 10px;">Billing Address</h3>
                                                <p class="mb-1"><strong>John Doe</strong></p>
                                                <p class="mb-1">123 Main Street</p>
                                                <p class="mb-1">Karachi, Pakistan</p>
                                                <p class="mb-1">+92 300 1234567</p>
                                                <p class="mb-0">john@example.com</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="card card-dashboard">
                                            <div class="card-body">
                                                <h3 class="card-title" style="font-size: 16px; border-bottom: 1px solid #ebebeb; padding-bottom: 10px;">Shipping Address</h3>
                                                <p class="mb-1"><strong>John Doe</strong></p>
                                                <p class="mb-1">123 Main Street</p>
                                                <p class="mb-1">Karachi, Pakistan</p>
                                                <p class="mb-0">+92 300 1234567</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Right Side: Order Summary Card -->
                            <aside class="col-lg-3">
                                <div class="summary summary-cart">
                                    <h3 class="summary-title">Order Summary</h3>

                                    <table class="table table-summary">
                                        <tbody>
                                            <tr class="summary-subtotal">
                                                <td>Order ID:</td>
                                                <td class="text-right">#MOLLA-50547</td>
                                            </tr>
                                            <tr class="summary-subtotal">
                                                <td>Date:</td>
                                                <td class="text-right">Sep 11, 2026</td>
                                            </tr>
                                            <tr class="summary-subtotal">
                                                <td>Subtotal:</td>
                                                <td class="text-right">$160.00</td>
                                            </tr>
                                            <tr class="summary-shipping">
                                                <td>Shipping:</td>
                                                <td class="text-right">Free Shipping</td>
                                            </tr>
                                            <tr class="summary-shipping">
                                                <td>Payment:</td>
                                                <td class="text-right">Cash on Delivery</td>
                                            </tr>
                                            <tr class="summary-total">
                                                <td>Total:</td>
                                                <td class="text-right">$160.00</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <a href="shop.php" class="btn btn-outline-primary-2 btn-order btn-block">
                                        CONTINUE SHOPPING
                                    </a>
                                </div>

                                <a href="index.php" class="btn btn-outline-dark-2 btn-block mb-3">
                                    <span>BACK TO HOME</span>
                                    <i class="icon-refresh"></i>
                                </a>
                            </aside>

                        </div>
                    </div>
                </div>
            </div>

        </main>

        <!-- ================= FOOTER INCLUDE START ================= -->
        <?php include '../includes/footer.php'; ?>
        <!-- ================= FOOTER INCLUDE END =================== -->

    </div>

    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

    <!-- Plugins JS -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>