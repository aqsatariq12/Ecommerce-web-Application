<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';

Middleware::admin();
Session::start();

$pageTitle = "Order Details";


// ==================================================
// GET ORDER ID
// ==================================================

$orderId = (int) ($_GET['id'] ?? 0);

if ($orderId <= 0) {

    Session::setFlash(
        'error',
        'Invalid order ID.'
    );

    header('Location: index.php');
    exit;
}


// ==================================================
// FETCH ORDER
// ==================================================

$sql = "SELECT
            o.id,
            o.user_id,
            o.billing_full_name,
            o.billing_phone,
            o.billing_country,
            o.billing_address_line1,
            o.billing_address_line2,
            o.billing_city,
            o.billing_state,
            o.billing_postcode,
            o.order_number,
            o.subtotal_amount,
            o.shipping_method_id,
            o.shipping_method_name,
            o.shipping_cost,
            o.total_amount,
            o.payment_method,
            o.payment_status,
            o.order_status,
            o.transaction_id,
            o.created_at,

            u.name AS customer_name,
            u.email AS customer_email

        FROM orders o

        INNER JOIN users u
            ON u.id = o.user_id

        WHERE o.id = :id

        LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $orderId
]);

$order = $stmt->fetch();


// ==================================================
// ORDER NOT FOUND
// ==================================================

if (!$order) {

    Session::setFlash(
        'error',
        'Order not found.'
    );

    header('Location: index.php');
    exit;
}


// ==================================================
// FETCH ORDER ITEMS
// ==================================================

$sql = "SELECT
            oi.id,
            oi.product_id,
            oi.quantity,
            oi.unit_price,
            oi.subtotal,

            p.name AS product_name,
            p.image AS product_image,

            c.name AS category_name

        FROM order_items oi

        INNER JOIN products p
            ON p.id = oi.product_id

        LEFT JOIN categories c
            ON c.id = p.category_id

        WHERE oi.order_id = :order_id

        ORDER BY oi.id ASC";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':order_id' => $orderId
]);

$orderItems = $stmt->fetchAll();


// ==================================================
// CALCULATED VALUES
// ==================================================

$subtotal = (float) $order['subtotal_amount'];

$shipping = (float) $order['shipping_cost'];

$grandTotal = (float) $order['total_amount'];

$discount = 0;


// ==================================================
// PAYMENT METHOD
// ==================================================

if ($order['payment_method'] === 'cod') {

    $paymentMethod = 'Cash on Delivery';

} elseif ($order['payment_method'] === 'paypal') {

    $paymentMethod = 'PayPal';

} else {

    $paymentMethod = ucfirst(
        $order['payment_method']
    );
}


// ==================================================
// DATE
// ==================================================

$orderDate = date(
    'd M Y, h:i A',
    strtotime($order['created_at'])
);


// ==================================================
// BUILD ADDRESS
// ==================================================

$addressParts = [];

if (!empty($order['billing_address_line1'])) {
    $addressParts[] = $order['billing_address_line1'];
}

if (!empty($order['billing_address_line2'])) {
    $addressParts[] = $order['billing_address_line2'];
}

if (!empty($order['billing_city'])) {
    $addressParts[] = $order['billing_city'];
}

if (!empty($order['billing_state'])) {
    $addressParts[] = $order['billing_state'];
}

if (!empty($order['billing_postcode'])) {
    $addressParts[] = $order['billing_postcode'];
}

if (!empty($order['billing_country'])) {
    $addressParts[] = $order['billing_country'];
}

$shippingAddress = implode(
    ', ',
    $addressParts
);


// ==================================================
// STATUS HELPERS
// ==================================================

$orderStatus = strtolower(
    $order['order_status']
);

$paymentStatus = strtolower(
    $order['payment_status']
);


$orderStatusClass = 'status-info';

if ($orderStatus === 'delivered') {
    $orderStatusClass = 'status-success';
} elseif ($orderStatus === 'shipped') {
    $orderStatusClass = 'status-warning';
} elseif ($orderStatus === 'cancelled') {
    $orderStatusClass = 'status-danger';
}


