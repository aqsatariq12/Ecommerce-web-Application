<?php

require_once '../core/Middleware.php';
require_once '../config/database.php';
require_once '../core/Session.php';

Middleware::admin();
Session::start();

$pageTitle = "Dashboard";


/*
|--------------------------------------------------------------------------
| TODAY'S SALES
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
| TODAY'S ORDERS
|--------------------------------------------------------------------------
*/

$sql = "SELECT COUNT(*) AS total
        FROM orders
        WHERE DATE(created_at) = CURDATE()";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$todayOrders = (int) $stmt->fetch()['total'];


/*
|--------------------------------------------------------------------------
| TOTAL CUSTOMERS
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
| TOTAL PRODUCTS
|--------------------------------------------------------------------------
*/

$sql = "SELECT COUNT(*) AS total
        FROM products";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$totalProducts = (int) $stmt->fetch()['total'];


/*
|--------------------------------------------------------------------------
| TOTAL SALES
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
| PROCESSING ORDERS
|--------------------------------------------------------------------------
*/

$sql = "SELECT COUNT(*) AS total
        FROM orders
        WHERE order_status = 'processing'";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$processingOrders = (int) $stmt->fetch()['total'];


/*
|--------------------------------------------------------------------------
| DELIVERED ORDERS
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
| LOW STOCK PRODUCTS
|--------------------------------------------------------------------------
|
| Products with stock <= 5
|
*/

$sql = "SELECT COUNT(*) AS total
        FROM products
        WHERE stock <= 5
        AND status = 1";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$lowStockProducts = (int) $stmt->fetch()['total'];


/*
|--------------------------------------------------------------------------
| SALES FOR LAST 7 DAYS
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            DATE(created_at) AS sale_date,
            COALESCE(SUM(total_amount), 0) AS total_sales
        FROM orders
        WHERE created_at >= CURDATE() - INTERVAL 6 DAY
        AND order_status != 'cancelled'
        GROUP BY DATE(created_at)
        ORDER BY sale_date ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$salesLast7Days = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| PREPARE LAST 7 DAYS CHART DATA
|--------------------------------------------------------------------------
*/

$dailyLabels = [];
$dailySales = [];

for ($i = 6; $i >= 0; $i--) {

    $date = date(
        'Y-m-d',
        strtotime("-$i days")
    );

    $dailyLabels[] = date(
        'D',
        strtotime($date)
    );

    $dailySales[$date] = 0;
}

foreach ($salesLast7Days as $sale) {

    $date = $sale['sale_date'];

    if (isset($dailySales[$date])) {
        $dailySales[$date] = (float) $sale['total_sales'];
    }
}

$dailySalesValues = array_values($dailySales);


/*
|--------------------------------------------------------------------------
| MONTHLY SALES - LAST 6 MONTHS
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            YEAR(created_at) AS sale_year,
            MONTH(created_at) AS sale_month,
            COALESCE(SUM(total_amount), 0) AS total_sales
        FROM orders
        WHERE created_at >= DATE_FORMAT(
            DATE_SUB(CURDATE(), INTERVAL 5 MONTH),
            '%Y-%m-01'
        )
        AND order_status != 'cancelled'
        GROUP BY
            YEAR(created_at),
            MONTH(created_at)
        ORDER BY
            sale_year ASC,
            sale_month ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$monthlySalesData = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| PREPARE LAST 6 MONTHS CHART DATA
|--------------------------------------------------------------------------
*/

$monthlyLabels = [];
$monthlySales = [];

for ($i = 5; $i >= 0; $i--) {

    $monthDate = strtotime("-$i months");

    $year = date('Y', $monthDate);
    $month = date('n', $monthDate);

    $key = $year . '-' . $month;

    $monthlyLabels[] = date(
        'M',
        $monthDate
    );

    $monthlySales[$key] = 0;
}

foreach ($monthlySalesData as $sale) {

    $key = $sale['sale_year'] . '-' . $sale['sale_month'];

    if (isset($monthlySales[$key])) {
        $monthlySales[$key] = (float) $sale['total_sales'];
    }
}

