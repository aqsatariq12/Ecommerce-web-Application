<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Middleware.php';
Middleware::admin();
Session::start();

$user = Auth::user();
?>
<style>
    /* ================================
       HEADER / NAVBAR ALIGNMENT
       ================================ */

    @media (min-width: 1200px) {

        .navbar-main {
            margin-left: 260px !important;
            margin-right: 12px !important;
            width: calc(100% - 292px) !important;
            padding-left: 10px !important;
        }

        .navbar-main>.container-fluid {
            padding-left: 0 !important;
        }

        .navbar-main .breadcrumb {
            padding-left: 0 !important;
        }
    }


    /* ================================
       TABLET / MOBILE
       ================================ */

    @media (max-width: 1199.98px) {

        .navbar-main {
            margin-left: 12px !important;
            margin-right: 12px !important;
            width: auto !important;
        }

    }

    /* =========================================
   SMALL SCREEN ONLY
   ========================================= */

    @media (max-width: 575.98px) {

        /* Make the right-side navbar stack vertically */
        .navbar-main #navbar {
            display: flex !important;
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100% !important;
        }

        /* Type here field */
        .navbar-main #navbar>.ms-md-auto {
            width: 100% !important;
            padding-right: 0 !important;
            margin-bottom: 10px !important;
        }

        .navbar-main #navbar>.ms-md-auto .input-group {
            width: 100% !important;
        }

        /* Online Builder + icons row */
        .navbar-main #navbar>.navbar-nav {
            width: 100% !important;
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: flex-end !important;
        }

        /* Online Builder */
        .navbar-main #navbar>.navbar-nav>.nav-item:first-child {
            margin-right: auto !important;
        }

        .navbar-main #navbar>.navbar-nav>.nav-item:first-child .btn {
            margin-right: 0 !important;
        }
    }
</style>


<nav class="navbar navbar-main navbar-expand-lg px-0 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">

    <div class="container-fluid py-1 px-3">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">

            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1">

                <li class="breadcrumb-item text-sm">

                    <a class="opacity-5 text-dark" href="javascript:;">
                        Pages
                    </a>

                </li>

                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">

                    <?= htmlspecialchars($pageTitle ?? 'Dashboard') ?>

                </li>

            </ol>

        </nav>


        <!-- Right Side Navbar -->
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">

            <div class="ms-md-auto pe-md-3 d-flex align-items-center">



            </div>


            <ul class="navbar-nav d-flex align-items-center justify-content-end">

                <!-- Online Builder -->
                <li class="nav-item d-flex align-items-center">

                    <a class="btn btn-outline-primary btn-sm mb-0 me-3" target="_blank"
                        href="https://www.creative-tim.com/builder?ref=navbar-material-dashboard">

                        Online Builder

                    </a>

                </li>


                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown d-flex align-items-center">

                    <a href="#" class="nav-link text-body font-weight-bold px-0 d-flex align-items-center"
                        id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">

                        <i class="material-symbols-rounded me-1">
                            account_circle
                        </i>

                        <span class="d-sm-inline d-none">
                            <?= htmlspecialchars($user['name']) ?>
                        </span>

                    </a>


                    <!-- Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="userDropdown">

                        <!-- User Name -->
                        <li class="px-3 mb-2">

                            <div class="d-flex align-items-center">

                                <i class="material-symbols-rounded me-2 text-primary">
                                    account_circle
                                </i>

                                <div>
                                    <p class="text-sm font-weight-bold mb-0">
                                        <?= htmlspecialchars($user['name']) ?>
                                    </p>

                                    <p class="text-xs text-secondary mb-0">
                                        Admin
                                    </p>
                                </div>

                            </div>

                        </li>


                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        <!-- Profile -->
                        <li>
                            <a class="dropdown-item border-radius-md" href="profile.php">

                                <i class="material-symbols-rounded me-2">
                                    person
                                </i>

                                Profile

                            </a>
                        </li>


                        <!-- Logout -->
                        <li>
                            <form action="/public/logout.php" method="POST" class="m-0">

                                <button type="submit" class="dropdown-item border-radius-md text-danger">

                                    <i class="material-symbols-rounded me-2">
                                        logout
                                    </i>

                                    Logout

                                </button>
                            </form>
                        </li>

                    </ul>

                </li>


                <!-- Mobile Sidebar Toggle -->
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">

                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">

                        <div class="sidenav-toggler-inner">

                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>

                        </div>

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

<!-- End Navbar -->