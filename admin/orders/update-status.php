<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';
require_once '../../config/stripe.php';

Middleware::admin();
Session::start();


// =====================================================
// ONLY ALLOW POST REQUEST
// =====================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    Session::setFlash(
        'error',
        'Invalid request.'
    );

    header('Location: index.php');
    exit;
}


// =====================================================
// GET VALUES FROM FORM
// =====================================================

$orderId = (int) ($_POST['order_id'] ?? 0);

$paymentStatus = $_POST['payment_status'] ?? '';

$orderStatus = $_POST['order_status'] ?? '';


// =====================================================
// VALIDATE ORDER ID
// =====================================================

if ($orderId <= 0) {

    Session::setFlash(
        'error',
        'Invalid order ID.'
    );

    header('Location: index.php');
    exit;
}


// =====================================================
// ALLOWED PAYMENT STATUSES
// =====================================================

$allowedPaymentStatuses = [
    'pending',
    'completed',
    'failed',
    'refunded'

];


// =====================================================
// ALLOWED ORDER STATUSES
// =====================================================

$allowedOrderStatuses = [
    'processing',
    'shipped',
    'delivered',
    'cancelled'
];


// =====================================================
// VALIDATE PAYMENT STATUS
// =====================================================

if (!in_array($paymentStatus, $allowedPaymentStatuses, true)) {

    Session::setFlash(
        'error',
        'Invalid payment status.'
    );

    header('Location: index.php');
    exit;
}


// =====================================================
// VALIDATE ORDER STATUS
// =====================================================

if (!in_array($orderStatus, $allowedOrderStatuses, true)) {

    Session::setFlash(
        'error',
        'Invalid order status.'
    );

    header('Location: index.php');
    exit;
}


try {

    // =================================================
    // START TRANSACTION
    // =================================================

    $pdo->beginTransaction();


    // =================================================
    // GET CURRENT ORDER STATUS
    // =================================================

    $sql = "SELECT
            id,
            order_status,
            payment_method,
            payment_status,
            transaction_id
        FROM orders
        WHERE id = :id
        LIMIT 1";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $orderId
    ]);

    $order = $stmt->fetch(PDO::FETCH_ASSOC);


    // =================================================
    // ORDER NOT FOUND
    // =================================================

    if (!$order) {

        throw new Exception(
            'Order not found.'
        );
    }


    // =================================================
    // CURRENT ORDER STATUS
    // =================================================

    $currentOrderStatus = $order['order_status'];
    // =================================================
// VALIDATE ORDER STATUS TRANSITION
// =================================================

    $allowedOrderTransitions = [

        'processing' => [
            'processing',
            'shipped',
            'cancelled'
        ],

        'shipped' => [
            'shipped',
            'delivered',
            'cancelled'
        ],

        'delivered' => [
            'delivered'
        ],

        'cancelled' => [
            'cancelled'
        ]

    ];


    if (
        !in_array(
            $orderStatus,
            $allowedOrderTransitions[$currentOrderStatus],
            true
        )
    ) {

        throw new Exception(
            'Invalid order status transition.'
        );
    }

    // =================================================
// VALIDATE PAYMENT STATUS TRANSITION
// =================================================

    $currentPaymentStatus = $order['payment_status'];
    $paymentMethod = $order['payment_method'];

    $allowedPaymentTransitions = [

        'pending' => [
            'pending',
            'completed',
            'failed'
        ],

        'completed' => [
            'completed',
            'refunded'
        ],

        'failed' => [
            'failed'
        ],

        'refunded' => [
            'refunded'
        ]

    ];


    if (
        !in_array(
            $paymentStatus,
            $allowedPaymentTransitions[$currentPaymentStatus],
            true
        )
    ) {

        throw new Exception(
            'Invalid payment status transition.'
        );
    }

    // =================================================
// COD CANNOT BE REFUNDED
// =================================================

    if (
        $paymentMethod === 'cod' &&
        $paymentStatus === 'refunded'
    ) {

        throw new Exception(
            'COD orders cannot be marked as refunded.'
        );
    }

    // =================================================
    // GET ORDER ITEMS
    // =================================================

    $itemsSql = "SELECT
                    product_id,
                    quantity
                 FROM order_items
                 WHERE order_id = :order_id";

    $itemsStmt = $pdo->prepare($itemsSql);

    $itemsStmt->execute([
        ':order_id' => $orderId
    ]);

    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);


    // =================================================
    // CASE 1:
    // ORDER IS BEING CANCELLED
    //
    // Example:
    // processing -> cancelled
    // shipped    -> cancelled
    // =================================================

    if (
        $currentOrderStatus !== 'cancelled' &&
        $orderStatus === 'cancelled'
    ) {

        /*
         * =========================================
         * REFUND STRIPE PAYMENT
         * =========================================
         */

        if (
            $order['payment_method'] === 'stripe' &&
            $order['payment_status'] === 'completed' &&
            !empty($order['transaction_id'])
        ) {

            \Stripe\Refund::create([
                'payment_intent' => $order['transaction_id']
            ]);

            /*
             * Stripe refund successful
             *
             * Mark payment as refunded
             */

            $paymentStatus = 'refunded';
        }


        /*
         * =========================================
         * RESTORE STOCK
         * =========================================
         */

        $stockSql = "UPDATE products
                 SET stock = stock + :quantity
                 WHERE id = :product_id";

        $stockStmt = $pdo->prepare($stockSql);

        foreach ($items as $item) {

            $stockStmt->execute([
                ':quantity' => (int) $item['quantity'],
                ':product_id' => (int) $item['product_id']
            ]);
        }
    }


    // =================================================
    // UPDATE ORDER STATUS
    // =================================================

    $sql = "UPDATE orders
            SET
                payment_status = :payment_status,
                order_status = :order_status
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':payment_status' => $paymentStatus,
        ':order_status' => $orderStatus,
        ':id' => $orderId
    ]);


    // =================================================
    // COMMIT
    // =================================================

    $pdo->commit();


    // =================================================
    // SUCCESS MESSAGE
    // =================================================

    Session::setFlash(
        'success',
        'Order status updated successfully.'
    );


} catch (Exception $e) {

    // =================================================
    // ROLLBACK
    // =================================================

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }


    // =================================================
    // ERROR MESSAGE
    // =================================================

    Session::setFlash(
        'error',
        'Order could not be updated: ' . $e->getMessage()
    );
}


// =====================================================
// BACK TO ORDERS
// =====================================================

header('Location: index.php');
exit;