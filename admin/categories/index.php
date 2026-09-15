<?php

require_once '../../core/Middleware.php';

Middleware::admin();

$pageTitle = "Categories";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Categories - ClothWear</title>

    <!--     Fonts and icons     -->
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
    <style>
        .search-wrapper {
            width: 180px;
        }

        .search-input {
            width: 100%;
        }

        /* Small screens */
        @media (max-width: 400px) {
            .search-wrapper {
                width: 130px;
                padding-left: 6px !important;
                padding-right: 6px !important;
            }

            .search-input {
                font-size: 13px;
                padding: 6px 4px;
            }
        }

        /* Very small screens */
        @media (max-width: 340px) {
            .search-wrapper {
                width: 115px;
            }

            .search-input {
                font-size: 12px;
                padding: 5px 3px;
            }

            .d-flex.justify-content-between {
                gap: 8px !important;
            }
        }

        @media (max-width: 576px) {
            .category-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 12px;
            }

            .category-header h5 {
                font-size: 17px;
            }

            .category-header .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body class="g-sidenav-show  bg-gray-100">
    <!-- ADMIN HEADER / NAVBAR -->
    <?php require_once '../../includes/admin-header.php'; ?>

    <!-- ADMIN SIDEBAR -->
    <?php require_once '../../includes/admin-sidenavbar.php'; ?>


    <!-- MAIN CONTENT -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!-- Page Header -->
        <div class="container-fluid py-4">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

                <div>
                    <h4 class="fw-bold text-dark mb-0">
                        Categories
                    </h4>
                </div>

                <div class="d-flex align-items-center gap-3 border rounded ps-4">

                    <input type="text" class="form-control" placeholder="Search categories..." style="width: 220px;">

                </div>
            </div>


            <!-- CATEGORY CARD -->
            <div class="card">

                <div class="card-header pb-0">

                    <div class="category-header d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            Category Management
                        </h5>

                        <a href="create.php" class="btn bg-gradient-dark btn-sm">
                            <i class="fa-solid fa-plus me-1"></i>
                            Add Category
                        </a>

                    </div>

                </div>


                <!-- CATEGORY TABLE -->
                <div class="card-body px-0 pb-2">

                    <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0">

                            <thead>

                                <tr>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Category Name
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Items Count
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Status
                                    </th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end pe-4">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <!-- Example Category -->
                                <tr>

                                    <td>
                                        <div class="d-flex px-3 py-1">

                                            <div class="d-flex flex-column justify-content-center">

                                                <h6 class="mb-0 text-sm">
                                                    Footwear
                                                </h6>

                                            </div>

                                        </div>
                                    </td>


                                    <td>

                                        <p class="text-xs font-weight-bold mb-0">
                                            140 Products
                                        </p>

                                    </td>


                                    <td>

                                        <span class="badge bg-gradient-success">
                                            Active
                                        </span>

                                    </td>


                                    <td class="text-end pe-4">

                                        <!-- Edit -->
                                        <a href="edit.php?id=1" class="btn btn-link text-secondary p-0 me-3"
                                            title="Edit Category"> <i class="fa-solid fa-pen"></i> </a>


                                        <!-- Delete -->
                                        <button class="btn btn-link text-danger p-0" data-bs-toggle="modal"
                                            data-bs-target="#deleteCatModal">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
            <!-- ADMIN FOOTER -->
            <?php require_once '../../includes/admin-footer.php'; ?>
        </div>


    </main>



    <!-- =========================
         DELETE CATEGORY MODAL
    ========================== -->

    <div class="modal fade" id="deleteCatModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered modal-sm">

            <div class="modal-content text-center p-3">

                <div class="text-danger fs-1 mb-2">

                    <i class="fa-solid fa-circle-exclamation"></i>

                </div>


                <h6 class="fw-bold">
                    Are you sure?
                </h6>


                <p class="small text-muted mb-3">
                    Do you want to delete this category?
                </p>


                <div class="d-flex justify-content-center gap-2">

                    <button class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>


                    <button class="btn btn-danger btn-sm">
                        Yes, Delete
                    </button>

                </div>

            </div>

        </div>

    </div>




    <!-- =========================
     JAVASCRIPT
========================= -->

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

</body>


</html>