$paymentStatusClass = 'status-warning';

if ($paymentStatus === 'completed') {
    $paymentStatusClass = 'status-success';
} elseif ($paymentStatus === 'failed') {
    $paymentStatusClass = 'status-danger';
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
    >

    <link
        rel="apple-touch-icon"
        sizes="76x76"
        href="../assets/img/apple-icon.png"
    >

    <link
        rel="icon"
        type="image/png"
        href="../assets/img/favicon.png"
    >

    <title>
        Order Details - ClothWear Admin
    </title>


    <!-- Fonts -->

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900"
    >


    <!-- Nucleo Icons -->

    <link
        href="../assets/css/nucleo-icons.css"
        rel="stylesheet"
    >

    <link
        href="../assets/css/nucleo-svg.css"
        rel="stylesheet"
    >


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- Material Icons -->

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
    >


    <!-- Material Dashboard -->

    <link
        id="pagestyle"
        href="../assets/css/material-dashboard.css?v=3.2.0"
        rel="stylesheet"
    >


    <style>

        /* ==================================================
           MAIN CARD
        ================================================== */

        .order-card {
            border-radius: 16px;
            overflow: hidden;
        }


        /* ==================================================
           PAGE HEADER
        ================================================== */

        .page-heading h4 {
            color: #344767;
            font-weight: 700;
        }


        .page-heading p {
            color: #8392ab;
        }


        /* ==================================================
           ORDER HEADER
        ================================================== */

        .order-card-header {
            padding: 24px 30px;
            border-bottom: 1px solid #e9ecef;
        }


        .order-title {
            color: #344767;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }


        .order-date {
            color: #8392ab;
            font-size: 13px;
            margin-bottom: 8px;
        }


        .order-id-info {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 5px 10px;

            background: #f8f9fa;

            border-radius: 7px;

            color: #8392ab;

            font-size: 11px;

            font-weight: 600;

        }


        /* ==================================================
           STATUS BADGES
        ================================================== */

        .order-status-badge {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 7px 12px;

            border-radius: 7px;

            font-size: 11px;

            font-weight: 700;

            white-space: nowrap;

        }


        .status-success {

            background-color: #d1e7dd;

            color: #146c43;

        }


        .status-warning {

            background-color: #fff3cd;

            color: #997404;

        }


        .status-info {

            background-color: #cff4fc;

            color: #087990;

        }


        .status-danger {

            background-color: #f8d7da;

            color: #842029;

        }


        /* ==================================================
           INFORMATION BOXES
        ================================================== */

        .order-info-box {

            height: 100%;

            padding: 22px;

            border: 1px solid #e9ecef;

            border-radius: 13px;

            background: #fff;

            transition: all 0.2s ease;

        }


        .order-info-box:hover {

            border-color: #dee2e6;

            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);

        }


        .order-info-box h6 {

            display: flex;

            align-items: center;

            gap: 5px;

            color: #344767;

            font-size: 14px;

            font-weight: 700;

            margin-bottom: 20px;

        }


        .order-info-box h6 i {

            font-size: 14px;

        }


        .info-label {

            display: block;

            color: #8392ab;

            font-size: 10px;

            font-weight: 700;

            letter-spacing: 0.3px;

            text-transform: uppercase;

            margin-bottom: 5px;

        }


        .info-value {

            color: #344767;

            font-size: 14px;

            font-weight: 600;

            line-height: 1.6;

            margin-bottom: 15px;

            overflow-wrap: anywhere;

            word-break: break-word;

        }


        .address-value {

            min-height: 45px;

        }


        /* ==================================================
           SECTION TITLE
        ================================================== */

        .section-title {

            color: #344767;

            font-size: 16px;

            font-weight: 700;

        }


        /* ==================================================
           ORDER ITEMS TABLE
        ================================================== */

        .order-items-wrapper {

            border: 1px solid #e9ecef;

            border-radius: 12px;

            overflow: hidden;

        }


        .order-items-table {

            min-width: 700px;

        }


        .order-items-table th {

            padding: 14px 12px;

            background: #f8f9fa;

            color: #8392ab;

            font-size: 10px;

            font-weight: 700;

            text-transform: uppercase;

            border-bottom: 1px solid #e9ecef;

        }


        .order-items-table td {

            padding: 16px 12px;

            vertical-align: middle;

            border-bottom: 1px solid #f0f2f5;

        }


        .order-items-table tbody tr:last-child td {

            border-bottom: 0;

        }


        .product-wrapper {

            display: flex;

            align-items: center;

            min-width: 250px;

        }


        .product-image {

            width: 55px;

            height: 55px;

            flex-shrink: 0;

            object-fit: cover;

            border-radius: 9px;

            border: 1px solid #e9ecef;

            margin-right: 13px;

        }


        .product-placeholder {

            width: 55px;

            height: 55px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f8f9fa;

            color: #8392ab;

            border-radius: 9px;

            margin-right: 13px;

        }


        .product-name {

            color: #344767;

            font-size: 13px;

            font-weight: 700;

            margin-bottom: 3px;

            overflow-wrap: anywhere;

        }


        .product-category {

            color: #8392ab;

            font-size: 10px;

            font-weight: 500;

        }


        .quantity-badge {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-width: 32px;

            height: 30px;

            padding: 0 8px;

            background: #f8f9fa;

            border-radius: 7px;

            color: #344767;

            font-size: 12px;

            font-weight: 700;

        }


        .price-text {

            color: #344767;

            font-size: 13px;

            font-weight: 600;

            white-space: nowrap;

        }


        /* ==================================================
           SUMMARY
        ================================================== */

        .summary-wrapper {

            display: flex;

            justify-content: flex-end;

            margin-top: 20px;

        }


        .order-summary {

            width: 100%;

            max-width: 360px;

            padding: 20px;

            border: 1px solid #e9ecef;

            border-radius: 12px;

            background: #fff;

        }


        .summary-title {

            color: #344767;

            font-size: 14px;

            font-weight: 700;

            margin-bottom: 18px;

        }


        .summary-row {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 12px;

            color: #67748e;

            font-size: 13px;

        }


        .summary-row strong {

            color: #344767;

            font-weight: 600;

            white-space: nowrap;

        }


        .summary-total {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding-top: 15px;

            margin-top: 14px;

            border-top: 1px solid #e9ecef;

            color: #344767;

            font-size: 15px;

            font-weight: 700;

        }


        .summary-total strong {

            font-size: 17px;

            white-space: nowrap;

        }


        /* ==================================================
           ACTIONS
        ================================================== */

        .order-actions {

            display: flex;

            justify-content: flex-end;

            align-items: center;

            gap: 10px;

            padding-top: 22px;

            margin-top: 24px;

            border-top: 1px solid #e9ecef;

        }


        /* ==================================================
           MOBILE
        ================================================== */

        @media (max-width: 991.98px) {

            .order-card-header {

                padding: 22px;

            }

        }


        @media (max-width: 767.98px) {

            .order-card-header {

                padding: 20px;

            }


            .order-card-header .d-flex {

                align-items: flex-start !important;

            }


            .order-title {

                font-size: 18px;

            }


            .order-info-box {

                padding: 18px;

            }


            .summary-wrapper {

                justify-content: stretch;

            }


            .order-summary {

                max-width: 100%;

            }


            .order-actions {

                flex-direction: column;

                align-items: stretch;

            }


            .order-actions .btn {

                width: 100%;

                margin: 0 !important;

            }

        }


        @media (max-width: 575.98px) {

            .container-fluid {

                padding-left: 12px !important;

                padding-right: 12px !important;

            }


            .page-heading h4 {

                font-size: 20px;

            }


            .order-card-header {

                padding: 17px;

            }


            .order-card .card-body {

                padding: 17px !important;

            }


            .order-title {

                font-size: 17px;

            }


            .order-date {

                font-size: 12px;

            }


            .order-status-badge {

                font-size: 10px;

                padding: 6px 9px;

            }


            .order-info-box {

                padding: 16px;

            }


            .order-info-box h6 {

                font-size: 13px;

            }


            .info-value {

                font-size: 13px;

            }


            .section-title {

                font-size: 15px;

            }


            .order-summary {

                padding: 17px;

            }

        }


        @media (max-width: 375px) {

            .page-heading p {

                font-size: 11px;

            }


            .back-orders-btn {

                width: 100%;

            }


            .order-card-header .d-flex {

                flex-direction: column;

            }


            .order-card-header .d-flex > div:last-child {

                width: 100%;

            }


            .order-card-header .d-flex > div:last-child .order-status-badge {

                width: 100%;

                justify-content: center;

            }


            .product-wrapper {

                min-width: 220px;

            }


            .product-image,

            .product-placeholder {

                width: 45px;

                height: 45px;

                margin-right: 10px;

            }

        }


        /* ==================================================
           PRINT
        ================================================== */

        @media print {

            .sidenav,

            .navbar,

            .order-actions,

            .page-heading .back-orders-btn {

                display: none !important;

            }


            .main-content {

                margin-left: 0 !important;

            }


            .order-card {

                box-shadow: none !important;

            }


            body {

                background: #fff !important;

            }

        }

    </style>

</head>


<body class="g-sidenav-show bg-gray-100">


    <!-- ==================================================
         ADMIN HEADER
    ================================================== -->

    <?php require_once '../../includes/admin-header.php'; ?>


    <!-- ==================================================
         ADMIN SIDEBAR
    ================================================== -->

    <?php require_once '../../includes/admin-sidenavbar.php'; ?>


    <!-- ==================================================
         MAIN CONTENT
    ================================================== -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">


        <div class="container-fluid py-4">


            <!-- ==================================================
                 PAGE HEADER
            ================================================== -->

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 page-heading">

                <div>

                    <h4 class="mb-1">
                        Order Details
                    </h4>

                    <p class="text-sm mb-0">
                        View complete information about this order
                    </p>

                </div>


                <a
                    href="index.php"
                    class="btn btn-outline-secondary btn-sm mb-0 back-orders-btn"
                >

                    <i class="fa-solid fa-arrow-left me-1"></i>

                    Back to Orders

                </a>

            </div>


            <!-- ==================================================
                 ORDER CARD
            ================================================== -->

            <div class="card order-card mb-4">


                <!-- ORDER HEADER -->

                <div class="order-card-header">

                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">


                        <div>

                            <div class="order-title">

                                Order #<?= htmlspecialchars($order['order_number']) ?>

                            </div>


                            <div class="order-date">

                                <i class="fa-regular fa-calendar me-1"></i>

                                Placed on
                                <?= htmlspecialchars($orderDate) ?>

                            </div>


                            <span class="order-id-info">

                                <i class="fa-solid fa-hashtag"></i>

                                Order ID:
                                <?= (int) $order['id'] ?>

                            </span>

                        </div>


                        <!-- ORDER STATUS -->

                        <div>

                            <span class="order-status-badge <?= $orderStatusClass ?>">

                                <?php if ($orderStatus === 'delivered'): ?>

                                    <i class="fa-solid fa-circle-check"></i>

                                <?php elseif ($orderStatus === 'processing'): ?>

                                    <i class="fa-solid fa-clock"></i>

                                <?php elseif ($orderStatus === 'shipped'): ?>

                                    <i class="fa-solid fa-truck"></i>

                                <?php elseif ($orderStatus === 'cancelled'): ?>

                                    <i class="fa-solid fa-circle-xmark"></i>

                                <?php else: ?>

                                    <i class="fa-solid fa-circle-info"></i>

                                <?php endif; ?>


                                <?= htmlspecialchars(ucfirst($orderStatus)) ?>

                            </span>

                        </div>

                    </div>

                </div>


                <!-- ==================================================
                     CARD BODY
                ================================================== -->

                <div class="card-body p-4">


                    <!-- ==================================================
                         CUSTOMER / SHIPPING / PAYMENT
                    ================================================== -->

                    <div class="row mb-4">


                        <!-- CUSTOMER -->

                        <div class="col-lg-4 col-md-6 mb-3">

                            <div class="order-info-box">

                                <h6>

                                    <i class="fa-solid fa-user"></i>

                                    Customer Information

                                </h6>


                                <span class="info-label">
                                    Name
                                </span>

                                <div class="info-value">

                                    <?= htmlspecialchars(
                                        $order['billing_full_name']
                                    ) ?>

                                </div>


                                <span class="info-label">
                                    Email
                                </span>

                                <div class="info-value">

                                    <?= htmlspecialchars(
                                        $order['customer_email']
                                    ) ?>

                                </div>


                                <span class="info-label">
                                    Phone
                                </span>

                                <div class="info-value mb-0">

                                    <?= htmlspecialchars(
                                        $order['billing_phone']
                                    ) ?>

                                </div>

                            </div>

                        </div>


                        <!-- SHIPPING -->

                        <div class="col-lg-4 col-md-6 mb-3">

                            <div class="order-info-box">

                                <h6>

                                    <i class="fa-solid fa-location-dot"></i>

                                    Shipping Information

                                </h6>


                                <span class="info-label">
                                    Shipping Address
                                </span>

                                <div class="info-value address-value">

                                    <?= !empty($shippingAddress)
                                        ? htmlspecialchars($shippingAddress)
                                        : 'N/A'
                                    ?>

                                </div>


                                <span class="info-label">
                                    Shipping Method
                                </span>

                                <div class="info-value">

                                    <?= !empty($order['shipping_method_name'])
                                        ? htmlspecialchars(
                                            $order['shipping_method_name']
                                        )
                                        : 'N/A'
                                    ?>

                                </div>


                                <span class="info-label">
                                    Delivery Status
                                </span>

                                <div class="info-value mb-0">

                                    <span class="order-status-badge <?= $orderStatusClass ?>">

                                        <?= htmlspecialchars(
                                            ucfirst($orderStatus)
                                        ) ?>

                                    </span>

                                </div>

                            </div>

                        </div>


                        <!-- PAYMENT -->

                        <div class="col-lg-4 col-md-12 mb-3">

                            <div class="order-info-box">

                                <h6>

                                    <i class="fa-solid fa-credit-card"></i>

                                    Payment Information

                                </h6>


                                <span class="info-label">
                                    Payment Method
                                </span>

                                <div class="info-value">

                                    <?= htmlspecialchars(
                                        $paymentMethod
                                    ) ?>

                                </div>


                                <span class="info-label">
                                    Payment Status
                                </span>

                                <div class="info-value">

                                    <span class="order-status-badge <?= $paymentStatusClass ?>">

                                        <?= htmlspecialchars(
                                            ucfirst($paymentStatus)
                                        ) ?>

                                    </span>

                                </div>


                                <?php if (!empty($order['transaction_id'])): ?>

                                    <span class="info-label">
                                        Transaction ID
                                    </span>

                                    <div class="info-value mb-0">

                                        <?= htmlspecialchars(
                                            $order['transaction_id']
                                        ) ?>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>


                    <!-- ==================================================
                         ORDER ITEMS
                    ================================================== -->

                    <div class="mb-4">


                        <h6 class="section-title mb-3">

                            <i class="fa-solid fa-box-open me-1"></i>

                            Order Items

                        </h6>


                        <div class="table-responsive order-items-wrapper">

                            <table class="table align-items-center mb-0 order-items-table">


                                <thead>

                                    <tr>

                                        <th>
                                            Product
                                        </th>

                                        <th class="text-center">
                                            Quantity
                                        </th>

                                        <th class="text-end">
                                            Unit Price
                                        </th>

                                        <th class="text-end">
                                            Total
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php if (empty($orderItems)): ?>

                                        <tr>

                                            <td
                                                colspan="4"
                                                class="text-center py-5"
                                            >

                                                <i
                                                    class="fa-solid fa-box-open fa-2x text-secondary mb-3"
                                                ></i>

                                                <p class="text-sm text-secondary mb-0">
                                                    No items found for this order.
                                                </p>

                                            </td>

                                        </tr>

                                    <?php else: ?>

                                        <?php foreach ($orderItems as $item): ?>

                                            <tr>


                                                <!-- PRODUCT -->

                                                <td>

                                                    <div class="product-wrapper">


                                                        <?php if (!empty($item['product_image'])): ?>

                                                            <img
                                                                src="../../public/uploads/products/<?= htmlspecialchars($item['product_image']) ?>"
                                                                alt="<?= htmlspecialchars($item['product_name']) ?>"
                                                                class="product-image"
                                                            >

                                                        <?php else: ?>

                                                            <div class="product-placeholder">

                                                                <i class="fa-solid fa-image"></i>

                                                            </div>

                                                        <?php endif; ?>


                                                        <div>

                                                            <div class="product-name">

                                                                <?= htmlspecialchars(
                                                                    $item['product_name']
                                                                ) ?>

                                                            </div>


                                                            <div class="product-category">

                                                                <?= !empty($item['category_name'])
                                                                    ? htmlspecialchars(
                                                                        $item['category_name']
                                                                    )
                                                                    : 'No Category'
                                                                ?>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </td>


                                                <!-- QUANTITY -->

                                                <td class="text-center">

                                                    <span class="quantity-badge">

                                                        <?= (int) $item['quantity'] ?>

                                                    </span>

                                                </td>


                                                <!-- PRICE -->

                                                <td class="text-end">

                                                    <span class="price-text">

                                                        PKR
                                                        <?= number_format(
                                                            (float) $item['unit_price'],
                                                            2
                                                        ) ?>

                                                    </span>

                                                </td>


                                                <!-- TOTAL -->

                                                <td class="text-end">

                                                    <span class="price-text">

                                                        PKR
                                                        <?= number_format(
                                                            (float) $item['subtotal'],
                                                            2
                                                        ) ?>

                                                    </span>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>


                    <!-- ==================================================
                         ORDER SUMMARY
                    ================================================== -->

                    <div class="summary-wrapper">

                        <div class="order-summary">


                            <div class="summary-title">

                                Order Summary

                            </div>


                            <div class="summary-row">

                                <span>
                                    Subtotal
                                </span>

                                <strong>

                                    PKR
                                    <?= number_format(
                                        $subtotal,
                                        2
                                    ) ?>

                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    Shipping
                                </span>

                                <strong>

                                    PKR
                                    <?= number_format(
                                        $shipping,
                                        2
                                    ) ?>

                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    Discount
                                </span>

                                <strong>

                                    -PKR
                                    <?= number_format(
                                        $discount,
                                        2
                                    ) ?>

                                </strong>

                            </div>


                            <div class="summary-total">

                                <span>
                                    Grand Total
                                </span>

                                <strong>

                                    PKR
                                    <?= number_format(
                                        $grandTotal,
                                        2
                                    ) ?>

                                </strong>

                            </div>

                        </div>

                    </div>


                    <!-- ==================================================
                         ACTIONS
                    ================================================== -->

                    <div class="order-actions">


                        <a
                            href="index.php"
                            class="btn btn-light mb-0"
                        >

                            <i class="fa-solid fa-arrow-left me-1"></i>

                            Back to Orders

                        </a>


                        <button
                            type="button"
                            class="btn btn-outline-secondary mb-0"
                            onclick="window.print()"
                        >

                            <i class="fa-solid fa-print me-1"></i>

                            Print Order

                        </button>


                    </div>


                </div>

            </div>


            <!-- ==================================================
                 ADMIN FOOTER
            ================================================== -->

            <?php require_once '../../includes/admin-footer.php'; ?>


        </div>

    </main>


    <!-- ==================================================
         SCRIPTS
    ================================================== -->

    <script src="../assets/js/core/popper.min.js"></script>

    <script src="../assets/js/core/bootstrap.min.js"></script>

    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>

    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>

</body>

</html>