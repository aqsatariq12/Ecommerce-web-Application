<?php
require_once '../core/Middleware.php';
Middleware::customer();
require_once '../core/Auth.php';
require_once '../core/Order.php';

$user = Auth::user();

$orderNumber = $_GET['order'] ?? "";

if ($orderNumber === "") {
    header("Location: orders.php");
    exit;
}

$order = Order::getByOrderNumberAndUserId($orderNumber, $user["id"]);
if (!$order) {
    die("Order not found.");
}

$orderItems = Order::getItems($order["id"]);
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
                                <div class="text-center p-4 mb-4"
                                    style="background-color: #f9f9f9; border-radius: 4px; border: 1px solid #e0e0e0;">
                                    <div class="mb-2">
                                        <i class="icon-check" style="font-size: 40px; color: #c96;"></i>
                                    </div>
                                    <h3 class="title mb-1" style="font-size: 22px;">Thank You For Your Order!</h3>
                                    <p class="mb-0 text-muted">

                                        <?php if ($order['order_status'] === 'processing'): ?>

                                            Your order has been placed and is currently being processed.

                                        <?php elseif ($order['order_status'] === 'cancelled'): ?>

                                            This order has been cancelled.

                                        <?php else: ?>

                                            Your order status is
                                            <strong>
                                                <?= htmlspecialchars(ucfirst($order['order_status'])) ?>
                                            </strong>.

                                        <?php endif; ?>

                                    </p>
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
                                        <?php foreach ($orderItems as $item): ?>

                                            <tr>
                                                <td class="product-col">
                                                    <div class="product">

                                                        <figure class="product-media">
                                                            <a
                                                                href="product-detail.php?id=<?= (int) $item['product_id'] ?>">
                                                                <img src="uploads/products/<?= htmlspecialchars($item['image']) ?>"
                                                                    alt="<?= htmlspecialchars($item['name']) ?>">
                                                            </a>
                                                        </figure>

                                                        <h3 class="product-title">
                                                            <a
                                                                href="product-detail.php?slug=<?= urlencode($item['slug']) ?>">
                                                                <?= htmlspecialchars($item['name']) ?>
                                                            </a>
                                                        </h3>

                                                    </div>
                                                </td>

                                                <td class="price-col">
                                                    $<?= number_format($item['unit_price'], 2) ?>
                                                </td>

                                                <td class="quantity-col">
                                                    <?= (int) $item['quantity'] ?>
                                                </td>

                                                <td class="total-col">
                                                    $<?= number_format($item['subtotal'], 2) ?>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <!-- Customer Billing & Shipping Info -->
                                <div class="row mt-4 mb-4">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <div class="card card-dashboard">
                                            <div class="card-body">
                                                <h3 class="card-title"
                                                    style="font-size: 16px; border-bottom: 1px solid #ebebeb; padding-bottom: 10px;">
                                                    Billing Address</h3>
                                                <p class="mb-1">
                                                    <strong>
                                                        <?= htmlspecialchars($order['billing_full_name']) ?>
                                                    </strong>
                                                </p>

                                                <p class="mb-1">
                                                    <?= htmlspecialchars($order['billing_address_line1']) ?>
                                                </p>

                                                <?php if (!empty($order['billing_address_line2'])): ?>
                                                    <p class="mb-1">
                                                        <?= htmlspecialchars($order['billing_address_line2']) ?>
                                                    </p>
                                                <?php endif; ?>

                                                <p class="mb-1">
                                                    <?= htmlspecialchars($order['billing_city']) ?>,
                                                    <?= htmlspecialchars($order['billing_state']) ?>,
                                                    <?= htmlspecialchars($order['billing_country']) ?>
                                                    <?= htmlspecialchars($order['billing_postcode']) ?>
                                                </p>

                                                <p class="mb-1">
                                                    <?= htmlspecialchars($order['billing_phone']) ?>
                                                </p>

                                                <p class="mb-0">
                                                    <?= htmlspecialchars($user['email']) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="card card-dashboard">
                                            <div class="card-body">
                                                <h3 class="card-title"
                                                    style="font-size: 16px; border-bottom: 1px solid #ebebeb; padding-bottom: 10px;">
                                                    Shipping Address</h3>
                                                <p class="mb-1">
                                                    <strong>
                                                        <?= htmlspecialchars($order['billing_full_name']) ?>
                                                    </strong>
                                                </p>

                                                <p class="mb-1">
                                                    <?= htmlspecialchars($order['billing_address_line1']) ?>
                                                </p>

                                                <?php if (!empty($order['billing_address_line2'])): ?>
                                                    <p class="mb-1">
                                                        <?= htmlspecialchars($order['billing_address_line2']) ?>
                                                    </p>
                                                <?php endif; ?>

                                                <p class="mb-1">
                                                    <?= htmlspecialchars($order['billing_city']) ?>,
                                                    <?= htmlspecialchars($order['billing_state']) ?>,
                                                    <?= htmlspecialchars($order['billing_country']) ?>
                                                    <?= htmlspecialchars($order['billing_postcode']) ?>
                                                </p>

                                                <p class="mb-0">
                                                    <?= htmlspecialchars($order['billing_phone']) ?>
                                                </p>
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
                                                <td class="text-right">
                                                    #<?= htmlspecialchars($order['order_number']) ?>
                                                </td>
                                            </tr>
                                            <tr class="summary-subtotal">
                                                <td>Status:</td>

                                                <td class="text-right">
                                                    <?= htmlspecialchars(
                                                        ucfirst($order['order_status'])
                                                    ) ?>
                                                </td>
                                            </tr>
                                            <tr class="summary-subtotal">
                                                <td>Date:</td>
                                                <td class="text-right">
                                                    <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                                </td>
                                            </tr>
                                            <tr class="summary-subtotal">
                                                <td>Subtotal:</td>
                                                <td class="text-right">
                                                    $
                                                    <?= number_format($order['subtotal_amount'], 2) ?>
                                                </td>
                                            </tr>
                                            <tr class="summary-shipping">
                                                <td>Shipping:</td>
                                                <td class="text-right">
                                                    <?= htmlspecialchars($order['shipping_method_name']) ?>

                                                    -
                                                    $<?= number_format($order['shipping_cost'], 2) ?>
                                                </td>
                                            </tr>
                                            <tr class="summary-shipping">
                                                <td>Payment:</td>
                                                <td class="text-right">
                                                    <?php if ($order['payment_method'] === 'cod'): ?>

                                                        Cash on Delivery

                                                    <?php elseif ($order['payment_method'] === 'paypal'): ?>

                                                        PayPal

                                                    <?php else: ?>

                                                        <?= htmlspecialchars($order['payment_method']) ?>

                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr class="summary-total">
                                                <td>Total:</td>
                                                <td class="text-right">
                                                    $<?= number_format($order['total_amount'], 2) ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                     <?php if ($order['order_status'] === 'processing'): ?>

    <form
        action="orders.php"
        method="POST"
        class="mb-2"
        onsubmit="return confirm('Are you sure you want to cancel this order?');"
    >

        <input
            type="hidden"
            name="order_id"
            value="<?= (int) $order['id'] ?>"
        >

        <button
            type="submit"
            class="btn btn-outline-danger btn-block"
        >
            CANCEL ORDER
        </button>

    </form>

<?php endif; ?>                   
                                    <a href="all_products.php" class="btn btn-outline-primary-2 btn-order btn-block">
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