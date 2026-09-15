<?php  
require_once '../../core/Middleware.php';

Middleware::admin();
$pageTitle = "Products";
?>
<!DOCTYPE html>

<html lang="en">

<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
<link rel="icon" type="image/png" href="../assets/img/favicon.png">

<title>Products - ClothWear</title>

<!-- Fonts and icons -->
<link
    rel="stylesheet"
    type="text/css"
    href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900"
/>

<!-- Nucleo Icons -->
<link href="../assets/css/nucleo-icons.css" rel="stylesheet">
<link href="../assets/css/nucleo-svg.css" rel="stylesheet">

<!-- Font Awesome -->
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>

<!-- Material Icons -->
<link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
>

<!-- Material Dashboard CSS -->
<link
    id="pagestyle"
    href="../assets/css/material-dashboard.css?v=3.2.0"
    rel="stylesheet"
>
</head>

<body class="g-sidenav-show  bg-gray-100">

<!-- ADMIN HEADER / NAVBAR -->
<?php require_once '../../includes/admin-header.php'; ?>


<!-- ADMIN SIDEBAR -->
<?php require_once '../../includes/admin-sidenavbar.php'; ?>


<!-- MAIN CONTENT -->
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

    <div class="container-fluid py-4">


        <!-- =========================
             PAGE HEADER
        ========================== -->

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

            <div>
                <h4 class="fw-bold text-dark mb-0">
                    Products
                </h4>

            </div>


            <div class="d-flex align-items-center gap-3 border rounded ps-4">

                <input
                    type="text"
                    class="form-control"
                    placeholder="Search products..."
                    style="width: 220px;"
                >

            </div>

        </div>


        <!-- =========================
             PRODUCT CARD
        ========================== -->

        <div class="card">


            <!-- CARD HEADER -->

            <div class="card-header pb-0">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <h5 class="mb-0">
                        Product Inventory
                    </h5>


                    <a
                        href="/admin/products/create.php"
                        class="btn bg-gradient-dark btn-sm"
                    >

                        <i class="fa-solid fa-plus me-1"></i>

                        Add Product

                    </a>

                </div>

            </div>


            <!-- =========================
                 PRODUCT TABLE
            ========================== -->

            <div class="card-body px-0 pb-2">

                <div class="table-responsive p-0">

                    <table class="table align-items-center mb-0">

                        <thead>

                            <tr>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Product
                                </th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Category
                                </th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Price
                                </th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Stock
                                </th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status
                                </th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end pe-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <!-- =========================
                                 EXAMPLE PRODUCT
                            ========================== -->

                            <tr>


                                <!-- PRODUCT -->

                                <td>

                                    <div class="d-flex px-3 py-1">

                                        <div>

                                            <img
                                                src="../assets/img/small-logos/logo-spotify.svg"
                                                class="avatar avatar-sm me-3"
                                                alt="Product"
                                            >

                                        </div>


                                        <div class="d-flex flex-column justify-content-center">

                                            <h6 class="mb-0 text-sm">
                                                Nike Air Max
                                            </h6>

                                            <p class="text-xs text-secondary mb-0">
                                                Product ID: 1
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                <!-- CATEGORY -->

                                <td>

                                    <span class="badge bg-gradient-secondary">
                                        Footwear
                                    </span>

                                </td>


                                <!-- PRICE -->

                                <td>

                                    <p class="text-xs font-weight-bold mb-0">
                                        $120.00
                                    </p>

                                </td>


                                <!-- STOCK -->

                                <td>

                                    <p class="text-xs font-weight-bold mb-0">
                                        50
                                    </p>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span class="badge bg-gradient-success">
                                        Active
                                    </span>

                                </td>


                                <!-- ACTION -->

                                <td class="text-end pe-4">


                                    <!-- EDIT -->

                                    <a
                                        href="/admin/products/edit.php?id=1"
                                        class="btn btn-link text-secondary p-0 me-3"
                                        title="Edit"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                    </a>


                                    <!-- DELETE -->

                                    <button
                                        type="button"
                                        class="btn btn-link text-danger p-0"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteProductModal"
                                        title="Delete"
                                    >

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
     DELETE PRODUCT MODAL
========================== -->

<div
    class="modal fade"
    id="deleteProductModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-sm">

        <div class="modal-content text-center p-3">


            <div class="text-danger fs-1 mb-2">

                <i class="fa-solid fa-circle-exclamation"></i>

            </div>


            <h6 class="fw-bold">
                Are you sure?
            </h6>


            <p class="small text-muted mb-3">
                This product will be permanently deleted.
            </p>


            <div class="d-flex justify-content-center gap-2">


                <button
                    type="button"
                    class="btn btn-light btn-sm"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>


                <button
                    type="button"
                    class="btn btn-danger btn-sm"
                >
                    Yes, Delete
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
