<?php
require_once '../../core/Middleware.php';

Middleware::admin();
$pageTitle = "Create Category";
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">

    <link rel="icon" type="image/png" href="../assets/img/favicon.png">

    <title>Create Category - ClothWear</title>


    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900">


    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet">

    <link href="../assets/css/nucleo-svg.css" rel="stylesheet">


    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>


    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">


    <!-- Material Dashboard CSS -->
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet">


    <!-- =========================
         CUSTOM CATEGORY FORM CSS
    ========================== -->
    <style>
        /* =========================
           FORM CARD
        ========================== */

        .category-form-card {
            border-radius: 16px;
            overflow: hidden;
        }


        /* =========================
           CARD HEADER
        ========================== */

        .category-form-header {
            padding: 28px 30px 18px;
            border-bottom: 1px solid #e9ecef;
        }

        .category-form-header h5 {
            font-weight: 700;
            color: #212529;
        }

        .category-form-header p {
            margin-bottom: 0;
            color: #67748e;
        }


        /* =========================
           FORM BODY
        ========================== */

        .category-form-body {
            padding: 30px;
        }


        /* =========================
           FORM FIELD
        ========================== */

        .category-field {
            margin-bottom: 28px;
        }


        /* =========================
           LABEL
        ========================== */

        .category-field label {
            display: flex;
            align-items: center;
            gap: 8px;

            margin-bottom: 10px;

            font-size: 14px;
            font-weight: 700;

            color: #344767;
        }


        /* Label Icon */

        .category-field label i {
            font-size: 14px;
            color: #2e37a4;
        }


        /* =========================
           INPUT + SELECT
        ========================== */

        .category-input {
            width: 100%;
            min-height: 48px;

            padding: 12px 15px;

            border: 1px solid #d8dce3;
            border-radius: 8px;

            background-color: #ffffff;

            font-size: 14px;
            color: #344767;

            transition: all 0.2s ease;

            box-shadow: none;
        }


        /* Placeholder */

        .category-input::placeholder {
            color: #adb5bd;
        }


        /* =========================
           INPUT FOCUS
        ========================== */

        .category-input:focus {

            border-color: #2e37a4;

            box-shadow:
                0 0 0 3px rgba(46, 55, 164, 0.10);

            outline: none;
        }


        /* =========================
           SELECT
        ========================== */

        select.category-input {
            cursor: pointer;
        }


        /* =========================
           HELPER TEXT
        ========================== */

        .field-help {
            display: block;

            margin-top: 7px;

            font-size: 12px;

            color: #8392ab;
        }


        /* =========================
           BUTTON AREA
        ========================== */

        .category-form-actions {

            display: flex;

            justify-content: flex-end;

            gap: 10px;

            padding-top: 20px;

            margin-top: 5px;

            border-top: 1px solid #e9ecef;
        }


        /* =========================
           CREATE BUTTON
        ========================== */

        .category-form-actions .create-btn {

            min-width: 160px;

            padding: 11px 18px;

            font-weight: 600;
        }


        /* =========================
           CANCEL BUTTON
        ========================== */

        .category-form-actions .cancel-btn {

            min-width: 100px;

            padding: 11px 18px;

            font-weight: 600;
        }


        /* =========================
           MOBILE RESPONSIVE
        ========================== */

        @media (max-width: 576px) {

            .category-form-body {

                padding: 20px;

            }


            .category-form-header {

                padding: 22px 20px 16px;

            }


            .category-form-actions {

                flex-direction: column-reverse;

            }


            .category-form-actions .btn {

                width: 100%;

            }

        }
    </style>

</head>


<body class="g-sidenav-show bg-gray-100">


    <!-- =========================
         ADMIN HEADER
    ========================== -->

    <?php require_once '../../includes/admin-header.php'; ?>


    <!-- =========================
         ADMIN SIDEBAR
    ========================== -->

    <?php require_once '../../includes/admin-sidenavbar.php'; ?>


    <!-- =========================
         MAIN CONTENT
    ========================== -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">


        <!-- PAGE CONTENT -->

        <div class="container-fluid py-4">


            <!-- =========================
                 PAGE HEADER
            ========================== -->

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">

                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        Create Category
                    </h4>

                    <p class="text-sm text-secondary mb-0">
                        Add a new product category
                    </p>
                </div>

                <!-- BACK BUTTON -->
                <a href="index.php" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                    Back to Categories
                </a>

            </div>

            <!-- =========================
                 CATEGORY FORM
            ========================== -->

            <div class="row">

                <div class="col-lg-8 col-md-10 mx-auto">


                    <!-- FORM CARD -->

                    <div class="card category-form-card">


                        <!-- =========================
                             CARD HEADER
                        ========================== -->

                        <div class="category-form-header">

                            <h5 class="mb-1">
                                Category Information
                            </h5>

                            <p class="text-sm">
                                Enter the details for the new product category.
                            </p>

                        </div>


                        <!-- =========================
                             FORM BODY
                        ========================== -->

                        <div class="category-form-body">


                            <form method="POST" action="">


                                <!-- =========================
                                     CATEGORY NAME
                                ========================== -->

                                <div class="category-field">

                                    <label for="categoryName">

                                        <i class="fa-solid fa-tag"></i>

                                        Category Name

                                    </label>


                                    <input type="text" id="categoryName" name="name" class="category-input"
                                        placeholder="e.g. Footwear, Shirts, Accessories" required>


                                    <span class="field-help">

                                        Enter a unique name for this product category.

                                    </span>

                                </div>


                                <!-- =========================
                                     STATUS
                                ========================== -->

                                <div class="category-field">

                                    <label for="categoryStatus">

                                        <i class="fa-solid fa-circle-check"></i>

                                        Category Status

                                    </label>


                                    <select id="categoryStatus" name="status" class="category-input" required>

                                        <option value="active">
                                            Active
                                        </option>

                                        <option value="inactive">
                                            Inactive
                                        </option>

                                    </select>


                                    <span class="field-help">

                                        Inactive categories will not be available for new products.

                                    </span>

                                </div>


                                <!-- =========================
                                     BUTTONS
                                ========================== -->

                                <div class="category-form-actions">


                                    <!-- CANCEL -->

                                    <a href="index.php" class="btn btn-light cancel-btn">

                                        <i class="fa-solid fa-xmark me-1"></i>

                                        Cancel

                                    </a>


                                    <!-- CREATE -->

                                    <button type="submit" name="save_category" class="btn bg-gradient-dark create-btn">

                                        <i class="fa-solid fa-plus me-1"></i>

                                        Create Category

                                    </button>


                                </div>


                            </form>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =========================
                 ADMIN FOOTER
            ========================== -->

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