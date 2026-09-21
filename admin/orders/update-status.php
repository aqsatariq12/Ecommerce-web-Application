<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';

Middleware::admin();
Session::start();


// Only allow POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    Session::setFlash(
        'error',
        'Invalid request.'
    );

    header('Location: index.php');
    exit;
}


// Get values from form
$orderId = (int) ($_POST['order_id'] ?? 0);

$paymentStatus = $_POST['payment_status'] ?? '';

$orderStatus = $_POST['order_status'] ?? '';


// Validate order ID
if ($orderId <= 0) {

    Session::setFlash(
        'error',
        'Invalid order ID.'
    );

    header('Location: index.php');
    exit;
}


// Allowed payment statuses
$allowedPaymentStatuses = [
    'pending',
    'completed',
    'failed'
];


// Allowed order statuses
$allowedOrderStatuses = [
    'processing',
    'shipped',
    'delivered',
    'cancelled'
];


// Validate payment status
if (!in_array($paymentStatus, $allowedPaymentStatuses, true)) {

    Session::setFlash(
        'error',
        'Invalid payment status.'
    );

    header('Location: index.php');
    exit;
}


// Validate order status
if (!in_array($orderStatus, $allowedOrderStatuses, true)) {

    Session::setFlash(
        'error',
        'Invalid order status.'
    );

    header('Location: index.php');
    exit;
}


// Check whether order exists
$sql = "SELECT id
        FROM orders
        WHERE id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $orderId
]);

$order = $stmt->fetch();


// Order not found
if (!$order) {

    Session::setFlash(
        'error',
        'Order not found.'
    );

    header('Location: index.php');
    exit;
}


// Update order statuses
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


// Success message
Session::setFlash(
    'success',
    'Order status updated successfully.'
);


// Back to orders
header('Location: index.php');
exit;