<?php

require_once '../core/Middleware.php';
require_once '../config/database.php';
require_once '../core/Session.php';

Middleware::admin();
Session::start();

$pageTitle = "Reports";

/*
|--------------------------------------------------------------------------
| Today's Sales
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            COALESCE(SUM(total_amount), 0) AS total
        FROM orders
        WHERE DATE(created_at) = CURDATE()
        AND order_status != 'cancelled'";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$todaySales = (float) $stmt->fetch()['total'];


/*
|--------------------------------------------------------------------------
| Total Sales
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            COALESCE(SUM(total_amount), 0) AS total
        FROM orders
        WHERE order_status != 'cancelled'";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$totalSales = (float) $stmt->fetch()['total'];


/*
|--------------------------------------------------------------------------
| Total Orders
|--------------------------------------------------------------------------
*/

$sql = "SELECT COUNT(*) AS total
        FROM orders";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$totalOrders = (int) $stmt->fetch()['total'];


/*
|--------------------------------------------------------------------------
| Total Customers
|--------------------------------------------------------------------------
*/

$sql = "SELECT COUNT(*) AS total
        FROM users
        WHERE role = 'customer'";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$totalCustomers = (int) $stmt->fetch()['total'];


/*
|--------------------------------------------------------------------------
| Pending Orders
|--------------------------------------------------------------------------
*/

$sql = "SELECT COUNT(*) AS total
        FROM orders
        WHERE order_status = 'processing'";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$pendingOrders = (int) $stmt->fetch()['total'];


/*
|--------------------------------------------------------------------------
| Delivered Orders
|--------------------------------------------------------------------------
*/

$sql = "SELECT COUNT(*) AS total
        FROM orders
        WHERE order_status = 'delivered'";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$deliveredOrders = (int) $stmt->fetch()['total'];