$monthlySalesValues = array_values($monthlySales);


/*
|--------------------------------------------------------------------------
| RECENT ORDERS
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            o.id,
            o.order_number,
            o.billing_full_name,
            o.total_amount,
            o.payment_method,
            o.payment_status,
            o.order_status,
            o.created_at
        FROM orders o
        ORDER BY o.id DESC
        LIMIT 5";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$recentOrders = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| TOP SELLING PRODUCTS
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            p.name AS product_name,
            p.image AS product_image,
            COALESCE(SUM(oi.quantity), 0) AS quantity_sold,
            COALESCE(SUM(oi.subtotal), 0) AS total_sales
        FROM order_items oi

        INNER JOIN orders o
            ON o.id = oi.order_id

        INNER JOIN products p
            ON p.id = oi.product_id

        WHERE o.order_status != 'cancelled'

        GROUP BY
            oi.product_id,
            p.name,
            p.image

        ORDER BY
            quantity_sold DESC

        LIMIT 5";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$topProducts = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>
    Material Dashboard 3 by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
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


            <!-- ====================================================== -->
            <!-- HEADER -->
            <!-- ====================================================== -->

            <div class="row">

                <div class="col-12">

                    <div class="ms-1 mb-4">

                        <h3 class="mb-1 h4 font-weight-bolder">
                            Dashboard
                        </h3>

                        <p class="mb-0 text-sm text-secondary">
                            Overview of your ClothWear store
                        </p>

                    </div>

                </div>

            </div>



            <!-- ====================================================== -->
            <!-- MAIN STATISTICS -->
            <!-- ====================================================== -->

            <div class="row">


                <!-- Today's Sales -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card dashboard-card">

                        <div class="card-header p-2 ps-3">

                            <div class="d-flex justify-content-between">

                                <div>

                                    <p class="text-sm mb-1 text-capitalize">
                                        Today's Sales
                                    </p>

                                    <h4 class="mb-0">
                                        PKR <?= number_format(
                                            $todaySales,
                                            2
                                        ) ?>
                                    </h4>

                                </div>


                                <div
                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg"
                                >

                                    <i class="material-symbols-rounded opacity-10">
                                        payments
                                    </i>

                                </div>

                            </div>

                        </div>


                        <hr class="dark horizontal my-0">


                        <div class="card-footer p-2 ps-3">

                            <p class="mb-0 text-sm text-secondary">
                                Sales from today
                            </p>

                        </div>

                    </div>

                </div>



                <!-- Today's Orders -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card dashboard-card">

                        <div class="card-header p-2 ps-3">

                            <div class="d-flex justify-content-between">

                                <div>

                                    <p class="text-sm mb-1 text-capitalize">
                                        Today's Orders
                                    </p>

                                    <h4 class="mb-0">
                                        <?= number_format($todayOrders) ?>
                                    </h4>

                                </div>


                                <div
                                    class="icon icon-md icon-shape bg-gradient-info shadow-dark shadow text-center border-radius-lg"
                                >

                                    <i class="material-symbols-rounded opacity-10">
                                        shopping_cart
                                    </i>

                                </div>

                            </div>

                        </div>


                        <hr class="dark horizontal my-0">


                        <div class="card-footer p-2 ps-3">

                            <p class="mb-0 text-sm text-secondary">
                                Orders placed today
                            </p>

                        </div>

                    </div>

                </div>



                <!-- Customers -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card dashboard-card">

                        <div class="card-header p-2 ps-3">

                            <div class="d-flex justify-content-between">

                                <div>

                                    <p class="text-sm mb-1 text-capitalize">
                                        Total Customers
                                    </p>

                                    <h4 class="mb-0">
                                        <?= number_format($totalCustomers) ?>
                                    </h4>

                                </div>


                                <div
                                    class="icon icon-md icon-shape bg-gradient-success shadow-dark shadow text-center border-radius-lg"
                                >

                                    <i class="material-symbols-rounded opacity-10">
                                        person
                                    </i>

                                </div>

                            </div>

                        </div>


                        <hr class="dark horizontal my-0">


                        <div class="card-footer p-2 ps-3">

                            <p class="mb-0 text-sm text-secondary">
                                Registered customers
                            </p>

                        </div>

                    </div>

                </div>



                <!-- Products -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card dashboard-card">

                        <div class="card-header p-2 ps-3">

                            <div class="d-flex justify-content-between">

                                <div>

                                    <p class="text-sm mb-1 text-capitalize">
                                        Total Products
                                    </p>

                                    <h4 class="mb-0">
                                        <?= number_format($totalProducts) ?>
                                    </h4>

                                </div>


                                <div
                                    class="icon icon-md icon-shape bg-gradient-warning shadow-dark shadow text-center border-radius-lg"
                                >

                                    <i class="material-symbols-rounded opacity-10">
                                        inventory_2
                                    </i>

                                </div>

                            </div>

                        </div>


                        <hr class="dark horizontal my-0">


                        <div class="card-footer p-2 ps-3">

                            <p class="mb-0 text-sm text-secondary">
                                Products in catalog
                            </p>

                        </div>

                    </div>

                </div>

            </div>



            <!-- ====================================================== -->
            <!-- SECONDARY STATISTICS -->
            <!-- ====================================================== -->

            <div class="row">


                <!-- Total Sales -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card">

                        <div class="card-body p-3">

                            <p class="text-sm mb-1 text-uppercase font-weight-bold">
                                Total Sales
                            </p>

                            <h5 class="font-weight-bolder mb-0">
                                PKR <?= number_format(
                                    $totalSales,
                                    2
                                ) ?>
                            </h5>

                        </div>

                    </div>

                </div>



                <!-- Processing -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card">

                        <div class="card-body p-3">

                            <p class="text-sm mb-1 text-uppercase font-weight-bold">
                                Processing Orders
                            </p>

                            <h5 class="font-weight-bolder mb-0">
                                <?= number_format($processingOrders) ?>
                            </h5>

                        </div>

                    </div>

                </div>



                <!-- Delivered -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card">

                        <div class="card-body p-3">

                            <p class="text-sm mb-1 text-uppercase font-weight-bold">
                                Delivered Orders
                            </p>

                            <h5 class="font-weight-bolder mb-0">
                                <?= number_format($deliveredOrders) ?>
                            </h5>

                        </div>

                    </div>

                </div>



                <!-- Low Stock -->
                <div class="col-xl-3 col-sm-6 mb-4">

                    <div class="card">

                        <div class="card-body p-3">

                            <p class="text-sm mb-1 text-uppercase font-weight-bold">
                                Low Stock
                            </p>

                            <h5 class="font-weight-bolder mb-0">

                                <?= number_format($lowStockProducts) ?>

                                <?php if ($lowStockProducts > 0): ?>

                                    <span class="text-xs text-warning">
                                        products
                                    </span>

                                <?php endif; ?>

                            </h5>

                        </div>

                    </div>

                </div>

            </div>



            <!-- ====================================================== -->
            <!-- CHARTS -->
            <!-- ====================================================== -->

            <div class="row">


                <!-- Last 7 Days -->
                <div class="col-lg-6 mb-4">

                    <div class="card">

                        <div class="card-body">

                            <h6 class="mb-0">
                                Sales - Last 7 Days
                            </h6>

                            <p class="text-sm text-secondary">
                                Daily sales performance
                            </p>


                            <div class="chart-container">

                                <canvas
                                    id="dailySalesChart"
                                    class="chart-canvas"
                                ></canvas>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- Last 6 Months -->
                <div class="col-lg-6 mb-4">

                    <div class="card">

                        <div class="card-body">

                            <h6 class="mb-0">
                                Sales - Last 6 Months
                            </h6>

                            <p class="text-sm text-secondary">
                                Monthly sales performance
                            </p>


                            <div class="chart-container">

                                <canvas
                                    id="monthlySalesChart"
                                    class="chart-canvas"
                                ></canvas>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- ====================================================== -->
            <!-- RECENT ORDERS + TOP PRODUCTS -->
            <!-- ====================================================== -->

            <div class="row mb-4">


                <!-- Recent Orders -->
                <div class="col-lg-7 mb-4 mb-lg-0">

                    <div class="card h-100">

                        <div class="card-header pb-0">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <h6 class="mb-1">
                                        Recent Orders
                                    </h6>

                                    <p class="text-sm mb-0 text-secondary">
                                        Latest customer orders
                                    </p>

                                </div>


                                <a
                                    href="orders/index.php"
                                    class="btn btn-sm bg-gradient-dark mb-0"
                                >
                                    View All
                                </a>

                            </div>

                        </div>


                        <div class="card-body px-0 pb-2">

                            <div class="table-responsive">

                                <table class="table align-items-center mb-0 dashboard-table">

                                    <thead>

                                        <tr>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Order
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Customer
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Total
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        <?php if (empty($recentOrders)): ?>

                                            <tr>

                                                <td
                                                    colspan="4"
                                                    class="text-center py-4"
                                                >

                                                    <p class="text-sm text-secondary mb-0">
                                                        No orders yet.
                                                    </p>

                                                </td>

                                            </tr>

                                        <?php else: ?>

                                            <?php foreach ($recentOrders as $order): ?>

                                                <tr>

                                                    <td>

                                                        <div class="px-3">

                                                            <a
                                                                href="orders/detail.php?id=<?= (int) $order['id'] ?>"
                                                                class="text-sm font-weight-bold"
                                                            >
                                                                #<?= htmlspecialchars(
                                                                    $order['order_number']
                                                                ) ?>
                                                            </a>

                                                            <p class="text-xs text-secondary mb-0">

                                                                <?= date(
                                                                    'd M Y',
                                                                    strtotime($order['created_at'])
                                                                ) ?>

                                                            </p>

                                                        </div>

                                                    </td>


                                                    <td>

                                                        <span class="text-sm">

                                                            <?= htmlspecialchars(
                                                                $order['billing_full_name']
                                                            ) ?>

                                                        </span>

                                                    </td>


                                                    <td>

                                                        <span class="text-sm font-weight-bold">

                                                            PKR <?= number_format(
                                                                (float) $order['total_amount'],
                                                                2
                                                            ) ?>

                                                        </span>

                                                    </td>


                                                    <td>

                                                        <?php if ($order['order_status'] === 'delivered'): ?>

                                                            <span class="badge badge-sm bg-gradient-success">
                                                                Delivered
                                                            </span>

                                                        <?php elseif ($order['order_status'] === 'shipped'): ?>

                                                            <span class="badge badge-sm bg-gradient-warning">
                                                                Shipped
                                                            </span>

                                                        <?php elseif ($order['order_status'] === 'cancelled'): ?>

                                                            <span class="badge badge-sm bg-gradient-danger">
                                                                Cancelled
                                                            </span>

                                                        <?php else: ?>

                                                            <span class="badge badge-sm bg-gradient-info">
                                                                Processing
                                                            </span>

                                                        <?php endif; ?>

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



                <!-- Top Products -->
                <div class="col-lg-5">

                    <div class="card h-100">

                        <div class="card-header pb-0">

                            <h6 class="mb-1">
                                Top Selling Products
                            </h6>

                            <p class="text-sm mb-0 text-secondary">
                                Products with highest sales quantity
                            </p>

                        </div>


                        <div class="card-body">

                            <?php if (empty($topProducts)): ?>

                                <p class="text-sm text-secondary">
                                    No product sales yet.
                                </p>

                            <?php else: ?>

                                <?php foreach ($topProducts as $product): ?>

                                    <div class="d-flex align-items-center mb-3">

                                        <?php if (!empty($product['product_image'])): ?>

                                            <img
                                                src="../public/uploads/products/<?= htmlspecialchars(
                                                    $product['product_image']
                                                ) ?>"
                                                alt="<?= htmlspecialchars(
                                                    $product['product_name']
                                                ) ?>"
                                                class="product-dashboard-image me-3"
                                            >

                                        <?php else: ?>

                                            <div
                                                class="product-dashboard-image me-3 bg-gray-200 d-flex align-items-center justify-content-center"
                                            >

                                                <i class="fa-solid fa-box text-secondary"></i>

                                            </div>

                                        <?php endif; ?>


                                        <div class="flex-grow-1">

                                            <h6 class="text-sm mb-0">

                                                <?= htmlspecialchars(
                                                    $product['product_name']
                                                ) ?>

                                            </h6>

                                            <p class="text-xs text-secondary mb-0">

                                                <?= number_format(
                                                    (int) $product['quantity_sold']
                                                ) ?>

                                                sold

                                            </p>

                                        </div>


                                        <div class="text-end">

                                            <span class="text-sm font-weight-bold">

                                                PKR <?= number_format(
                                                    (float) $product['total_sales'],
                                                    0
                                                ) ?>

                                            </span>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>



            <!-- Admin Footer -->
            <?php require_once '../includes/admin-footer.php'; ?>

        </div>

    </main>



    <!-- ====================================================== -->
    <!-- JAVASCRIPT -->
    <!-- ====================================================== -->

    <script src="assets/js/core/popper.min.js"></script>

    <script src="assets/js/core/bootstrap.min.js"></script>

    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>

    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>

    <script src="assets/js/plugins/chartjs.min.js"></script>


    <script>

        /*
        |--------------------------------------------------------------------------
        | Daily Sales Chart
        |--------------------------------------------------------------------------
        */

        const dailyLabels = <?= json_encode($dailyLabels) ?>;

        const dailySales = <?= json_encode($dailySalesValues) ?>;


        const dailyCanvas =
            document.getElementById('dailySalesChart');


        if (dailyCanvas) {

            new Chart(dailyCanvas, {

                type: 'bar',

                data: {

                    labels: dailyLabels,

                    datasets: [{

                        label: 'Sales',

                        data: dailySales,

                        borderWidth: 0,

                        borderRadius: 5

                    }]

                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {
                            display: false
                        }

                    },

                    scales: {

                        y: {

                            beginAtZero: true,

                            ticks: {

                                callback: function(value) {

                                    return 'PKR ' +
                                        Number(value).toLocaleString();

                                }

                            }

                        }

                    }

                }

            });

        }



        /*
        |--------------------------------------------------------------------------
        | Monthly Sales Chart
        |--------------------------------------------------------------------------
        */

        const monthlyLabels =
            <?= json_encode($monthlyLabels) ?>;

        const monthlySales =
            <?= json_encode(array_values($monthlySales)) ?>;


        const monthlyCanvas =
            document.getElementById('monthlySalesChart');


        if (monthlyCanvas) {

            new Chart(monthlyCanvas, {

                type: 'line',

                data: {

                    labels: monthlyLabels,

                    datasets: [{

                        label: 'Sales',

                        data: monthlySales,

                        tension: 0.4,

                        borderWidth: 2,

                        pointRadius: 4,

                        fill: false

                    }]

                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {
                            display: false
                        }

                    },

                    scales: {

                        y: {

                            beginAtZero: true,

                            ticks: {

                                callback: function(value) {

                                    return 'PKR ' +
                                        Number(value).toLocaleString();

                                }

                            }

                        }

                    }

                }

            });

        }

    </script>


    <script>

        var win =
            navigator.platform.indexOf('Win') > -1;

        if (
            win &&
            document.querySelector('#sidenav-scrollbar')
        ) {

            var options = {
                damping: '0.5'
            };

            Scrollbar.init(
                document.querySelector('#sidenav-scrollbar'),
                options
            );

        }

    </script>


    <script src="assets/js/material-dashboard.min.js?v=3.2.0"></script>

</body>

</html>