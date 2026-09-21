<?php

require_once '../core/Middleware.php';
require_once '../core/Session.php';
require_once '../config/database.php';

Middleware::admin();
Session::start();

$pageTitle = "Profile";

/*
|--------------------------------------------------------------------------
| Get logged-in admin
|--------------------------------------------------------------------------
*/

$userId = (int) Session::get('user_id');

if ($userId <= 0) {
    Session::setFlash('error', 'User session not found.');
    header('Location: login.php');
    exit;
}

$sql = "SELECT
            id,
            name,
            email,
            role,
            is_active,
            created_at
        FROM users
        WHERE id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $userId
]);

$admin = $stmt->fetch();

if (!$admin) {
    Session::setFlash('error', 'Admin account not found.');
    header('Location: login.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Admin initials
|--------------------------------------------------------------------------
*/

$nameParts = preg_split('/\s+/', trim($admin['name']));

$initials = '';

foreach ($nameParts as $part) {
    if ($part !== '') {
        $initials .= strtoupper(substr($part, 0, 1));
    }
}

$initials = substr($initials, 0, 2);


/*
|--------------------------------------------------------------------------
| Account status
|--------------------------------------------------------------------------
*/

$accountStatus = ((int) $admin['is_active'] === 1)
    ? 'Active'
    : 'Disabled';

$accountStatusClass = ((int) $admin['is_active'] === 1)
    ? 'success'
    : 'danger';


/*
|--------------------------------------------------------------------------
| Account created date
|--------------------------------------------------------------------------
*/

$createdDate = !empty($admin['created_at'])
    ? date('d M Y', strtotime($admin['created_at']))
    : 'N/A';


/*
|--------------------------------------------------------------------------
| Dashboard statistics
|--------------------------------------------------------------------------
*/

/* Total customers */

$sql = "SELECT COUNT(*)
        FROM users
        WHERE role = 'customer'";

$stmt = $pdo->query($sql);
$totalCustomers = (int) $stmt->fetchColumn();


/* Active products */

$sql = "SELECT COUNT(*)
        FROM products
        WHERE status = 1";

$stmt = $pdo->query($sql);
$totalProducts = (int) $stmt->fetchColumn();


/* Total orders */

$sql = "SELECT COUNT(*)
        FROM orders";

$stmt = $pdo->query($sql);
$totalOrders = (int) $stmt->fetchColumn();


/* Total sales excluding cancelled orders */

$sql = "SELECT COALESCE(SUM(total_amount), 0)
        FROM orders
        WHERE order_status <> 'cancelled'";

$stmt = $pdo->query($sql);
$totalSales = (float) $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon"
        sizes="76x76"
        href="assets/img/apple-icon.png">

    <link rel="icon"
        type="image/png"
        href="assets/img/favicon.png">

    <title>
        <?= htmlspecialchars($pageTitle) ?> | ClothWear Admin
    </title>

    <!-- Fonts -->
    <link rel="stylesheet"
        type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />

    <!-- Nucleo Icons -->
    <link href="assets/css/nucleo-icons.css"
        rel="stylesheet" />

    <link href="assets/css/nucleo-svg.css"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js"
        crossorigin="anonymous"></script>

    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- Material Dashboard -->
    <link id="pagestyle"
        href="assets/css/material-dashboard.css?v=3.2.0"
        rel="stylesheet" />

</head>


<body class="g-sidenav-show bg-gray-100">

    <?php require_once '../includes/admin-header.php'; ?>

    <?php require_once '../includes/admin-sidenavbar.php'; ?>


    <div class="main-content position-relative max-height-vh-100 h-100">

        <div class="container-fluid px-2 px-md-4">

            <!-- =====================================================
                 PROFILE HEADER
            ====================================================== -->

            <div class="page-header min-height-200 border-radius-xl mt-4"
                style="
                    background-image:
                    url('https://images.unsplash.com/photo-1557683316-973673baf926?auto=format&fit=crop&w=1920&q=80');
                ">

                <span class="mask bg-gradient-dark opacity-6"></span>

            </div>


            <!-- =====================================================
                 PROFILE CARD
            ====================================================== -->

            <div class="card card-body mx-2 mx-md-2 mt-n6">

                <div class="row gx-4 align-items-center">

                    <!-- Avatar -->

                    <div class="col-auto">

                        <div class="avatar avatar-xl position-relative">

                            <div class="avatar avatar-xl bg-gradient-primary text-white
                                        d-flex align-items-center justify-content-center
                                        border-radius-lg shadow">

                                <span class="text-white font-weight-bold text-lg">
                                    <?= htmlspecialchars($initials) ?>
                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- Admin Name -->

                    <div class="col-auto my-auto">

                        <div class="h-100">

                            <h5 class="mb-1">
                                <?= htmlspecialchars($admin['name']) ?>
                            </h5>

                            <p class="mb-0 font-weight-normal text-sm">
                                <?= htmlspecialchars(ucfirst($admin['role'])) ?> Account
                            </p>

                        </div>

                    </div>


                    <!-- Status -->

                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">

                        <div class="text-end">

                            <span class="badge bg-gradient-<?= $accountStatusClass ?>">
                                <?= htmlspecialchars($accountStatus) ?>
                            </span>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     ACCOUNT INFORMATION
                ================================================== -->

                <div class="row mt-4">

                    <!-- Profile Information -->

                    <div class="col-12 col-xl-6 mb-4">

                        <div class="card card-plain h-100">

                            <div class="card-header pb-0 p-3">

                                <h6 class="mb-0">
                                    Profile Information
                                </h6>

                            </div>


                            <div class="card-body p-3">

                                <p class="text-sm">
                                    This is the account information of the
                                    currently logged-in ClothWear administrator.
                                </p>

                                <hr class="horizontal gray-light my-4">


                                <ul class="list-group">

                                    <!-- Name -->

                                    <li class="list-group-item border-0 ps-0 text-sm">

                                        <strong class="text-dark">
                                            Full Name:
                                        </strong>

                                        &nbsp;

                                        <?= htmlspecialchars($admin['name']) ?>

                                    </li>


                                    <!-- Email -->

                                    <li class="list-group-item border-0 ps-0 text-sm">

                                        <strong class="text-dark">
                                            Email:
                                        </strong>

                                        &nbsp;

                                        <?= htmlspecialchars($admin['email']) ?>

                                    </li>


                                    <!-- Role -->

                                    <li class="list-group-item border-0 ps-0 text-sm">

                                        <strong class="text-dark">
                                            Role:
                                        </strong>

                                        &nbsp;

                                        <?= htmlspecialchars(ucfirst($admin['role'])) ?>

                                    </li>


                                    <!-- Status -->

                                    <li class="list-group-item border-0 ps-0 text-sm">

                                        <strong class="text-dark">
                                            Account Status:
                                        </strong>

                                        &nbsp;

                                        <span class="badge bg-gradient-<?= $accountStatusClass ?>">

                                            <?= htmlspecialchars($accountStatus) ?>

                                        </span>

                                    </li>


                                    <!-- Created -->

                                    <li class="list-group-item border-0 ps-0 pb-0 text-sm">

                                        <strong class="text-dark">
                                            Account Created:
                                        </strong>

                                        &nbsp;

                                        <?= htmlspecialchars($createdDate) ?>

                                    </li>

                                </ul>

                            </div>

                        </div>

                    </div>


                    <!-- Account Summary -->

                    <div class="col-12 col-xl-6 mb-4">

                        <div class="card card-plain h-100">

                            <div class="card-header pb-0 p-3">

                                <h6 class="mb-0">
                                    Store Summary
                                </h6>

                            </div>


                            <div class="card-body p-3">

                                <div class="row">


                                    <!-- Customers -->

                                    <div class="col-md-6 mb-4">

                                        <div class="card">

                                            <div class="card-body p-3">

                                                <div class="text-center">

                                                    <div class="icon icon-shape
                                                                icon-md
                                                                bg-gradient-primary
                                                                shadow
                                                                text-center
                                                                border-radius-lg">

                                                        <i class="material-symbols-rounded opacity-10">
                                                            group
                                                        </i>

                                                    </div>

                                                </div>


                                                <div class="text-center mt-3">

                                                    <h4 class="mb-0">
                                                        <?= number_format($totalCustomers) ?>
                                                    </h4>

                                                    <p class="text-sm mb-0">
                                                        Customers
                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- Products -->

                                    <div class="col-md-6 mb-4">

                                        <div class="card">

                                            <div class="card-body p-3">

                                                <div class="text-center">

                                                    <div class="icon icon-shape
                                                                icon-md
                                                                bg-gradient-success
                                                                shadow
                                                                text-center
                                                                border-radius-lg">

                                                        <i class="material-symbols-rounded opacity-10">
                                                            inventory_2
                                                        </i>

                                                    </div>

                                                </div>


                                                <div class="text-center mt-3">

                                                    <h4 class="mb-0">
                                                        <?= number_format($totalProducts) ?>
                                                    </h4>

                                                    <p class="text-sm mb-0">
                                                        Active Products
                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- Orders -->

                                    <div class="col-md-6 mb-4">

                                        <div class="card">

                                            <div class="card-body p-3">

                                                <div class="text-center">

                                                    <div class="icon icon-shape
                                                                icon-md
                                                                bg-gradient-warning
                                                                shadow
                                                                text-center
                                                                border-radius-lg">

                                                        <i class="material-symbols-rounded opacity-10">
                                                            shopping_bag
                                                        </i>

                                                    </div>

                                                </div>


                                                <div class="text-center mt-3">

                                                    <h4 class="mb-0">
                                                        <?= number_format($totalOrders) ?>
                                                    </h4>

                                                    <p class="text-sm mb-0">
                                                        Total Orders
                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- Sales -->

                                    <div class="col-md-6 mb-4">

                                        <div class="card">

                                            <div class="card-body p-3">

                                                <div class="text-center">

                                                    <div class="icon icon-shape
                                                                icon-md
                                                                bg-gradient-info
                                                                shadow
                                                                text-center
                                                                border-radius-lg">

                                                        <i class="material-symbols-rounded opacity-10">
                                                            payments
                                                        </i>

                                                    </div>

                                                </div>


                                                <div class="text-center mt-3">

                                                    <h4 class="mb-0">
                                                        PKR <?= number_format($totalSales, 2) ?>
                                                    </h4>

                                                    <p class="text-sm mb-0">
                                                        Total Sales
                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     ADMIN ACCOUNT CARD
                ================================================== -->

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-header pb-0 p-3">

                                <h6 class="mb-0">
                                    Account Details
                                </h6>

                            </div>


                            <div class="card-body p-3">

                                <div class="row">

                                    <!-- Account ID -->

                                    <div class="col-md-3 mb-3">

                                        <small class="text-uppercase text-secondary
                                                     font-weight-bolder">

                                            Account ID

                                        </small>

                                        <p class="text-sm font-weight-bold mb-0">

                                            #<?= (int) $admin['id'] ?>

                                        </p>

                                    </div>


                                    <!-- Email -->

                                    <div class="col-md-3 mb-3">

                                        <small class="text-uppercase text-secondary
                                                     font-weight-bolder">

                                            Email

                                        </small>

                                        <p class="text-sm font-weight-bold mb-0">

                                            <?= htmlspecialchars($admin['email']) ?>

                                        </p>

                                    </div>


                                    <!-- Role -->

                                    <div class="col-md-3 mb-3">

                                        <small class="text-uppercase text-secondary
                                                     font-weight-bolder">

                                            Role

                                        </small>

                                        <p class="text-sm font-weight-bold mb-0">

                                            <?= htmlspecialchars(ucfirst($admin['role'])) ?>

                                        </p>

                                    </div>


                                    <!-- Joined -->

                                    <div class="col-md-3 mb-3">

                                        <small class="text-uppercase text-secondary
                                                     font-weight-bolder">

                                            Joined

                                        </small>

                                        <p class="text-sm font-weight-bold mb-0">

                                            <?= htmlspecialchars($createdDate) ?>

                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <?php require_once "../includes/admin-footer.php"; ?>

    </div>


    <!-- =============================================================
         FIXED PLUGIN
    ============================================================= -->

    <div class="fixed-plugin">

        <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">

            <i class="material-symbols-rounded py-2">
                settings
            </i>

        </a>


        <div class="card shadow-lg">

            <div class="card-header pb-0 pt-3">

                <div class="float-start">

                    <h5 class="mt-3 mb-0">
                        Material UI Configurator
                    </h5>

                    <p>
                        See your dashboard options.
                    </p>

                </div>


                <div class="float-end mt-4">

                    <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">

                        <i class="material-symbols-rounded">
                            clear
                        </i>

                    </button>

                </div>

            </div>


            <hr class="horizontal dark my-1">


            <div class="card-body pt-sm-3 pt-0">

                <!-- Sidebar Colors -->

                <div>

                    <h6 class="mb-0">
                        Sidebar Colors
                    </h6>

                </div>


                <a href="javascript:void(0)"
                    class="switch-trigger background-color">

                    <div class="badge-colors my-2 text-start">

                        <span class="badge filter bg-gradient-primary"
                            data-color="primary"
                            onclick="sidebarColor(this)">
                        </span>

                        <span class="badge filter bg-gradient-dark active"
                            data-color="dark"
                            onclick="sidebarColor(this)">
                        </span>

                        <span class="badge filter bg-gradient-info"
                            data-color="info"
                            onclick="sidebarColor(this)">
                        </span>

                        <span class="badge filter bg-gradient-success"
                            data-color="success"
                            onclick="sidebarColor(this)">
                        </span>

                        <span class="badge filter bg-gradient-warning"
                            data-color="warning"
                            onclick="sidebarColor(this)">
                        </span>

                        <span class="badge filter bg-gradient-danger"
                            data-color="danger"
                            onclick="sidebarColor(this)">
                        </span>

                    </div>

                </a>


                <!-- Sidenav Type -->

                <div class="mt-3">

                    <h6 class="mb-0">
                        Sidenav Type
                    </h6>

                    <p class="text-sm">
                        Choose between different sidenav types.
                    </p>

                </div>


                <div class="d-flex">

                    <button class="btn bg-gradient-dark px-3 mb-2"
                        data-class="bg-gradient-dark"
                        onclick="sidebarType(this)">
                        Dark
                    </button>

                    <button class="btn bg-gradient-dark px-3 mb-2 ms-2"
                        data-class="bg-transparent"
                        onclick="sidebarType(this)">
                        Transparent
                    </button>

                    <button class="btn bg-gradient-dark px-3 mb-2 ms-2 active"
                        data-class="bg-white"
                        onclick="sidebarType(this)">
                        White
                    </button>

                </div>


                <p class="text-sm d-xl-none d-block mt-2">
                    You can change the sidenav type just on desktop view.
                </p>


                <!-- Navbar Fixed -->

                <div class="mt-3 d-flex">

                    <h6 class="mb-0">
                        Navbar Fixed
                    </h6>

                    <div class="form-check form-switch ps-0 ms-auto my-auto">

                        <input class="form-check-input mt-1 ms-auto"
                            type="checkbox"
                            id="navbarFixed"
                            onclick="navbarFixed(this)">

                    </div>

                </div>


                <hr class="horizontal dark my-3">


                <!-- Dark Mode -->

                <div class="mt-2 d-flex">

                    <h6 class="mb-0">
                        Light / Dark
                    </h6>

                    <div class="form-check form-switch ps-0 ms-auto my-auto">

                        <input class="form-check-input mt-1 ms-auto"
                            type="checkbox"
                            id="dark-version"
                            onclick="darkMode(this)">

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Core JS -->

    <script src="assets/js/core/popper.min.js"></script>

    <script src="assets/js/core/bootstrap.min.js"></script>

    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>

    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>


    <script>

        var win = navigator.platform.indexOf('Win') > -1;

        if (win && document.querySelector('#sidenav-scrollbar')) {

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