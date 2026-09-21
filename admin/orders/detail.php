<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';

Middleware::admin();
Session::start();

$pageTitle = "Order Details";


// --------------------------------------------------
// GET ORDER ID
// --------------------------------------------------

$orderId = (int) ($_GET['id'] ?? 0);

if ($orderId <= 0) {

    Session::setFlash(
        'error',
        'Invalid order ID.'
    );

    header('Location: index.php');
    exit;
}


// --------------------------------------------------
// FETCH ORDER
// --------------------------------------------------

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


// --------------------------------------------------
// ORDER NOT FOUND
// --------------------------------------------------

if (!$order) {

    Session::setFlash(
        'error',
        'Order not found.'
    );

    header('Location: index.php');
    exit;
}


// --------------------------------------------------
// FETCH ORDER ITEMS
// --------------------------------------------------

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


// --------------------------------------------------
// CALCULATED VALUES
// --------------------------------------------------

$subtotal = (float) $order['subtotal_amount'];

$shipping = (float) $order['shipping_cost'];

$grandTotal = (float) $order['total_amount'];


// There is currently no discount column in your orders table.
$discount = 0;


// --------------------------------------------------
// PAYMENT METHOD DISPLAY
// --------------------------------------------------

if ($order['payment_method'] === 'cod') {

    $paymentMethod = 'Cash on Delivery';

} elseif ($order['payment_method'] === 'paypal') {

    $paymentMethod = 'PayPal';

} else {

    $paymentMethod = ucfirst(
        $order['payment_method']
    );

}


// --------------------------------------------------
// PAYMENT STATUS DISPLAY
// --------------------------------------------------

$paymentStatus = ucfirst(
    $order['payment_status']
);


// --------------------------------------------------
// ORDER STATUS DISPLAY
// --------------------------------------------------

$orderStatus = ucfirst(
    $order['order_status']
);


// --------------------------------------------------
// ORDER DATE
// --------------------------------------------------

$orderDate = date(
    'd M Y, h:i A',
    strtotime($order['created_at'])
);


// --------------------------------------------------
// BUILD BILLING / SHIPPING ADDRESS
// --------------------------------------------------

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

?>

<!DOCTYPE html>

<html lang="en">

<head>


    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">

    <link rel="icon" type="image/png" href="../assets/img/favicon.png">

    <title>
        Order Details - ClothWear Admin
    </title>


    <!-- Fonts -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900">


    <!-- Nucleo Icons -->

    <link href="../assets/css/nucleo-icons.css" rel="stylesheet">

    <link href="../assets/css/nucleo-svg.css" rel="stylesheet">


    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <!-- Material Icons -->

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">


    <!-- Material Dashboard CSS -->

    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet">


    <!-- =========================
     CUSTOM ORDER DETAIL CSS
