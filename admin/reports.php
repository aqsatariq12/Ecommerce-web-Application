<?php 
require_once '../core/Middleware.php';

Middleware::admin(); 
$pageTitle = "Reports";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <title>Reports - ClothWear Admin</title>

  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>


<body class="g-sidenav-show  bg-gray-100">

    <!-- Admin Header -->
    <?php require_once '../includes/admin-header.php'; ?>

    <!-- Admin Sidebar -->
    <?php require_once '../includes/admin-sidenavbar.php'; ?>

    <!-- Main Content -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!-- Top Navbar / Header -->
        <div class="container-fluid py-4">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>
                            <h4 class="font-weight-bolder mb-0">
                                Analytics & Reports
                            </h4>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Metric Cards -->
            <div class="row">

                <!-- Today's Sales -->
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">

                            <div class="row">

                                <div class="col-8">
                                    <div class="numbers">

                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">
                                            Today's Sales
                                        </p>

                                        <h5 class="font-weight-bolder mb-0">
                                            $53,000
                                        </h5>

                                    </div>
                                </div>

                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                        <i class="ni ni-money-coins text-lg opacity-10"></i>
                                    </div>
                                </div>

                            </div>

                            <hr class="horizontal dark">

                            <p class="mb-0 text-sm">
                                <span class="text-success font-weight-bolder">
                                    +55%
                                </span>
                                than last week
                            </p>

                        </div>
                    </div>
                </div>


                <!-- Total Users -->
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">

                            <div class="row">

                                <div class="col-8">
                                    <div class="numbers">

                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">
                                            Total Users
                                        </p>

                                        <h5 class="font-weight-bolder mb-0">
                                            2,300
                                        </h5>

                                    </div>
                                </div>

                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                        <i class="ni ni-single-02 text-lg opacity-10"></i>
                                    </div>
                                </div>

                            </div>

                            <hr class="horizontal dark">

                            <p class="mb-0 text-sm">
                                <span class="text-success font-weight-bolder">
                                    +3%
                                </span>
                                than last month
                            </p>

                        </div>
                    </div>
                </div>

            </div>


            <!-- Sales Summary -->
            <div class="row">

                <div class="col-12">

                    <div class="card mb-4">

                        <div class="card-header pb-0">
                            <h6>Sales Summary</h6>
                        </div>

                        <div class="card-body px-0 pt-0 pb-2">

                            <div class="table-responsive p-0">

                                <table class="table align-items-center mb-0">

                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Month
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Sales
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <!-- Example Row -->
                                        <tr>

                                            <td>
                                                <div class="d-flex px-3 py-1">

                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">
                                                            January 2026
                                                        </h6>
                                                    </div>

                                                </div>
                                            </td>


                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">
                                                    $45,200
                                                </p>
                                            </td>


                                            <td>
                                                <span class="badge badge-sm bg-gradient-success">
                                                    Completed
                                                </span>
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