/*
|--------------------------------------------------------------------------
| Monthly Sales Summary
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            YEAR(created_at) AS sale_year,
            MONTH(created_at) AS sale_month,
            DATE_FORMAT(created_at, '%M %Y') AS month_name,
            COUNT(*) AS total_orders,
            COALESCE(SUM(total_amount), 0) AS total_sales
        FROM orders
        WHERE order_status != 'cancelled'
        GROUP BY
            YEAR(created_at),
            MONTH(created_at),
            DATE_FORMAT(created_at, '%M %Y')
        ORDER BY
            sale_year DESC,
            sale_month DESC
        LIMIT 12";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$monthlySales = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| Top Selling Products
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            p.name AS product_name,
            COALESCE(SUM(oi.quantity), 0) AS total_quantity,
            COALESCE(SUM(oi.subtotal), 0) AS total_sales
        FROM order_items oi
        INNER JOIN orders o
            ON o.id = oi.order_id
        INNER JOIN products p
            ON p.id = oi.product_id
        WHERE o.order_status != 'cancelled'
        GROUP BY
            oi.product_id,
            p.name
        ORDER BY
            total_quantity DESC
        LIMIT 5";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$topProducts = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| Order Status Summary
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            order_status,
            COUNT(*) AS total
        FROM orders
        GROUP BY order_status
        ORDER BY total DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$orderStatuses = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
    >

    <link
        rel="apple-touch-icon"
        sizes="76x76"
        href="assets/img/apple-icon.png"
    >

    <link
        rel="icon"
        type="image/png"
        href="assets/img/favicon.png"
    >

    <title>
        Reports - ClothWear Admin
    </title>


    <!-- Fonts -->
    <link
        rel="stylesheet"
        type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900"
    />


    <!-- Nucleo Icons -->
    <link
        href="assets/css/nucleo-icons.css"
        rel="stylesheet"
    />

    <link
        href="assets/css/nucleo-svg.css"
        rel="stylesheet"
    />


    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- Material Icons -->
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
    />


    <!-- Material Dashboard -->
    <link
        id="pagestyle"
        href="assets/css/material-dashboard.css?v=3.2.0"
        rel="stylesheet"
    />


    <style>

        .report-card {
            min-height: 145px;
        }

        .report-icon {
            width: 48px;
            height: 48px;
        }

        .report-table th,
        .report-table td {
            vertical-align: middle;
        }

    </style>

</head>


<body class="g-sidenav-show bg-gray-100">


    <!-- Admin Header -->
    <?php require_once '../includes/admin-header.php'; ?>


    <!-- Admin Sidebar -->
    <?php require_once '../includes/admin-sidenavbar.php'; ?>


    <!-- Main Content -->
    <main
        class="main-content position-relative max-height-vh-100 h-100 border-radius-lg"
    >

        <div class="container-fluid py-4">


            <!-- Page Heading -->
            <div class="row">

                <div class="col-12">

                    <div
                        class="d-flex justify-content-between align-items-center mb-4"
                    >

                        <div>

                            <h4 class="font-weight-bolder mb-1">
                                Analytics & Reports
                            </h4>

                            <p class="text-sm text-secondary mb-0">
                                Overview of your ClothWear store performance
                            </p>

                        </div>

                    </div>

                </div>

            </div>



            <!-- ====================================================== -->
            <!-- SUMMARY CARDS -->
            <!-- ====================================================== -->

            <div class="row">


                <!-- Today's Sales -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card report-card">

                        <div class="card-body p-3">

                            <div class="row">

                                <div class="col-8">

                                    <div class="numbers">

                                        <p class="text-sm mb-1 text-uppercase font-weight-bold">
                                            Today's Sales
                                        </p>

                                        <h5 class="font-weight-bolder mb-0">
                                            PKR <?= number_format($todaySales, 2) ?>
                                        </h5>

                                    </div>

                                </div>


                                <div class="col-4 text-end">

                                    <div
                                        class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md report-icon"
                                    >

                                        <i class="ni ni-money-coins text-lg opacity-10"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- Total Sales -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card report-card">

                        <div class="card-body p-3">

                            <div class="row">

                                <div class="col-8">

                                    <div class="numbers">

                                        <p class="text-sm mb-1 text-uppercase font-weight-bold">
                                            Total Sales
                                        </p>

                                        <h5 class="font-weight-bolder mb-0">
                                            PKR <?= number_format($totalSales, 2) ?>
                                        </h5>

                                    </div>

                                </div>


                                <div class="col-4 text-end">

                                    <div
                                        class="icon icon-shape bg-gradient-success shadow text-center border-radius-md report-icon"
                                    >

                                        <i class="ni ni-chart-bar-32 text-lg opacity-10"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- Total Orders -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card report-card">

                        <div class="card-body p-3">

                            <div class="row">

                                <div class="col-8">

                                    <div class="numbers">

                                        <p class="text-sm mb-1 text-uppercase font-weight-bold">
                                            Total Orders
                                        </p>

                                        <h5 class="font-weight-bolder mb-0">
                                            <?= number_format($totalOrders) ?>
                                        </h5>

                                    </div>

                                </div>


                                <div class="col-4 text-end">

                                    <div
                                        class="icon icon-shape bg-gradient-info shadow text-center border-radius-md report-icon"
                                    >

                                        <i class="ni ni-cart text-lg opacity-10"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- Total Customers -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card report-card">

                        <div class="card-body p-3">

                            <div class="row">

                                <div class="col-8">

                                    <div class="numbers">

                                        <p class="text-sm mb-1 text-uppercase font-weight-bold">
                                            Customers
                                        </p>

                                        <h5 class="font-weight-bolder mb-0">
                                            <?= number_format($totalCustomers) ?>
                                        </h5>

                                    </div>

                                </div>


                                <div class="col-4 text-end">

                                    <div
                                        class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md report-icon"
                                    >

                                        <i class="ni ni-single-02 text-lg opacity-10"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- ====================================================== -->
            <!-- ORDER STATISTICS -->
            <!-- ====================================================== -->

            <div class="row">


                <!-- Pending Orders -->
                <div class="col-lg-6 mb-4">

                    <div class="card">

                        <div class="card-body p-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <p class="text-sm mb-1 text-uppercase font-weight-bold">
                                        Processing Orders
                                    </p>

                                    <h5 class="font-weight-bolder mb-0">
                                        <?= number_format($pendingOrders) ?>
                                    </h5>

                                </div>

                                <div
                                    class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md"
                                >

                                    <i class="fa-solid fa-clock text-lg opacity-10"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- Delivered Orders -->
                <div class="col-lg-6 mb-4">

                    <div class="card">

                        <div class="card-body p-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <p class="text-sm mb-1 text-uppercase font-weight-bold">
                                        Delivered Orders
                                    </p>

                                    <h5 class="font-weight-bolder mb-0">
                                        <?= number_format($deliveredOrders) ?>
                                    </h5>

                                </div>

                                <div
                                    class="icon icon-shape bg-gradient-success shadow text-center border-radius-md"
                                >

                                    <i class="fa-solid fa-circle-check text-lg opacity-10"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- ====================================================== -->
            <!-- MONTHLY SALES -->
            <!-- ====================================================== -->

            <div class="row">

                <div class="col-lg-8 mb-4">

                    <div class="card">

                        <div class="card-header pb-0">

                            <h6>
                                Monthly Sales Summary
                            </h6>

                            <p class="text-sm mb-0 text-secondary">
                                Sales and orders from the last 12 active months
                            </p>

                        </div>


                        <div class="card-body px-0 pt-0 pb-2">

                            <div class="table-responsive p-0">

                                <table class="table align-items-center mb-0 report-table">

                                    <thead>

                                        <tr>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Month
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Orders
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Sales
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        <?php if (empty($monthlySales)): ?>

                                            <tr>

                                                <td
                                                    colspan="3"
                                                    class="text-center py-4"
                                                >

                                                    <p class="text-sm text-secondary mb-0">
                                                        No sales data available.
                                                    </p>

                                                </td>

                                            </tr>

                                        <?php else: ?>

                                            <?php foreach ($monthlySales as $month): ?>

                                                <tr>

                                                    <td>

                                                        <div class="d-flex px-3 py-1">

                                                            <h6 class="mb-0 text-sm">
                                                                <?= htmlspecialchars($month['month_name']) ?>
                                                            </h6>

                                                        </div>

                                                    </td>


                                                    <td>

                                                        <span class="text-sm font-weight-bold">
                                                            <?= number_format((int) $month['total_orders']) ?>
                                                        </span>

                                                    </td>


                                                    <td>

                                                        <span class="text-sm font-weight-bold">
                                                            PKR <?= number_format(
                                                                (float) $month['total_sales'],
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

                    </div>

                </div>



                <!-- Order Status -->
                <div class="col-lg-4 mb-4">

                    <div class="card">

                        <div class="card-header pb-0">

                            <h6>
                                Order Status
                            </h6>

                        </div>


                        <div class="card-body">

                            <?php if (empty($orderStatuses)): ?>

                                <p class="text-sm text-secondary mb-0">
                                    No orders available.
                                </p>

                            <?php else: ?>

                                <?php foreach ($orderStatuses as $status): ?>

                                    <?php

                                    $statusName = ucfirst(
                                        $status['order_status']
                                    );

                                    ?>

                                    <div class="d-flex justify-content-between align-items-center mb-3">

                                        <span class="text-sm">
                                            <?= htmlspecialchars($statusName) ?>
                                        </span>

                                        <span class="badge badge-sm bg-gradient-dark">
                                            <?= number_format(
                                                (int) $status['total']
                                            ) ?>
                                        </span>

                                    </div>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>



            <!-- ====================================================== -->
            <!-- TOP SELLING PRODUCTS -->
            <!-- ====================================================== -->

            <div class="row">

                <div class="col-12">

                    <div class="card mb-4">

                        <div class="card-header pb-0">

                            <h6>
                                Top Selling Products
                            </h6>

                            <p class="text-sm mb-0 text-secondary">
                                Products with the highest quantity sold
                            </p>

                        </div>


                        <div class="card-body px-0 pt-0 pb-2">

                            <div class="table-responsive p-0">

                                <table class="table align-items-center mb-0">

                                    <thead>

                                        <tr>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Product
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Quantity Sold
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Sales
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        <?php if (empty($topProducts)): ?>

                                            <tr>

                                                <td
                                                    colspan="3"
                                                    class="text-center py-4"
                                                >

                                                    <p class="text-sm text-secondary mb-0">
                                                        No product sales available.
                                                    </p>

                                                </td>

                                            </tr>

                                        <?php else: ?>

                                            <?php foreach ($topProducts as $product): ?>

                                                <tr>

                                                    <td>

                                                        <div class="d-flex px-3 py-1">

                                                            <div class="d-flex flex-column justify-content-center">

                                                                <h6 class="mb-0 text-sm">
                                                                    <?= htmlspecialchars(
                                                                        $product['product_name']
                                                                    ) ?>
                                                                </h6>

                                                            </div>

                                                        </div>

                                                    </td>


                                                    <td>

                                                        <span class="text-sm font-weight-bold">

                                                            <?= number_format(
                                                                (int) $product['total_quantity']
                                                            ) ?>

                                                        </span>

                                                    </td>


                                                    <td>

                                                        <span class="text-sm font-weight-bold">

                                                            PKR <?= number_format(
                                                                (float) $product['total_sales'],
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

                    </div>

                </div>

            </div>



            <!-- Admin Footer -->
            <?php require_once '../includes/admin-footer.php'; ?>


        </div>

    </main>



    <!-- Popper -->
    <script src="assets/js/core/popper.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- Perfect Scrollbar -->
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>

    <!-- Smooth Scrollbar -->
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>

    <!-- Material Dashboard -->
    <script src="assets/js/material-dashboard.min.js?v=3.2.0"></script>

</body>

</html>