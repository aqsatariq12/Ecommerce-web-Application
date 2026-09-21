<?php

require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Auth.php';
require_once '../core/Order.php';
require_once '../core/Session.php';

Session::start();

$user = Auth::user();

/*
 * Cancel order
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $orderId = (int) ($_POST['order_id'] ?? 0);

    if ($orderId <= 0) {
        die("Invalid order.");
    }

    try {

        Order::cancel(
            $orderId,
            $user['id']
        );

        Session::set(
            'success',
            'Order cancelled successfully.'
        );

        header("Location: orders.php");
        exit;

    } catch (Exception $e) {

        Session::set(
            'error',
            $e->getMessage()
        );

        header("Location: orders.php");
        exit;
    }
}

/*
 * Get customer's orders
 */

$orders = Order::getByUserId($user['id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
    >

    <title>My Orders - ClothWear</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/demos/demo-4.css">

</head>

<body>

<div class="page-wrapper">

    <?php include '../includes/header.php'; ?>

    <main class="main">

        <div
            class="page-header text-center"
            style="background-image: url('assets/images/page-header-bg.jpg')"
        >
            <div class="container">

                <h1 class="page-title">
                    My Orders
                    <span>Shop</span>
                </h1>

            </div>
        </div>

        <nav aria-label="breadcrumb" class="breadcrumb-nav">

            <div class="container">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">
                        <a href="index.php">Home</a>
                    </li>

                    <li class="breadcrumb-item active">
                        My Orders
                    </li>

                </ol>

            </div>

        </nav>

        <div class="page-content">

            <div class="container">

                <div class="row">

                    <div class="col-lg-12">

                        <h2 class="checkout-title">
                            My Orders
                        </h2>

                        <?php if (empty($orders)): ?>

                            <div class="text-center py-5">

                                <h3>No Orders Yet</h3>

                                <p class="text-muted">
                                    You have not placed any orders yet.
                                </p>

                                <a
                                    href="shop.php"
                                    class="btn btn-outline-primary-2"
                                >
                                    START SHOPPING
                                </a>

                            </div>

                        <?php else: ?>

                            <div class="table-responsive">

                                <table class="table">

                                    <thead>

                                        <tr>

                                            <th>Order</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Action</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php foreach ($orders as $order): ?>

                                            <tr>

                                                <td>
                                                    <strong>
                                                        #<?= htmlspecialchars(
                                                            $order['order_number']
                                                        ) ?>
                                                    </strong>
                                                </td>

                                                <td>
                                                    <?= date(
                                                        'M d, Y',
                                                        strtotime($order['created_at'])
                                                    ) ?>
                                                </td>

                                                <td>
                                                    $<?= number_format(
                                                        $order['total_amount'],
                                                        2
                                                    ) ?>
                                                </td>

                                                <td>
                                                    <?php if ($order['payment_method'] === 'cod'): ?>

                                                        Cash on Delivery

                                                    <?php elseif ($order['payment_method'] === 'paypal'): ?>

                                                        PayPal

                                                    <?php else: ?>

                                                        <?= htmlspecialchars(
                                                            $order['payment_method']
                                                        ) ?>

                                                    <?php endif; ?>
                                                </td>

                                                <td>

                                                    <?php
                                                    $status = $order['order_status'];
                                                    ?>

                                                    <span class="badge badge-secondary">
                                                        <?= htmlspecialchars(
                                                            ucfirst($status)
                                                        ) ?>
                                                    </span>

                                                </td>

                                                <td>

<a
    href="order-detail.php?order=<?= urlencode(
        $order['order_number']
    ) ?>"
    class="btn btn-sm btn-outline-dark"
>
    View
</a>

                                                    <?php if ($status === 'processing'): ?>

                                                        <form
                                                            action="orders.php"
                                                            method="POST"
                                                            style="display: inline-block;"
                                                        >

                                                            <input
                                                                type="hidden"
                                                                name="order_id"
                                                                value="<?= (int) $order['id'] ?>"
                                                            >

                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Are you sure you want to cancel this order?');"
                                                            >
                                                                Cancel
                                                            </button>

                                                        </form>

                                                    <?php endif; ?>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        <?php endif; ?>

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