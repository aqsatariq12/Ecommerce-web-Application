<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';

Middleware::admin();
Session::start();

$pageTitle = "Orders";


// Flash messages
$successMessage = Session::getFlash('success');
$errorMessage = Session::getFlash('error');


// FETCH ORDERS
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Orders - ClothWear Admin</title>

    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-100">

    <!-- Admin Header -->
    <?php require_once '../../includes/admin-header.php'; ?>

    <!-- Admin Sidebar -->
    <?php require_once '../../includes/admin-sidenavbar.php'; ?>


    <!-- Main Content -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <div class="container-fluid py-4">
            <?php if ($successMessage): ?>

                <div class="alert alert-success alert-dismissible fade show" role="alert">

                    <i class="fa-solid fa-circle-check me-2"></i>

                    <?= htmlspecialchars($successMessage) ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <?php if ($errorMessage): ?>

                <div class="alert alert-danger alert-dismissible fade show" role="alert">

                    <i class="fa-solid fa-circle-exclamation me-2"></i>

                    <?= htmlspecialchars($errorMessage) ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>

            <!-- Page Heading -->
            <div class="row">
                <div class="col-12">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>
                            <h4 class="font-weight-bolder mb-0">
                                Orders Management
                            </h4>
                        </div>

                    </div>

                </div>
            </div>


            <!-- Orders Table -->
            <div class="row">

                <div class="col-12">

                    <div class="card mb-4">

                        <!-- Card Header -->
                        <div class="card-header pb-0">

                            <div class="d-flex justify-content-between align-items-center">

                                <h6>
                                    Orders
                                </h6>

                                <span class="text-sm text-secondary">
                                    Manage customer orders
                                </span>

                            </div>

                        </div>


                        <!-- Table -->
                        <div class="card-body px-0 pt-0 pb-2">

                            <div class="table-responsive p-0">

                                <table class="table align-items-center mb-0">

                                    <thead>

                                        <tr>

                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Order
                                            </th>

                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Customer
                                            </th>

                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Total
                                            </th>

                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Payment
                                            </th>

                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Order Status
                                            </th>

                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Date
                                            </th>

                                            <th class="text-secondary opacity-7">
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        <?php if (empty($orders)): ?>

                                            <tr>

                                                <td colspan="7" class="text-center py-5">

                                                    <i class="fa-solid fa-box-open text-secondary fs-3 mb-3"></i>

                                                    <p class="text-sm text-secondary mb-0">
                                                        No orders found.
                                                    </p>

                                                </td>

                                            </tr>

                                        <?php else: ?>

                                            <?php foreach ($orders as $order): ?>

                                                <tr>

                                                    <!-- ORDER -->
                                                    <td>

                                                        <div class="d-flex px-3 py-1">

                                                            <div class="d-flex flex-column justify-content-center">

                                                                <h6 class="mb-0 text-sm">

                                                                    #<?= htmlspecialchars($order['order_number']) ?>

                                                                </h6>

                                                                <p class="text-xs text-secondary mb-0">

                                                                    Order ID:
                                                                    <?= (int) $order['id'] ?>

                                                                </p>

                                                            </div>

                                                        </div>

                                                    </td>


                                                    <!-- CUSTOMER -->
                                                    <td>

                                                        <div class="d-flex flex-column">

                                                            <p class="text-sm font-weight-bold mb-0">

                                                                <?= htmlspecialchars($order['customer_name']) ?>

                                                            </p>

                                                            <p class="text-xs text-secondary mb-0">

                                                                <?= htmlspecialchars($order['customer_email']) ?>

                                                            </p>

                                                        </div>

                                                    </td>


                                                    <!-- TOTAL -->
                                                    <td>

                                                        <p class="text-sm font-weight-bold mb-0">

                                                            PKR <?= number_format(
                                                                (float) $order['total_amount'],
                                                                2
                                                            ) ?>

                                                        </p>

                                                    </td>


                                                    <!-- PAYMENT -->
                                                    <td>

                                                        <select name="payment_status"
                                                            class="form-select form-select-sm payment-status-select"
                                                            form="order-form-<?= (int) $order['id'] ?>">

                                                            <option value="pending" <?= $order['payment_status'] === 'pending' ? 'selected' : '' ?>>
                                                                Pending
                                                            </option>

                                                            <option value="completed" <?= $order['payment_status'] === 'completed' ? 'selected' : '' ?>>
                                                                Completed
                                                            </option>

                                                            <option value="failed" <?= $order['payment_status'] === 'failed' ? 'selected' : '' ?>>
                                                                Failed
                                                            </option>

                                                        </select>


                                                        <p class="text-xs text-secondary mb-0 mt-1">

                                                            <?= strtoupper(
                                                                htmlspecialchars($order['payment_method'])
                                                            ) ?>

                                                        </p>

                                                    </td>


                                                    <!-- ORDER STATUS -->
                                                    <td>

                                                        <select name="order_status"
                                                            class="form-select form-select-sm order-status-select"
                                                            form="order-form-<?= (int) $order['id'] ?>">

                                                            <option value="processing" <?= $order['order_status'] === 'processing' ? 'selected' : '' ?>>
                                                                Processing
                                                            </option>

                                                            <option value="shipped" <?= $order['order_status'] === 'shipped' ? 'selected' : '' ?>>
                                                                Shipped
                                                            </option>

                                                            <option value="delivered" <?= $order['order_status'] === 'delivered' ? 'selected' : '' ?>>
                                                                Delivered
                                                            </option>

                                                            <option value="cancelled" <?= $order['order_status'] === 'cancelled' ? 'selected' : '' ?>>
                                                                Cancelled
                                                            </option>

                                                        </select>

                                                    </td>


                                                    <!-- DATE -->
                                                    <td>

                                                        <span class="text-secondary text-xs font-weight-bold">

                                                            <?= date(
                                                                'd M Y',
                                                                strtotime($order['created_at'])
                                                            ) ?>

                                                        </span>

                                                    </td>


                                                    <!-- VIEW -->
                                                    <!-- ACTIONS -->
                                                    <td class="align-middle">

                                                        <!-- Update Status Form -->
                                                        <form method="POST" action="update-status.php"
                                                            id="order-form-<?= (int) $order['id'] ?>" class="d-inline">

                                                            <input type="hidden" name="order_id"
                                                                value="<?= (int) $order['id'] ?>">

                                                            <button type="submit" class="btn btn-sm bg-gradient-dark mb-1"
                                                                title="Save Status">
                                                                <i class="fa-solid fa-check me-1"></i>
                                                                Save
                                                            </button>

                                                        </form>


                                                        <!-- View Order -->
                                                        <a href="detail.php?id=<?= (int) $order['id'] ?>"
                                                            class="btn btn-link text-secondary mb-0" title="View Order">

                                                            <i class="fa fa-eye text-xs"></i>

                                                        </a>

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

            </div>
            <!-- Admin Footer -->
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