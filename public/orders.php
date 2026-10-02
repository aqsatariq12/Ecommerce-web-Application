<?php

require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Auth.php';
require_once '../core/Order.php';
require_once '../core/Session.php';
require_once '../core/Mail.php';

Session::start();

$user = Auth::user();

/*
 * Cancel order
 */

/*
 * Cancel order
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $orderId = (int) ($_POST['order_id'] ?? 0);

    if ($orderId <= 0) {
        die("Invalid order.");
    }

    try {

        /*
         * Get the customer's order first
         *
         * This also makes sure the order belongs
         * to the currently logged-in customer.
         */

        $order = Order::getByIdAndUserId(
            $orderId,
            $user['id']
        );

        if (!$order) {
            throw new Exception("Order not found.");
        }


        /*
         * Store information that we need
         * for the cancellation email.
         */

        $customerName = $user['name'];
        $customerEmail = $user['email'];

        $orderNumber = $order['order_number'];
        $totalAmount = $order['total_amount'];

        $paymentMethod = $order['payment_method'];


        /*
         * Cancel the order
         *
         * This handles:
         *
         * - status validation
         * - stock restoration
         * - Stripe refund
         * - order status update
         */

        Order::cancel(
            $orderId,
            $user['id']
        );


        /*
         * Get the order again after cancellation.
         *
         * This is important because a Stripe order may
         * have changed:
         *
         * completed → refunded
         */

        $updatedOrder = Order::getByIdAndUserId(
            $orderId,
            $user['id']
        );


        /*
         * Send professional cancellation email
         */

        Mail::sendCancellationEmail(
            $customerEmail,
            $customerName,
            $orderNumber,
            $totalAmount,
            $paymentMethod,
            $updatedOrder['payment_status']
        );


        /*
         * Success message
         */

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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>My Orders - ElectroCart</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/demos/demo-4.css">

</head>

<body>

    <div class="page-wrapper">

        <?php include '../includes/header.php'; ?>

        <main class="main">

            <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
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

                                    <a href="shop.php" class="btn btn-outline-primary-2">
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

                                                        <a href="order-detail.php?order=<?= urlencode(
                                                            $order['order_number']
                                                        ) ?>" class="btn btn-sm btn-outline-dark">
                                                            View
                                                        </a>

                                                        <?php if ($status === 'processing'): ?>

                                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                                data-toggle="modal"
                                                                data-target="#cancelOrderModal<?= (int) $order['id'] ?>">
                                                                Cancel
                                                            </button>

                                                        <?php endif; ?>

                                                    </td>

                                                </tr>

                                            <?php endforeach; ?>

                                        </tbody>

                                    </table>

                                </div>
                                <?php foreach ($orders as $order): ?>

                                    <?php if ($order['order_status'] === 'processing'): ?>

                                        <div class="modal fade" id="cancelOrderModal<?= (int) $order['id'] ?>" tabindex="-1"
                                            role="dialog" aria-labelledby="cancelOrderModalLabel<?= (int) $order['id'] ?>"
                                            aria-hidden="true">

                                            <div class="modal-dialog modal-dialog-centered" role="document">

                                                <div class="modal-content">

                                                    <div class="modal-header">

                                                        <h5 class="modal-title" id="cancelOrderModalLabel<?= (int) $order['id'] ?>">
                                                            Cancel Order
                                                        </h5>

                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>

                                                    </div>

                                                    <div class="modal-body p-4">

                                                        <p class="mb-0">
                                                            Are you sure you want to cancel this order?
                                                        </p>

                                                        <small class="text-muted">
                                                            Order #<?= htmlspecialchars($order['order_number']) ?>
                                                        </small>

                                                    </div>

                                                    <div class="modal-footer">

                                                        <button type="button" class="btn btn-secondary cancel-order-no-btn"
                                                            data-dismiss="modal">

                                                            No

                                                        </button>

                                                        <form action="orders.php" method="POST" style="display: inline;"
                                                            class="cancel-order-form">

                                                            <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">

                                                            <button type="submit" class="btn btn-danger cancel-order-btn">

                                                                <span class="cancel-order-text">
                                                                    Yes, Cancel Order
                                                                </span>

                                                                <span
                                                                    class="cancel-order-loader spinner-border spinner-border-sm ms-2"
                                                                    role="status" aria-hidden="true" style="display: none;">
                                                                </span>

                                                            </button>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </main>

        <?php include '../includes/footer.php'; ?>

    </div>

    <button id="scroll-top" title="Back to Top">
        <i class="icon-arrow-up"></i>
    </button>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script>

        document.querySelectorAll('.cancel-order-form').forEach(function (form) {

            form.addEventListener('submit', function () {

                const button = form.querySelector('.cancel-order-btn');
                const buttonText = form.querySelector('.cancel-order-text');
                const loader = form.querySelector('.cancel-order-loader');

                const modal = form.closest('.modal');
                const noButton = modal.querySelector('.cancel-order-no-btn');

                // Prevent multiple submissions
                button.disabled = true;

                // Disable "No" button
                noButton.disabled = true;

                // Change button text
                buttonText.textContent = 'Cancelling Order...';

                // Show loader
                loader.style.display = 'inline-block';

            });

        });

    </script>


</body>

</html>