========================== -->

    <style>
        /* =========================
       CARD
    ========================== */

        .order-card {
            border-radius: 16px;
            overflow: hidden;
        }


        /* =========================
       CARD HEADER
    ========================== */

        .order-card-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e9ecef;
        }


        .order-card-header h5 {
            font-weight: 700;
            color: #212529;
        }


        /* =========================
       ORDER ID
    ========================== */

        .order-id-info {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            margin-top: 5px;

            padding: 5px 10px;

            border-radius: 6px;

            background-color: #f8f9fa;

            color: #8392ab;

            font-size: 11px;

            font-weight: 600;
        }


        /* =========================
       INFO BOX
    ========================== */

        .order-info-box {

            padding: 20px;

            border: 1px solid #e9ecef;

            border-radius: 12px;

            height: 100%;
        }


        .order-info-box h6 {

            font-size: 14px;

            font-weight: 700;

            color: #344767;

            margin-bottom: 15px;
        }


        .info-label {

            display: block;

            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;

            color: #8392ab;

            margin-bottom: 4px;
        }


        .info-value {

            font-size: 14px;

            font-weight: 600;

            color: #344767;

            margin-bottom: 14px;
        }


        /* =========================
       STATUS BADGES
    ========================== */

        .order-status-badge {

            display: inline-block;

            padding: 6px 12px;

            border-radius: 6px;

            font-size: 11px;

            font-weight: 700;
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


        /* =========================
       ORDER ITEMS
    ========================== */

        .order-items-table th {

            font-size: 11px;

            text-transform: uppercase;

            color: #8392ab;

            font-weight: 700;

            border-bottom: 1px solid #e9ecef;
        }


        .order-items-table td {

            padding: 16px 12px;

            vertical-align: middle;

            border-bottom: 1px solid #f0f2f5;
        }


        .product-name {

            font-size: 14px;

            font-weight: 700;

            color: #344767;

            margin-bottom: 3px;
        }


        .product-category {

            font-size: 11px;

            color: #8392ab;
        }


        /* =========================
       SUMMARY
    ========================== */

        .order-summary {

            margin-left: auto;

            max-width: 350px;

            padding: 20px;
        }


        .summary-row {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 12px;

            font-size: 14px;

            color: #67748e;
        }


        .summary-total {

            display: flex;

            justify-content: space-between;

            align-items: center;

            padding-top: 15px;

            margin-top: 10px;

            border-top: 1px solid #e9ecef;

            font-size: 16px;

            font-weight: 700;

            color: #212529;
        }


        /* =========================
       ACTIONS
    ========================== */

        .order-actions {

            display: flex;

            justify-content: flex-end;

            gap: 10px;

            padding-top: 20px;

            margin-top: 10px;

            border-top: 1px solid #e9ecef;
        }


        /* =========================
       MOBILE
    ========================== */

        @media (max-width: 768px) {

            .order-card-header {
                padding: 20px;
            }


            .order-info-box {
                margin-bottom: 15px;
            }


            .order-summary {
                max-width: 100%;
            }


            .order-actions {
                flex-direction: column;
            }


            .order-actions .btn {
                width: 100%;
            }

        }
    </style>


</head>

<body class="g-sidenav-show bg-gray-100">


    <!-- =========================
     ADMIN HEADER
========================== -->

    <?php require_once '../../includes/admin-header.php'; ?>


    <!-- =========================
     ADMIN SIDEBAR
========================== -->

    <?php require_once '../../includes/admin-sidenavbar.php'; ?>


    <!-- =========================
     MAIN CONTENT
========================== -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">


        <div class="container-fluid py-4">



            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

                <div>

                    <h4 class="fw-bold text-dark mb-1">
                        Order Details
                    </h4>

                    <p class="text-sm text-secondary mb-0">
                        View complete information about this order </p>

                </div>


                <!-- BACK BUTTON -->

                <a href="index.php" class="btn btn-outline-secondary btn-sm">

                    <i class="fa-solid fa-arrow-left me-1"></i>

                    Back to Orders

                </a>

            </div>




            <div class="card order-card mb-4">


                <!-- CARD HEADER -->

                <div class="order-card-header">

                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

                        <div>

                            <h5 class="mb-1">
                                Order
                                #<?= htmlspecialchars($order['order_number']) ?>
                            </h5>

                            <p class="text-sm text-secondary mb-0">
                                Placed on
                                <?= htmlspecialchars($orderDate) ?>
                            </p>


                            <span class="order-id-info">

                                <i class="fa-solid fa-hashtag"></i>

                                Order ID:
                                <?= (int) $order['id'] ?>

                            </span>

                        </div>


                        <!-- ORDER STATUS -->

                        <!-- ORDER STATUS -->

                        <div>

                            <?php if ($order['order_status'] === 'delivered'): ?>

                                <span class="order-status-badge status-success">
                                    Delivered
                                </span>

                            <?php elseif ($order['order_status'] === 'processing'): ?>

                                <span class="order-status-badge status-info">
                                    Processing
                                </span>

                            <?php elseif ($order['order_status'] === 'shipped'): ?>

                                <span class="order-status-badge status-warning">
                                    Shipped
                                </span>

                            <?php elseif ($order['order_status'] === 'cancelled'): ?>

                                <span class="order-status-badge" style="background-color:#f8d7da;color:#842029;">
                                    Cancelled
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>


                <!-- CARD BODY -->

                <div class="card-body p-4">


                    <!-- =========================
                     CUSTOMER + SHIPPING + PAYMENT
                ========================== -->

                    <div class="row mb-4">


                        <!-- CUSTOMER INFORMATION -->

                        <div class="col-lg-4 col-md-6 mb-3">

                            <div class="order-info-box">

                                <h6>
                                    <i class="fa-solid fa-user me-2"></i>
                                    Customer Information
                                </h6>


                                <span class="info-label">
                                    Name
                                </span>

                                <div class="info-value">
                                    <?= htmlspecialchars($order['billing_full_name']) ?>
                                </div>


                                <span class="info-label">
                                    Email
                                </span>

                                <div class="info-value">
                                    <?= htmlspecialchars($order['customer_email']) ?>
                                </div>


                                <span class="info-label">
                                    Phone
                                </span>

                                <div class="info-value mb-0">
                                    <?= htmlspecialchars($order['billing_phone']) ?>
                                </div>

                            </div>

                        </div>


                        <!-- SHIPPING INFORMATION -->

                        <!-- SHIPPING INFORMATION -->

                        <div class="col-lg-4 col-md-6 mb-3">

                            <div class="order-info-box">

                                <h6>
                                    <i class="fa-solid fa-location-dot me-2"></i>
                                    Shipping Information
                                </h6>


                                <span class="info-label">
                                    Shipping Address
                                </span>

                                <div class="info-value">

                                    <?= htmlspecialchars(
                                        $shippingAddress
                                    ) ?>

                                </div>


                                <span class="info-label">
                                    Shipping Method
                                </span>

                                <div class="info-value">

                                    <?= !empty($order['shipping_method_name'])
                                        ? htmlspecialchars($order['shipping_method_name'])
                                        : 'N/A'
                                        ?>

                                </div>


                                <span class="info-label">
                                    Delivery Status
                                </span>

                                <div class="info-value mb-0">

                                    <?php if ($order['order_status'] === 'delivered'): ?>

                                        <span class="order-status-badge status-success">
                                            Delivered
                                        </span>

                                    <?php elseif ($order['order_status'] === 'processing'): ?>

                                        <span class="order-status-badge status-info">
                                            Processing
                                        </span>

                                    <?php elseif ($order['order_status'] === 'shipped'): ?>

                                        <span class="order-status-badge status-warning">
                                            Shipped
                                        </span>

                                    <?php elseif ($order['order_status'] === 'cancelled'): ?>

                                        <span class="order-status-badge" style="background-color:#f8d7da;color:#842029;">
                                            Cancelled
                                        </span>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>


                        <!-- PAYMENT INFORMATION -->

                        <!-- PAYMENT INFORMATION -->

                        <div class="col-lg-4 col-md-12 mb-3">

                            <div class="order-info-box">

                                <h6>
                                    <i class="fa-solid fa-credit-card me-2"></i>
                                    Payment Information
                                </h6>


                                <span class="info-label">
                                    Payment Method
                                </span>

                                <div class="info-value">
                                    <?= htmlspecialchars($paymentMethod) ?>
                                </div>


                                <span class="info-label">
                                    Payment Status
                                </span>

                                <div class="info-value">

                                    <?php if ($order['payment_status'] === 'completed'): ?>

                                        <span class="order-status-badge status-success">
                                            Completed
                                        </span>

                                    <?php elseif ($order['payment_status'] === 'pending'): ?>

                                        <span class="order-status-badge status-warning">
                                            Pending
                                        </span>

                                    <?php elseif ($order['payment_status'] === 'failed'): ?>

                                        <span class="order-status-badge" style="background-color:#f8d7da;color:#842029;">
                                            Failed
                                        </span>

                                    <?php endif; ?>

                                </div>


                                <?php if (!empty($order['transaction_id'])): ?>

                                    <span class="info-label">
                                        Transaction ID
                                    </span>

                                    <div class="info-value mb-0">
                                        <?= htmlspecialchars($order['transaction_id']) ?>
                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>


                    <!-- =========================
                     ORDER ITEMS
                ========================== -->

                    <div class="mb-4">

                        <h6 class="fw-bold mb-3">
                            Order Items
                        </h6>


                        <div class="table-responsive">

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
                                            Price
                                        </th>

                                        <th class="text-end">
                                            Total
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                <tbody>

                                    <?php if (empty($orderItems)): ?>

                                        <tr>

                                            <td colspan="4" class="text-center py-4 text-secondary">

                                                <i class="fa-solid fa-box-open fs-4 mb-2"></i>

                                                <p class="text-sm mb-0">
                                                    No items found for this order.
                                                </p>

                                            </td>

                                        </tr>

                                    <?php else: ?>

                                        <?php foreach ($orderItems as $item): ?>

                                            <tr>

                                                <td>

                                                    <div class="d-flex align-items-center">

                                                        <?php if (!empty($item['product_image'])): ?>

                                                            <img src="../../public/uploads/products/<?= htmlspecialchars($item['product_image']) ?>"
                                                                alt="<?= htmlspecialchars($item['product_name']) ?>" width="50"
                                                                height="50" class="rounded me-3" style="object-fit: cover;">

                                                        <?php endif; ?>


                                                        <div>

                                                            <div class="product-name">

                                                                <?= htmlspecialchars(
                                                                    $item['product_name']
                                                                ) ?>

                                                            </div>

                                                            <div class="product-category">

                                                                <?= !empty($item['category_name'])
                                                                    ? htmlspecialchars($item['category_name'])
                                                                    : 'No Category'
                                                                    ?>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </td>


                                                <td class="text-center">

                                                    <span class="text-sm font-weight-bold">

                                                        <?= (int) $item['quantity'] ?>

                                                    </span>

                                                </td>


                                                <td class="text-end">

                                                    <span class="text-sm font-weight-bold">

                                                        PKR <?= number_format(
                                                            (float) $item['unit_price'],
                                                            2
                                                        ) ?>

                                                    </span>

                                                </td>


                                                <td class="text-end">

                                                    <span class="text-sm font-weight-bold">

                                                        PKR <?= number_format(
                                                            (float) $item['subtotal'],
                                                            2
                                                        ) ?>

                                                    </span>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php endif; ?>

                                </tbody>

                                </tbody>


                            </table>

                        </div>

                    </div>


                    <!-- ORDER SUMMARY -->

                    <div class="order-summary">

                        <div class="summary-row">

                            <span>
                                Subtotal
                            </span>

                            <strong>
                                PKR <?= number_format(
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
                                PKR <?= number_format(
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
                                -PKR <?= number_format(
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
                                PKR <?= number_format(
                                    $grandTotal,
                                    2
                                ) ?>
                            </strong>

                        </div>

                    </div>


                    <!-- =========================
                     ACTIONS
                ========================== -->

                    <div class="order-actions">


                        <!-- BACK -->

                        <a href="index.php" class="btn btn-light">

                            <i class="fa-solid fa-arrow-left me-1"></i>

                            Back to Orders

                        </a>


                        <!-- PRINT -->

                        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">

                            <i class="fa-solid fa-print me-1"></i>

                            Print Order

                        </button>


                    </div>


                </div>

            </div>


            <!-- =========================
             ADMIN FOOTER
        ========================== -->

            <?php require_once '../../includes/admin-footer.php'; ?>


        </div>

    </main>
    <!-- Popper -->
    <script src="../assets/js/core/popper.min.js"></script>

    <!-- Bootstrap -->
    <script src="../assets/js/core/bootstrap.min.js"></script>

    <!-- Perfect Scrollbar -->
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>

    <!-- Smooth Scrollbar -->
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

    <!-- Material Dashboard -->
    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>

</body>

</html>