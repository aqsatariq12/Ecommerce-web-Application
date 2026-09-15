<?php  

require_once '../../core/Middleware.php';

Middleware::admin();
$pageTitle = "Orders";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Orders - ClothWear Admin</title>

<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
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

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Order
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Customer
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Total
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Payment
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Order Status
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Date
                                            </th>

                                            <th class="text-secondary opacity-7">
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                        <!-- ORDER 1 -->
                                        <tr>

                                            <td>

                                                <div class="d-flex px-3 py-1">

                                                    <div class="d-flex flex-column justify-content-center">

                                                        <h6 class="mb-0 text-sm">
                                                            #ORD-1001
                                                        </h6>

                                                    </div>

                                                </div>

                                            </td>


                                            <td>

                                                <p class="text-sm font-weight-bold mb-0">
                                                    John Doe
                                                </p>

                                            </td>


                                            <td>

                                                <p class="text-sm font-weight-bold mb-0">
                                                    $125.00
                                                </p>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-success">
                                                    Completed
                                                </span>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-info">
                                                    Processing
                                                </span>

                                            </td>


                                            <td>

                                                <span class="text-secondary text-xs font-weight-bold">
                                                    10 Sep 2026
                                                </span>

                                            </td>


                                            <td class="align-middle">

                                                <a
                                                    href="detail.php?id=1"
                                                    class="btn btn-link text-secondary mb-0">

                                                    <i class="fa fa-eye text-xs"></i>

                                                </a>

                                            </td>

                                        </tr>


                                        <!-- ORDER 2 -->
                                        <tr>

                                            <td>

                                                <div class="d-flex px-3 py-1">

                                                    <div class="d-flex flex-column justify-content-center">

                                                        <h6 class="mb-0 text-sm">
                                                            #ORD-1002
                                                        </h6>

                                                    </div>

                                                </div>

                                            </td>


                                            <td>

                                                <p class="text-sm font-weight-bold mb-0">
                                                    Sarah Ahmed
                                                </p>

                                            </td>


                                            <td>

                                                <p class="text-sm font-weight-bold mb-0">
                                                    $240.00
                                                </p>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-warning">
                                                    Pending
                                                </span>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-info">
                                                    Processing
                                                </span>

                                            </td>


                                            <td>

                                                <span class="text-secondary text-xs font-weight-bold">
                                                    09 Sep 2026
                                                </span>

                                            </td>


                                            <td class="align-middle">

                                                <a
                                                    href="/admin/orders/detail.php?id=2"
                                                    class="btn btn-link text-secondary mb-0">

                                                    <i class="fa fa-eye text-xs"></i>

                                                </a>

                                            </td>

                                        </tr>


                                        <!-- ORDER 3 -->
                                        <tr>

                                            <td>

                                                <div class="d-flex px-3 py-1">

                                                    <div class="d-flex flex-column justify-content-center">

                                                        <h6 class="mb-0 text-sm">
                                                            #ORD-1003
                                                        </h6>

                                                    </div>

                                                </div>

                                            </td>


                                            <td>

                                                <p class="text-sm font-weight-bold mb-0">
                                                    Ali Khan
                                                </p>

                                            </td>


                                            <td>

                                                <p class="text-sm font-weight-bold mb-0">
                                                    $89.50
                                                </p>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-success">
                                                    Completed
                                                </span>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-success">
                                                    Delivered
                                                </span>

                                            </td>


                                            <td>

                                                <span class="text-secondary text-xs font-weight-bold">
                                                    07 Sep 2026
                                                </span>

                                            </td>


                                            <td class="align-middle">

                                                <a
                                                    href="/admin/orders/detail.php?id=3"
                                                    class="btn btn-link text-secondary mb-0">

                                                    <i class="fa fa-eye text-xs"></i>

                                                </a>

                                            </td>

                                        </tr>


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
