<?php

require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Auth.php';
require_once '../core/Order.php';

$user = Auth::user();

/*
 * Get order number from URL
 */

$orderNumber = $_GET['order'] ?? '';

if ($orderNumber === '') {
    die("Invalid order.");
}

/*
 * Get order belonging to logged-in customer
 */

$order = Order::getByOrderNumberAndUserId(
    $orderNumber,
    $user['id']
);

if (!$order) {
    die("Order not found.");
}

/*
 * Get order items
 */

$orderItems = Order::getItems($order['id']);

if (empty($orderItems)) {
    die("Order items not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
    >

    <title>
        Order Details - ClothWear
    </title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="assets/css/demos/demo-4.css">

</head>

<body>

<div class="page-wrapper">

    <?php include '../includes/header.php'; ?>

    <main class="main">

        <!-- Page Header -->

        <div
            class="page-header text-center"
            style="background-image: url('assets/images/page-header-bg.jpg')"
        >

            <div class="container">

                <h1 class="page-title">
                    Order Details
                    <span>Shop</span>
                </h1>

            </div>

        </div>

        <!-- Breadcrumb -->

        <nav
            aria-label="breadcrumb"
            class="breadcrumb-nav"
        >

            <div class="container">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">
                        <a href="index.php">
                            Home
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="orders.php">
                            My Orders
                        </a>
                    </li>

                    <li
                        class="breadcrumb-item active"
                        aria-current="page"
                    >
                        Order Details
                    </li>

                </ol>

            </div>

        </nav>
                <div class="page-content">

            <div class="container">

                <div class="row">

                    <div class="col-lg-12">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>

                                <h2 class="checkout-title mb-1">
                                    Order #<?= htmlspecialchars(
                                        $order['order_number']
                                    ) ?>
                                </h2>

                                <p class="text-muted mb-0">

                                    Placed on
                                    <?= date(
                                        'M d, Y',
                                        strtotime($order['created_at'])
                                    ) ?>

                                </p>

                            </div>

                            <div>

                                <span class="badge badge-secondary">

                                    <?= htmlspecialchars(
                                        ucfirst($order['order_status'])
                                    ) ?>

                                </span>

                            </div>

                        </div>

                                                <div class="card mb-4">

                            <div class="card-body">

                                <h3
                                    class="card-title"
                                    style="font-size: 18px;"
                                >
                                    Ordered Products
                                </h3>

                                <div class="table-responsive">

                                    <table class="table table-cart">

                                        <thead>

                                            <tr>

                                                <th>
                                                    Product
                                                </th>

                                                <th>
                                                    Price
                                                </th>

                                                <th>
                                                    Quantity
                                                </th>

                                                <th>
                                                    Total
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php foreach ($orderItems as $item): ?>

                                                <tr>

                                                    <td class="product-col">

                                                        <div class="product">

                                                            <figure class="product-media">

                                                                <img
                                                                    src="uploads/products/<?= htmlspecialchars(
                                                                        $item['image']
                                                                    ) ?>"
                                                                    alt="<?= htmlspecialchars(
                                                                        $item['name']
                                                                    ) ?>"
                                                                >

                                                            </figure>

                                                            <h3 class="product-title">

                                                                <?= htmlspecialchars(
                                                                    $item['name']
                                                                ) ?>

                                                            </h3>

                                                        </div>

                                                    </td>

                                                    <td>

                                                        $<?= number_format(
                                                            $item['unit_price'],
                                                            2
                                                        ) ?>

                                                    </td>

                                                    <td>

                                                        <?= (int) $item['quantity'] ?>

                                                    </td>

                                                    <td>

                                                        $<?= number_format(
                                                            $item['subtotal'],
                                                            2
                                                        ) ?>

                                                    </td>

                                                </tr>

                                            <?php endforeach; ?>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>
                                                <div class="row mb-4">

                            <div class="col-md-6 mb-4 mb-md-0">

                                <div class="card card-dashboard">

                                    <div class="card-body">

                                        <h3
                                            class="card-title"
                                            style="
                                                font-size: 16px;
                                                border-bottom: 1px solid #ebebeb;
                                                padding-bottom: 10px;
                                            "
                                        >
                                            Billing Address
                                        </h3>

                                        <p class="mb-1">

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $order['billing_full_name']
                                                ) ?>
                                            </strong>

                                        </p>

                                        <p class="mb-1">

                                            <?= htmlspecialchars(
                                                $order['billing_address_line1']
                                            ) ?>

                                        </p>

                                        <?php if (!empty($order['billing_address_line2'])): ?>

                                            <p class="mb-1">

                                                <?= htmlspecialchars(
                                                    $order['billing_address_line2']
                                                ) ?>

                                            </p>

                                        <?php endif; ?>

                                        <p class="mb-1">

                                            <?= htmlspecialchars(
                                                $order['billing_city']
                                            ) ?>,

                                            <?= htmlspecialchars(
                                                $order['billing_state']
                                            ) ?>,

                                            <?= htmlspecialchars(
                                                $order['billing_country']
                                            ) ?>

                                            <?= htmlspecialchars(
                                                $order['billing_postcode']
                                            ) ?>

                                        </p>

                                        <p class="mb-0">

                                            <?= htmlspecialchars(
                                                $order['billing_phone']
                                            ) ?>

                                        </p>

                                    </div>

                                </div>

                            </div>

                                                        <div class="col-md-6">

                                <div class="card card-dashboard">

                                    <div class="card-body">

                                        <h3
                                            class="card-title"
                                            style="
                                                font-size: 16px;
                                                border-bottom: 1px solid #ebebeb;
                                                padding-bottom: 10px;
                                            "
                                        >
                                            Shipping Address
                                        </h3>

                                        <p class="mb-1">

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $order['billing_full_name']
                                                ) ?>
                                            </strong>

                                        </p>

                                        <p class="mb-1">

                                            <?= htmlspecialchars(
                                                $order['billing_address_line1']
                                            ) ?>

                                        </p>

                                        <?php if (!empty($order['billing_address_line2'])): ?>

                                            <p class="mb-1">

                                                <?= htmlspecialchars(
                                                    $order['billing_address_line2']
                                                ) ?>

                                            </p>

                                        <?php endif; ?>

                                        <p class="mb-1">

                                            <?= htmlspecialchars(
                                                $order['billing_city']
                                            ) ?>,

                                            <?= htmlspecialchars(
                                                $order['billing_state']
                                            ) ?>,

                                            <?= htmlspecialchars(
                                                $order['billing_country']
                                            ) ?>

                                            <?= htmlspecialchars(
                                                $order['billing_postcode']
                                            ) ?>

                                        </p>

                                        <p class="mb-0">

                                            <?= htmlspecialchars(
                                                $order['billing_phone']
                                            ) ?>

                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>
                                                <div class="row">

                            <div class="col-lg-8">
                            </div>

                            <div class="col-lg-4">

                                <div class="summary summary-cart">

                                    <h3 class="summary-title">
                                        Order Summary
                                    </h3>

                                    <table class="table table-summary">

                                        <tbody>

                                            <tr class="summary-subtotal">

                                                <td>
                                                    Order ID:
                                                </td>

                                                <td class="text-right">

                                                    #<?= htmlspecialchars(
                                                        $order['order_number']
                                                    ) ?>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    Status:
                                                </td>

                                                <td class="text-right">

                                                    <?= htmlspecialchars(
                                                        ucfirst(
                                                            $order['order_status']
                                                        )
                                                    ) ?>

                                                </td>

                                            </tr>

                                            <tr class="summary-subtotal">

                                                <td>
                                                    Subtotal:
                                                </td>

                                                <td class="text-right">

                                                    $<?= number_format(
                                                        $order['subtotal_amount'],
                                                        2
                                                    ) ?>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    Shipping:
                                                </td>

                                                <td class="text-right">

                                                    <?= htmlspecialchars(
                                                        $order['shipping_method_name']
                                                    ) ?>

                                                    -

                                                    $<?= number_format(
                                                        $order['shipping_cost'],
                                                        2
                                                    ) ?>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    Payment:
                                                </td>

                                                <td class="text-right">

                                                    <?php if (
                                                        $order['payment_method']
                                                        === 'cod'
                                                    ): ?>

                                                        Cash on Delivery

                                                    <?php elseif (
                                                        $order['payment_method']
                                                        === 'paypal'
                                                    ): ?>

                                                        PayPal

                                                    <?php else: ?>

                                                        <?= htmlspecialchars(
                                                            $order['payment_method']
                                                        ) ?>

                                                    <?php endif; ?>

                                                </td>

                                            </tr>

                                            <tr class="summary-total">

                                                <td>
                                                    Total:
                                                </td>

                                                <td class="text-right">

                                                    $<?= number_format(
                                                        $order['total_amount'],
                                                        2
                                                    ) ?>

                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>
                                    
                                                                        <?php if (
                                        $order['order_status']
                                        === 'processing'
                                    ): ?>

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
                                                                        <a
                                        href="orders.php"
                                        class="btn btn-outline-primary-2 btn-block"
                                    >
                                        BACK TO MY ORDERS
                                    </a>

                                    <a
                                        href="shop.php"
                                        class="btn btn-outline-dark-2 btn-block"
                                    >
                                        CONTINUE SHOPPING
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>
        <?php include '../includes/footer.php'; ?>

</div>

<button
    id="scroll-top"
    title="Back to Top"
>
    <i class="icon-arrow-up"></i>
</button>

<script src="assets/js/jquery.min.js"></script>

<script src="assets/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/jquery.hoverIntent.min.js"></script>

<script src="assets/js/jquery.waypoints.min.js"></script>

<script src="assets/js/superfish.min.js"></script>

<script src="assets/js/owl.carousel.min.js"></script>

<script src="assets/js/main.js"></script>

</body>

</html>