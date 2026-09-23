<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';

Middleware::admin();
Session::start();

$pageTitle = "Orders";


// =====================================================
// FLASH MESSAGES
// =====================================================

$successMessage = Session::getFlash('success');
$errorMessage = Session::getFlash('error');


// =====================================================
// FETCH ORDERS
// =====================================================

$sql = "SELECT
            o.id,
            o.order_number,
            o.billing_full_name,
            o.total_amount,
            o.payment_method,
            o.payment_status,
            o.order_status,
            o.created_at,
            u.name AS customer_name,
            u.email AS customer_email
        FROM orders o
        INNER JOIN users u
            ON u.id = o.user_id
        ORDER BY o.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$orders = $stmt->fetchAll();


// =====================================================
// ORDER COUNT
// =====================================================

$totalOrders = count($orders);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

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

    <title>Orders - ClothWear Admin</title>


    <!-- =================================================
         FONTS
    ================================================== -->

    <link
        rel="stylesheet"
        type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900"
    >


    <!-- =================================================
         NUCLEO ICONS
    ================================================== -->

    <link
        href="../assets/css/nucleo-icons.css"
        rel="stylesheet"
    >

    <link
        href="../assets/css/nucleo-svg.css"
        rel="stylesheet"
    >


    <!-- =================================================
         FONT AWESOME
    ================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- =================================================
         MATERIAL ICONS
    ================================================== -->

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
    >


    <!-- =================================================
         MATERIAL DASHBOARD
    ================================================== -->

    <link
        id="pagestyle"
        href="../assets/css/material-dashboard.css?v=3.2.0"
        rel="stylesheet"
    >


    <style>

        /* =====================================================
           PAGE HEADER
        ===================================================== */

        .orders-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            margin-bottom: 24px;
        }

        .orders-page-title {
            margin-bottom: 4px;

            color: #344767;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .orders-page-subtitle {
            margin-bottom: 0;

            color: #8392ab;
            font-size: 0.875rem;
        }


        /* =====================================================
           ORDER COUNT
        ===================================================== */

        .orders-count-card {
            min-width: 150px;

            padding: 12px 18px;

            border: 1px solid #e9ecef;
            border-radius: 12px;

            background: #fff;
        }

        .orders-count-label {
            display: block;

            margin-bottom: 3px;

            color: #8392ab;

            font-size: 0.72rem;
            font-weight: 600;

            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .orders-count-value {
            color: #344767;

            font-size: 1.1rem;
            font-weight: 700;
        }


        /* =====================================================
           ALERTS
        ===================================================== */

        .orders-alert {
            border-radius: 10px;
            font-size: 0.82rem;
        }


        /* =====================================================
           ORDERS CARD
        ===================================================== */

        .orders-card {
            overflow: hidden;
            border-radius: 14px;
        }

        .orders-card-header {
            padding: 20px 24px !important;

            border-bottom: 1px solid #f0f2f5;
        }

        .orders-card-title {
            margin-bottom: 3px;

            color: #344767;

            font-size: 1rem;
            font-weight: 700;
        }

        .orders-card-subtitle {
            margin-bottom: 0;

            color: #8392ab;

            font-size: 0.8rem;
        }


        /* =====================================================
           ORDER NUMBER
        ===================================================== */

        .order-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background: #f1f3f9;

            color: #344767;

            font-size: 14px;
        }

        .order-number {
            margin-bottom: 2px;

            color: #344767;

            font-size: 0.85rem;
            font-weight: 700;
        }

        .order-id {
            margin-bottom: 0;

            color: #8392ab;

            font-size: 0.68rem;
        }


        /* =====================================================
           CUSTOMER
        ===================================================== */

        .customer-avatar {
            width: 40px;
            height: 40px;
            min-width: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: linear-gradient(
                135deg,
                #e9efff,
                #f3f5ff
            );

            color: #5e72e4;

            font-size: 13px;
            font-weight: 700;

            text-transform: uppercase;
        }

        .customer-name {
            max-width: 180px;

            margin-bottom: 2px;

            color: #344767;

            font-size: 0.82rem;
            font-weight: 600;

            overflow-wrap: anywhere;
        }

        .customer-email {
            max-width: 200px;

            margin-bottom: 0;

            color: #8392ab;

            font-size: 0.7rem;

            overflow-wrap: anywhere;
            word-break: break-word;
        }


        /* =====================================================
           TOTAL
        ===================================================== */

        .order-total {
            margin-bottom: 0;

            color: #344767;

            font-size: 0.85rem;
            font-weight: 700;

            white-space: nowrap;
        }


        /* =====================================================
           PAYMENT
        ===================================================== */

        .payment-status-select,
        .order-status-select {

            min-width: 110px;

            padding: 7px 28px 7px 10px;

            border: 1px solid #e9ecef;
            border-radius: 8px;

            background-color: #f8f9fa;

            color: #344767;

            font-size: 0.7rem;
            font-weight: 600;

            cursor: pointer;

            transition: all 0.2s ease;
        }

        .payment-status-select:hover,
        .order-status-select:hover {

            border-color: #adb5bd;

            background-color: #fff;
        }

        .payment-status-select:focus,
        .order-status-select:focus {

            border-color: #5e72e4;

            box-shadow:
                0 0 0 3px
                rgba(94, 114, 228, 0.1);
        }

        .payment-method {

            display: block;

            margin-top: 5px;

            color: #8392ab;

            font-size: 0.65rem;
            font-weight: 600;

            text-transform: uppercase;
        }


        /* =====================================================
           DATE
        ===================================================== */

        .order-date {
            color: #67748e;

            font-size: 0.76rem;
            font-weight: 500;

            white-space: nowrap;
        }


        /* =====================================================
           ACTION BUTTONS
        ===================================================== */

        .order-actions {
            display: flex;

            align-items: center;
            justify-content: center;

            gap: 6px;
        }

        .order-save-btn {

            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 5px;

            min-width: 70px;

            padding: 7px 11px;

            margin-bottom: 0 !important;

            border-radius: 8px !important;

            font-size: 0.68rem !important;
            font-weight: 600 !important;

            box-shadow: none !important;
        }

        .order-view-btn {

            width: 34px;
            height: 34px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            padding: 0 !important;

            margin-bottom: 0 !important;

            border-radius: 8px !important;

            color: #67748e !important;

            background: #f8f9fa;

            transition: all 0.2s ease;
        }

        .order-view-btn:hover {

            color: #344767 !important;

            background: #eef0f3;
        }


        /* =====================================================
           TABLE
        ===================================================== */

        .orders-table {

            margin-bottom: 0;
        }

        .orders-table thead th {

            padding: 14px 18px;

            background: #fafbfc;

            color: #8392ab;

            font-size: 0.63rem;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: 0.04em;

            border-bottom: 1px solid #edf0f2;
        }

        .orders-table tbody td {

            padding: 16px 18px;

            vertical-align: middle;

            border-bottom: 1px solid #f0f2f5;
        }

        .orders-table tbody tr:last-child td {

            border-bottom: none;
        }

        .orders-table tbody tr {

            transition: background-color 0.2s ease;
        }

        .orders-table tbody tr:hover {

            background-color: #fafbfc;
        }


        /* =====================================================
           EMPTY STATE
        ===================================================== */

        .orders-empty-state {

            padding: 60px 20px !important;

            text-align: center;
        }

        .orders-empty-icon {

            width: 58px;
            height: 58px;

            display: flex;

            align-items: center;
            justify-content: center;

            margin: 0 auto 15px;

            border-radius: 50%;

            background: #f1f3f5;

            color: #8392ab;

            font-size: 20px;
        }

        .orders-empty-title {

            margin-bottom: 5px;

            color: #344767;

            font-size: 0.95rem;
            font-weight: 600;
        }

        .orders-empty-text {

            margin-bottom: 0;

            color: #8392ab;

            font-size: 0.78rem;
        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 767.98px) {

            .orders-page-header {

                align-items: flex-start;

                flex-direction: column;

                gap: 12px;
            }

            .orders-count-card {

                width: 100%;
            }

            .orders-page-title {

                font-size: 1.3rem;
            }

            .orders-card-header {

                padding: 18px !important;
            }

            /*
             * Keep horizontal scrolling inside
             * the table instead of the whole page.
             */

            .orders-table {

                min-width: 950px;
            }

            .orders-table thead th,
            .orders-table tbody td {

                padding-left: 14px;
                padding-right: 14px;
            }

        }


        /* =====================================================
           SMALL MOBILE
        ===================================================== */

        @media (max-width: 575.98px) {

            .orders-page-title {

                font-size: 1.2rem;
            }

            .orders-page-subtitle {

                font-size: 0.78rem;
            }

            .orders-count-card {

                padding: 10px 14px;
            }

            .orders-card-header {

                padding: 16px !important;
            }

            .orders-card-title {

                font-size: 0.9rem;
            }

            .orders-card-subtitle {

                font-size: 0.74rem;
                line-height: 1.4;
            }

            .customer-avatar,
            .order-icon {

                width: 38px;
                height: 38px;
                min-width: 38px;
            }

        }


        /* =====================================================
           EXTRA SMALL MOBILE
        ===================================================== */

        @media (max-width: 375px) {

            .container-fluid {

                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .orders-page-title {

                font-size: 1.1rem;
            }

            .orders-card-title {

                font-size: 0.88rem;
            }

        }

    </style>

</head>


<body class="g-sidenav-show bg-gray-100">


    <!-- =====================================================
         ADMIN HEADER
    ====================================================== -->

    <?php require_once '../../includes/admin-header.php'; ?>


    <!-- =====================================================
         ADMIN SIDEBAR
    ====================================================== -->

    <?php require_once '../../includes/admin-sidenavbar.php'; ?>


    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main
        class="main-content position-relative max-height-vh-100 h-100 border-radius-lg"
    >

        <div class="container-fluid py-4">


            <!-- =================================================
                 FLASH SUCCESS
            ================================================== -->

            <?php if ($successMessage): ?>

                <div
                    class="alert alert-success alert-dismissible fade show orders-alert"
                    role="alert"
                >

                    <i class="fa-solid fa-circle-check me-2"></i>

                    <?= htmlspecialchars($successMessage) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 FLASH ERROR
            ================================================== -->

            <?php if ($errorMessage): ?>

                <div
                    class="alert alert-danger alert-dismissible fade show orders-alert"
                    role="alert"
                >

                    <i class="fa-solid fa-circle-exclamation me-2"></i>

                    <?= htmlspecialchars($errorMessage) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 PAGE HEADER
            ================================================== -->

            <div class="orders-page-header">

                <div>

                    <h4 class="orders-page-title">
                        Orders Management
                    </h4>

                    <p class="orders-page-subtitle">
                        Review customer orders, payments and delivery status.
                    </p>

                </div>


                <!-- Order Count -->

                <div class="orders-count-card">

                    <span class="orders-count-label">
                        Total Orders
                    </span>

                    <span class="orders-count-value">
                        <?= number_format($totalOrders) ?>
                    </span>

                </div>

            </div>


            <!-- =================================================
                 ORDERS CARD
            ================================================== -->

            <div class="card orders-card mb-4">


                <!-- Card Header -->

                <div class="card-header orders-card-header">

                    <div>

                        <h6 class="orders-card-title">
                            All Orders
                        </h6>

                        <p class="orders-card-subtitle">
                            Manage payment and order fulfillment status.
                        </p>

                    </div>

                </div>


                <!-- =================================================
                     TABLE
                ================================================== -->

                <div class="card-body px-0 pt-0 pb-0">

                    <div class="table-responsive">

                        <table class="table orders-table align-items-center">


                            <!-- =================================================
                                 TABLE HEADER
                            ================================================== -->

                            <thead>

                                <tr>

                                    <th>
                                        Order
                                    </th>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Payment
                                    </th>

                                    <th>
                                        Order Status
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                    <th class="text-center">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <!-- =================================================
                                 TABLE BODY
                            ================================================== -->

                            <tbody>


                            <?php if (empty($orders)): ?>


                                <!-- EMPTY STATE -->

                                <tr>

                                    <td
                                        colspan="7"
                                        class="orders-empty-state"
                                    >

                                        <div class="orders-empty-icon">

                                            <i class="fa-solid fa-box-open"></i>

                                        </div>

                                        <h6 class="orders-empty-title">
                                            No orders found
                                        </h6>

                                        <p class="orders-empty-text">
                                            Customer orders will appear here once they are placed.
                                        </p>

                                    </td>

                                </tr>


                            <?php else: ?>


                                <?php foreach ($orders as $order): ?>


                                    <tr>


                                        <!-- =====================================
                                             ORDER
                                        ====================================== -->

                                        <td>

                                            <div class="d-flex align-items-center">


                                                <div class="order-icon me-3">

                                                    <i class="fa-solid fa-receipt"></i>

                                                </div>


                                                <div>

                                                    <h6 class="order-number">

                                                        #<?= htmlspecialchars(
                                                            $order['order_number']
                                                        ) ?>

                                                    </h6>

                                                    <p class="order-id">

                                                        Order ID:
                                                        <?= (int) $order['id'] ?>

                                                    </p>

                                                </div>

                                            </div>

                                        </td>


                                        <!-- =====================================
                                             CUSTOMER
                                        ====================================== -->

                                        <td>

                                            <div class="d-flex align-items-center">


                                                <!-- Avatar -->

                                                <div class="customer-avatar me-3">

                                                    <?= htmlspecialchars(
                                                        strtoupper(
                                                            substr(
                                                                trim($order['customer_name']),
                                                                0,
                                                                1
                                                            )
                                                        )
                                                    ) ?>

                                                </div>


                                                <!-- Customer Details -->

                                                <div>

                                                    <p class="customer-name">

                                                        <?= htmlspecialchars(
                                                            $order['customer_name']
                                                        ) ?>

                                                    </p>

                                                    <p class="customer-email">

                                                        <?= htmlspecialchars(
                                                            $order['customer_email']
                                                        ) ?>

                                                    </p>

                                                </div>

                                            </div>

                                        </td>


                                        <!-- =====================================
                                             TOTAL
                                        ====================================== -->

                                        <td>

                                            <p class="order-total">

                                                PKR <?= number_format(
                                                    (float) $order['total_amount'],
                                                    2
                                                ) ?>

                                            </p>

                                        </td>


                                        <!-- =====================================
                                             PAYMENT
                                        ====================================== -->

                                        <td>

                                            <select
                                                name="payment_status"
                                                class="form-select payment-status-select"
                                                form="order-form-<?= (int) $order['id'] ?>"
                                            >

                                                <option
                                                    value="pending"
                                                    <?= $order['payment_status'] === 'pending'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Pending
                                                </option>

                                                <option
                                                    value="completed"
                                                    <?= $order['payment_status'] === 'completed'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Completed
                                                </option>

                                                <option
                                                    value="failed"
                                                    <?= $order['payment_status'] === 'failed'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Failed
                                                </option>

                                            </select>


                                            <span class="payment-method">

                                                <?= strtoupper(
                                                    htmlspecialchars(
                                                        $order['payment_method']
                                                    )
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- =====================================
                                             ORDER STATUS
                                        ====================================== -->

                                        <td>

                                            <select
                                                name="order_status"
                                                class="form-select order-status-select"
                                                form="order-form-<?= (int) $order['id'] ?>"
                                            >

                                                <option
                                                    value="processing"
                                                    <?= $order['order_status'] === 'processing'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Processing
                                                </option>

                                                <option
                                                    value="shipped"
                                                    <?= $order['order_status'] === 'shipped'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Shipped
                                                </option>

                                                <option
                                                    value="delivered"
                                                    <?= $order['order_status'] === 'delivered'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Delivered
                                                </option>

                                                <option
                                                    value="cancelled"
                                                    <?= $order['order_status'] === 'cancelled'
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    Cancelled
                                                </option>

                                            </select>

                                        </td>


                                        <!-- =====================================
                                             DATE
                                        ====================================== -->

                                        <td>

                                            <span class="order-date">

                                                <?= date(
                                                    'd M Y',
                                                    strtotime($order['created_at'])
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- =====================================
                                             ACTIONS
                                        ====================================== -->

                                        <td>

                                            <div class="order-actions">


                                                <!-- Update Status -->

                                                <form
                                                    method="POST"
                                                    action="update-status.php"
                                                    id="order-form-<?= (int) $order['id'] ?>"
                                                    class="d-inline"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="order_id"
                                                        value="<?= (int) $order['id'] ?>"
                                                    >


                                                    <button
                                                        type="submit"
                                                        class="btn bg-gradient-dark order-save-btn"
                                                        title="Save Status"
                                                    >

                                                        <i class="fa-solid fa-check"></i>

                                                        Save

                                                    </button>

                                                </form>


                                                <!-- View Order -->

                                                <a
                                                    href="detail.php?id=<?= (int) $order['id'] ?>"
                                                    class="btn order-view-btn"
                                                    title="View Order"
                                                >

                                                    <i class="fa-solid fa-eye"></i>

                                                </a>

                                            </div>

                                        </td>


                                    </tr>


                                <?php endforeach; ?>


                            <?php endif; ?>


                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


        </div>


        <!-- =====================================================
             ADMIN FOOTER
        ====================================================== -->

        <?php require_once '../../includes/admin-footer.php'; ?>


    </main>


    <!-- =====================================================
         SCRIPTS
    ====================================================== -->

    <script src="../assets/js/core/popper.min.js"></script>

    <script src="../assets/js/core/bootstrap.min.js"></script>

    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>

    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>

</body>

</html>
