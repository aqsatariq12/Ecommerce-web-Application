<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';

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
    'failed'
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
                order_status
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
    // CASE 2:
    // CANCELLED ORDER IS BEING RESTORED
    //
    // Example:
    // cancelled -> processing
    // cancelled -> shipped
    // =================================================

    if (
        $currentOrderStatus === 'cancelled' &&
        $orderStatus !== 'cancelled'
    ) {

        $stockSql = "UPDATE products
                     SET stock = stock - :quantity
                     WHERE id = :product_id
                     AND stock >= :quantity";

        $stockStmt = $pdo->prepare($stockSql);


        foreach ($items as $item) {

            $quantity = (int) $item['quantity'];

            $productId = (int) $item['product_id'];


            $stockStmt->execute([
                ':quantity' => $quantity,
                ':product_id' => $productId
            ]);


            // =========================================
            // NOT ENOUGH STOCK
            // =========================================

            if ($stockStmt->rowCount() === 0) {

                throw new Exception(
                    'Not enough stock to restore this order.'
                );
            }
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