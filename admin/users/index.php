<?php  
require_once '../../core/Middleware.php';

Middleware::admin();
$pageTitle = "Users";
?>
<!DOCTYPE html>
<html lang="en">
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Users - ClothWear Admin</title>

  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>
  <!-- Material Icons -->
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
                                Users Management
                            </h4>
                        </div>

                    </div>

                </div>
            </div>


            <!-- Users Table -->
            <div class="row">

                <div class="col-12">

                    <div class="card mb-4">

                        <!-- Card Header -->
                        <div class="card-header pb-0">

                            <div class="d-flex justify-content-between align-items-center">

                                <h6>
                                    Users
                                </h6>

                                <span class="text-sm text-secondary">
                                    Manage registered users
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
                                                User
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Email
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Role
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status
                                            </th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Joined
                                            </th>

                                            <th class="text-secondary opacity-7">
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                        <!-- USER 1 -->
                                        <tr>

                                            <td>

                                                <div class="d-flex px-3 py-1">

                                                    <div class="d-flex flex-column justify-content-center">

                                                        <h6 class="mb-0 text-sm">
                                                            John Doe
                                                        </h6>

                                                    </div>

                                                </div>

                                            </td>


                                            <td>

                                                <p class="text-sm font-weight-bold mb-0">
                                                    john@example.com
                                                </p>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-info">
                                                    Customer
                                                </span>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-success">
                                                    Active
                                                </span>

                                            </td>


                                            <td>

                                                <span class="text-secondary text-xs font-weight-bold">
                                                    01 Sep 2026
                                                </span>

                                            </td>


                                            <td class="align-middle">

                                                <button
                                                    type="button"
                                                    class="btn btn-link text-secondary mb-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteUserModal">

                                                    <i class="fa fa-trash text-xs"></i>

                                                </button>

                                            </td>

                                        </tr>


                                        <!-- USER 2 -->
                                        <tr>

                                            <td>

                                                <div class="d-flex px-3 py-1">

                                                    <div class="d-flex flex-column justify-content-center">

                                                        <h6 class="mb-0 text-sm">
                                                            Sarah Ahmed
                                                        </h6>

                                                    </div>

                                                </div>

                                            </td>


                                            <td>

                                                <p class="text-sm font-weight-bold mb-0">
                                                    sarah@example.com
                                                </p>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-info">
                                                    Customer
                                                </span>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-success">
                                                    Active
                                                </span>

                                            </td>


                                            <td>

                                                <span class="text-secondary text-xs font-weight-bold">
                                                    28 Aug 2026
                                                </span>

                                            </td>


                                            <td class="align-middle">

                                                <button
                                                    type="button"
                                                    class="btn btn-link text-secondary mb-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteUserModal">

                                                    <i class="fa fa-trash text-xs"></i>

                                                </button>

                                            </td>

                                        </tr>


                                        <!-- ADMIN USER -->
                                        <tr>

                                            <td>

                                                <div class="d-flex px-3 py-1">

                                                    <div class="d-flex flex-column justify-content-center">

                                                        <h6 class="mb-0 text-sm">
                                                            Admin
                                                        </h6>

                                                    </div>

                                                </div>

                                            </td>


                                            <td>

                                                <p class="text-sm font-weight-bold mb-0">
                                                    admin@example.com
                                                </p>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-dark">
                                                    Admin
                                                </span>

                                            </td>


                                            <td>

                                                <span class="badge badge-sm bg-gradient-success">
                                                    Active
                                                </span>

                                            </td>


                                            <td>

                                                <span class="text-secondary text-xs font-weight-bold">
                                                    20 Aug 2026
                                                </span>

                                            </td>


                                            <td class="align-middle">

                                                <button
                                                    type="button"
                                                    class="btn btn-link text-secondary mb-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteUserModal">

                                                    <i class="fa fa-trash text-xs"></i>

                                                </button>

                                            </td>

                                        </tr>


                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
            <!-- Admin Footer -->
    <?php require_once '../../includes/admin-footer.php'; ?>

    </main>


    <!-- Delete User Modal -->
    <div
        class="modal fade"
        id="deleteUserModal"
        tabindex="-1"
        aria-labelledby="deleteUserModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="deleteUserModalLabel">
                        Delete User
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                <div class="modal-body">

                    Are you sure you want to delete this user?

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="button"
                        class="btn btn-danger">

                        Delete

                    </button>

                </div>

            </div>

        </div>

    </div>



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
